<?php
require 'vendor/autoload.php';
require 'vendor/slim/slim/Slim/Slim.php';
require 'connectdb.php';
\Slim\Slim::registerAutoloader();

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App();

// Add route callbacks
$app->get('/', function ($request, $response, $args) {
    return $response->withStatus(200)->write('Hello World!');
});

// Run application
$app->run();

?>
