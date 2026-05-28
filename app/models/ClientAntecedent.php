<?php

namespace App\Models;

use App\Core\Model;

class ClientAntecedent extends Model {

    //Definimos la tabla
    protected $tableName = 'antecedentes_clientes';

    public function create($clientId, $antecedentId, $conceptId, $termName, $note = '') {

        $sql = "INSERT INTO {$this->tableName} (id_cliente, id_tipo_antecedente, concept_id, term_name, nota) VALUES (:clientId, :antecedentId, :conceptId, :termName, :note)";

        $stmt = $this->query($sql, [
            ':clientId' => $clientId,
            ':antecedentId' => $antecedentId,
            ':conceptId' => $conceptId,
            ':termName' => $termName,
            ':note' => $note
        ]);

    }

}

?>