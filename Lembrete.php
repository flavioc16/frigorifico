<?php

namespace frigorifico\Model;

use \frigorifico\DB\Sql;
use \frigorifico\Model;

class Lembrete extends Model {

    public static function listLembrete(){

        $sql = new Sql();

        $lembretes = $sql->select("SELECT * FROM lembretes WHERE promocaoLembrete = 0");

        for($i = 0; $i < sizeof($lembretes);$i++)
        {
            $dataBrasil = date("d/m/Y", strtotime($lembretes[$i]["dataLembrete"]));

            $lembretes[$i]["dataLembrete"] = $dataBrasil;
            
        }
        
        return $lembretes; 
        
    }
    public static function listLembretePromocional(){

        $sql = new Sql();

        $lembretes = $sql->select("SELECT * FROM lembretes WHERE promocaoLembrete != 0 ORDER BY idLembrete desc");

        for($i = 0; $i < sizeof($lembretes);$i++)
        {
            $dataBrasil = date("d/m/Y", strtotime($lembretes[$i]["dataLembrete"]));

            $lembretes[$i]["dataLembrete"] = $dataBrasil;

            $diaEscolhido = $lembretes[$i]["promocaoLembrete"];

            switch ($diaEscolhido) {
                case 1:
                    $lembretes[$i]["dataLembrete"] = "Segunda";
                    break;
                case 2:
                    $lembretes[$i]["dataLembrete"] = "Terça";
                    break;
                case 3:
                    $lembretes[$i]["dataLembrete"] = "Quarta";
                    break;
                case 4:
                    $lembretes[$i]["dataLembrete"] = "Quinta";
                    break;
                case 5:
                    $lembretes[$i]["dataLembrete"] = "Sexta";
                    break;
                case 6:
                    $lembretes[$i]["dataLembrete"] = "Sábado";
                    break;      
                    
                }
            
        }
        
        return $lembretes; 
        
    }

    public function createLembrete($dados){

        $sql = new Sql();

        $query = "INSERT INTO lembretes (dataLembrete, descricaoLembrete) VALUES (:dataLembrete, :descricaoLembrete)";

        $sql->query($query, $dados);

    }
    public function createLembretePromocional($dados){

        $sql = new Sql();

        $query = "INSERT INTO lembretes (descricaoLembrete, promocaoLembrete, dataLembrete) VALUES (:descricaoLembrete, :promocaoLembrete, :dataLembrete)";

        $sql->query($query, $dados);

    }

    public static function listLembretes(){

        $sql = new Sql();

        $dataAtual = date("Y-m-d");

        $lembretes = $sql->select("SELECT * FROM lembretes WHERE dataLembrete = :dataAtual AND promocaoLembrete = 0  ORDER BY statusLembrete", array(
            "dataAtual"=>$dataAtual
        ));
        
        for($i = 0; $i < sizeof($lembretes);$i++)
        {
            $dataBrasil = date("d/m/Y", strtotime($lembretes[$i]["dataLembrete"]));

            $lembretes[$i]["dataLembrete"] = $dataBrasil;
            
        }
        
        return $lembretes;

    }

    public static function listLembretesPromocionais($diaDaSemana){

        $sql = new Sql();

        $lembretes = $sql->select("SELECT * FROM lembretes WHERE promocaoLembrete = :diaDaSemana ORDER BY promocaoLembrete", array(
            "diaDaSemana"=>$diaDaSemana
        ));
        
        for($i = 0; $i < sizeof($lembretes);$i++)
        {
            $dataBrasil = date("d/m/Y", strtotime($lembretes[$i]["dataLembrete"]));

            $lembretes[$i]["dataLembrete"] = $dataBrasil;
            
        }
        
        return $lembretes;

    }

    public function getLembrete($idlembrete){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM lembretes WHERE idLembrete = :idLembrete", array(
            "idLembrete"=>$idlembrete
        ));

        $this->setData($results[0]);
        
    }
    
    public function updateLembrete($idlembrete){

        $sql = new Sql();

        $query = "UPDATE lembretes SET dataLembrete = :dataLembrete, descricaoLembrete = :descricaoLembrete, statusLembrete = :statusLembrete WHERE idLembrete = :idLembrete";
           
        $sql->query($query, array(
            ":dataLembrete"=>$this->getdataLembrete(),
            ":descricaoLembrete"=>$this->getdescricaoLembrete(),
            ":statusLembrete"=>$this->getstatusLembrete(),
            ":idLembrete"=>$idlembrete  
        ));
    }
    public function statusLembrete($idlembrete){

        $sql = new Sql();

        $statusLembrete = 1;

        $query = "UPDATE lembretes SET statusLembrete = :statusLembrete WHERE idLembrete = :idLembrete";
           
        $sql->query($query, array(
            ":statusLembrete"=>$statusLembrete,
            ":idLembrete"=>$idlembrete  
        ));
    }

    public function deleteLembrete($idlembrete){

        $sql = new Sql();

        $query = "DELETE FROM lembretes WHERE idLembrete = :idLembrete";
           
        $sql->query($query, array(
            ":idLembrete"=>$idlembrete  
        ));
    }
    

    public function deleteLembreteSegunda(){

        $sql = new Sql();

        $query = "DELETE FROM lembretes WHERE promocaoLembrete = :promocaoLembrete";
           
        $sql->query($query, array(
            ":promocaoLembrete"=>1
        ));
    }

    public static function segundaPromocao(){

        $sql = new Sql();

        $lembretes = $sql->select("SELECT * FROM lembretes WHERE promocaoLembrete = 1 ");
        
        return $lembretes; 
        
    }

    public function deleteLembreteTerca(){

        $sql = new Sql();

        $query = "DELETE FROM lembretes WHERE promocaoLembrete = :promocaoLembrete";
           
        $sql->query($query, array(
            ":promocaoLembrete"=>2 
        ));
    }

    public static function tercaPromocao(){

        $sql = new Sql();

        $lembretes = $sql->select("SELECT * FROM lembretes WHERE promocaoLembrete = 2 ");
        
        return $lembretes; 
        
    }

    public function deleteLembreteQuarta(){

        $sql = new Sql();

        $query = "DELETE FROM lembretes WHERE promocaoLembrete = :promocaoLembrete";
           
        $sql->query($query, array(
            ":promocaoLembrete"=>3  
        ));
    }

    public static function quartaPromocao(){

        $sql = new Sql();

        $lembretes = $sql->select("SELECT * FROM lembretes WHERE promocaoLembrete = 3");
        
        return $lembretes; 
        
    }
    
    public function deleteLembreteQuinta(){

        $sql = new Sql();

        $query = "DELETE FROM lembretes WHERE promocaoLembrete = :promocaoLembrete";
           
        $sql->query($query, array(
            ":promocaoLembrete"=>4  
        ));
    }

    public static function quintaPromocao(){

        $sql = new Sql();

        $lembretes = $sql->select("SELECT * FROM lembretes WHERE promocaoLembrete = 4 ");
        
        return $lembretes; 
        
    }
    
    public function deleteLembreteSexta(){

        $sql = new Sql();

        $query = "DELETE FROM lembretes WHERE promocaoLembrete = :promocaoLembrete";
           
        $sql->query($query, array(
            ":promocaoLembrete"=>5  
        ));
    }

    public static function sextaPromocao(){

        $sql = new Sql();

        $lembretes = $sql->select("SELECT * FROM lembretes WHERE promocaoLembrete = 5");
        
        return $lembretes; 
        
    }

    public function deleteLembreteSabado(){

        $sql = new Sql();

        $query = "DELETE FROM lembretes WHERE promocaoLembrete = :promocaoLembrete";
           
        $sql->query($query, array(
            ":promocaoLembrete"=>6  
        ));
    }
    
    public static function sabadoPromocao(){

        $sql = new Sql();

        $lembretes = $sql->select("SELECT * FROM lembretes WHERE promocaoLembrete = 6 ");
        
        return $lembretes; 
        
    }
}   
?>