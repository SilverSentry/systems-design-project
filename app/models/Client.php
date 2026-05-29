<?php
//Archivo modelo para el cliente

namespace App\Models;

use App\Core\Model;

class Client extends Model {

    protected $tableName = "clientes";

    //Método para registrar un nuevo cliente
    public function create($name, $surname, $phone, $dni, $birthdate, $gender, $roleId = 3) {

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

    //Método para obtener todos los clientes
    public function read() {
        return $this->findAll();
    }

}

?>