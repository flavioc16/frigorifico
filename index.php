<?php 
session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \frigorifico\Page;
use \frigorifico\PageAdmin;
use \frigorifico\Model\Alert;
use \frigorifico\Model\User;
use \frigorifico\Model\Produto;
use \frigorifico\Model\Compra;
use \frigorifico\Model\Encomenda;
use \frigorifico\Model\Lembrete;
use frigorifico\Model\Loginfo;
use \frigorifico\Model\Relatorios;
use \frigorifico\Model\Pagamento;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {

    $contadorNotificacao = null;

    $lembretePromocional = null;

    $listLembretesPromocionais = null;

    $contadorProdutos = 0;

    $contadorCliente = 0;

    $totalDiario = Compra::somaDiaria();

    $totalDiario = round($totalDiario, 2);

    $totalDiario = number_format($totalDiario, 2, ',', '.');

    User::verifyLogin();

    $verificaDias = Compra::verificaDias();


    if($verificaDias != null){

        $contadorNotificacao++;
        
    }

    $contadorCliente = User::countCliente();

    $countEncomendas = Encomenda::countEncomendas();

    $listLembretes = Lembrete::listLembretes();

    if($countEncomendas != null)
    {
        for($i = 0; $i < sizeof($countEncomendas);$i++)
        {
            $contadorNotificacao++;
            
        } 
    }
    if($listLembretes != null)
    {
        for($i = 0; $i < sizeof($listLembretes);$i++)
        {
            $contadorNotificacao++;
            
        }  
        
    }

    $contadorProdutos = Produto::countProdutos();

    $dataInicio = date("Y-m-d");

    $pagamentosDiario = Pagamento::somaTotal($dataInicio,$dataInicio); 

    foreach ($pagamentosDiario as $value){
       foreach($value as $total){
        $total;
       }
    }

    $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');

    $data = date('Y-m-d');

    $dataAtual = date('d/m/Y');

    $lembrete = new Lembrete();

    $diasemana_numero = date('w', strtotime($data));

  switch ($diasemana_numero){
    case 1:
        $listLembretesPromocionais = Lembrete::listLembretesPromocionais(1);
        break;
    case 2:
        $listLembretesPromocionais = Lembrete::listLembretesPromocionais(2);
        break;
    case 3:
        $listLembretesPromocionais = Lembrete::listLembretesPromocionais(3);
        break;
    case 4:
        $listLembretesPromocionais = Lembrete::listLembretesPromocionais(4);
        break;
    case 5:
        $listLembretesPromocionais = Lembrete::listLembretesPromocionais(5);
        break;
    case 6:
        $listLembretesPromocionais = Lembrete::listLembretesPromocionais(6);
        break;
  }

  if($listLembretesPromocionais != null)
    {
        for($i = 0; $i < sizeof($listLembretesPromocionais);$i++)

        {
            $contadorNotificacao++;
            
        }  
        
    }

    $pagamentosDiario = round($total, 2);

    $pagamentosDiario = number_format($total, 2, ',', '.');

    $mensagem = null;

    $users = User::listAll();

    $page = new Page();
    
    $page->setTpl("index", array(
        "users"=>$users,
        "contadorCliente"=>$contadorCliente,
        "contadorNotificacao"=>$contadorNotificacao,
        "contadorProdutos"=>$contadorProdutos,
        "totalDiario"=>$totalDiario,
        "countEncomendas"=>$countEncomendas,
        "listLembretes"=>$listLembretes,
        "listLembretesPromocionais"=>$listLembretesPromocionais,
        "verificaDias"=>$verificaDias,
        "dataAtual"=>$dataAtual,
        "pagamentosDiario"=>$pagamentosDiario,
        "usuario"=>$_SESSION[User::SESSION]["nome_usuario"],
        "mensagem"=>$mensagem
    ));

});

$app->get('/login', function (){

    User::logout();

    $mensagem = null;
    
    $page = new PageAdmin([
        "header"=>false,
        "footer"=>false
    ]);

    $page->setTpl("login", array(
        "mensagem"=>$mensagem
    ));
    
});
$app->post("/produtos/create", function(){
    
    User::verifyLogin();

    $produto = new Produto();

    $valorComPonto = str_replace(",", ".",$_POST["valorProduto"]);
   
    $_POST["valorProduto"] = $valorComPonto;

    $produto->setData($_POST);

    $produto->createProduto($_POST);

    $page = new Page();

    $mensagem = Alert::showMensage("success", 2.5);
    
	$page->setTpl("produtos-create", array(
        "mensagem"=>$mensagem
    ));

});
$app->post("/clientes/create", function(){
    
    User::verifyLogin();

    $cliente = new User();

    $cliente->setData($_POST);

    $cliente->createCliente($_POST);

    $page = new Page();

    $dataAtual = date("Y-m-d"); 
    
    $idCliente = $cliente->lastId();

    $cliente->get((int)$idCliente);

    $mensagem = Alert::showMensage("success", 2.5);
    
	$page->setTpl("novacompra", array(
        "mensagem"=>$mensagem,
        "idCliente"=>$idCliente,
        "dataAtual"=>$dataAtual,
        "cliente"=>$cliente->getValues(),
        "mensagem"=>$mensagem
    ));

});

$app->post("/login", function(){
    
    $mensagem = User::login($_POST["login"], $_POST["password"]); 
    
    if(gettype($mensagem) == 'boolean'){
        $page = new PageAdmin([
            "header"=>false,
            "footer"=>false
        ]);
    
        $mensagem = "alertify.notify('Dados incorretos', 'error', 3, function(){});";
    
        $page->setTpl("login", array(
            "mensagem"=>$mensagem,
            
        ));
    }else{
        header("Location: /");
        exit;
    }

});

$app->get("/clientes", function(){
    
    User::verifyLogin();

    $mensagem = null;

    $users = User::listAll();

    $page = new Page();
    
	$page->setTpl("clientes", array(
        "users"=>$users,
        "mensagem"=>$mensagem
    ));

});

$app->get("/clientes/create", function(){
    
    User::verifyLogin();

    $page = new Page();

	$mensagem = null;
    
    $page->setTpl("clientes-create", array(
        "mensagem"=>$mensagem
    ));

});
$app->get("/clientes/:idcliente", function($idcliente){
    
    User::verifyLogin();

    $user = new User();
    
    $user->get((int)$idcliente);

    $page = new Page();

    $mensagem = null;

    $page->setTpl("clientes-update", array(
        "mensagem"=>$mensagem,
        "user"=>$user->getValues()
        
    ));

});

$app->post("/clientes/:idcliente", function($idcliente){
    
    User::verifyLogin();

    $user = new User();
    
    $user->get((int)$idcliente);

    $user->setData($_POST);
    
    $user->updateCliente($idcliente);

    $page = new Page();

    $mensagem = Alert::showMensage("success", 2.5);
    
	$page->setTpl("clientes-update", array(
        "mensagem"=>$mensagem,
        "user"=>$user->getValues()
        
    ));
    
});

$app->get("/produtos/create", function(){
    
    User::verifyLogin();
    
    $mensagem = null;

    $page = new Page();

	$page->setTpl("produtos-create", array(
        "mensagem"=>$mensagem
    ));

});

$app->get("/produtos", function(){
    
    User::verifyLogin();

    $products = Produto::listProducts();

    $page = new Page();

    $mensagem = null;

	$page->setTpl("produtos", array(
        "products"=>$products,
        "mensagem"=>$mensagem
    ));

});


$app->get("/produtos/:idproduto", function($idproduto){
    
    User::verifyLogin();

    $mensagem = null;

    $produto = new Produto();
    
    $produto->getProduto((int)$idproduto);

    $tamanho = strlen($idproduto);

    switch ($tamanho) {
        case 1:
            $codigoProduto = "000000000000".$idproduto;
            break;
        case 2:
            $codigoProduto = "00000000000".$idproduto;
            break;
        case 3:
            $codigoProduto = "0000000000".$idproduto;
            break;
        case 4:
            $codigoProduto = "000000000".$idproduto;
            break;
        case 5:
            $codigoProduto = "00000000".$idproduto;
            break;
        case 6:
            $codigoProduto = "0000000".$idproduto;
            break;
        case 7:
            $codigoProduto = "000000".$idproduto;
            break;
        case 8:
            $codigoProduto = "00000".$idproduto;
            break; 
        case 9:
            $codigoProduto = "0000".$idproduto;
            break;
        case 10:
            $codigoProduto = "000".$idproduto;
            break;                          
        case 11:
            $codigoProduto = "00".$idproduto;
            break; 
        case 12:
            $codigoProduto = "0".$idproduto;
            break;
        case 13:
            $codigoProduto = $idproduto;
            break;                                    
    }

    $page = new Page();

    $page->setTpl("produtos-update", array(
        "mensagem"=>$mensagem,
        "produto"=>$produto->getValues(),
        "codigoProduto"=>$codigoProduto
    ));

});
$app->post("/produtos/:idproduto", function($idproduto){
    
    User::verifyLogin();

    $produto = new Produto();
    
    $produto->getProduto((int)$idproduto);

    $valorComPonto = str_replace(",", ".",$_POST["valorProduto"]);
   
    $_POST["valorProduto"] = $valorComPonto;

    $produto->setData($_POST);
    
    $produto->updateProduto($idproduto);

    $tamanho = strlen($idproduto);

    switch ($tamanho) {
        case 1:
            $codigoProduto = "000000000000".$idproduto;
            break;
        case 2:
            $codigoProduto = "00000000000".$idproduto;
            break;
        case 3:
            $codigoProduto = "0000000000".$idproduto;
            break;
        case 4:
            $codigoProduto = "000000000".$idproduto;
            break;
        case 5:
            $codigoProduto = "00000000".$idproduto;
            break;
        case 6:
            $codigoProduto = "0000000".$idproduto;
            break;
        case 7:
            $codigoProduto = "000000".$idproduto;
            break;
        case 8:
            $codigoProduto = "00000".$idproduto;
            break; 
        case 9:
            $codigoProduto = "0000".$idproduto;
            break;
        case 10:
            $codigoProduto = "000".$idproduto;
            break;                          
        case 11:
            $codigoProduto = "00".$idproduto;
            break; 
        case 12:
            $codigoProduto = "0".$idproduto;
            break;
        case 13:
            $codigoProduto = $idproduto;
            break;                                    
    }

    $page = new Page();

    $mensagem = Alert::showMensage("success", 2.5);
    
	$page->setTpl("produtos-update", array(
        "mensagem"=>$mensagem,
        "produto"=>$produto->getValues(),
        "codigoProduto"=>$codigoProduto
        
    ));
    
});

$app->get("/clientes/:idcliente/delete", function($idcliente){
    
    User::verifyLogin();

    $cliente = new User();

    $loginfo = new Loginfo();

    $cliente->get((int)$idcliente);

    $nome = $cliente->nome($idcliente);

    $apelido = $cliente->apelido($idcliente);

    foreach($nome as $key){
        
        foreach($key as $value){
            $nomeCliente = $value;
        }
    }

    foreach($apelido as $key){
        
        foreach($key as $value){
            $apelidoCliente = $value;
        }
    
    }

    $dados = array(
        "descricaoLog"=>"Deletando o cliente - " .$nomeCliente." | ".$apelidoCliente
    );

    $loginfo->createLog($dados);

    $cliente->delete($idcliente);
    
    $compra = new Compra();

    $compra->deleteCompras($idcliente);

    $users = User::listAll();

    $page = new Page();

    $mensagem = Alert::showMensage("success", 2.5);
    
	$page->setTpl("clientes", array(
        "users"=>$users,
        "mensagem"=>$mensagem
    ));

});
$app->get("/produtos/:idproduto/delete", function($idproduto){
    User::verifyLogin();

    $produto = new Produto();

    $produto->getProduto((int)$idproduto);

    $produto->deleteProduto($idproduto);

    $mensagem = Alert::showMensage("success", 2.5);
    
	$products = Produto::listProducts();

    $page = new Page();

	$page->setTpl("produtos", array(
        "products"=>$products,
        "mensagem"=>$mensagem
    ));

});
$app->get("/clientes/novacompra/:idcliente", function($idcliente){
    
    User::verifyLogin();

    $cliente = new User();

    $cliente->get((int)$idcliente);

    $page = new Page();

    $mensagem = null;

    $dataAtual = date("Y-m-d"); 

	$page->setTpl("novacompra", array(
        "mensagem"=>$mensagem,
        "idCliente"=>$idcliente,
        "dataAtual"=>$dataAtual,
        "cliente"=>$cliente->getValues(),
        "mensagem"=>$mensagem
    ));

});
$app->post("/clientes/novacompra/:idcliente", function($idcliente){
    
    User::verifyLogin();

    $mensagem = null;

    $compra = new Compra();

    $cliente = new User();

    $cliente->get((int)$idcliente);

    $dataAtual = date("Y-m-d");
    
    $valorComPonto = str_replace(",", ".",$_POST["totalCompra"]);
   
    $_POST["totalCompra"] = $valorComPonto;

    $compra->setData($_POST);

    $compra->createCompra($_POST);

    $page = new Page();

    $mensagem = Alert::showMensage("success", 2.5);
    
	$page->setTpl("novacompra", array(
        "mensagem"=>$mensagem,
        "idCliente"=>$idcliente,
        "dataAtual"=>$dataAtual,
        "cliente"=>$cliente->getValues(),
    ));

});
$app->get("/clientes/listadecompras/:idcliente", function($idcliente){  

    $mensagem = null;

    $compra = new Compra();

    $pagamento = new Pagamento();

    $cliente = new User();

    $loginfo = new Loginfo();

    $nome = $cliente->nome($idcliente);

    $apelido = $cliente->apelido($idcliente);

    foreach($nome as $key){
        
        foreach($key as $value){
            $nomeCliente = $value;
        }
    }

    foreach($apelido as $key){
        
        foreach($key as $value){
            $apelidoCliente = $value;
        }
    
    }
 
    if(isset($_GET["valorRetirado"])&& isset($_GET["restante"])){

       $dados = array(
            "descricaoLog"=>"Pagamento - ".$_GET["valorRetirado"]." - cliente - ".$nomeCliente." | ".$apelidoCliente
        );

        $loginfo->createLog($dados);
        
        if($_GET["restante"] > 0){

            $dados = array(
                "dataCompra"=>date("Y-m-d"),
                "descricaoCompra"=>"Restante",
                "totalCompra"=>$_GET["restante"],
                "tipoCompra"=>1,
                "statusCompra"=>0,
                "idCliente"=>$idcliente
            );

            $compra->setStatusCompras($idcliente);

            $compra->createCompra($dados);

            $mensagem = Alert::showMensage("success", 2.5);

        }else{
            
            $compra->setStatusCompras($idcliente);
            
            $mensagem = Alert::showMensage("success", 2.5);
        }

        $dadosPagamento = array(
            "dataPagamento"=>date("Y-m-d"),
            "valorPagamento"=>$_GET["valorRetirado"],
            "idCliente"=>$idcliente
        );

        $pagamento->createPagamento($dadosPagamento);
        
    }
    
    
    User::verifyLogin();

    $cliente = new User();

    $cliente->get((int)$idcliente);

    $compras = Compra::listCompras($idcliente); 

    $totalCompra = Compra::somaTotal($idcliente);

    $totalCompra = round($totalCompra, 2);

    $totalJanela = $totalCompra;

    $totalCompra = number_format($totalCompra, 2, ',', '.');

    $page = new Page();

	$page->setTpl("listadecompras", array(
        "compras"=>$compras,
        "totalMostra"=>$totalCompra, 
        "mensagem"=>$mensagem,
        "idCliente"=>$idcliente,
        "totalJanela"=>$totalJanela,
        "cliente"=>$cliente->getValues()

    ));

});
$app->post("/clientes/listadecompras/:idcliente", function($idcliente){
    
    User::verifyLogin();

    var_dump($_POST);

});
$app->get("/compras/:idcompra", function($idcompra){
    
    User::verifyLogin();

    $mensagem = null;

    $compra = new Compra();
    
    $compra->getCompra((int)$idcompra);

    $idcliente = $compra->selectIdCliente($idcompra);

    $page = new Page();

	$page->setTpl("compras-update", array(
        "mensagem"=>$mensagem,
        "compra"=>$compra->getValues(),
        "idCliente"=>$idcliente,
        "idCompra"=>$idcompra,
        "totalJanela"=>""
    ));

});
$app->post("/compras/:idcompra", function($idcompra){
    
    User::verifyLogin();

    $compra = new Compra();
    
    $compra->getCompra((int)$idcompra);

    $valorComPonto = str_replace(",", ".",$_POST["totalCompra"]);
   
    $_POST["totalCompra"] = $valorComPonto;

    $compra->setData($_POST);
    
    $compra->updateCompra($idcompra);

    $idcliente = $compra->selectIdCliente($idcompra);

    $page = new Page();

    $mensagem = Alert::showMensage("success", 2.5);

	$page->setTpl("compras-update", array(
        "mensagem"=>$mensagem,
        "compra"=>$compra->getValues(),
        "idCliente"=>$idcliente,
        "idCompra"=>$idcompra
    ));

});

$app->get("/clientes/listadecompras/:idcompra/delete", function($idcompra){

    User::verifyLogin();

    $compra = new Compra();

    $cliente = new User();

    $compra->getCompra((int)$idcompra);

    $idcliente = $compra->selectIdCliente($idcompra);

    $loginfo = new Loginfo();

    $nome = $cliente->nome($idcliente);

    $apelido = $cliente->apelido($idcliente);

    foreach($nome as $key){
        
        foreach($key as $value){
            $nomeCliente = $value;
        }
    }

    foreach($apelido as $key){
        
        foreach($key as $value){
            $apelidoCliente = $value;
        }
    
    }

    $dados = array(
        "descricaoLog"=>"Deletando a compra - ".$nomeCliente." | ".$apelidoCliente." de ID : ".$idcompra
    );

    $loginfo->createLog($dados);

    $cliente->get((int)$idcliente);

    $compra->deleteCompra($idcompra);

    $mensagem = Alert::showMensage("success", 2.5);

    $compras = Compra::listCompras($idcliente);

    $totalCompra = Compra::somaTotal($idcliente);
    
    $totalCompra = round($totalCompra, 2);

    $totalJanela = $totalCompra;

    $totalCompra = number_format($totalCompra, 2, ',', '.');

    $page = new Page();

	$page->setTpl("listadecompras", array(
        "compras"=>$compras,
        "totalMostra"=>$totalCompra, 
        "mensagem"=>$mensagem,
        "idCliente"=>$idcliente,
        "totalJanela"=>$totalJanela,
        "cliente"=>$cliente->getValues()

    ));

});

$app->get("/clientes/listadecompras/:idcliente/deleteall", function($idcliente){

    User::verifyLogin();

    $compra = new Compra();

    $cliente = new User();

    $cliente->get((int)$idcliente);

    $compra->deleteCompras($idcliente);

    $mensagem = Alert::showMensage("success", 2.5);

    $compras = Compra::listCompras($idcliente);

    $totalCompra = Compra::somaTotal($idcliente);

    $totalJanela = $totalCompra;

    $page = new Page();

	$page->setTpl("listadecompras", array(
        "compras"=>$compras,
        "totalMostra"=>$totalCompra, 
        "mensagem"=>$mensagem,
        "idCliente"=>$idcliente,
        "totalJanela"=>$totalJanela,
        "cliente"=>$cliente->getValues()
    ));

});

$app->get("/encomendas", function(){

    User::verifyLogin();

    $page = new Page();

    $encomenda = new Encomenda();

    $encomendas = $encomenda->listEncomendas(); 

    $mensagem = null;

	$page->setTpl("encomendas", array(
        "encomendas"=>$encomendas,
        "mensagem"=>$mensagem
        
    ));

});
$app->post("/encomendas", function(){

    User::verifyLogin();

    $encomenda = new Encomenda();

    $encomenda->setData($_POST);

    $encomenda->createEncomenda($_POST);

    $encomendas = $encomenda->listEncomendas();

    $page = new Page();

    $mensagem = Alert::showMensage("success", 2.5);

	$page->setTpl("encomendas", array(
        "encomendas"=>$encomendas,
        "mensagem"=>$mensagem
        
    ));

});
$app->get("/encomendas/:idencomenda/delete", function($idencomenda){

    User::verifyLogin();

    $encomenda = new Encomenda();

    $encomenda->getEncomenda((int)$idencomenda);

    $encomenda->deleteEncomenda($idencomenda);

    $encomendas = $encomenda->listEncomendas();

    $mensagem = Alert::showMensage("success", 2.5);

    $page = new Page();

	$page->setTpl("encomendas", array(
        "encomendas"=>$encomendas,
        "mensagem"=>$mensagem
        
    ));

});
$app->get("/encomendas/:idencomenda", function($idencomenda){

    User::verifyLogin();

    $encomenda = new Encomenda();

    $encomenda->getEncomenda((int)$idencomenda);

    $encomenda->statusEncomenda($idencomenda);

    $page = new Page();

    $mensagem = null;

	$page->setTpl("encomenda-update", array(
        "encomendas"=>$encomenda->getValues(),
        "mensagem"=>$mensagem
        
    ));

});
$app->post("/encomendas/:idencomenda", function($idencomenda){

    User::verifyLogin();

    $encomenda = new Encomenda();

    $encomenda->getEncomenda((int)$idencomenda);

    $encomenda->setData($_POST);
    
    $encomenda->updateEncomenda($idencomenda);

    $page = new Page();

    $mensagem = Alert::showMensage("success", 2.5);

	$page->setTpl("encomenda-update", array(
        "encomendas"=>$encomenda->getValues(),
        "mensagem"=>$mensagem
        
    ));

});

$app->get("/lembretes", function(){

    User::verifyLogin();

    $page = new Page();

    $lembrete = new Lembrete();

    $lembretes = $lembrete->listLembrete(); 

    $mensagem = null;

	$page->setTpl("lembretes", array(
        "lembretes"=>$lembretes,
        "mensagem"=>$mensagem
        
    ));

});

$app->post("/lembretes", function(){

    $lembrete = new Lembrete();

    $lembrete->setData($_POST);

    $lembrete->createLembrete($_POST);

    $lembretes = Lembrete::listLembrete();

    $page = new Page();

    $mensagem = Alert::showMensage("success", 2.5);

	$page->setTpl("lembretes", array(
        "lembretes"=>$lembretes,
        "mensagem"=>$mensagem
        
    ));

});
$app->get("/lembretes/:idlembrete", function($idlembrete){

    User::verifyLogin();

    $lembrete = new Lembrete();

    $lembrete->getLembrete((int)$idlembrete);

    $lembrete->statusLembrete($idlembrete);

    $page = new Page();

    $mensagem = null;

	$page->setTpl("lembrete-update", array(
        "lembretes"=>$lembrete->getValues(),
        "mensagem"=>$mensagem
            
    ));

});
$app->post("/lembretes/:idlembrete", function($idlembrete){

    User::verifyLogin();

    $lembrete = new Lembrete();

    $lembrete->getLembrete((int)$idlembrete);

    $lembrete->setData($_POST);
    
    $lembrete->updateLembrete($idlembrete);

    $page = new Page();

    $mensagem = Alert::showMensage("success", 2.5);

	$page->setTpl("lembrete-update", array(
        "lembretes"=>$lembrete->getValues(),
        "mensagem"=>$mensagem
        
    ));

});

$app->get("/lembretes/:idlembrete/delete", function($idlembrete){

    User::verifyLogin();

    $lembrete = new Lembrete();

    $lembrete->getLembrete((int)$idlembrete);

    $lembrete->deleteLembrete($idlembrete);

    $lembretes = $lembrete->listLembrete();

    $mensagem = Alert::showMensage("success", 2.5);

    $page = new Page();

	$page->setTpl("lembretes", array(
        "lembretes"=>$lembretes,
        "mensagem"=>$mensagem
        
    ));

});
$app->get("/relatorios", function(){

    $relatorio = new Relatorios();
    
    $page = new Page();

    $dataAtual = date("Y-m-d");

    $dataInicio = date("Y-m-d");

    $relatorio = Relatorios::listComprasEntreDatas($dataInicio, $dataInicio);

    $total = Relatorios::somaTotal($dataInicio,$dataAtual); 

    foreach ($total as $value){
       foreach($value as $total){
        $total;
       }
    }
    $total = round($total, 2);

    $total = number_format($total, 2, ',', '.');

    $mensagem = null;

	$page->setTpl("relatorios", array(
        "relatorio"=>$relatorio,
        "dataAtual"=>$dataAtual,
        "dataInicio"=>$dataInicio,
        "total"=>$total,
        "mensagem"=>$mensagem
        
    ));

});
$app->post("/relatorios", function(){

    $relatorio = new Relatorios();
    
    $relatorio = Relatorios::listComprasEntreDatas($_POST["dataInicio"],$_POST["dataFinal"]); 
    
    $total = Relatorios::somaTotal($_POST["dataInicio"],$_POST["dataFinal"]); 

    foreach ($total as $value){
        foreach($value as $total){
         $total;
        }
    }

    $total = round($total, 2);
 
    $total = number_format($total, 2, ',', '.');

    $page = new Page();

    $dataAtual = $_POST["dataFinal"];

    $dataInicio = $_POST["dataInicio"];

    $mensagem = null;

	$page->setTpl("relatorios", array(
        "relatorio"=>$relatorio,
        "dataAtual"=>$dataAtual,
        "dataInicio"=>$dataInicio,
        "total"=>$total,
        "mensagem"=>$mensagem
        
    ));

});

$app->get("/clientes/imprimir/:idcliente", function($idcliente){
    
    User::verifyLogin();

    $cliente = new User();

    $cliente->get((int)$idcliente);

    $mensagem = null;

    $compras = Compra::listCompras($idcliente); 

    $totalCompra = Compra::somaTotal($idcliente);

    $totalCompra = round($totalCompra, 2);

    $totalCompra = number_format($totalCompra, 2, ',', '.');

    $dataAtual = date("d/m/Y");

    $page = new Page([
        "header"=> false,
        "footer"=> false,
    ]);

	$page->setTpl("cliente-imprimir", array(
        "compras"=>$compras,
        "totalMostra"=>$totalCompra, 
        "mensagem"=>$mensagem,
        "idCliente"=>$idcliente,
        "dataAtual"=>$dataAtual,
        "cliente"=>$cliente->getValues()

    ));

});

$app->post("/pagamentos", function(){
    
    $pagamento = new Pagamento();
    
    $page = new Page();

    $pagamento = Pagamento::listPagamentosEntreDatas($_POST["dataInicio"],$_POST["dataFinal"]);

    $total = Pagamento::somaTotal($_POST["dataInicio"],$_POST["dataFinal"]); 

    foreach ($total as $value){
       foreach($value as $total){
        $total;
       }
    }

    $total = round($total, 2);

    $total = number_format($total, 2, ',', '.');
    
    $dataAtual = $_POST["dataFinal"];

    $dataInicio = $_POST["dataInicio"];

    $mensagem = null;

	$page->setTpl("pagamentos", array(
        "relatorio"=>$pagamento,
        "dataAtual"=>$dataAtual,
        "dataInicio"=>$dataInicio,
        "total"=>$total,
        "mensagem"=>$mensagem  
    ));
    
});

$app->get("/pagamentos", function(){
    
    $pagamento = new Pagamento();
    
    $page = new Page();

    $dataAtual = date("Y-m-d");

    $dataInicio = date("Y-m-d");

    $pagamento = Pagamento::listPagamentosEntreDatas($dataInicio, $dataInicio);

    $total = Pagamento::somaTotal($dataInicio,$dataAtual); 

    foreach ($total as $value){
       foreach($value as $total){
        $total;
       }
    }
    $total = round($total, 2);

    $total = number_format($total, 2, ',', '.');

    $mensagem = null;

	$page->setTpl("pagamentos", array(
        "relatorio"=>$pagamento,
        "dataAtual"=>$dataAtual,
        "dataInicio"=>$dataInicio,
        "total"=>$total,
        "mensagem"=>$mensagem
        
    ));
    
});

$app->get("/relatorios/flavio", function(){
    
    $relatorio = new Relatorios();
    
    $page = new Page();

    $dataAtual = date("Y-m-d");

    $dataInicio = date("Y-m-d");

    $relatorio = Relatorios::listComprasEntreDatasAVista($dataInicio, $dataInicio);

    $total = Relatorios::somaTotalAVista($dataInicio,$dataAtual); 

    foreach ($total as $value){
       foreach($value as $total){
        $total;
       }
    }
    $total = round($total, 2);

    $total = number_format($total, 2, ',', '.');

    $mensagem = null;

	$page->setTpl("relatoriosflavio", array(
        "relatorio"=>$relatorio,
        "dataAtual"=>$dataAtual,
        "dataInicio"=>$dataInicio,
        "total"=>$total,
        "mensagem"=>$mensagem
        
    ));
    
});

$app->post("/relatorios/flavio", function(){

    $mensagem = null;    

    $relatorio = new Relatorios();

    $relatorio = Relatorios::listComprasEntreDatasAVista($_POST["dataInicio"],$_POST["dataFinal"]);

    $total = Relatorios::somaTotalAVista($_POST["dataInicio"],$_POST["dataFinal"]); 

    foreach ($total as $value){
        foreach($value as $total){
         $total;
        }
    }

    $total = round($total, 2);
 
    $total = number_format($total, 2, ',', '.');

    $page = new Page();

    $dataAtual = $_POST["dataFinal"];

    $dataInicio = $_POST["dataInicio"];

	$page->setTpl("relatoriosflavio", array(
        "relatorio"=>$relatorio,
        "dataAtual"=>$dataAtual,
        "dataInicio"=>$dataInicio,
        "total"=>$total,
        "mensagem"=>$mensagem
        
    ));
    
});

$app->get("/promocionais", function(){

    User::verifyLogin();

    $page = new Page();

    $lembrete = new Lembrete();

    $lembretes = $lembrete->listLembretePromocional(); 

    $mensagem = null;

	$page->setTpl("promocionais", array(
        "lembretes"=>$lembretes,
        "mensagem"=>$mensagem
        
    ));

});

$app->post("/promocionais", function(){

    $lembrete = new Lembrete();

    $_POST["dataLembrete"] = date("Y-m-d");

    $lembrete->setData($_POST);

    $lembrete->createLembretePromocional($_POST);

    $lembretes = Lembrete::listLembretePromocional();

    $page = new Page();

    $mensagem = Alert::showMensage("success", 2.5);

	$page->setTpl("promocionais", array(
        "lembretes"=>$lembretes,
        "mensagem"=>$mensagem
        
    ));

});

$app->get("/promocionais/:idlembrete/delete", function($idlembrete){

    User::verifyLogin();

    $lembrete = new Lembrete();

    $lembrete->getLembrete((int)$idlembrete);

    $lembrete->deleteLembrete($idlembrete);

    $lembretes = $lembrete->listLembretePromocional();

    $mensagem = Alert::showMensage("success", 2.5);

    $page = new Page();

	$page->setTpl("promocionais", array(
        "lembretes"=>$lembretes,
        "mensagem"=>$mensagem
        
    ));

});



$app->run();

?>