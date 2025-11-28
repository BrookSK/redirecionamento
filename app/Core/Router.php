<?php
namespace App\Core;

class Router {
    private array $routes = [];
    private array $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    public function get(string $pattern, $handler): void { $this->map('GET', $pattern, $handler); }
    public function post(string $pattern, $handler): void { $this->map('POST', $pattern, $handler); }

    private function map(string $method, string $pattern, $handler): void {
        $regex = $this->toRegex($pattern);
        $this->routes[] = ['method'=>$method,'pattern'=>$pattern,'regex'=>$regex,'handler'=>$handler];
    }

    private function toRegex(string $pattern): string {
        $regex = preg_replace('#\\{([a-zA-Z_][a-zA-Z0-9_]*)\\}#', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $regex . '$#';
    }

    public function dispatch(string $method, string $path): void {
        foreach ($this->routes as $r) {
            if ($r['method'] !== $method) continue;
            if (preg_match($r['regex'], $path, $matches)) {
                $params = [];
                foreach ($matches as $k=>$v) if (!is_int($k)) $params[$k]=$v;
                $this->invoke($r['handler'], $params);
                return;
            }
        }
        http_response_code(404);
        echo '404 Not Found';
    }

    private function invoke($handler, array $params): void {
        if (is_array($handler) && is_string($handler[0])) {
            $class = $handler[0];
            $method = $handler[1];
            $controller = new $class($this->config);
            $ref = new \ReflectionMethod($controller, $method);
            if ($ref->getNumberOfParameters() > 0) {
                $controller->$method($params);
            } else {
                $controller->$method();
            }
        } elseif (is_callable($handler)) {
            call_user_func($handler, $params);
        } else {
            throw new \RuntimeException('Invalid route handler');
        }
    }
}
