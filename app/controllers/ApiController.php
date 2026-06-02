<?php

namespace App\Controllers;

class ApiController {

    //Método para manejar la búsqueda en SNOMED CT a través de BioPortal
    public function search() {

        //Bloqueamos para que solo responda si viene el parámetro 'q'
        if(!isset($_GET['q']) || strlen(trim($_GET['q'])) < 3) {
            echo json_encode([]);
            exit();
        }

        //Se sanitizan y codifican los caracteres especiales para evitar problemas de formato en la URL
        $queryStr = trim($_GET['q']);
        $query = urlencode($queryStr);

        //Usar API key
        $apiKey = $_ENV['BIOPORTAL_API_KEY'] ?? '';

        //URL oficial de BioPortal optimizada para SNOMED CT en español
        $url = "https://data.bioontology.org/search?q={$query}&ontologies=SNOMEDCT&display_links=false&display_context=false&links_not_html=true&pagesize=5";

        //2. Hacer la petición usando cURL (nativo de PHP)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //Pasamos la API Key en las cabeceras, tal como lo exige BioPortal
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: apikey token={$apiKey}",
            "Accept: application/json"
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_errno($ch);
        curl_close($ch);

        //Configurar la cabecera para que el navegador entienda que es un JSON
        header('Content-Type: application/json');

        // --- SISTEMA DE RESPALDO (FALLBACK OFFLINE) ---
        //Si cURL da error (código 0, sin internet) o la API devuelve un error del servidor (no 200)
        if($curlError || $httpCode !== 200) {
            $localItems = $this->searchLocalCatalog($queryStr);
            echo json_encode(['items' => $localItems]);
            exit();
        }

        //Normalizamos la respuesta de BioPortal para que coincida con
        //la estructura que espera el frontend (items[].term, items[].concept.conceptId)
        $data = json_decode($response, true);
        $items = [];

        //BioPortal puede devolver los resultados en diferentes formatos dependiendo de la consulta y la versión de la API, así que hacemos una normalización flexible
        $collection = null;

        if(isset($data['collection']) && is_array($data['collection'])) {

            $collection = $data['collection'];

        } elseif(isset($data['results']) && is_array($data['results'])) {

            $collection = $data['results'];

        } elseif(is_array($data) && array_values($data) === $data) {

            $collection = $data;

        }

        //Iteramos sobre la colección de resultados y extraemos el término y el conceptId (CUI)
        if(is_array($collection)) {

            //El término puede estar en diferentes campos dependiendo de la versión de la API o el tipo de resultado, así que hacemos varios intentos de extracción
            foreach($collection as $item) {

                $term = $item['prefLabel'] ?? $item['label'] ?? $item['displayLabel'] ?? $item['pref_label'] ?? null;

                if(!$term && isset($item['metadata']['label'])) $term = $item['metadata']['label'];
                $conceptId = $item['ui'] ?? null;

                if(!$conceptId && isset($item['@id'])) {
                    $parts = explode('/', rtrim($item['@id'], '/'));
                    $conceptId = end($parts);
                }

                if($term) {
                    $items[] = ['term' => $term, 'concept' => ['conceptId' => $conceptId]];
                }
            }
        }

        //Devolvemos los resultados en formato JSON
        echo json_encode(['items' => $items]);
        exit();
    }

    /**
     * Busca coincidencias en un catálogo JSON local (Uso Offline)
     */
    private function searchLocalCatalog(string $searchString): array {

        //Alternativa segura usando la raíz del proyecto
        $jsonPath = dirname(__DIR__, 2) . '/core/snomed_catalog.json';

        //2. Verificación defensiva por seguridad si el archivo no existe
        if (!file_exists($jsonPath)) {
            return [];
        }

        //3. Leemos el contenido del archivo y lo transformamos a un array asociativo de PHP
        $jsonContent = file_get_contents($jsonPath);
        $catalog = json_decode($jsonContent, true) ?: [];

        $results = [];

        //Convertimos la búsqueda a minúsculas e ignoramos diferencias de caracteres
        $searchString = mb_strtolower($searchString, 'UTF-8');

        //4. Iteramos sobre el catálogo extraído del JSON
        foreach ($catalog as $row) {
            $termLower = mb_strtolower($row['term'], 'UTF-8');
            
            //Si coincide la búsqueda parcial
            if (strpos($termLower, $searchString) !== false) {

                //Mantenemos la estructura exacta requerida por el frontend
                $results[] = [
                    'term' => $row['term'],
                    'concept' => [
                        'conceptId' => $row['conceptId']
                    ]
                ];
            }
        }

        //Limitamos a un máximo de 10 resultados para el Select2 o dropdown del frontend
        return array_slice($results, 0, 10);
    }

    /**
     * Registra información de depuración de la respuesta de BioPortal en un archivo local
     * Este método está disponible para activar logs de diagnóstico cuando sea necesario
     */
    private function logBioPortalDebug(string $message, array $context = []): void {

        $rootPath = dirname(__DIR__, 2);
        $logDir = $rootPath . '/storage/logs';
        $logFile = $logDir . '/bioportal_debug.log';

        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }

        $date = new \DateTime('now', new \DateTimeZone('America/Caracas'));
        $entry = '[' . $date->format('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
        if (!empty($context)) {
            $entry .= print_r($context, true) . PHP_EOL;
        }

        @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
    }
}

?>