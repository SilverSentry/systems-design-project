<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class PaymentMethod extends Model
{
    protected $tableName = "metodos_pago";

    /**
     * Get all payment methods, seeding defaults first if the table is empty.
     *
     * @return array
     */
    public function getAll(): array
    {
        $this->seedDefaultMethods();

        $sql = "SELECT * FROM " . $this->tableName . " ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Seed default payment methods if none exist in the database.
     *
     * @return void
     */
    public function seedDefaultMethods(): void
    {
        $sql = "SELECT COUNT(*) as count FROM " . $this->tableName;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($result['count']) && (int)$result['count'] === 0) {
            $defaultMethods = [
                'Efectivo',
                'Punto de Venta',
                'Pago Móvil',
                'Transferencia',
                'Divisas (Dólares)'
            ];

            $insertSql = "INSERT INTO " . $this->tableName . " (nombre) VALUES (:name)";
            $insertStmt = $this->db->prepare($insertSql);

            foreach ($defaultMethods as $methodName) {
                $insertStmt->execute([':name' => $methodName]);
            }
        }
    }
}
