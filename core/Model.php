<?php
//Archivo modelo base

abstract class Model {

    protected $db;
    protected $tableName; //Cada modelo hijo debe definir su tabla

    public function __construct() {
        //Se obtiene la conexión PDO a la base de datos
        $this->db = Connection::getConnection();
    }

    //Método para obtener todos los registros de la tabla
    public function findAll() {

        $stmt = $this->db->prepare("SELECT * FROM {$this->tableName}");
        $stmt->execute();
        return $stmt->fetchAll();

    }

    //Método para obtener un registro por su ID
    public function findById($id) {

        $stmt = $this->db->prepare("SELECT * FROM {$this->tableName} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();

    }

    //Método auxiliar útil para ejecutar cualquier query con parámetros
    protected function query($sql, $params = []) {

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;

    }

}

?>