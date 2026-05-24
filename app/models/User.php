<?php
//Archivo modelo para manejar la lógica de usuarios

    class User extends Model {

        //Se define el nombre de la tabla para este modelo
        protected $tableName = "usuarios";

        //Método para el registro
        public function register($name, $surname, $email, $password){

            $sql = "INSERT INTO " . $this->tableName . " (nombre, apellido, email, password, fk_id_rol) VALUES (:name, :surname, :email, :password, 1)";
            //Se utiliza el método auxiliar para ejecutar la consulta con los parámetros
            $stmt = $this->query($sql, [
                ':name' => $name,
                ':surname' => $surname,
                ':email' => $email,
                ':password' => password_hash($password, PASSWORD_BCRYPT)
            ]);

            return $stmt !== false;

        }

        //Método para validar que no existan dos usuarios con el mismo correo
        public function emailExists($email){

            $sql = "SELECT id_usuario FROM " . $this->tableName . " WHERE email = :email LIMIT 1";
            $stmt = $this->query($sql, [':email' => $email]);
            return $stmt->rowCount() > 0;

        }

        //Método para el login
        public function login($email, $password){

            $query = "SELECT id_usuario, nombre, password FROM " . $this->tableName . " WHERE email = :email";
            $stmt = $this->query($query, [':email' => $email]);

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