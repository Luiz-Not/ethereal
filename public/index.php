<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/classes/banco.php';
require '../src/classes/cliente.php';
require '../src/classes/rds.php';
require '../src/classes/stack.php';


$app = new \Slim\App(["settings" => $config]);


$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");


    return $response;
});


$app->get('/clientes/dashboard', function (Request $request , Response $response){

$cliente = new Cliente();

$dashboard = $cliente->dashboard();

$response = json_encode($dashboard);

return $response;

});

$app->get('/rds',function (Request $request, Response $response){

$rds = new Rds();

$rdsList = $rds->listRds();

$response = json_encode($rdsList);

return $response;

});

$app->get('/stack',function (Request $request, Response $response){

$stack = new Stack();

$stackList = $stack->listStack();

$response = json_encode($stackList);

return $response;

});

$app->post('/addrds', 'insertRds');
$app->post('/addstack', 'insertStack');
$app->post('/updaterds', 'updateRds');
$app->post('/updatestack', 'updateStack');
$app->delete('/deleterds/delete/:update_id','deleteUpdate');
$app->delete('/deletestack/delete/:update_id','deleteUpdate');


$app->run();