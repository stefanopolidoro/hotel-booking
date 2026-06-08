<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina non trovata — <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body>
    <div class="error-page">
        <div class="code">404</div>
        <h1>Pagina non trovata</h1>
        <p>La pagina che cerchi non esiste o è stata spostata.</p>
        <a href="<?= url('/') ?>" class="btn btn-primary">Torna alla home</a>
    </div>
</body>
</html>