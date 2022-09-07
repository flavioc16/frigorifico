<?php

namespace frigorifico\Model;

use \frigorifico\DB\Sql;
use \frigorifico\Model;

class Loginfo extends Model {
    
    public function createLog($dados){

        $sql = new Sql();

        $query = "INSERT INTO loginfo (descricaoLog) VALUES (:descricaoLog)";

        $sql->query($query, $dados);

    }

    public function deleteLog($idLog){

        $sql = new Sql();

        $query = "DELETE FROM descricao_log WHERE idLog = :idLog";
           
        $sql->query($query, array(
            ":idLog"=>$idLog  
        ));
    }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
}   
?>