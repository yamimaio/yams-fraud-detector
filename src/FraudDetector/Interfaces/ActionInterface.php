<?php
/**
 * ActionInterface.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Interfaces;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Interface ActionInterface
 *
 * @package FraudDetector\Interfaces
 */
interface ActionInterface
{
    /**
     * Invoke a route callable.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     * @param array    $args     The route's placeholder arguments
     *
     * @return Response|string The response from the callable.
     */
    public function __invoke(Request $request, Response $response, array $args);
}
