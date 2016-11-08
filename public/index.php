<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/classes/banco.php';
require '../src/classes/cliente.php';
require '../src/classes/rds.php';
require '../src/classes/stack.php';


$app = new \Slim\App ;


$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");


    return $response;
});

// Retorna um JSON com os clientes cadastrados no banco com suas respectivas Rds e Stacks

$app->get('/clientes/', function (Request $request , Response $response){

$cliente = new Cliente();

$dashboard = $cliente->dashboard();

$response = json_encode($dashboard);

return $response;

});

// Retorna um JSON com a lista de RDS

$app->get('/rds/',function (Request $request, Response $response){

$rds = new Rds();

$rdsList = $rds->listRds();

$response = json_encode($rdsList);

return $response;

});

// Retorna um JSON com a lista de Stacks

$app->get('/stack/',function (Request $request, Response $response){

$stack = new Stack();

$stackList = $stack->listStack();

$response = json_encode($stackList);

return $response;

});

$app->post('/clientes/' ,function ($request,$response){

//   $parsedBody = $request->getParsedBody();

$cliente = new Cliente();

$parsedBody = $this->request->getParsedBody();

$idRds		= 	$parsedBody['idRds'];
$idStack 	= 	$parsedBody['idStack'];
$nome 		= 	$parsedBody['nome'];
$email 		= 	$parsedBody['email'];
$dominio 	= 	$parsedBody['dominio'];

	$cliente->setIdRds($idRds);	
	$cliente->setIdStack($idStack);
	$cliente->setNome($nome);
	$cliente->setEmail($email);
	$cliente->setDominio($dominio);

	$verificar = $cliente->verificarEmail();

		
	if($verificar > 0){
	
	$response =  json_encode(array("Email ja cadastrado no banco"));
		
			} else {


	$cadastrarCliente = $cliente->cadastrarNoBanco();
	

	$response = json_encode($cadastrarCliente);	
	
	}

	return $response;
});

$app->post('/rds/' ,function ($request,$response){

//   $parsedBody = $request->getParsedBody();

$rds = new Rds();


$parsedBody = $this->request->getParsedBody();

$nome = $parsedBody['nome'];
$url =  $parsedBody['url'];
$port = $parsedBody['port'];

$rds->setNome($nome); 
$rds->setUrl($url); 
$rds->setPort($port); 

$cadastro = $rds->cadastrarRds();

$response = json_encode($cadastro);

return $response;

});


$app->put('/stack/' ,function ($request,$response){

//   $parsedBody = $request->getParsedBody();

$stack = new Stack();


$parsedBody = $this->request->getParsedBody();

$nome = $parsedBody['nome'];
$endereco =  $parsedBody['endereco'];

$stack->setNome($nome); 
$stack->setEndereco($endereco); 


$cadastro = $stack->cadastrarStack();

$response = json_encode($cadastro);

return $response;

});

$app->put('/clientes/' ,function ($request,$response){

$cliente = new Cliente();

$parsedBody = $this->request->getParsedBody();

$idCliente 	=	$parsedBody['idCliente'];
$idRds		= 	$parsedBody['idRds'];
$idStack 	= 	$parsedBody['idStack'];
$nome 		= 	$parsedBody['nome'];
$email 		= 	$parsedBody['email'];
$dominio 	= 	$parsedBody['dominio'];

	$cliente->setId($idCliente);
	$cliente->setIdRds($idRds);	
	$cliente->setIdStack($idStack);
	$cliente->setNome($nome);
	$cliente->setEmail($email);
	$cliente->setDominio($dominio);

	$verificar = $cliente->verificarEmail();

	
	die();

	if($verificar < 1){
	
	$response =  json_encode(array("Cadastro não localizado para alteração"));
		
			} else {

	$updateCliente = $cliente->alterarCliente();
	
	$response = json_encode($updateCliente);	
	
	};

	return $response;
});


$app->post('/updaterds', 'updateRds');
$app->post('/updatestack', 'updateStack');


$app->delete('/deleterds/delete/:update_id','deleteUpdate');
$app->delete('/deletestack/delete/:update_id','deleteUpdate');


$app->run();