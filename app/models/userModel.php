<?php
//Modelo para manejar los comportamientos registro y el login

    class UserModel{

        private $conn;
        private $tableName = "users";

        public function __construct($db) {

        $this->conn = $db;

    }

        //Método para el registro
        public function register($name, $surname, $email, $password){

            $sql = "INSERT INTO " . $this->tableName . " (name, surname, email, password) VALUES (:name, :surname, :email, :password)";
            $stmt = $this->conn->prepare($sql);

            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":surname", $surname);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $passwordHash);

            return $stmt->execute();

        }

        //Método para validar que no existan dos usuarios con el mismo correo
        public function emailExists($email){

            $sql = "SELECT id_user FROM " . $this->tableName . " WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(":email", $email);
            $stmt->execute();

            return $stmt->rowCount() > 0;

        }

        //Método para el login
        public function login($email, $password){

            $query = "SELECT id_user, name, password FROM " . $this->tableName . " WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

                if($stmt->rowCount() > 0){

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    //Se verifica si la contraseña coincide con el hash
                    if(password_verify($password, $row['password'])){

                        return $row;

                    }

                }

            return false;

        }

    }

?>