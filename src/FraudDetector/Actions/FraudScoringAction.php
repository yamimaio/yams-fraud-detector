<?php
/**
 * FraudScoringAction.phphp
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Actions;

use FraudDetector\Interfaces\ActionInterface;
use Monolog\Logger as Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class FraudScoringAction
 *
 * @package FraudDetector\Actions
 */
class FraudScoringAction implements ActionInterface
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * FraudScoringAction constructor.
     *
     * @param $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Invoke a route callable.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     * @param array    $args     The route's placeholder arguments
     *
     * @return Response|string The response from the callable.
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        $this->logger->addInfo("Fraud Scoring");
        $data = $request->getParsedBody();

        $response->getBody()->write('Your fraud score is... OFF THE CHARTS!');
        $response->getBody()->write(json_encode($data));
        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    }
}
