<?php
/**
 * FraudScoringAction.phphp
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Actions;

use FraudDetector\FraudDetector;
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
     * @var FraudDetector
     */
    protected $detector;

    /**
     * FraudScoringAction constructor.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->logger = $config['logger'];
        $this->detector = $config['detector'];
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
        $data = $request->getParsedBody();

        $scoring = $this->detector->getScoring($data);

        $this->logger->addInfo('Fraud Scoring ' . $scoring);

        $body['scoring'] = $scoring;
        //todo could add detail of score per rule

        $response->getBody()->write(json_encode($body));

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    }
}
