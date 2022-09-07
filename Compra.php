<?php

namespace frigorifico\Model;

use \frigorifico\DB\Sql;
use \frigorifico\Model;

class Compra extends Model {

    public static function listCompras($idcliente){

        $sql = new Sql();

        $compras = $sql->select("SELECT * FROM compra WHERE idCliente = :idCliente AND statusCompra = 0", array(
            "idCliente"=>$idcliente
        ));
        for($i = 0; $i < sizeof($compras);$i++)
        {
            $dataBrasil = date("d/m/Y", strtotime($compras[$i]["dataCompra"]));

            $totalFormatado = number_format($compras[$i]["totalCompra"], 2, ',', ' ');

            $compras[$i]["totalCompra"] = $totalFormatado;

            $compras[$i]["dataCompra"] = $dataBrasil;
            
        }
        
        return $compras; 
        
    }
    public static function somaTotal($idcliente){

        $sql = new Sql();

        $result =  $sql->select("SELECT SUM(totalCompra) FROM compra WHERE idCliente = :idCliente AND statusCompra = 0", array(
            "idCliente"=>$idcliente
        ));

        foreach ($result as $conta){
            $total =  $conta;
        }
        foreach ($total as $totalMostra){

            if($totalMostra == null){
                $total ="0.00";
            }else{
                $total = $totalMostra.".00";
            }
            
        }
        
        return $total;
    }
    public static function somaDiaria(){

        $sql = new Sql();

        $dataAtual = date("Y-m-d");

        $result =  $sql->select("SELECT SUM(totalCompra) FROM compra WHERE dataCompra = :dataAtual AND tipoCompra = 0 ", array(
            "dataAtual"=>$dataAtual
        ));

        foreach ($result as $conta){
            $total =  $conta;
        }
        foreach ($total as $totalMostra){

            if($totalMostra == null){
                $total ="0.00";
            }else{
                $total = $totalMostra.".00";
            }
            
        }
        
        return $total;
    }
    
    public function createCompra($dados){

        $sql = new Sql();

        $query = "INSERT INTO compra (dataCompra, descricaoCompra, totalCompra, tipoCompra, statusCompra, idCliente) VALUES (:dataCompra, :descricaoCompra, :totalCompra, :tipoCompra, :statusCompra, :idCliente)";

        $sql->query($query, $dados);

    }
    
    public function getCompra($idcompra){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM compra WHERE idCompra = :idCompra", array(
            "idCompra"=>$idcompra
        ));

        $this->setData($results[0]);
        
    }
    public function selectIdCliente($idcompra){

        $sql = new Sql();

        $results = $sql->select("SELECT idCliente FROM compra WHERE idCompra = :idCompra", array(
            "idCompra"=>$idcompra
        ));

        foreach ($results as $id){    
            foreach ($id as $idmostra){
                $results = $idmostra;
            }
            
        }

        return $results;
    }
    public function updateCompra($idcompra){

        $sql = new Sql();

        $query = "UPDATE compra SET dataCompra = :dataCompra, descricaoCompra = :descricaoCompra, tipoCompra = :tipoCompra, totalCompra = :totalCompra WHERE idCompra = :idCompra";
           
        $sql->query($query, array(
            ":dataCompra"=>$this->getdataCompra(),
            ":descricaoCompra"=>$this->getdescricaoCompra(),
            ":totalCompra"=>$this->gettotalCompra(),
            ":tipoCompra"=>$this->gettipoCompra(),
            ":idCompra"=>$idcompra  
        ));
    }

    public function setStatusCompra($idcompra){

        $statusCompra = 1;

        $sql = new Sql();

        $query = "UPDATE compra SET statusCompra = :statusCompra WHERE idCompra = :idCompra";
           
        $sql->query($query, array(
            ":statusCompra"=>$statusCompra,
            ":idCompra"=>$idcompra  
        ));
    }
    public function setStatusCompras($idcliente){

        $statusCompra = 1;

        $sql = new Sql();

        $query = "UPDATE compra SET statusCompra = :statusCompra WHERE idCliente = :idCliente";
           
        $sql->query($query, array(
            ":statusCompra"=>$statusCompra,
            ":idCliente"=>$idcliente  
        ));
    }

    public function deleteCompra($idcompra){

        $sql = new Sql();

        $query = "DELETE FROM compra WHERE idCompra = :idCompra";
           
        $sql->query($query, array(
            ":idCompra"=>$idcompra  
        ));
    }
    public function deleteCompras($idcliente){

        $sql = new Sql();

        $query = "DELETE FROM compra WHERE idCliente = :idCliente";
           
        $sql->query($query, array(
            ":idCliente"=>$idcliente  
        ));
    }

    public static function verificaDias() {

        $sql = new Sql();

        $dataAtual = date("Y-m-d");

        $dataCobranca = date('Y-m-d', strtotime($dataAtual. ' - 31 days'));

        $results = $sql->select("SELECT C.dataCompra, C.idCliente, C.descricaoCompra, L.nomeCliente FROM compra AS C JOIN cliente as L ON L.idCliente = C.idCliente WHERE dataCompra = :dataCobranca AND statusCompra = 0 GROUP BY L.nomeCliente", array(
            "dataCobranca"=>$dataCobranca
        ));

        for($i = 0; $i < sizeof($results);$i++)
        {
            $results[$i]["dataCompra"] = date("d/m/Y", strtotime($results[$i]["dataCompra"]));
            
        }

        return $results;
        
    }   
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
}   
?>