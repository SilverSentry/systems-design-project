<?php
//Archivo modelo para el cliente

class Client extends Model {

    protected $tableName = "clientes";

    //Método para registrar un nuevo cliente
    public function create($name, $surname, $phone, $birthdate, $gender, $roleId = 3) {

        $sql = "INSERT INTO " . $this->tableName . " (nombre, apellido, telefono, fecha_nacimiento, genero, id_rol) VALUES (:name, :surname, :phone, :birthdate, :gender, :roleId)";
        $stmt = $this->query($sql, [
            ':name' => $name,
            ':surname' => $surname,
            ':phone' => $phone,
            ':birthdate' => $birthdate,
            ':gender' => $gender,
            ':roleId' => $roleId
        ]);
        //Después de ejecutar la consulta, obtenemos el ID del cliente recién creado
        return $this->db->lastInsertId();
    }

}

?>