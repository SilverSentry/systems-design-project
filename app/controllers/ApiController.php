<?php

namespace App\Controllers;

class ApiController {

    //Método para manejar la búsqueda en SNOMED CT a través del servidor de España o Local
    public function search() {
        header('Content-Type: application/json');

        // Bloqueamos para que solo responda si viene el parámetro 'q'
        if(!isset($_GET['q']) || strlen(trim($_GET['q'])) < 3) {
            echo json_encode(['items' => []]);
            exit();
        }

        //Se sanitizan y codifican los caracteres especiales para evitar problemas de formato en la URL
        $queryStr = trim($_GET['q']);
        $query = urlencode($queryStr);

        //Usar API key
        $apiKey = $_ENV['BIOPORTAL_API_KEY'] ?? '';

        //URL de la API de BioPortal para buscar en SNOMED CT
        $url = "https://data.bioontology.org/search?q={$query}&ontologies=SNOMEDCT&search_properties=true&display_links=false&display_context=false&links_not_html=true&pagesize=10";

        //Hacer la petición usando cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3); //Timeout de 3 segundos por si falla la red

        //Cabeceras estándar para pedir JSON e identificarnos
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: apikey token={$apiKey}",
            "Accept: application/json"
        ]);

        //Evitamos caídas por certificados SSL vencidos o ausentes en XAMPP local
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //Ejecutamos la petición y obtenemos la respuesta, el código HTTP y cualquier error de cURL
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_errno($ch);
        curl_close($ch);

        //SISTEMA DE RESPALDO (FALLBACK OFFLINE)
        //Si cURL da error (código 0, sin internet) o la API devuelve un error del servidor (no 200)
        if($curlError || $httpCode !== 200) {

            /**
             * var_dump(["CURL_ERROR_NUM" => $curlError, "HTTP_CODE" => $httpCode, "CURL_STR_ERR" => curl_error($ch)]); exit();
             * Bloque para debugging en caso de fallos, se puede activar para ver el error específico de cURL o el código HTTP devuelto por la API
             */
            $localItems = $this->searchLocalCatalog($queryStr);
            echo json_encode(['items' => $localItems]);
            exit();
        }

        //Si la respuesta no es un JSON válido, también usamos el fallback
        $data = json_decode($response, true);
        $items = [];
        $collection = $data['collection'] ?? $data['results'] ?? (array_values($data) === $data ? $data : null);

        //Se guardan las coincidencias dentro de la llave 'matches'
        if(is_array($collection)) {
            
            //Iteramos sobre cada resultado para extraer el término y su conceptId
            foreach($collection as $item) {
                
                $termEnglish = $item['prefLabel'] ?? $item['label'] ?? $item['displayLabel'] ?? null;
                $conceptId = $item['ui'] ?? null;

                //En algunos casos, el conceptId puede estar dentro de la URL del '@id', así que hacemos una extracción adicional
                if(!$conceptId && isset($item['@id'])) {
                    $parts = explode('/', rtrim($item['@id'], '/'));
                    $conceptId = end($parts);
                }

                if($termEnglish) {

                    //Traducimos de Inglés a Español
                    $termSpanish = $this->translateToSpanish($termEnglish);

                    $items[] = [
                        'term' => $termSpanish, 
                        'concept' => ['conceptId' => $conceptId]
                    ];
                }
            }
        }

        echo json_encode(['items' => $items]);
        exit();

    }

    /**
     * Función auxiliar para traducir texto usando la API gratuita de MyMemory Translated
     */
    private function translateToSpanish(string $text): string {
        $textUrl = urlencode($text);

        //Pedimos traducción de Inglés (en) a Español (es)
        $url = "https://api.mymemory.translated.net/get?q={$textUrl}&langpair=en|es";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2); //Timeout corto para no retrasar el buscador
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $res = curl_exec($ch);
        curl_close($ch);

        if($res) {
            $json = json_decode($res, true);

            //Si la traducción fue exitosa, la retornamos
            if(isset($json['responseData']['translatedText'])) {
                return $json['responseData']['translatedText'];
            }
        }

        //Si la traducción falla por red, retornamos el término original en inglés
        return $text;
    }

    /**
     * Busca coincidencias en un catálogo JSON local (Uso Offline)
     * Este método es usado cuando la API de BioPortal no responde o no hay conexión a Internet
     */
    private function searchLocalCatalog(string $searchString): array {

        //Alternativa segura usando la raíz del proyecto
        $jsonPath = dirname(__DIR__, 2) . '/core/snomed_catalog.json';

        //2. Verificación defensiva por seguridad si el archivo no existe
        if(!file_exists($jsonPath)) {
            return [];
        }

        //3. Leemos el contenido del archivo y lo transformamos a un array asociativo de PHP
        $jsonContent = file_get_contents($jsonPath);
        $catalog = json_decode($jsonContent, true) ?: [];

        $results = [];

        //Convertimos la búsqueda a minúsculas e ignoramos diferencias de caracteres
        $searchString = mb_strtolower($searchString, 'UTF-8');

        //4. Iteramos sobre el catálogo extraído del JSON
        foreach($catalog as $row) {
            $termLower = mb_strtolower($row['term'], 'UTF-8');
            
            //Si coincide la búsqueda parcial
            if(strpos($termLower, $searchString) !== false) {

                //Mantenemos la estructura exacta requerida por el frontend
                $results[] = [
                    'term' => $row['term'],
                    'concept' => [
                        'conceptId' => $row['conceptId']
                    ]
                ];
            }
        }

        //Limitamos a un máximo de 10 resultados
        return array_slice($results, 0, 10);
    }
}

?>