<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class InvoiceDetail extends Model
{
    protected $tableName = "detalles_factura";

    /**
     * Insert a new invoice detail line item.
     *
     * @param int $invoiceId
     * @param int $serviceId
     * @param int $quantity
     * @param float $unitPrice
     * @param float $total
     * @return int The ID of the newly created detail.
     */
    public function create(int $invoiceId, int $serviceId, int $quantity, float $unitPrice, float $total): int
    {
        $sql = "INSERT INTO " . $this->tableName . " 
                (id_factura, id_servicio, cantidad, precio_unitario, total) 
                VALUES (:invoiceId, :serviceId, :quantity, :unitPrice, :total)";

        $this->query($sql, [
            ':invoiceId' => $invoiceId,
            ':serviceId' => $serviceId,
            ':quantity' => $quantity,
            ':unitPrice' => $unitPrice,
            ':total' => $total
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Get all details for a specific invoice.
     *
     * @param int $invoiceId
     * @return array
     */
    public function getByInvoiceId(int $invoiceId): array
    {
        $sql = "SELECT df.*, s.nombre AS service_name, s.descripcion AS service_description
                FROM " . $this->tableName . " df
                JOIN servicios s ON df.id_servicio = s.id
                WHERE df.id_factura = :invoiceId";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':invoiceId' => $invoiceId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
