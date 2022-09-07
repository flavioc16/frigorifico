<?php

namespace frigorifico\Model;

use \frigorifico\DB\Sql;
use \frigorifico\Model;

class Pagamento extends Model {

    public static function somaTotal($dataInicio, $dataFinal){

        $sql = new Sql();

        $result =  $sql->select("SELECT SUM(valorPagamento) FROM pagamento WHERE dataPagamento BETWEEN :dataInicio AND :dataFinal", array(
            "dataInicio"=>$dataInicio,
            "dataFinal"=>$dataFinal
        ));
        
        return $result;
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
    
    public function createPagamento($dados){

        $sql = new Sql();

        $query = "INSERT INTO pagamento (dataPagamento, valorPagamento, idCliente) VALUES (:dataPagamento, :valorPagamento, :idCliente)";

        $sql->query($query, $dados);

    }
    

    public static function listPagamentosEntreDatas($dataInicio, $dataFinal){

        $sql = new Sql();

        $pagamentos = $sql->select("SELECT A.dataPagamento, A.valorPagamento, B.nomeCliente, B.apelidoCliente 
                                    FROM pagamento A INNER JOIN cliente B ON A.idCliente = B.idCliente 
                                    WHERE dataPagamento BETWEEN :dataInicio AND :dataFinal", array(
            "dataInicio"=>$dataInicio,
            "dataFinal"=>$dataFinal
        ));
        
        for($i = 0; $i < sizeof($pagamentos);$i++)
        {
            $pagamentos[$i]["dataPagamento"] = date("d/m/Y", strtotime($pagamentos[$i]["dataPagamento"]));

            $pagamentos[$i]["valorPagamento"] = number_format($pagamentos[$i]["valorPagamento"], 2, ',', '.');
            
        }
        
        return $pagamentos; 
        
    }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
}   
?>