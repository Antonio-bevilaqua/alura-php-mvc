<?php
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Server\RequestHandlerInterface;
use Alura\Cursos\Controller\InterfaceControladorRequisicao;

error_reporting(E_USER_WARNING);

require __DIR__ . '/../vendor/autoload.php';


$caminho = $_SERVER['PATH_INFO'];
$rotas = require __DIR__ . '/../config/routes.php';

if (!array_key_exists($caminho, $rotas)){
    http_response_code(404);
}

session_start();

$psr17Factory = new Psr17Factory();

$creator = new ServerRequestCreator(
    $psr17Factory,  //   ServerRequestFactory
    $psr17Factory,  //   UriFactory
    $psr17Factory,  //   UploadedFileFactory
    $psr17Factory   //   StreamFactory
);

$request = $creator->fromGlobals();

if (!isset($_SESSION['logado']) && stripos($caminho, "login") === false){
    header("Location: /login");
    return;
}

$classeControladora = $rotas[$caminho];

/**
 * @var ContainerInterface $container
 */
$container = require __DIR__ . '/../config/dependencies.php';
/**
 * @var RequestHandlerInterface $controlador 
 */
$controlador = $container->get($classeControladora);

$response = $controlador->handle($request);

foreach($response->getHeaders() as $name => $values){
    foreach($values as $value){
        header(sprintf('%s: %s', $name, $value), false);
    }
}

echo $response->getBody();
