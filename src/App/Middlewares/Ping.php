<?php

namespace App\Middlewares;

use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Ping
{
    private $allowedMethods = [];

    public function __construct()
    {
        $this->allowedMethods = ['GET', 'POST'];
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        return new JsonResponse(
            [
                'method' => $_SERVER['REQUEST_METHOD'],
                'ack' => time()
            ]
        );
    }

    /**
     * Get allowedMethods.
     *
     * @return allowedMethods.
     */
    public function getAllowedMethods()
    {
        return $this->allowedMethods;
    }
}
