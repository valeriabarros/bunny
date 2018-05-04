<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \GuzzleHttp\Client as Client;
use GuzzleHttp\Exception\ClientException;

require '../vendor/autoload.php';

$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);
$container = $app->getContainer();
$container['view'] = new \Slim\Views\PhpRenderer('../templates/');

$app->get('/', function (Request $request, Response $response) {
    $response = $this->view->render($response, 'index.phtml', ['text' => "Let's try?"]);
    return $response;
});

$app->post('/send', function (Request $request, Response $response)  {
    $client = new Client();
    $movieData = $client->request('GET', 'https://tv-v2.api-fetch.website/random/movie')->getBody();
    $text = json_decode($movieData)->synopsis;                     
    $formData = array (
        'title' => 'Valéria Barros - Job Application for Bunny Inc.',
        'script' => $text,
        'fake_project' => 1,
        'talentID' => 47524
    );
    $headers = ['Authorization' => 'Basic MTMxMjMxOjU3ZDUwZTRlN2M1YTRmYmQwMDYxZGVjYTY1OGJhZThh','Content-Type' =>'application/x-www-form-urlencoded'];
    try {
        $client->request('POST', "https://api.voicebunny.com/projects/addBooking", ['form_params' => $formData, 'headers' => $headers])->getBody();
    } catch (ClientException $e) {
        $error = "Something goes wrong with Voice Bunny API.";
    }
    $response = $this->view->render($response, 'index.phtml', ['text' => $text, 'error' => $error]);
    return $response;
});

$app->run();
