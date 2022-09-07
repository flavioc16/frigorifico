<?php

namespace frigorifico\Model;

use \frigorifico\DB\Sql;
use \frigorifico\Model;

$_SESSION['mensagem'] = false;

class User extends Model {

    const SESSION = "User";
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
    public static function login ($login, $password)
    {
        $sql = new Sql();   

        $mensagem = null;

        $results = $sql->select("SELECT * FROM usuario WHERE usuario_usuario = :USUARIO", array(
            ":USUARIO"=>$login
        ));

        if(count($results) === 0)
        {
            $mensagem = false;
            return $mensagem;
            
        }

        define('SECRET_IV', pack('a16', 'senha'));
        define('SECRET', pack('a16', 'senha'));
        
        $openssl = openssl_encrypt (
            json_encode($password),
            'AES-128-CBC',
            SECRET,
            0,
            SECRET_IV
        );

        $data = $results[0];

        if($openssl === $data["senha_usuario"]){

            $user = new User();

            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues();
            $_SESSION['mensagem'] = true;
            
            return $user;
            
        }else{

            $mensagem = true;
            return $mensagem;

        }

        return $mensagem;

    }
    public static function verifyLogin()
    {
        if(!isset($_SESSION[User::SESSION]) || !$_SESSION[User::SESSION]){
            $_SESSION['mensagem'] = null;
            header("Location: /login");
            exit;
        }
    }
    public static function logout()
    {
        if(isset($_SESSION[User::SESSION]))
        {
            unset($_SESSION[User::SESSION]);
        }
    }
    public static function listAll(){

        $sql = new Sql();

        return $sql->select("SELECT * FROM cliente");

        
    }

    public static function countCliente(){

        $sql = new Sql();

        $countCliente =  $sql->select("SELECT COUNT(nomeCliente) FROM cliente");

        foreach ($countCliente as $value){
            foreach ($value as $contadorCliente){
    
            }
        }

        return $contadorCliente;

    }

    public static function countNotificacao(){

        $sql = new Sql();

        $countNofificacao = $sql->select("SELECT COUNT(idEncomenda) FROM encomenda");

        foreach ($countNofificacao as $value){
            foreach ($value as $contadorNotificacao){
    
            }
        }
        return $contadorNotificacao;        
    }

    public function createCliente($dados){

        $sql = new Sql();

        $query = "INSERT INTO cliente (nomeCliente, apelidoCliente, enderecoCliente, telefoneCliente, limiteCliente) VALUES (:nomeCliente, :apelidoCliente, :enderecoCliente, :telefoneCliente, :limiteCliente)";

        $sql->query($query, $dados);

    }

    public function get($idcliente){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM cliente WHERE idCliente = :idcliente", array(
            "idcliente"=>$idcliente
        ));

        $this->setData($results[0]);
    }
    public function nome($idcliente){

        $sql = new Sql();

        $results = $sql->select("SELECT nomeCliente FROM cliente WHERE idCliente = :idcliente", array(
            "idcliente"=>$idcliente
        ));

        return $results;
    }

    public function apelido($idcliente){

        $sql = new Sql();

        $results = $sql->select("SELECT apelidoCliente FROM cliente WHERE idCliente = :idcliente", array(
            "idcliente"=>$idcliente
        ));

        return $results;
    }

    public function lastId(){

        $sql = new Sql();

        $results = $sql->select("SELECT MAX(idCliente) FROM cliente");

        foreach ($results as $id){
            foreach($id as $value){
                $results = $value;
            }
        }

        return $results;
    }

    public function updateCliente($idcliente){

        $sql = new Sql();

        $query = "UPDATE cliente SET nomeCliente = :nomeCliente, apelidoCliente = :apelidoCliente, enderecoCliente = :enderecoCliente, telefoneCliente =:telefoneCliente, limiteCliente = :limiteCliente WHERE idCliente = :idCliente";
           
        $sql->query($query, array(
            ":nomeCliente"=>$this->getnomeCliente(),
            ":apelidoCliente"=>$this->getapelidoCliente(),
            ":enderecoCliente"=>$this->getenderecoCliente(),
            ":telefoneCliente"=>$this->gettelefoneCliente(),
            ":limiteCliente"=>$this->getlimiteCliente(),
            ":idCliente"=>$idcliente   
        ));
    }
    
    public function delete($idcliente){

        $sql = new Sql();

        $query = "DELETE FROM cliente WHERE idCliente = :idCliente";
           
        $sql->query($query, array(
            ":idCliente"=>$idcliente  
        ));
    }   
}   
?>