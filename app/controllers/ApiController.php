<?php

class ApiController {

    //Método para manejar la búsqueda en SNOMED CT a través de BioPortal
    public function search() {

        //Bloqueamos para que solo responda si viene el parámetro 'q'
        if(!isset($_GET['q']) || strlen(trim($_GET['q'])) < 3) {
            echo json_encode([]);
            exit();
        }

        $query = urlencode($_GET['q']);

        //Usar API key desde configuración
        $apiKey = defined('BIOPORTAL_API_KEY') ? BIOPORTAL_API_KEY : '';

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
        curl_close($ch);

        //Configurar la cabecera para que el navegador entienda que es un JSON
        header('Content-Type: application/json');

        if($httpCode !== 200) {
            echo json_encode(["error" => "Error al conectar con BioPortal"]);
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

                //El término puede estar en los metadatos como 'label', así que hacemos un intento de extracción adicional
                if(!$term && isset($item['metadata']['label'])) $term = $item['metadata']['label'];
                $conceptId = $item['ui'] ?? null;

                //El conceptId puede estar en el @id como parte de la URL, así que se hacem un intento de extracción adicional
                if(!$conceptId && isset($item['@id'])) {
                    $parts = explode('/', rtrim($item['@id'], '/'));
                    $conceptId = end($parts);
                }

                //Solo incluimos en los resultados aquellos que tengan un término y un conceptId válidos
                if($term) {
                    $items[] = ['term' => $term, 'concept' => ['conceptId' => $conceptId]];
                }
            }
        }

        //Devolvemos los resultados en formato JSON
        echo json_encode(['items' => $items]);
        exit();
    }
}

?>