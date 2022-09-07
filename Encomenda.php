<?php

namespace frigorifico\Model;

use \frigorifico\DB\Sql;
use \frigorifico\Model;

class Encomenda extends Model {

    public static function listEncomendas(){

        $sql = new Sql();

        $encomendas = $sql->select("SELECT * FROM encomenda");

        for($i = 0; $i < sizeof($encomendas);$i++)
        {
            $dataBrasil = date("d/m/Y", strtotime($encomendas[$i]["dataEncomenda"]));

            $encomendas[$i]["dataEncomenda"] = $dataBrasil;
            
        }
        
        return $encomendas; 
        
    }
    
    public function createEncomenda($dados){

        $sql = new Sql();

        $query = "INSERT INTO encomenda (dataEncomenda, descricaoEncomenda) VALUES (:dataEncomenda, :descricaoEncomenda)";

        $sql->query($query, $dados);

    }

    public static function countEncomendas(){

        $sql = new Sql();

        $dataAtual = date("Y-m-d");

        $encomendas = $sql->select("SELECT * FROM encomenda WHERE dataEncomenda = :dataAtual ORDER BY statusEncomenda", array(
            "dataAtual"=>$dataAtual
        ));
        
        for($i = 0; $i < sizeof($encomendas);$i++)
        {
            $dataBrasil = date("d/m/Y", strtotime($encomendas[$i]["dataEncomenda"]));

            $encomendas[$i]["dataEncomenda"] = $dataBrasil;
            
        }
        
        return $encomendas; 
    }
    public function getEncomenda($idencomenda){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM encomenda WHERE idEncomenda = :idEncomenda", array(
            "idEncomenda"=>$idencomenda
        ));

        $this->setData($results[0]);
        
    }

    public function updateEncomenda($idencomenda){

        $sql = new Sql();

        $query = "UPDATE encomenda SET dataEncomenda = :dataEncomenda, descricaoEncomenda = :descricaoEncomenda, statusEncomenda = :statusEncomenda WHERE idEncomenda = :idEncomenda";
           
        $sql->query($query, array(
            ":dataEncomenda"=>$this->getdataEncomenda(),
            ":descricaoEncomenda"=>$this->getdescricaoEncomenda(),
            ":statusEncomenda"=>$this->getstatusEncomenda(),
            ":idEncomenda"=>$idencomenda  
        ));
    }
    public function statusEncomenda($idencomenda){

        $sql = new Sql();

        $statusEncomenda = 1;

        $query = "UPDATE encomenda SET statusEncomenda = :statusEncomenda WHERE idEncomenda = :idEncomenda";
           
        $sql->query($query, array(
            ":statusEncomenda"=>$statusEncomenda,
            ":idEncomenda"=>$idencomenda  
        ));
    }
    
    public function deleteEncomenda($idencomenda){

        $sql = new Sql();

        $query = "DELETE FROM encomenda WHERE idEncomenda = :idEncomenda";
           
        $sql->query($query, array(
            ":idEncomenda"=>$idencomenda  
        ));
    }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
}   
?>