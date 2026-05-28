<?php

class ClientController {

    public function register() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $clientModel = new Client();
            $clientAntecedentModel = new ClientAntecedent();

            $db = Connection::getInstance()->getConnection();

            try {
                $db->beginTransaction();

                //1. Registrar el cliente usando su propio modelo
                $clientId = $clientModel->create(
                    $_POST['name'],
                    $_POST['surname'],
                    $_POST['phone'],
                    $_POST['birthdate'],
                    $_POST['gender']
                );

                //2. Registrar los antecedentes de BioPortal usando su modelo correspondiente
                if (!empty($_POST['antecedentes']) && is_array($_POST['antecedentes'])) {
                    foreach ($_POST['antecedentes'] as $antecedente) {
                        
                        $clientAntecedentModel->create(
                            $clientId,
                            intval($antecedente['tipo_id']),
                            $antecedente['concept_id'], // CUI / Notation de BioPortal
                            htmlspecialchars($antecedente['term_name']),
                            'Declarado en el registro inicial de BioPortal.'
                        );
                    }
                }

                //Si todo salió bien en ambos modelos, confirmamos los cambios
                $db->commit();
                
                header("Location: " . Paths::to('clients/create?success'));
                exit();

            } catch (Exception $e) {
                //Si cualquiera de los dos modelos falla, se cancela todo
                $db->rollBack();
                die("Error en la transacción de modelos: " . $e->getMessage());
            }

        }

    }

}

?>