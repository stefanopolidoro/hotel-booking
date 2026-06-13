<?php

declare(strict_types=1);

namespace Src\Core;

use Src\Exceptions\NotFoundException;
use Src\Exceptions\HttpException;

abstract class BaseController
{
    protected function render(
        string $view,
        array  $data   = [],
        string $layout = 'main'
    ): void {
        extract($data, EXTR_SKIP);

        $viewPath = ROOT_PATH . '/app/Views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new NotFoundException("View non trovata: {$view}");
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        $layoutPath = ROOT_PATH . '/app/Views/layouts/' . $layout . '.php';
        if (!file_exists($layoutPath)) {
            throw new NotFoundException("Layout non trovato: {$layout}");
        }

        require $layoutPath;
    }

    protected function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }

    protected function abort(int $code, string $message = ''): never
    {
        throw new HttpException($code, $message);
    }

    protected function verifyCsrf(): void
    {
        $fromForm    = $_POST['_csrf']         ?? '';
        $fromSession = $_SESSION['csrf_token'] ?? '';

        if (!hash_equals($fromSession, $fromForm)) {
            $this->abort(403, 'CSRF token non valido');
        }
    }

    protected function getPost(): array
    {
        return array_map(
            fn($v) => is_string($v) ? trim($v) : $v,
            $_POST
        );
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION[ADMIN_SESSION_KEY])) {
            $this->redirect(url('/admin/login'));
        }
    }
}