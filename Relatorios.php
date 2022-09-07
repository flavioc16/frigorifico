<?php

namespace frigorifico\Model;

use \frigorifico\DB\Sql;
use \frigorifico\Model;

class Relatorios extends Model {

    public static function listCompras(){

        $sql = new Sql();

        $compras = $sql->select("SELECT * FROM compra");

        for($i = 0; $i < sizeof($compras);$i++)
        {
            $dataBrasil = date("d/m/Y", strtotime($compras[$i]["dataCompra"]));

            $totalFormatado = number_format($compras[$i]["totalCompra"], 2, ',', '.');

            $compras[$i]["totalCompra"] = $totalFormatado;

            $compras[$i]["dataCompra"] = $dataBrasil;
            
        }
        
        return $compras; 
        
    }

    public static function listComprasEntreDatas($dataInicio, $dataFinal){

        $sql = new Sql();

        $compras = $sql->select("SELECT C.dataCompra, C.totalCompra, C.idCliente, C.descricaoCompra, L.nomeCliente, L.apelidoCliente FROM compra AS C JOIN cliente as L ON L.idCliente = C.idCliente WHERE tipoCompra = 0 AND dataCompra BETWEEN :dataInicio AND :dataFinal ORDER BY dataCompra", array(
            "dataInicio"=>$dataInicio,
            "dataFinal"=>$dataFinal
        ));
        
        for($i = 0; $i < sizeof($compras);$i++)
        {
            $dataBrasil = date("d/m/Y", strtotime($compras[$i]["dataCompra"]));

            $totalFormatado = number_format($compras[$i]["totalCompra"], 2, ',', '.');

            $compras[$i]["totalCompra"] = $totalFormatado;

            $compras[$i]["dataCompra"] = $dataBrasil;
            
        }
        
        return $compras; 
        
    }

    public static function listComprasEntreDatasAVista($dataInicio, $dataFinal){

        $sql = new Sql();

        $compras = $sql->select("SELECT * FROM compra WHERE idCliente = 102 AND dataCompra BETWEEN :dataInicio AND :dataFinal ORDER BY dataCompra", array(
            "dataInicio"=>$dataInicio,
            "dataFinal"=>$dataFinal
        ));
        
        for($i = 0; $i < sizeof($compras);$i++)
        {
            $dataBrasil = date("d/m/Y", strtotime($compras[$i]["dataCompra"]));

            $totalFormatado = number_format($compras[$i]["totalCompra"], 2, ',', '.');

            $compras[$i]["totalCompra"] = $totalFormatado;

            $compras[$i]["dataCompra"] = $dataBrasil;
            
        }
        
        return $compras; 
        
    }
    
    public static function somaTotal($dataInicio, $dataFinal){

        $sql = new Sql();

        $compras = $sql->select("SELECT SUM(totalCompra) FROM compra WHERE tipoCompra = 0 AND dataCompra BETWEEN :dataInicio AND :dataFinal", array(
            "dataInicio"=>$dataInicio,
            "dataFinal"=>$dataFinal
        ));

        foreach ($compras as $conta){
            $total =  $conta;
        }
        foreach ($total as $totalMostra){

            if($totalMostra == null){
                $total ="0.00";
            }else{
                $total = $totalMostra.".00";
            }
            
        }
        
        return $compras;
        
    }

    public static function somaTotalAVista($dataInicio, $dataFinal){

        $sql = new Sql();

        $compras = $sql->select("SELECT SUM(totalCompra) FROM compra WHERE idCliente = 102 AND dataCompra BETWEEN :dataInicio AND :dataFinal", array(
            "dataInicio"=>$dataInicio,
            "dataFinal"=>$dataFinal
        ));

        foreach ($compras as $conta){
            $total =  $conta;
        }
        foreach ($total as $totalMostra){

            if($totalMostra == null){
                $total ="0.00";
            }else{
                $total = $totalMostra.".00";
            }
            
        }
        
        return $compras;
        
    }

    public static function somaDiaria(){

        $sql = new Sql();

        $dataAtual = date("Y-m-d");

        $result =  $sql->select("SELECT SUM(totalCompra) FROM compra WHERE dataCompra = :dataAtual AND tipoCompra = 0", array(
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

        $query = "INSERT INTO compra (dataCompra, descricaoCompra, totalCompra, tipoCompra, idCliente) VALUES (:dataCompra, :descricaoCompra, :totalCompra, :tipoCompra, :idCliente)";

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

        $dataCobranca = date('Y-m-d', strtotime($dataAtual. ' - 30 days'));

        $results = $sql->select("SELECT C.dataCompra, L.nomeCliente FROM compra AS C JOIN cliente as L ON L.idCliente = C.idCliente WHERE dataCompra = :dataCobranca", array(
            "dataCobranca"=>$dataCobranca
        ));

        return $results;
        
    }   
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
}   
?>