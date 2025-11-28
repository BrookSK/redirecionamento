<?php
namespace App\Core;

class View {
    public static function render(string $view, array $data, array $config): void {
        $viewFile = BASE_PATH . '/app/Views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo 'View not found';
            return;
        }
        $t = Translation::class;
        extract($data);
        $baseUrl = $config['base_url'] ?? '/';
        $csrf = CSRF::token();
        include BASE_PATH . '/app/Views/layout.php';
    }
}
