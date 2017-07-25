<?php
/**
 * fraud
 * OrderValidator.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Middleware;

use FraudDetector\Interfaces\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class OrderValidator
 *
 * Checks the order payload is valid
 *
 * @package FraudDetector\Middleware
 */
class OrderValidator implements MiddlewareInterface
{
    /**
     * Invoke a route callable.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     * @param callable $next     Next middleware
     *
     * @return Response The response from the callable.
     *
     * @todo complete order payload validation
     */
    public function __invoke(
        Request $request,
        Response $response,
        callable $next
    ) {
        $response = $next($request, $response);

        return $response;
    }
}