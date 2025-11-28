<?php
namespace App\Core;

class Controller {
    protected array $config;
    public function __construct(array $config) {
        $this->config = $config;
    }
    protected function view(string $view, array $data = []): void {
        View::render($view, $data, $this->config);
    }
    protected function redirect(string $path): void {
        header('Location: ' . $path);
        exit;
    }
    protected function isPost(): bool {
        return $_SERVER['REQUEST_METHOD']==='POST';
    }
}
