<?php
//Archivo de conexión a la base de datos

//Configuración de la base de datos
Class connection {

    private $host = "localhost";
    private $dbName = "ordo_stetic";
    private $user = "root";
    private $password = "";
    public $conn;

    //Método para obtener la conexión a la base de datos
    public function getConnection() {

        //Inicializamos la conexión como null
        $this->conn = null;

        //Intentamos conectar a la base de datos mendiante un bloque try-catch
        try {

            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbName, $this->user, $this->password);
            $this->conn->exec("set names utf8");

        } catch (PDOException $exception) {

            die("Error de conexión: " . $exception->getMessage());

        }

        //Retornamos si la conexión fue exitosa
        return $this->conn;

    }

}

?>