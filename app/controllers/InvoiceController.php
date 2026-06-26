<?php

namespace App\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\PaymentMethod;
use App\Models\Appointment;
use App\Core\Session;
use App\Core\Paths;
use App\Config\Messages;
use App\Config\Connection;

class InvoiceController
{
    public $invoiceModel;
    public $invoiceDetailModel;
    public $paymentMethodModel;
    public $appointmentModel;

    public function __construct()
    {
        $this->invoiceModel = new Invoice();
        $this->invoiceDetailModel = new InvoiceDetail();
        $this->paymentMethodModel = new PaymentMethod();
        $this->appointmentModel = new Appointment();
    }

    /**
     * Show the invoices list page.
     *
     * @return void
     */
    public function index(): void
    {
        if (!Session::isLogged()) {
            redirect('login');
        }

        $user = Session::getUser();
        $invoices = $this->invoiceModel->getAll();

        // Load the cached BCV rate for display/calculating totals in Bs.
        $cacheFile = dirname(__DIR__, 2) . '/storage/tasa.json';
        $bcvRate = 1.0;
        if (file_exists($cacheFile)) {
            $cacheData = json_decode(file_get_contents($cacheFile), true);
            $bcvRate = floatval($cacheData['bcv'] ?? 1.0);
        }

        $urlCreate = Paths::to('invoices/create');
        $title = 'Facturación';
        $bodyClass = 'layout-footer';
        $extraScripts = [
            'DataTables/jquery-3.7.0.min.js',
            'DataTables/jquery.dataTables.min.js',
            'DataTables/dataTables.bootstrap5.min.js',
            'js/sidebar.js',
            'js/invoices.js'
        ];

        $statusBadges = [
            'pagada' => 'text-bg-success',
            'anulada' => 'text-bg-danger'
        ];

        require_once __DIR__ . '/../views/invoices/index.php';
    }

    /**
     * Show the invoice creation page.
     *
     * @return void
     */
    public function create(): void
    {
        if (!Session::isLogged()) {
            redirect('login');
        }

        $user = Session::getUser();
        
        // Ensure payment methods exist
        $paymentMethods = $this->paymentMethodModel->getAll();
        
        // Fetch appointments that are not billed yet
        $appointments = $this->appointmentModel->getUnbilled();

        // Load the cached BCV rate
        $cacheFile = dirname(__DIR__, 2) . '/storage/tasa.json';
        $bcvRate = 1.0;
        $bcvRateFormatted = "0,00";
        $bcvRateDate = "No actualizada";
        
        if (file_exists($cacheFile)) {
            $cacheData = json_decode(file_get_contents($cacheFile), true);
            $bcvRate = floatval($cacheData['bcv'] ?? 1.0);
            $bcvRateFormatted = number_format($bcvRate, 2, ',', '.');
            if (isset($cacheData['date'])) {
                $bcvRateDate = (new \DateTimeImmutable($cacheData['date'], new \DateTimeZone('America/Caracas')))->format('d-m-Y h:i A');
            }
        }

        $title = 'Nueva Factura';
        $bodyClass = 'layout-footer';
        $extraScripts = [
            'js/sidebar.js',
            'js/invoice-create.js'
        ];

        require_once __DIR__ . '/../views/invoices/create.php';
    }

    /**
     * API Endpoint to fetch appointment details for invoicing.
     *
     * @return void
     */
    public function getAppointmentDetails(): void
    {
        header('Content-Type: application/json');

        if (!Session::isLogged()) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => Messages::ERR_AUTH_SESSION]);
            exit();
        }

        $appointmentId = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($appointmentId <= 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID de cita no válido']);
            exit();
        }

        $details = $this->appointmentModel->getDetailsForBilling($appointmentId);

        if (!$details) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'No se encontraron detalles para esta cita']);
            exit();
        }

        echo json_encode(['status' => 'success', 'data' => $details]);
        exit();
    }

    /**
     * Store a newly created invoice in the database.
     *
     * @return void
     */
    public function store(): void
    {
        header('Content-Type: application/json');

        if (!Session::isLogged()) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => Messages::ERR_AUTH_SESSION]);
            exit();
        }

        $appointmentId = isset($_POST['appointment_id']) ? intval($_POST['appointment_id']) : 0;
        $paymentMethodId = isset($_POST['payment_method_id']) ? intval($_POST['payment_method_id']) : 0;

        if ($appointmentId <= 0 || $paymentMethodId <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios', 'field' => 'all']);
            exit();
        }

        // Verify the appointment details and check if it's already invoiced
        $details = $this->appointmentModel->getDetailsForBilling($appointmentId);
        if (!$details) {
            echo json_encode(['status' => 'error', 'message' => 'Cita no encontrada o no válida']);
            exit();
        }

        // Check if there's already an invoice for this appointment
        $checkSql = "SELECT id FROM facturas WHERE id_cita = :appointmentId LIMIT 1";
        $checkStmt = Connection::getConnection()->prepare($checkSql);
        $checkStmt->execute([':appointmentId' => $appointmentId]);
        if ($checkStmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'Esta cita ya ha sido facturada previamente']);
            exit();
        }

        // Retrieve pricing information and user info
        $services = $details['services'];
        if (empty($services)) {
            echo json_encode(['status' => 'error', 'message' => 'La cita no tiene servicios asociados para facturar']);
            exit();
        }

        $user = Session::getUser();
        $userId = intval($user['id'] ?? 1);

        // Load the current BCV rate
        $cacheFile = dirname(__DIR__, 2) . '/storage/tasa.json';
        $bcvRate = 1.0;
        if (file_exists($cacheFile)) {
            $cacheData = json_decode(file_get_contents($cacheFile), true);
            $bcvRate = floatval($cacheData['bcv'] ?? 1.0);
        }

        // Calculate pricing
        $subtotalUsd = 0.0;
        foreach ($services as $service) {
            $subtotalUsd += floatval($service['precio']);
        }
        $ivaUsd = $subtotalUsd * 0.16; // Standard 16% IVA
        $totalUsd = $subtotalUsd + $ivaUsd;

        $db = Connection::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            // Generate sequential invoice number
            $invoiceNumber = $this->invoiceModel->generateInvoiceNumber();

            // 1. Create the Invoice
            $invoiceId = $this->invoiceModel->create(
                $invoiceNumber,
                intval($details['client_id']),
                $appointmentId,
                $userId,
                $subtotalUsd,
                $ivaUsd,
                $totalUsd,
                $bcvRate,
                $paymentMethodId,
                1 // Status: pagada (1)
            );

            // 2. Create the Invoice Details
            foreach ($services as $service) {
                $servicePrice = floatval($service['precio']);
                $this->invoiceDetailModel->create(
                    $invoiceId,
                    intval($service['id']),
                    1, // Quantity defaults to 1 for aesthetic services
                    $servicePrice,
                    $servicePrice
                );
            }

            // 3. Update the Appointment Status to 'Asistida' (ID 2)
            $updateApptSql = "UPDATE citas SET id_estado = 2, updated_at = NOW() WHERE id = :appointmentId";
            $updateStmt = $db->prepare($updateApptSql);
            $updateStmt->execute([':appointmentId' => $appointmentId]);

            $db->commit();

            echo json_encode([
                'status' => 'success',
                'message' => '¡Factura registrada y cita marcada como asistida con éxito!',
                'redirect' => Paths::to('invoices')
            ]);
            exit();

        } catch (\Exception $e) {
            $db->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'Error de base de datos: ' . $e->getMessage()
            ]);
            exit();
        }
    }

    /**
     * Cancel / Void an invoice.
     *
     * @return void
     */
    public function cancel(): void
    {
        header('Content-Type: application/json');

        if (!Session::isLogged()) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => Messages::ERR_AUTH_SESSION]);
            exit();
        }

        $invoiceId = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if ($invoiceId <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'ID de factura no válido']);
            exit();
        }

        $invoice = $this->invoiceModel->getById($invoiceId);
        if (!$invoice) {
            echo json_encode(['status' => 'error', 'message' => 'Factura no encontrada']);
            exit();
        }

        if (intval($invoice['id_estado']) === 2) {
            echo json_encode(['status' => 'error', 'message' => 'Esta factura ya está anulada']);
            exit();
        }

        // Void the invoice
        $success = $this->invoiceModel->updateStatus($invoiceId, 2); // Status: anulada (2)

        if ($success) {
            echo json_encode([
                'status' => 'success',
                'message' => '¡Factura anulada exitosamente!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se pudo anular la factura, intente nuevamente'
            ]);
        }
        exit();
    }

    /**
     * Show printable invoice details.
     *
     * @return void
     */
    public function show(): void
    {
        if (!Session::isLogged()) {
            redirect('login');
        }

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id <= 0) {
            redirect('invoices');
        }

        $invoice = $this->invoiceModel->getById($id);
        if (!$invoice) {
            redirect('invoices');
        }

        $invoiceDetails = $this->invoiceDetailModel->getByInvoiceId($id);

        $title = 'Detalle de Factura - ' . $invoice['numero_factura'];
        $bodyClass = 'layout-footer';
        $extraScripts = ['js/sidebar.js'];

        require_once __DIR__ . '/../views/invoices/show.php';
    }
}
