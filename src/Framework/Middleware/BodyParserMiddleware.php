<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * Class BodyParserMiddleware
 *
 * @package Ares\Framework\Middleware
 */
class BodyParserMiddleware implements MiddlewareInterface
{
    /**
     * @param   Request         $request
     * @param   RequestHandler  $handler
     *
     * @return Response
     */
    public function process(
        Request $request,
        RequestHandler $handler
    ): Response {
        $contentType = $request->getHeaderLine('Content-Type');

        if (false !== strpos($contentType, 'application/json')) {
            $contents = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request = $request->withParsedBody($contents);
            }
        }

        $response = $handler->handle($request);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
