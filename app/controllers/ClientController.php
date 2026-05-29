<?php

namespace App\Controllers;

use App\Models\Client;
use App\Models\ClientAntecedent;
use App\Config\Connection;
use App\Core\Paths;
use App\Config\Messages;

class ClientController {

    public function register() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $clientModel = new Client();
            $clientAntecedentModel = new ClientAntecedent();

            $name = trim($_POST['name']);
            $surname = trim($_POST['surname']);
            $phone = trim($_POST['phone']);
            $dni = trim($_POST['dni']);
            $birthdate = trim($_POST['birthdate']);
            $gender = trim($_POST['gender']);

            //Obtenemos la conexión para manejar la transacción entre ambos modelos
            $db = Connection::getInstance()->getConnection();

            //Aseguramos que la cabecera de respuesta siempre indique JSON
            header('Content-Type: application/json');

            /*
            Bloque para las validaciones de los campos del formulario
            Se valida que no estén vacíos, que el nombre y apellido sean solo letras, que el teléfono tenga un formato válido, que la fecha de nacimiento sea una fecha válida y que se haya seleccionado un género
            */
            if(empty($name) || empty($surname) || empty($phone) || empty($dni) || empty($birthdate) || empty($gender)){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_EMPTY_FIELDS,
                    'field' => 'all'
                ]);
                exit();

            } elseif(!preg_match("/^[a-zA-z]+$/", $name)){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_NAME_INVALID,
                    'field' => 'name'
                ]);
                exit(); 

            } elseif(!preg_match("/^[a-zA-z]+$/", $surname)){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_SURNAME_INVALID,
                    'field' => 'surname'
                ]);
                exit();

            } elseif(!preg_match("/^\d{11}$/", $phone)){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_PHONE_INVALID,
                    'field' => 'phone'
                ]);
                exit();
            }elseif(!preg_match("/^\d{7,8}$/", $dni)){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_DNI_INVALID,
                    'field' => 'dni'
                ]);
                exit();

            } elseif(!strtotime($birthdate)){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_BIRTHDATE_INVALID,
                    'field' => 'birthdate'
                ]);
                exit();

            } elseif(!in_array($gender, ['Masculino', 'Femenino', 'Otro'])){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_EMPTY_FIELDS,
                    'field' => 'gender'
                ]);
                exit();

            }

            //Se utiliza un bloque try-catch para asegurar que ambos modelos se ejecuten correctamente o ninguno lo haga, manteniendo la integridad de los datos
            try {

                //Iniciamos una transacción para asegurar la integridad de los datos entre ambos modelos
                $db->beginTransaction();

                //1. Registrar el cliente
                $clientId = $clientModel->create(
                    $name,
                    $surname,
                    $phone,
                    $dni,
                    $birthdate,
                    $gender
                );

                //2. Registrar los antecedentes de BioPortal
                if(!empty($_POST['antecedentes']) && is_array($_POST['antecedentes'])) {
                    foreach ($_POST['antecedentes'] as $antecedente) {
                        
                        $clientAntecedentModel->create(
                            $clientId,
                            intval($antecedente['tipo_id']),
                            $antecedente['concept_id'], //CUI / Notation de BioPortal
                            htmlspecialchars($antecedente['term_name']),
                            'Declarado en el registro inicial de BioPortal.'
                        );
                    }
                }

                //Si todo salió bien en ambos modelos, confirmamos los cambios
                $db->commit();
                
                echo json_encode([
                    'status' => 'success',
                    'redirect' => Paths::to('clients/create?success')
                ]);
                exit();

            } catch (\Exception $e) {
                //Si cualquiera de los dos modelos falla, se cancela todo
                $db->rollBack();

                //Respondemos un JSON estructurado para el catch del Javascript
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error crítico en la base de datos al procesar el registro.',
                    'debug' => $e->getMessage() //Opcional: solo para guiarte en desarrollo
                ]);
                exit();
            }

        }

    }

}


?>