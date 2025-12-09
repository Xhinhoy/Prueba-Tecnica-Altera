<?php

namespace Nicolas\ItemsApi;

class Router
{
    private $routes = [];

    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put($path, $handler)
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete($path, $handler)
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];

        // Remover query string
        $requestUri = strtok($requestUri, '?');

        // Remover trailing slash
        $requestUri = rtrim($requestUri, '/');
        if (empty($requestUri)) {
            $requestUri = '/';
        }

        foreach ($this->routes as $route) {
            // Convertir parámetros {id} a regex
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remover el match completo

                $handler = $route['handler'];

                if (is_callable($handler)) {
                    call_user_func_array($handler, $matches);
                    return;
                } elseif (is_array($handler)) {
                    $controller = new $handler[0]();
                    $method = $handler[1];
                    call_user_func_array([$controller, $method], $matches);
                    return;
                }
            }
        }

        // Ruta no encontrada
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode([
            'error' => 'Ruta no encontrada',
            'path' => $requestUri,
            'method' => $requestMethod
        ], JSON_PRETTY_PRINT);
    }
}
