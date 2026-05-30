<?php

namespace App\Core;

use App\Config\Messages;

class ValidationHelper {

    /**
     * Ejecuta las reglas de validación pasadas
     * Cada regla es un array con 'condition', 'message' y 'field'
     * Si alguna condición se cumple, responde JSON y termina la ejecución
     *
     * @param array $rules
     * @return void
     */
    public static function validate(array $rules) {

        header('Content-Type: application/json');

        foreach($rules as $rule) {

            if(!empty($rule['condition'])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => $rule['message'] ?? Messages::UNEXPECTED_ERR,
                    'field' => $rule['field'] ?? 'all'
                ]);
                exit();
            }
        }

    }

}

?>