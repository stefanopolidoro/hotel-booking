<?php declare(strict_types=1); ?>

<div style="background:#fff; border-radius:16px; padding:2.5rem;
            width:100%; max-width:420px; box-shadow:0 8px 40px rgba(0,0,0,.3);">

    <div style="text-align:center; margin-bottom:2rem;">
        <div style="font-size:2.5rem; margin-bottom:.5rem;">🏨</div>
        <h1 style="font-size:1.4rem; margin-bottom:.25rem;"><?= e(APP_NAME) ?></h1>
        <p style="color:#64748b; font-size:.9rem;">Accesso pannello amministrativo</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error" style="margin-bottom:1.25rem;">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('/admin/login') ?>">
        <?= csrf_field() ?>

        <div class="form-group" style="margin-bottom:1rem;">
            <label for="email">Email</label>
            <input type="email"
                   id="email"
                   name="email"
                   placeholder="admin@hotel.test"
                   autocomplete="email"
                   required>
        </div>

        <div class="form-group" style="margin-bottom:1.5rem;">
            <label for="password">Password</label>
            <input type="password"
                   id="password"
                   name="password"
                   placeholder="••••••••"
                   autocomplete="current-password"
                   required>
        </div>

        <button type="submit" class="btn btn-primary btn-full">
            Accedi
        </button>

    </form>

    <p style="text-align:center; margin-top:1.5rem;">
        <a href="<?= url('/') ?>" style="color:#94a3b8; font-size:.85rem;">
            ← Torna al sito
        </a>
    </p>

</div>