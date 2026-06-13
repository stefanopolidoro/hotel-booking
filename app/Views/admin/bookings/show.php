<?php declare(strict_types=1); ?>

<div class="admin-header" style="display:flex; justify-content:space-between; align-items:flex-start;">
    <div>
        <a href="<?= url('/admin/bookings') ?>"
           style="color:#64748b; font-size:.9rem; display:block; margin-bottom:.5rem;">
            ← Torna alle prenotazioni
        </a>
        <h1>Prenotazione #<?= (int) $booking['id'] ?></h1>
        <p style="color:#64748b; margin-top:.25rem; font-size:.9rem;">
            Ricevuta il <?= e(format_date(substr($booking['created_at'], 0, 10))) ?>
            —
            Token:
            <code style="background:#f1f5f9; padding:.1rem .4rem;
                         border-radius:4px; font-size:.85rem;">
                <?= e(strtoupper(substr($booking['token'], 0, 8))) ?>
            </code>
        </p>
    </div>
    <span class="badge badge-<?= e($booking['status']) ?>"
          style="font-size:.9rem; padding:.4rem 1rem;">
        <?= e(ucfirst($booking['status'])) ?>
    </span>
</div>

<div style="display:grid; grid-template-columns:1fr 320px; gap:2rem; align-items:start;">

    <!-- Colonna sinistra: dati prenotazione e ospite -->
    <div style="display:flex; flex-direction:column; gap:1.5rem;">

        <!-- Dati soggiorno -->
        <div style="background:#fff; border-radius:12px;
                    box-shadow:0 2px 8px rgba(0,0,0,.06); overflow:hidden;">
            <div style="background:#1a1a2e; color:#fff;
                        padding:.9rem 1.5rem; font-weight:600;">
                Dettagli soggiorno
            </div>
            <div style="padding:1.5rem;
                        display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.25rem;">Camera</p>
                    <p style="font-weight:600;"><?= e($booking['room_name']) ?></p>
                </div>

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.25rem;">Ospiti</p>
                    <p style="font-weight:600;"><?= e($booking['guests']) ?></p>
                </div>

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.25rem;">Check-in</p>
                    <p style="font-weight:600;"><?= e(format_date($booking['check_in'])) ?></p>
                </div>

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.25rem;">Check-out</p>
                    <p style="font-weight:600;"><?= e(format_date($booking['check_out'])) ?></p>
                </div>

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.25rem;">Durata</p>
                    <p style="font-weight:600;">
                        <?= $nights ?> nott<?= $nights === 1 ? 'e' : 'i' ?>
                    </p>
                </div>

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.25rem;">Totale</p>
                    <p style="font-weight:700; color:#2563eb; font-size:1.1rem;">
                        <?= format_price((float) $booking['total_price']) ?>
                    </p>
                </div>

                <?php if (!empty($booking['notes'])): ?>
                    <div style="grid-column: 1 / -1;">
                        <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                                  letter-spacing:.05em; margin-bottom:.25rem;">Note ospite</p>
                        <p style="color:#475569; font-style:italic;">
                            "<?= e($booking['notes']) ?>"
                        </p>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- Dati ospite -->
        <div style="background:#fff; border-radius:12px;
                    box-shadow:0 2px 8px rgba(0,0,0,.06); overflow:hidden;">
            <div style="background:#1a1a2e; color:#fff;
                        padding:.9rem 1.5rem; font-weight:600;">
                Dati ospite
            </div>
            <div style="padding:1.5rem;
                        display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.25rem;">Nome e cognome</p>
                    <p style="font-weight:600;">
                        <?= e($booking['first_name']) ?> <?= e($booking['last_name']) ?>
                    </p>
                </div>

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.25rem;">Email</p>
                    <p style="font-weight:600;">
                        <a href="mailto:<?= e($booking['email']) ?>">
                            <?= e($booking['email']) ?>
                        </a>
                    </p>
                </div>

                <?php if (!empty($booking['phone'])): ?>
                    <div>
                        <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                                  letter-spacing:.05em; margin-bottom:.25rem;">Telefono</p>
                        <p style="font-weight:600;">
                            <a href="tel:<?= e($booking['phone']) ?>">
                                <?= e($booking['phone']) ?>
                            </a>
                        </p>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>

    <!-- Colonna destra: cambio stato -->
    <div style="background:#fff; border-radius:12px; padding:1.5rem;
                box-shadow:0 2px 8px rgba(0,0,0,.06); position:sticky; top:90px;">

        <h2 style="font-size:1rem; margin-bottom:1.25rem;">Aggiorna stato</h2>

        <form method="POST"
              action="<?= url('/admin/bookings/' . $booking['id'] . '/status') ?>">
            <?= csrf_field() ?>

            <div class="form-group" style="margin-bottom:1.25rem;">
                <label for="status">Nuovo stato</label>
                <select id="status" name="status">
                    <option value="pending"
                        <?= $booking['status'] === 'pending'   ? 'selected' : '' ?>>
                        In attesa
                    </option>
                    <option value="confirmed"
                        <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>
                        Confermata
                    </option>
                    <option value="cancelled"
                        <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>
                        Cancellata
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                Salva stato
            </button>

        </form>

        <div style="border-top:1px solid #f1f5f9; margin-top:1.5rem; padding-top:1.25rem;">
            <p style="font-size:.8rem; color:#94a3b8; margin-bottom:.5rem;">
                Link conferma ospite
            </p>
            <code style="display:block; font-size:.75rem; background:#f8fafc;
                         padding:.6rem; border-radius:6px; word-break:break-all;
                         color:#475569; border:1px solid #e2e8f0;">
                <?= e(url('/booking/confirm/' . $booking['token'])) ?>
            </code>
        </div>

    </div>

</div>