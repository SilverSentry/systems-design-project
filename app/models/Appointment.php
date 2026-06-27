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
        $serviceIds = array_filter(array_map('intval', $serviceIds), fn($id) => $id > 0);

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
    public function getAll(): array
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

    /**
     * Get all active appointments that have not been invoiced yet.
     *
     * @return array
     */
    public function getUnbilled(): array
    {
        $sql = "SELECT ci.id, cl.nombre AS client_name, cl.apellido AS client_surname, cl.dni AS client_dni, ci.fecha, ci.monto_total 
                FROM " . $this->tableName . " ci
                JOIN clientes cl ON ci.id_cliente = cl.id
                LEFT JOIN facturas f ON ci.id = f.id_cita
                WHERE f.id IS NULL AND ci.id_estado IN (1, 2)
                ORDER BY ci.fecha DESC, ci.hora_inicio DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Get details of an appointment for billing, including client information and services.
     *
     * @param int $appointmentId
     * @return array|null
     */
    public function getDetailsForBilling(int $appointmentId): ?array
    {
        $sql = "SELECT ci.id, ci.monto_total, 
                       cl.id AS client_id, cl.nombre AS client_name, cl.apellido AS client_surname, cl.dni AS client_dni, cl.telefono AS client_phone
                FROM " . $this->tableName . " ci
                JOIN clientes cl ON ci.id_cliente = cl.id
                WHERE ci.id = :appointmentId";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':appointmentId' => $appointmentId]);
        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$appointment) {
            return null;
        }

        // Cargar servicios
        $servicesSql = "SELECT s.id, s.nombre, s.precio 
                        FROM detalles_cita dc
                        JOIN servicios s ON dc.id_servicio = s.id
                        WHERE dc.id_cita = :appointmentId";

        $servicesStmt = $this->db->prepare($servicesSql);
        $servicesStmt->execute([':appointmentId' => $appointmentId]);
        $appointment['services'] = $servicesStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return $appointment;
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
