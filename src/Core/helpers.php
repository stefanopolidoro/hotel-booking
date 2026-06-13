<?php

declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function url(string $path = ''): string
{
    return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) return null;
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function format_price(float $amount): string
{
    return '€ ' . number_format($amount, 2, ',', '.');
}

function format_date(string $date): string
{
    return date('d/m/Y', strtotime($date));
}

function nights_between(string $checkIn, string $checkOut): int
{
    $diff = (new \DateTime($checkIn))->diff(new \DateTime($checkOut))->days;
    return max(1, (int) $diff);
}

/**
 * Scrive un'eccezione nel file di log.
 * Chiamata automaticamente dal gestore globale in public/index.php.
 */
function log_error(\Throwable $e): void
{
    $logFile = ROOT_PATH . '/storage/logs/app.log';

    $line = sprintf(
        "[%s] %s: %s in %s:%d\n",
        date('Y-m-d H:i:s'),
        get_class($e),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine()
    );

    error_log($line, 3, $logFile);
}