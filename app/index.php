<?php
/**
 * App File for Fraud detection API
 *
 * @author Yamila Maio  <yamimaio@gmail.com>
 */

use Monolog\Handler\StreamHandler as StreamHandler;
use Monolog\Logger as Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once '../vendor/autoload.php';

$app = new \Slim\App;

//load dependencies as services
$container = $app->getContainer();

//add logger as a service
$container['logger'] = function ($c) {
    $logger = new Logger('fraud_logger');
    $file_handler = new StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

//define API routes
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $this->logger->addInfo("App is running. Hello route called");
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->run();
