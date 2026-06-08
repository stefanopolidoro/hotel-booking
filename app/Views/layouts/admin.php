<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?>Admin | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body>

<div class="admin-wrapper">

    <aside class="admin-sidebar">
        <div class="brand">🏨 Admin Panel</div>
        <nav>
            <a href="<?= url('/admin/dashboard') ?>"
               class="<?= str_contains($_SERVER['REQUEST_URI'], '/dashboard') ? 'active' : '' ?>">
                Dashboard
            </a>
            <a href="<?= url('/admin/rooms') ?>"
               class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/rooms') ? 'active' : '' ?>">
                Camere
            </a>
            <a href="<?= url('/admin/bookings') ?>"
               class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/bookings') ? 'active' : '' ?>">
                Prenotazioni
            </a>
            <a href="<?= url('/admin/logout') ?>" style="margin-top:auto; color:#f87171;">
                Esci
            </a>
        </nav>
    </aside>

    <div class="admin-content">

        <?php $flash = get_flash(); ?>
        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?>">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?= $content ?>

    </div>

</div>

</body>
</html>