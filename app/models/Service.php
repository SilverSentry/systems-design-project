<?php
//Modelo para manejar los datos relacionados con los servicios disponibles

namespace App\Models;

use App\Core\Model;
use PDO;

class Service extends Model
{
    protected $tableName = "servicios";

    public function getAll()
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function create(string $name, string $description, float $price)
    {
        $sql = "INSERT INTO " . $this->tableName . " (nombre, descripcion, precio, estado) VALUES (:nombre, :descripcion, :precio, 1)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre' => $name,
            ':descripcion' => $description,
            ':precio' => $price
        ]);

        return intval($this->db->lastInsertId());
    }

    public function getByIds(array $ids)
    {
        $ids = array_filter(array_map('intval', $ids), function ($id) {
            return $id > 0;
        });

        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT * FROM " . $this->tableName . " WHERE id IN ($placeholders) AND estado = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($ids);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function countActive()
    {
        $sql = "SELECT COUNT(*) AS total FROM " . $this->tableName . " WHERE estado = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return intval($result['total'] ?? 0);
    }
}
