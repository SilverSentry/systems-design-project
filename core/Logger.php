<?php

namespace App\Core;

/**
 * Clase Logger para registrar eventos, errores y mensajes importantes en un archivo de log
 */
class Logger {

    public static function log(string $message, string $level = 'INFO'): void {

        //Calculamos la ruta subiendo desde app/Core hacia la raíz, entrando a storage/logs
        $logDir = dirname(__DIR__, 2) . '/storage/logs';

        //Si la carpeta no existe por ser la primera vez, la creamos de forma segura
        if(!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $filePath = $logDir . '/app.log';

        //Formato profesional: [2026-06-01 22:00:00] [ERROR] Mensaje del log
        $date = date('Y-m-d H:i:s');
        $formattedMessage = "[{$date}] [{$level}] {$message}" . PHP_EOL;

        //Escribe al final del archivo sin borrar lo anterior (FILE_APPEND)
        file_put_contents($filePath, $formattedMessage, FILE_APPEND);
    }

}