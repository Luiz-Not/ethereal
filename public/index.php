<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/classes/banco.php';
require '../src/classes/cliente.php';
require '../src/classes/rds.php';
require '../src/classes/stack.php';

$app = new \Slim\App;

$app->get('/hello/{name}', function(Request $request, Response $response){
	
	$name = $request->getAttribute('name');
	$response->getBody()->write("Hello, $name");

	return $response;
});

// Retorna um JSON com os clientes cadastrados no banco com suas respectivas Rds e Stacks

$app->get('/clientes/', function(Request $request , Response $response){
	$cliente   = new Cliente();
	$dashboard = $cliente->dashboard();
	$data  = json_encode($dashboard);

	return $response->write($data);
});

// Retorna um Json com o client especificado pelo Id

$app->get('/clientes/{id}',function(Request $request , Response $response){
	
	$idUrl		 = $request->getAttribute('id');
	$cliente   = new Cliente();
	$busca		 = $cliente->buscaCliente($idUrl);

	$data = json_encode($busca);

	return $response->write($data);
});

// Retorna um JSON com a lista de RDS

$app->get('/rds/',function(Request $request, Response $response){
	$rds      = new Rds();
	$rdsList  = $rds->listRds();
	$data = json_encode($rdsList);

	return $response->write($data);
});

// Retorna um JSON com a lista de Stacks
$app->get('/stack/',function (Request $request, Response $response){
	$stack     = new Stack();
	$stackList = $stack->listStack();
	$data  = json_encode($stackList);

	return $response->write($data);
});

$app->post('/clientes/',function($request,$response){
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
		$data =  json_encode(array("Email ja cadastrado no banco"));
	} else {
		$cadastrarCliente = $cliente->cadastrarNoBanco();
		$data = json_encode($cadastrarCliente);	
	}

	return $response->write($data);

});

$app->post('/rds/',function($request,$response){

//   $parsedBody = $request->getParsedBody();
	$rds = new Rds();

	$parsedBody = $this->request->getParsedBody();

	$nome = $parsedBody['nome'];
	$url  = $parsedBody['url'];
	$port = $parsedBody['port'];

	$rds->setNome($nome); 
	$rds->setUrl($url); 
	$rds->setPort($port); 

	$cadastro = $rds->cadastrarRds();
	$data = json_encode($cadastro);

	return $response->write($data);
});

$app->put('/stack/',function($request,$response){

	$stack = new Stack();

	$parsedBody = $this->request->getParsedBody();

	$nome     =  $parsedBody['nome'];
	$endereco =  $parsedBody['endereco'];
	
	$stack->setNome($nome); 
	$stack->setEndereco($endereco); 
	$cadastro = $stack->cadastrarStack();
	$data = json_encode($cadastro);

	return $response->write($data);
});

$app->put('/clientes/{id}' ,function ($request,$response){

	$cliente = new Cliente();

	$idUrl = $request->getAttribute('id');

	$parsedBody = $this->request->getParsedBody();

	$idCliente =	$idUrl;
	$idRds     = 	$parsedBody['idRds'];
	$idStack   = 	$parsedBody['idStack'];
	$nome      = 	$parsedBody['nome'];
	$email     = 	$parsedBody['email'];
	$dominio   = 	$parsedBody['dominio'];

	$cliente->setId($idCliente);
	$cliente->setIdRds($idRds);	
	$cliente->setIdStack($idStack);
	$cliente->setNome($nome);
	$cliente->setEmail($email);
	$cliente->setDominio($dominio);

	$updateCliente = $cliente->alterarCliente();

	$data = json_encode($updateCliente);	
	
	return $response->write($data);
});
//$app->post('/updaterds', 'updateRds');
//$app->post('/updatestack', 'updateStack');
//$app->delete('/deleterds/delete/:update_id','deleteUpdate');
//$app->delete('/deletestack/delete/:update_id','deleteUpdate');

$app->run();