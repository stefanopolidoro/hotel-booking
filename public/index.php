<?php

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/config/config.php';

use Src\Core\Router;
use Src\Exceptions\HttpException;
use Src\Exceptions\NotFoundException;

set_exception_handler(function (\Throwable $e) {
    $code = $e instanceof HttpException ? $e->getStatusCode() : 500;
    http_response_code($code);

    if (APP_ENV === 'development') {
        echo '<pre style="background:#1e1e1e;color:#d4d4d4;padding:1rem">';
        echo '<strong>' . get_class($e) . '</strong>: ' . e($e->getMessage()) . "\n\n";
        echo e($e->getTraceAsString());
        echo '</pre>';
        return;
    }

    $errorView = ROOT_PATH . '/app/Views/errors/' . $code . '.php';
    $fallback  = ROOT_PATH . '/app/Views/errors/500.php';

    if (file_exists($errorView))    require $errorView;
    elseif (file_exists($fallback)) require $fallback;
    else echo "Errore {$code}";
});

$router = new Router();

$router->get('/', [\App\Controllers\HomeController::class, 'index']);

$router->dispatch();