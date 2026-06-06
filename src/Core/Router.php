<?php

declare(strict_types=1);

namespace Src\Core;

use Src\Exceptions\NotFoundException;
use Src\Exceptions\HttpException;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, array $handler): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => $path,
            'handler' => $handler,
        ];
    }

    public function dispatch(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri    = $uri !== '/' ? rtrim($uri, '/') : '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            $params = $this->matchRoute($route['pattern'], $uri);

            if ($params !== null) {
                $this->callHandler($route['handler'], $params);
                return;
            }
        }

        throw new NotFoundException("Nessuna rotta per: {$method} {$uri}");
    }

    private function matchRoute(string $pattern, string $uri): ?array
    {
        $regex = '#^' . preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern) . '$#';

        if (preg_match($regex, $uri, $matches)) {
            return array_filter($matches, fn($k) => is_string($k), ARRAY_FILTER_USE_KEY);
        }

        return null;
    }

    private function callHandler(array $handler, array $params): void
    {
        [$class, $method] = $handler;

        if (!class_exists($class)) {
            throw new HttpException(500, "Controller non trovato: {$class}");
        }

        $controller = new $class();

        if (!method_exists($controller, $method)) {
            throw new HttpException(500, "Metodo non trovato: {$class}::{$method}");
        }

        $controller->$method(...array_values($params));
    }
}