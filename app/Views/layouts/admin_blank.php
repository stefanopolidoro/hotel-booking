<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?><?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <?= $content ?>
</body>
</html>