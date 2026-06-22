<?php
//Logger para registrar eventos, errores y mensajes importantes en un archivo de log

namespace App\Core;
class Logger
{

    public static function log(string $message, string $level = 'INFO'): void
    {

        //Calculamos la ruta desde core/ hasta la raíz del proyecto y luego a storage/logs
        $logDir = dirname(__DIR__) . '/storage/logs';

        //Si la carpeta no existe por ser la primera vez, la creamos de forma segura
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $filePath = $logDir . '/app.log';

        //Formato profesional en hora de Caracas: [2026-06-01 22:00:00] [ERROR] Mensaje del log
        $date = (new \DateTimeImmutable('now', new \DateTimeZone('America/Caracas')))->format('Y-m-d H:i:s');
        $formattedMessage = "[{$date}] [{$level}] {$message}" . PHP_EOL;

        //Escribe al final del archivo sin borrar lo anterior (FILE_APPEND)
        file_put_contents($filePath, $formattedMessage, FILE_APPEND);
    }
}
