<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
$app = new \Slim\App;
require '../src/classes/banco.php';
require '../src/classes/cliente.php';
require '../src/classes/rds.php';
require '../src/classes/stack.php';
require '../src/middleware.php';

$app->get('/hello/{name}', function(Request $request, Response $response){
	
	$name = $request->getAttribute('name');
	$response->getBody()->write("Hello, $name");

	return $response;
});

// Retorna um JSON com os clientes cadastrados no banco com suas respectivas Rds e Stacks

$app->get('/clientes/', function(Request $request , Response $response){
	$cliente   = new Cliente();
	$dashboard = $cliente->dashboard();
	$data      = json_encode($dashboard);
	$status    = 200;

	return $response->withStatus($status)->write($data);
});

// Retorna um Json com o client especificado pelo Id

$app->get('/clientes/{id}',function(Request $request , Response $response){
	
	$idUrl		 = $request->getAttribute('id');
	$cliente   = new Cliente();
	
	if(is_numeric($idUrl)){

		$cliente->setId($idUrl);
		$verificar = $cliente->verificarId($idUrl);

		if($verificar > 0){
			$busca  = $cliente->buscaCliente($idUrl);
			$data   = json_encode($busca);
			$status = 200;

		}else{
			$data   = json_encode("Not Found");
			$status = 404;
		}

		return $response->withStatus($status)->write($data);

	}else {

		$data = json_encode("Parâmetro inválido");
		$status = 400;

		return $response->withStatus($status)->write($data);

	}

});

// Retorna um JSON com a lista de RDS

$app->get('/rds/',function(Request $request, Response $response){
	$rds     = new Rds();
	$rdsList = $rds->listRds();
	$data    = json_encode($rdsList);
	$status  = 200;

	return $response->withStatus($status)->write($data);
});

// Retorna um Json com o rds especificado pelo Id

$app->get('/rds/{id}',function(Request $request, Response $response){
	
	$idUrl = $request->getAttribute('id');
	$rds   = new Rds();

	if(is_numeric($idUrl)){
		$verificar = $rds->verificarId($idUrl);

		if($verificar > 0){
			$busca  = $rds->buscaRds($idUrl);
			$data   = json_encode($busca);
			$status = 200;
		}else{
			$data   = json_encode("Not Found");
			$status = 404;
		}
		
		return $response->withStatus($status)->write($data);
	}else{

		$data   = json_encode("Parâmetro inválido");
		$status = 400;

		return $response->withStatus($status)->write($data);
	}
});

// Retorna um JSON com a lista de Stacks
$app->get('/stack/',function (Request $request, Response $response){
	
	$stack     = new Stack();
	$stackList = $stack->listStack();
	$data      = json_encode($stackList);
	$status    = 200;

	return $response->withStatus($status)->write($data);
});

// Retorna um Json com o stack especificado pelo Id

$app->get('/stack/{id}',function(Request $request, Response $response){
	
	$idUrl = $request->getAttribute('id');
	$stack = new Stack();

	if(is_numeric($idUrl)){
		$verificar = $stack->verificarId($idUrl);

		if($verificar > 0){
			$busca  = $stack->buscaStack($idUrl);
			$data   = json_encode($busca);
			$status = 200;
		}else{
			$data   = json_encode("Not Found");
			$stauts = 404;
		}

		return $response->withStatus($status)->write($data);

	}else{

		$data   = json_encode("Parâmetro inválido");
		$status = 400;

		return $response->withStatus($status)->write($data);
	}
});

// cadastra o cliente no banco de dados

$app->post('/clientes/',function($request,$response){
	$cliente    = new Cliente();
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

	$cliente->verificarCampo($idRds);
	$cliente->verificarCampo($idStack);
	$cliente->verificarCampo($nome);
	$cliente->verificarCampo($email);
	$cliente->verificarCampo($dominio);

	$verificar = $cliente->verificarEmail();

	if($verificar > 0){
		$data =  json_encode(array("Email ja cadastrado no banco"));
	}else{
		$cadastrarCliente = $cliente->cadastrarNoBanco();
		$data = json_encode($cadastrarCliente);	
	}

	return $response->write($data);
});
// atualiza o cadastro do cliente do id informado

$app->put('/clientes/{id}' ,function ($request,$response){

	$cliente    = new Cliente();
	
	$idUrl      = $request->getAttribute('id');
	$parsedBody = $this->request->getParsedBody();

	$idCliente =	$idUrl;
	if(is_numeric($idUrl)){
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

		$cliente->verificarCampo($idRds);
		$cliente->verificarCampo($idStack);
		$cliente->verificarCampo($nome);
		$cliente->verificarCampo($email);
		$cliente->verificarCampo($dominio);

		$updateCliente = $cliente->alterarCliente();

		$data = json_encode($updateCliente);	

		return $response->write($data);

	}else{

		$data   = json_encode("Parâmetro inválido");
		$status = 400;

		return $response->withStatus($status)->write($data);
	}

});

// cadastra o rds no banco de dados

$app->post('/rds/',function($request,$response){

	$rds        = new Rds();
	$parsedBody = $this->request->getParsedBody();

	$nome = $parsedBody['nome'];
	$url  = $parsedBody['url'];
	$port = $parsedBody['port'];

	$rds->setNome($nome); 
	$rds->setUrl($url); 
	$rds->setPort($port); 

	$rds->verificarCampo($url);
	$rds->verificarCampo($port);
	$rds->verificarCampo($nome);
	
	$cadastro = $rds->cadastrarRds();
	$data = json_encode($cadastro);

	return $response->write($data);
});

// cadastra o stack no banco de dados

$app->post('/stack/',function($request,$response){

	$stack      = new Stack();
	$parsedBody = $this->request->getParsedBody();

	$nome     =  $parsedBody['nome'];
	$endereco =  $parsedBody['endereco'];
	
	$stack->setNome($nome); 
	$stack->setEndereco($endereco); 

	$stack->verificarCampo($nome);
	$stack->verificarCampo($endereco);

	$cadastro = $stack->cadastrarStack();
	$data     = json_encode($cadastro);

	return $response->write($data);
});

// atualiza as informações do rds no banco de dados o id informado

$app->put('/rds/{id}' ,function ($request,$response){

	$rds   = new Rds();
	$idUrl = $request->getAttribute('id');
	$parsedBody = $this->request->getParsedBody();

	$idRds = 	$idUrl;

	if(is_numeric($idUrl)){

		$nome  = 	$parsedBody['nome'];
		$url   = 	$parsedBody['url'];
		$port  = 	$parsedBody['port'];

		$rds->setId($idRds);
		$rds->setNome($nome);
		$rds->setUrl($url);
		$rds->setPort($port);

		$updateRds = $rds->alterarRds($idUrl);

		$data = json_encode($updateRds);	
		
		return $response->write($data);

	}else{

		$data   = json_encode("Parâmetro inválido");
		$status = 400;

		return $response->withStatus($status)->write($data);
	}
});

// atualiza as informações do stack no banco de dados o id informado

$app->put('/stack/{id}' ,function ($request,$response){

	$stack = new Stack();
	$idUrl = $request->getAttribute('id');
	$parsedBody = $this->request->getParsedBody();

	if(is_numeric($idUrl)){

		$idStack  = 	$idUrl;
		$nome     = 	$parsedBody['nome'];
		$endereco = 	$parsedBody['endereco'];

		$stack->setId($idStack);
		$stack->setNome($nome);
		$stack->setEndereco($endereco);

		$updateStack = $stack->alterarStack($idUrl);

		$data = json_encode($updateStack);	
		
		return $response->write($data);

	}else{

		$data   = json_encode("Parâmetro inválido");
		$status = 400;

		return $response->withStatus($status)->write($data);
	}
});

// exclui as informações do cliente no banco de dados o id informado

$app->delete('/clientes/{id}' ,function ($request,$response){

	$cliente    = new Cliente();
	$idUrl      = $request->getAttribute('id');
	$parsedBody = $this->request->getParsedBody();
	$idCliente  =	$idUrl;
	
	if(is_numeric($idUrl)){

		$cliente->setId($idCliente);

		$deleteCliente = $cliente->excluirCliente();
		$status        = 200;
		$data          = json_encode($deleteCliente);	

		return $response->write($data);

	}else{

		$data   = json_encode("Parâmetro inválido");
		$status = 400;

		return $response->withStatus($status)->write($data);
	}
});

// exclui as informações do rds no banco de dados o id informado

$app->delete('/rds/{id}' ,function ($request,$response){

	$rds        = new Rds ();
	$idUrl      = $request->getAttribute('id');
	$parsedBody = $this->request->getParsedBody();
	$idRds      =	$idUrl;

	if(is_numeric($idUrl)){

		$rds->setId($idRds);

		$deleteRds = $rds->excluirRds();
		$status    = 200;
		$data      = json_encode($deleteRds);	

		return $response->write($data);

	}else{

		$data   = json_encode("Parâmetro inválido");
		$status = 400;

		return $response->withStatus($status)->write($data);
	}
});

// exclui as informações do stack no banco de dados o id informado

$app->delete('/stack/{id}' ,function ($request,$response){

	$stack      = new Stack ();
	$idUrl      = $request->getAttribute('id');
	$parsedBody = $this->request->getParsedBody();
	$idStack    =	$idUrl;
	
	if(is_numeric($idUrl)){

		$stack->setId($idStack);

		$deleteStack = $stack->excluirStack();
		$status      = 200;
		$data        = json_encode($deleteStack);	

		return $response->withStatus($status)->write($data);

	}else{

		$data   = json_encode("Parâmetro inválido");
		$status = 400;

		return $response->withStatus($status)->write($data);
	}
});


$app->get('/clientes/graficos/stack/', function ($request,$response){

	$cliente = new Cliente();

	$grafico = $cliente->graficoClientesStack();
	$status  = 200;
	$data    = json_encode($grafico);	

	return $response->withStatus($status)->write($data);
});

$app->get('/clientes/graficos/rds/', function ($request,$response){

	$cliente = new Cliente();

	$grafico = $cliente->graficoClientesRds();
	$status  = 200;
	$data    = json_encode($grafico);	

	return $response->withStatus($status)->write($data);
});



$app->run();