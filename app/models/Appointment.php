<?php
//Modelo para manejar los datos relacionados con las citas

namespace App\Models;

use App\Core\Model;
use PDO;

class Appointment extends Model
{

    protected $tableName = "citas";

    //Método para crear una nueva cita
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

    //Método para crear registros de servicios seleccionados en la tabla intermedia detalles_cita
    public function attachServices(int $appointmentId, array $serviceIds): bool
    {
        $serviceIds = array_filter(array_map('intval', $serviceIds), fn ($id) => $id > 0);

        if (empty($serviceIds)) {
            return false;
        }

        $placeholders = implode(', ', array_fill(0, count($serviceIds), '(?, ?)'));
        $sql = "INSERT INTO detalles_cita (id_cita, id_servicio) VALUES $placeholders";

        $params = [];
        foreach ($serviceIds as $serviceId) {
            $params[] = $appointmentId;
            $params[] = $serviceId;
        }

        $this->query($sql, $params);

        return true;
    }

    //Método de conveniencia para crear la cita y sus detalles de servicio de forma atómica
    public function createWithServices($clientId, $employeeId, $date, $startTime, $endTime, $totalAmount, $stateId, $notes, array $serviceIds)
    {
        $this->db->beginTransaction();

        try {
            $appointmentId = $this->create($clientId, $employeeId, $date, $startTime, $endTime, $totalAmount, $stateId, $notes);
            $this->attachServices($appointmentId, $serviceIds);
            $this->db->commit();

            return $appointmentId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    //Método para obtener todas las citas activas con información del cliente, estado y servicios agendados
    public function getAll()
    {

        $sql = "SELECT ci.id, cl.nombre, cl.apellido, ci.created_at AS creada, ci.fecha, ci.hora_inicio, ci.hora_fin, ci.monto_total, ci.notas, e.nombre AS estado_nombre, " .
            "GROUP_CONCAT(DISTINCT s.nombre ORDER BY s.nombre SEPARATOR ', ') AS servicios " .
            "FROM " . $this->tableName . " ci " .
            "JOIN clientes cl ON ci.id_cliente = cl.id " .
            "JOIN estados_cita e ON ci.id_estado = e.id " .
            "LEFT JOIN detalles_cita dc ON ci.id = dc.id_cita " .
            "LEFT JOIN servicios s ON dc.id_servicio = s.id " .
            "WHERE ci.id_estado = 1 " .
            "GROUP BY ci.id, cl.nombre, cl.apellido, ci.created_at, ci.fecha, ci.hora_inicio, ci.hora_fin, ci.monto_total, ci.notas, e.nombre " .
            "ORDER BY ci.fecha ASC, ci.hora_inicio ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function countDistinctClients()
    {
        $sql = "SELECT COUNT(DISTINCT id_cliente) AS total FROM " . $this->tableName;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return intval($result['total'] ?? 0);
    }

    public function sumRevenue()
    {
        $sql = "SELECT COALESCE(SUM(monto_total), 0) AS total FROM " . $this->tableName;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return floatval($result['total'] ?? 0);
    }

    public function countByState(int $stateId)
    {
        $sql = "SELECT COUNT(*) AS total FROM " . $this->tableName . " WHERE id_estado = :stateId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':stateId' => $stateId]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return intval($result['total'] ?? 0);
    }

    public function countTodayAppointments()
    {
        $sql = "SELECT COUNT(*) AS total FROM " . $this->tableName . " WHERE DATE(fecha) = CURDATE()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return intval($result['total'] ?? 0);
    }
}
