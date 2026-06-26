<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Invoice extends Model
{
    protected $tableName = "facturas";

    /**
     * Get all invoices with related information.
     *
     * @return array
     */
    public function getAll(): array
    {
        $sql = "SELECT f.*, 
                       cl.nombre AS client_name, cl.apellido AS client_surname, cl.dni AS client_dni,
                       ci.fecha AS appointment_date,
                       u.nombre AS user_name, u.apellido AS user_surname,
                       mp.nombre AS payment_method_name,
                       ef.nombre AS status_name
                FROM " . $this->tableName . " f
                JOIN clientes cl ON f.id_cliente = cl.id
                JOIN citas ci ON f.id_cita = ci.id
                JOIN usuarios u ON f.id_usuario = u.id
                JOIN metodos_pago mp ON f.id_metodo_pago = mp.id
                JOIN estados_factura ef ON f.id_estado = ef.id
                ORDER BY f.fecha DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Get details of a single invoice by ID.
     *
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT f.*, 
                       cl.nombre AS client_name, cl.apellido AS client_surname, cl.dni AS client_dni, cl.telefono AS client_phone,
                       ci.fecha AS appointment_date,
                       u.nombre AS user_name, u.apellido AS user_surname,
                       mp.nombre AS payment_method_name,
                       ef.nombre AS status_name
                FROM " . $this->tableName . " f
                JOIN clientes cl ON f.id_cliente = cl.id
                JOIN citas ci ON f.id_cita = ci.id
                JOIN usuarios u ON f.id_usuario = u.id
                JOIN metodos_pago mp ON f.id_metodo_pago = mp.id
                JOIN estados_factura ef ON f.id_estado = ef.id
                WHERE f.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Generate the next sequential invoice number.
     *
     * @return string
     */
    public function generateInvoiceNumber(): string
    {
        $sql = "SELECT MAX(id) as max_id FROM " . $this->tableName;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $nextId = ($result['max_id'] ?? 0) + 1;
        return 'FAC-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Insert a new invoice.
     *
     * @param string $invoiceNumber
     * @param int $clientId
     * @param int $appointmentId
     * @param int $userId
     * @param float $subtotalUsd
     * @param float $ivaUsd
     * @param float $totalUsd
     * @param float $bcvRate
     * @param int $paymentMethodId
     * @param int $statusId
     * @return int The ID of the newly created invoice.
     */
    public function create(
        string $invoiceNumber,
        int $clientId,
        int $appointmentId,
        int $userId,
        float $subtotalUsd,
        float $ivaUsd,
        float $totalUsd,
        float $bcvRate,
        int $paymentMethodId,
        int $statusId = 1
    ): int {
        $sql = "INSERT INTO " . $this->tableName . " 
                (numero_factura, id_cliente, id_cita, id_usuario, subtotal_usd, iva_usd, total_usd, tasa_bcv, id_metodo_pago, id_estado) 
                VALUES (:invoiceNumber, :clientId, :appointmentId, :userId, :subtotalUsd, :ivaUsd, :totalUsd, :bcvRate, :paymentMethodId, :statusId)";

        $this->query($sql, [
            ':invoiceNumber' => $invoiceNumber,
            ':clientId' => $clientId,
            ':appointmentId' => $appointmentId,
            ':userId' => $userId,
            ':subtotalUsd' => $subtotalUsd,
            ':ivaUsd' => $ivaUsd,
            ':totalUsd' => $totalUsd,
            ':bcvRate' => $bcvRate,
            ':paymentMethodId' => $paymentMethodId,
            ':statusId' => $statusId
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Update the status of an invoice.
     *
     * @param int $id
     * @param int $statusId
     * @return bool
     */
    public function updateStatus(int $id, int $statusId): bool
    {
        $sql = "UPDATE " . $this->tableName . " SET id_estado = :statusId WHERE id = :id";
        $stmt = $this->query($sql, [
            ':id' => $id,
            ':statusId' => $statusId
        ]);

        return $stmt->rowCount() > 0;
    }
}
