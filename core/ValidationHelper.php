<?php
//ValidationHelper para centralizar la validación de datos en el backend y responder con JSON

namespace App\Core;

use App\Config\Messages;
use App\Core\Session;

class ValidationHelper
{

    /**
     * Ejecuta las reglas de validación
     * Cada regla es un array con 'condition', 'message' y 'field'
     * Si alguna condición se cumple, puede responder JSON o redirigir con flash
     *
     * @param array $rules
     * @param string|null $redirect
     * @param string|null $flashKey
     * @return void
     */
    public static function validate(array $rules, ?string $redirect = null, ?string $flashKey = null): void
    {
        foreach ($rules as $rule) {
            if (!empty($rule['condition'])) {
                $errorPayload = [
                    'status' => 'error',
                    'message' => $rule['message'] ?? Messages::UNEXPECTED_ERR,
                    'field' => $rule['field'] ?? 'all'
                ];

                if ($redirect !== null) {
                    if ($flashKey !== null) {
                        Session::flash($flashKey, [
                            'message' => $errorPayload['message'],
                            'field' => $errorPayload['field']
                        ]);
                    }
                    \redirect($redirect);
                }

                header('Content-Type: application/json');
                echo json_encode($errorPayload);
                exit();
            }
        }
    }
}
