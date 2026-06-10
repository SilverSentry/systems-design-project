<?php
//Controlador para manejar las solicitudes API

namespace App\Controllers;

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
        $jsonPath = dirname(__DIR__, 2) . '/core/snomed_catalog.json';

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
}
