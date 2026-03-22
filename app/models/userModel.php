<?php

    class user{

        private $conn;
        private $tableName = "users";

        public function __construct($db) {

        $this->conn = $db; 

    }

        //Método para el registro
        public function register($name, $surname, $email, $password){

            $sql = "INSERT INTO " . $this->tableName . " (name, surname, email, password) VALUES (:name, :surname, :email, :password)";
            $stmt = $this->conn->prepare($sql);

            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":surname", $surname);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password_hash);

            return $stmt->execute();

        }

        //Método para el login
        public function login($email, $password) {

            $query = "SELECT id_user, name, password FROM " . $this->tableName . " WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

                if ($stmt->rowCount() > 0) {

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    //Se verifica si la contraseña coincide con el hash
                        if (password_verify($password, $row['password'])) {

                            return $row;

            }

        }

        return false;

    }

}

?>