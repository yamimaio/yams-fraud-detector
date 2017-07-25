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
interface MiddlewareInterface
{
    /**
     * Invoke a route callable.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     * @param callable $next     Next middleware
     *
     * @return Response The response from the callable.
     */
    public function __invoke(
        Request $request,
        Response $response,
        callable $next
    );
}
