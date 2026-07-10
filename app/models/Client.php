<?php
//Modelo para manejar los datos relacionados con los clientes, tanto para el registro como para otras operaciones relacionadas con los clientes

namespace App\Models;

use App\Core\Model;

class Client extends Model
{

    protected $tableName = "clientes";

    //Método para registrar un nuevo cliente
    public function create($name, $surname, $phone, $dni, $birthdate, $gender, $roleId = 3)
    {

        $sql = "INSERT INTO " . $this->tableName . " (nombre, apellido, telefono, dni, fecha_nacimiento, genero, id_rol) VALUES (:name, :surname, :phone, :dni, :birthdate, :gender, :roleId)";
        $stmt = $this->query($sql, [
            ':name' => $name,
            ':surname' => $surname,
            ':phone' => $phone,
            ':dni' => $dni,
            ':birthdate' => $birthdate,
            ':gender' => $gender,
            ':roleId' => $roleId
        ]);
        //Después de ejecutar la consulta, obtenemos el ID del cliente recién creado
        return $this->db->lastInsertId();
    }

    //Método para obtener todos los clientes activos
    public function read()
    {

        $sql = "SELECT c.*, r.nombre AS rol_nombre FROM " . $this->tableName . " c LEFT JOIN roles r ON c.id_rol = r.rol WHERE c.id_estado = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    //Método para obtener un cliente por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE id = :id";
        $stmt = $this->query($sql, [':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //Método para actualizar un cliente
    public function update($id, $name, $surname, $phone, $dni, $birthdate, $gender)
    {
        $sql = "UPDATE " . $this->tableName . " SET nombre = :name, apellido = :surname, telefono = :phone, dni = :dni, fecha_nacimiento = :birthdate, genero = :gender WHERE id = :id";
        $this->query($sql, [
            ':id' => $id,
            ':name' => $name,
            ':surname' => $surname,
            ':phone' => $phone,
            ':dni' => $dni,
            ':birthdate' => $birthdate,
            ':gender' => $gender
        ]);
        return true;
    }

    //Método para desactivar (eliminar lógico) un cliente
    public function deactivate($id)
    {
        $sql = "UPDATE " . $this->tableName . " SET id_estado = 2 WHERE id = :id";
        $this->query($sql, [':id' => $id]);
        return true;
    }
}
