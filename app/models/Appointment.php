<?php
//Modelo para manejar los datos relacionados con las citas

namespace App\Models;

use App\Core\Model;
use PDO;

class Appointment extends Model
{

    protected $tableName = "citas";

    public function create($clientId, $employeeId, $date, $startTime, $endTime, $totalAmount, $stateId, $notes)
    {

        $sql = "INSERT INTO " . $this->tableName . " (id_cliente, id_empleado, fecha, hora_inicio, hora_fin, monto_total, id_estado, notas) VALUES (:clientId, :employeeId, :date, :startTime, :endTime, :totalAmount, :stateId, :notes)";

        $stmt = $this->query($sql, [
            ':clientId' => $clientId,
            ':employeeId' => $employeeId,
            ':date' => $date,
            ':startTime' => $startTime,
            ':endTime' => $endTime,
            ':totalAmount' => $totalAmount,
            ':stateId' => $stateId,
            ':notes' => $notes
        ]);

        return $this->db->lastInsertId();
    }

    public function getAll(){

        $sql = "SELECT ci.id, cl.nombre, cl.apellido, ci.created_at AS creada, ci.fecha, ci.hora_inicio, ci.hora_fin, ci.monto_total, ci.notas, e.nombre AS estado_nombre " .
               "FROM " . $this->tableName . " ci " .
               "JOIN clientes cl ON ci.id_cliente = cl.id " .
               "JOIN estados_cita e ON ci.id_estado = e.id " .
               "WHERE ci.id_estado = 1 " .
               "ORDER BY ci.fecha ASC, ci.hora_inicio ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    }
}
