<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \GuzzleHttp\Client as Client;

require '../vendor/autoload.php';

$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);
$container = $app->getContainer();
$container['view'] = new \Slim\Views\PhpRenderer('../templates/');

$app->get('/', function (Request $request, Response $response) {
    $response = $this->view->render($response, 'index.phtml', ['name' => 'GET']);
    return $response;
});

$app->post('/send', function (Request $aa, Response $response)  {
    $client = new Client();
    $movieData = $client->request('GET', 'https://tv-v2.api-fetch.website/random/movie')->getBody();
    $synopsis = json_decode($movieData)->synopsis;                     


    echo PHP_EOL;
    // echo $response->getBody();
    // echo $response->getHeader('Content-Length');


    // $response = $this->view->render($response, 'index.phtml', ['name' => 'POST']);
    // return $response;
    echo 'he';
});

$app->run();
