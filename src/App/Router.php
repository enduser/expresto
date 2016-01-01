<?php

namespace App;

class Router
{
    /**
     * @param string $path
     * @return array
     */
    public static function resolver($path = '')
    {
        $config = [];

        if (empty($path)) {
            $path = $_SERVER['REQUEST_URI'];
        }

        if ($path == '/') {
            return $config;
        }

        $mapper = require_once(__DIR__ . '/../../config/router-mapper.php');
        $middlewaresDir = __DIR__ . '/./Middlewares';
        $uri = explode('/', $path);

        $name = ucfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $uri[1]))));

        if (array_key_exists($uri[1], $mapper)) {
            $name = $mapper[$uri[1]];
        }

        $phpFile = $middlewaresDir . '/' . $name . '.php';
        $className = 'App\\Middlewares\\' . $name;

        if (file_exists($phpFile)) {
            $middleware = new $className;

            $allowedMethods = [];
            if (method_exists($middleware, 'getAllowedMethods')) {
                $allowedMethods = $middleware->getAllowedMethods();
            }

            $config['name'] = $uri[1];
            $routePath = '/' . $uri[1];

            if (empty($allowedMethods)
                or in_array('GET', $allowedMethods)
                or in_array('PUT', $allowedMethods)
            ) {
                $routePath .= '[/{id}]';
            }

            $config['path'] = $routePath;
            $config['middleware'] = $middleware;

            if (!empty($allowedMethods)) {
                $config['allowed_methods'] = $allowedMethods;
            }
        }

        return $config;
    }
}
