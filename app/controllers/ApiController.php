<?php
//Controlador para manejar las solicitudes API

namespace App\Controllers;

use DateTimeImmutable;
use DateTimeZone;
use App\Config\Messages;

class ApiController
{

    /**
     * Maneja la búsqueda de términos médicos de forma local
     * Consume el catálogo JSON interno del sistema
     */
    public function search()
    {
        header('Content-Type: application/json');

        //Bloqueamos para que solo responda si viene el parámetro 'q' con al menos 3 caracteres
        if (!isset($_GET['q']) || strlen(trim($_GET['q'])) < 3) {
            echo json_encode(['items' => []]);
            exit();
        }

        $queryStr = trim($_GET['q']);

        //Llamamos directamente al motor de búsqueda local
        $localItems = $this->searchLocalCatalog($queryStr);

        //Retornamos los resultados de inmediato al frontend
        echo json_encode(['items' => $localItems]);
        exit();
    }

    /**
     * Busca coincidencias en el catálogo JSON local
     */
    private function searchLocalCatalog(string $searchString): array
    {

        //Alternativa segura usando la raíz del proyecto
        $jsonPath = dirname(__DIR__, 2) . '/storage/snomed_catalog.json';

        //Verificación defensiva por seguridad si el archivo no existe
        if (!file_exists($jsonPath)) {
            return [];
        }

        //Leemos el contenido del archivo y lo transformamos a un array asociativo de PHP
        $jsonContent = file_get_contents($jsonPath);
        $catalog = json_decode($jsonContent, true) ?: [];

        $results = [];

        //Convertimos la búsqueda a minúsculas para una comparación insensible a mayúsculas/minúsculas
        $searchString = mb_strtolower($searchString, 'UTF-8');

        //Iteramos sobre el catálogo extraído del JSON
        foreach ($catalog as $row) {
            $termLower = mb_strtolower($row['term'], 'UTF-8');

            //Si coincide la búsqueda parcial
            if (strpos($termLower, $searchString) !== false) {

                $results[] = [
                    'term' => $row['term'],
                    'conceptId' => $row['conceptId']
                ];
            }
        }

        //Limitamos a un máximo de 10 resultados para no sobrecargar la interfaz
        return array_slice($results, 0, 10);
    }

    /**
     * Maneja la actualización de la tasa oficial del BCV
     * Consume la API comunitaria del BCV y guarda el resultado
     * Ese resultado guardado se puede usar para mostrar la tasa
     * en el navbar sin necesidad de consultar el BCV cada vez
     */
    public function updateTasa() {
        header('Content-Type: application/json');

        //URL de la API pública del dólar que responde JSON de forma consistente
        $url = "https://ve.dolarapi.com/v1/dolares/oficial";
        
        //Opciones para la solicitud HTTP, incluyendo un User-Agent personalizado
        $options = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: StudioOrdoSteticApp/1.0\r\n"
            ]
        ];
        
        //Creamos el contexto de la solicitud con las opciones definidas
        $context = stream_context_create($options);
        
        try {
            $response = @file_get_contents($url, false, $context);
            
            if ($response === FALSE) {
                http_response_code(503);
                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_TASA_BCV_CONNECTION_FAILED
                ]);
                exit;
            }

            $data = json_decode($response, true);
            $dollarPrice = $data['promedio'] ?? $data['price'] ?? $data['efectivo'] ?? $data['venta'] ?? $data['compra'] ?? null;
            
            if ($dollarPrice === null || $dollarPrice === '') {
                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_TASA_BCV_INVALID_RESPONSE
                ]);
                exit;
            }

            $finalPrice = round($dollarPrice, 2);
            $caracasNow = new DateTimeImmutable('now', new DateTimeZone('America/Caracas'));
            $storedDate = $caracasNow->format('Y-m-d H:i:s');

            //Guardamos el nuevo valor de forma física en tu carpeta storage
            $archivoCache = dirname(__DIR__, 2) . '/storage/tasa.json';
            file_put_contents($archivoCache, json_encode([
                'bcv' => $finalPrice,
                'date' => $storedDate,
                'timezone' => 'America/Caracas'
            ]));

            echo json_encode([
                'status' => 'success',
                'message' => Messages::SUCCESS_TASA_BCV_UPDATED,
                'bcv' => $finalPrice,
                'date' => $caracasNow->format('d-m-Y h:i A')
            ]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit();
    }
}
