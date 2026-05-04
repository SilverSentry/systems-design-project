<?php

    class UserModel{

        private $conn;
        private $tableName = "users";

        //Método para el registro
        public function register($name, $surname, $email, $password){

            //Se obtiene la conexión a la base de datos
            $db = Connection::getConnection();

            $sql = "INSERT INTO " . $this->tableName . " (name, surname, email, password) VALUES (:name, :surname, :email, :password)";
            $stmt = $db->prepare($sql);

            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":surname", $surname);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $passwordHash);

            return $stmt->execute();

        }

        //Método para validar que no existan dos usuarios con el mismo correo
        public function emailExists($email){

            $db = Connection::getConnection();

            $sql = "SELECT id_user FROM " . $this->tableName . " WHERE email = :email LIMIT 1";
            $stmt = $db->prepare($sql);

            $stmt->bindParam(":email", $email);
            $stmt->execute();

            return $stmt->rowCount() > 0;

        }

        //Método para el login
        public function login($email, $password){

            $db = Connection::getConnection();

            $query = "SELECT id_user, name, password FROM " . $this->tableName . " WHERE email = :email";
            $stmt = $db->prepare($query);
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