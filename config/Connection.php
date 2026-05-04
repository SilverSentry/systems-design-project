<?php
//Archivo para la conexión a la base de datos
//Se utiliza el patrón Singleton para asegurar que solo exista una instancia de la conexión a la base de datos

class Connection {

    private static $instance = null;
    private $pdo;

    private $host = "localhost";
    private $dbName = "ordo_stetic";
    private $user = "root";
    private $password = "";

    //Constructor privado para evitar instanciación directa
    private function __construct() {

        try {

            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbName . ";charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

        } catch (PDOException $exception) {

            die("Error de conexión: " . $exception->getMessage());

        }

    }

    //Método mágico que controla la existencia de la conexión
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;

    }

    //Método para obtener la conexión PDO
    public static function getConnection() {
        return self::getInstance()->pdo;
    }

    //Método para evitar la clonación de la instancia
    private function __clone() { }

}

?>