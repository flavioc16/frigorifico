<?php

namespace frigorifico\Model;

use \frigorifico\DB\Sql;
use \frigorifico\Model;

class Produto extends Model {

    public static function listProducts(){

        $sql = new Sql();

        $produtos =  $sql->select("SELECT * FROM produtos");

        for($i = 0; $i < sizeof($produtos);$i++)
        {
            
            $produtos[$i]["valorProduto"] = number_format($produtos[$i]["valorProduto"], 2, ',', ' ');

            $produtos[$i]["valorProdutoP"] = number_format($produtos[$i]["valorProdutoP"], 2, ',', ' ');
            
        }

        return $produtos;
        
    }
    
    public function createProduto($dados){

        $sql = new Sql();

        $query = "INSERT INTO produtos (nomeProduto, marcaProduto, valorProduto, valorProdutoP, promocaoProduto, idUsuario) VALUES (:nomeProduto, :marcaProduto, :valorProduto, :valorProdutoP, :promocaoProduto, :idUsuario)";

        $sql->query($query, $dados);

    }
    public static function countProdutos(){

        $sql = new Sql();

        $countProdutos = $sql->select("SELECT COUNT(nomeProduto) FROM produtos");

        foreach ($countProdutos as $value){
            foreach ($value as $contadorProdutos){
    
            }
        }
        return $contadorProdutos;
    }
    public function getProduto($idproduto){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM produtos WHERE idProduto = :idproduto", array(
            "idproduto"=>$idproduto
        ));

        $this->setData($results[0]);
    }
    public function updateProduto($idproduto){

        $sql = new Sql();

        $query = "UPDATE produtos SET nomeProduto = :nomeProduto, marcaProduto = :marcaProduto, valorProduto = :valorProduto, valorProdutoP = :valorProdutoP WHERE idProduto = :idProduto";
           
        $sql->query($query, array(
            ":nomeProduto"=>$this->getnomeProduto(),
            ":marcaProduto"=>$this->getmarcaProduto(),
            ":valorProduto"=>$this->getvalorProduto(),
            ":valorProdutoP"=>$this->getvalorProdutoP(),
            ":idProduto"=>$idproduto  
        ));
    }
    public function deleteProduto($idproduto){

        $sql = new Sql();

        $query = "DELETE FROM produtos WHERE idProduto = :idProduto";
           
        $sql->query($query, array(
            ":idProduto"=>$idproduto  
        ));
    }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
}   
?>