<?php
/**
 * App File for Fraud detection API
 *
 * @author Yamila Maio  <yamimaio@gmail.com>
 */


use FraudDetector\Builders\ContainerBuilder as Builder;
use FraudDetector\Middleware\OrderValidator;

require_once '../vendor/autoload.php';

//temporary config options
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

//load dependencies as services
$container = $app->getContainer();
$builder = new Builder($container);
$container = $builder->getContainer();

//define API routes
$app->group('/fraud', function () use ($app) {
    /**
     * Route for indicating if given order is fraud or not
     */
    $app->post('/status', 'FraudStatusAction');

    /**
     * Route for indicating fraud scoring for given order
     */
    $app->post('/scoring', 'FraudScoringAction');
})->add(new OrderValidator());
// add middleware to validate order payload for all methods in fraud group


$app->run();
