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
use Dompdf\Dompdf;
use Dompdf\Options;

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

        // Cargar la tasa BCV en caché para mostrar/calcular totales en Bs.
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
        
        // Asegurar que existan métodos de pago
        $paymentMethods = $this->paymentMethodModel->getAll();
        
        // Cargar citas que aún no han sido facturadas
        $appointments = $this->appointmentModel->getUnbilled();

        // Cargar la tasa BCV en caché
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

    public function downloadPDF(): void
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

        $statusName = htmlspecialchars(strtoupper($invoice['status_name'] ?? 'PAGADA'));
        $badgeClass = strtolower($invoice['status_name'] ?? '') === 'anulada' ? 'badge-anulada' : '';
        $clientName = htmlspecialchars(ucfirst($invoice['client_name']) . ' ' . ucfirst($invoice['client_surname']));
        $clientDni = htmlspecialchars($invoice['client_dni'] ?: 'S/D');
        $clientPhone = htmlspecialchars($invoice['client_phone'] ?: 'S/D');
        $employeeName = htmlspecialchars(ucfirst($invoice['user_name']) . ' ' . ucfirst($invoice['user_surname']));
        $paymentMethod = htmlspecialchars($invoice['payment_method_name'] ?? 'No definido');
        $invoiceNumber = htmlspecialchars($invoice['numero_factura'] ?? 'SIN-NUMERO');
        $invoiceDate = htmlspecialchars(date('d/m/Y h:i A', strtotime($invoice['fecha'])));
        $appointmentDate = htmlspecialchars(date('d/m/Y', strtotime($invoice['appointment_date'])));

        $detailsRows = '';
        foreach ($invoiceDetails as $detail) {
            $serviceName = htmlspecialchars($detail['service_name'] ?? 'Servicio');
            $serviceDescription = htmlspecialchars($detail['service_description'] ?: 'Sin descripción');
            $quantity = intval($detail['cantidad'] ?? 1);
            $unitPrice = number_format(floatval($detail['precio_unitario'] ?? 0), 2, ',', '.');
            $total = number_format(floatval($detail['total'] ?? 0), 2, ',', '.');

            $detailsRows .= "<tr>
                <td>
                    <div class='fw-semibold'>{$serviceName}</div>
                    <small class='text-muted'>{$serviceDescription}</small>
                </td>
                <td class='text-center'>{$quantity}</td>
                <td class='text-end'>\${$unitPrice}</td>
                <td class='text-end'>\${$total}</td>
            </tr>";
        }

        $subtotalUsd = number_format(floatval($invoice['subtotal_usd'] ?? 0), 2, ',', '.');
        $ivaUsd = number_format(floatval($invoice['iva_usd'] ?? 0), 2, ',', '.');
        $totalUsd = number_format(floatval($invoice['total_usd'] ?? 0), 2, ',', '.');
        $bcvRate = number_format(floatval($invoice['tasa_bcv'] ?? 0), 2, ',', '.');
        $totalVes = number_format(floatval($invoice['total_usd'] ?? 0) * floatval($invoice['tasa_bcv'] ?? 0), 2, ',', '.');

        $html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {$invoiceNumber}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; margin: 24px; }
        .header { border-bottom: 2px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 20px; }
        .company { font-size: 20px; font-weight: 700; margin: 0; }
        .muted { color: #6b7280; font-size: 12px; }
        .badge { display: inline-block; padding: 6px 10px; border-radius: 999px; color: #fff; background: #16a34a; font-size: 12px; font-weight: 700; }
        .badge-anulada { background: #dc2626; }
        .section-title { font-size: 14px; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; }
        th { background: #f9fafb; text-align: left; }
        .totals { width: 100%; margin-top: 16px; }
        .totals td { border: 0; padding: 4px 0; font-size: 13px; }
        .text-end { text-align: right; }
        .footer { margin-top: 28px; text-align: center; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <p class="company">STUDIO ORDO STETIC</p>
                <div class="muted">RIF: J-0000000-0</div>
                <div class="muted">Avenida Santa Rosa, Valencia, Edo. Carabobo.</div>
                <div class="muted">Teléfono: (0412) 000-000</div>
            </div>
            <div style="text-align: right;">
                <span class="badge {$badgeClass}">{$statusName}</span>
                <h2 style="margin: 8px 0 4px;">{$invoiceNumber}</h2>
                <div class="muted">Fecha: {$invoiceDate}</div>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; gap: 20px; margin-bottom: 20px;">
        <div style="width: 48%;">
            <div class="section-title">Cliente</div>
            <div style="font-size: 13px; line-height: 1.5;">{$clientName}</div>
            <div style="font-size: 13px; line-height: 1.5;">Cédula: {$clientDni}</div>
            <div style="font-size: 13px; line-height: 1.5;">Teléfono: {$clientPhone}</div>
        </div>
        <hr>
        <div style="width: 48%;">
            <div class="section-title">Detalles de Operación</div>
            <div style="font-size: 13px; line-height: 1.5;">Cita Relacionada: #{$invoice['id_cita']}</div>
            <div style="font-size: 13px; line-height: 1.5;">Fecha de Cita: {$appointmentDate}</div>
            <div style="font-size: 13px; line-height: 1.5;">Atendido por: {$employeeName}</div>
        </div>
    </div>

    <div class="section-title">Detalle de Servicios</div>
    <table>
        <thead>
            <tr>
                <th>Servicio</th>
                <th style="width: 80px; text-align: center;">Cant.</th>
                <th style="width: 120px; text-align: right;">Precio Unit.</th>
                <th style="width: 120px; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            {$detailsRows}
        </tbody>
    </table>

    <table class="totals">
        <tr><td class="text-end">Subtotal USD:</td><td class="text-end">\${$subtotalUsd}</td></tr>
        <tr><td class="text-end">IVA (16%):</td><td class="text-end">\${$ivaUsd}</td></tr>
        <tr><td class="text-end" style="font-weight: 700;">Total USD:</td><td class="text-end" style="font-weight: 700;">\${$totalUsd}</td></tr>
        <tr><td class="text-end">Tasa BCV:</td><td class="text-end">Bs. {$bcvRate}</td></tr>
        <tr><td class="text-end" style="font-weight: 700;">Total VES:</td><td class="text-end" style="font-weight: 700;">Bs. {$totalVes}</td></tr>
        <tr><td class="text-end">Método de Pago:</td><td class="text-end">{$paymentMethod}</td></tr>
    </table>

    <div class="footer">
        <p>¡Gracias por preferir a Studio Ordo Stetic!</p>
        <p>Documento no fiscal emitido por el sistema.</p>
    </div>
</body>
</html>
HTML;

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $safeInvoiceNumber = preg_replace('/[^A-Za-z0-9\-]+/', '-', $invoice['numero_factura'] ?? 'factura');
        $filename = 'factura-' . trim($safeInvoiceNumber, '-') . '.pdf';

        if (ob_get_level()) {
            ob_end_clean();
        }

        $dompdf->stream($filename, ['Attachment' => true]);
        exit();
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

        // Verificar los detalles de la cita y comprobar si ya está facturada
        $details = $this->appointmentModel->getDetailsForBilling($appointmentId);
        if (!$details) {
            echo json_encode(['status' => 'error', 'message' => 'Cita no encontrada o no válida']);
            exit();
        }

        // Comprobar si ya existe una factura para esta cita
        $checkSql = "SELECT id FROM facturas WHERE id_cita = :appointmentId LIMIT 1";
        $checkStmt = Connection::getConnection()->prepare($checkSql);
        $checkStmt->execute([':appointmentId' => $appointmentId]);
        if ($checkStmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'Esta cita ya ha sido facturada previamente']);
            exit();
        }

        // Obtener la información de precios e información del usuario
        $services = $details['services'];
        if (empty($services)) {
            echo json_encode(['status' => 'error', 'message' => 'La cita no tiene servicios asociados para facturar']);
            exit();
        }

        $user = Session::getUser();
        $userId = intval($user['id'] ?? 1);

        // Cargar la tasa BCV actual
        $cacheFile = dirname(__DIR__, 2) . '/storage/tasa.json';
        $bcvRate = 1.0;
        if (file_exists($cacheFile)) {
            $cacheData = json_decode(file_get_contents($cacheFile), true);
            $bcvRate = floatval($cacheData['bcv'] ?? 1.0);
        }

        // Calcular precios
        $subtotalUsd = 0.0;
        foreach ($services as $service) {
            $subtotalUsd += floatval($service['precio']);
        }
        $ivaUsd = $subtotalUsd * 0.16; // IVA estándar 16%
        $totalUsd = $subtotalUsd + $ivaUsd;

        $db = Connection::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            // Generar número de factura secuencial
            $invoiceNumber = $this->invoiceModel->generateInvoiceNumber();

            // 1. Crear la factura
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
                1 // Estado: pagada (1)
            );

            // 2. Crear los detalles de la factura
            foreach ($services as $service) {
                $servicePrice = floatval($service['precio']);
                $this->invoiceDetailModel->create(
                    $invoiceId,
                    intval($service['id']),
                    1, // Cantidad predeterminada 1 para servicios estéticos
                    $servicePrice,
                    $servicePrice
                );
            }

            // 3. Actualizar el estado de la cita a 'Asistida' (ID 2)
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

        // Anular la factura
        $success = $this->invoiceModel->updateStatus($invoiceId, 2); // Estado: anulada (2)

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
