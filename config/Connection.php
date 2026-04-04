<?php
//Archivo de conexión a la base de datos

class Connection {

    public static function getConnection() {

        try {

            //Usamos las constantes que definimos en config.php
            $link = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);

            $link->exec("set names utf8");
            return $link;

        } catch (PDOException $e) {

            // Si hay error, no mostramos la contraseña, solo un mensaje genérico
            die("Error en la conexión a la base de datos.");

        }
    }
}

?>