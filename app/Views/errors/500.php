<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Errore interno — <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body>
    <div class="error-page">
        <div class="code">500</div>
        <h1>Errore interno del server</h1>
        <p>Qualcosa è andato storto. Riprova tra qualche minuto.</p>
        <a href="<?= url('/') ?>" class="btn btn-primary">Torna alla home</a>
    </div>
</body>
</html>