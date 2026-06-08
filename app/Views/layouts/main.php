<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?><?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body>

<header>
    <nav class="navbar">
        <div class="container">
            <a href="<?= url('/') ?>" class="navbar-brand">🏨 <?= e(APP_NAME) ?></a>
            <ul class="navbar-nav">
                <li><a href="<?= url('/') ?>">Home</a></li>
                <li><a href="<?= url('/rooms') ?>">Camere</a></li>
            </ul>
        </div>
    </nav>
</header>

<main class="site-main">
    <div class="container">

        <?php $flash = get_flash(); ?>
        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?>" style="margin-top:1rem">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?= $content ?>

    </div>
</main>

<footer class="site-footer">
    <div class="container">
        <p>&copy; <?= date('Y') ?> <?= e(APP_NAME) ?>. Tutti i diritti riservati.</p>
    </div>
</footer>

</body>
</html>