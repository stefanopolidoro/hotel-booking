<?php declare(strict_types=1); ?>

<div style="max-width:620px; margin:0 auto; text-align:center; padding: 2rem 0 1rem;">

    <div style="font-size:4rem; margin-bottom:1rem;">✅</div>

    <h1 style="font-size:1.9rem; margin-bottom:.5rem;">Prenotazione ricevuta!</h1>
    <p style="color:#64748b; font-size:1.05rem; margin-bottom:2rem;">
        Grazie <strong><?= e($booking['first_name']) ?></strong>, abbiamo ricevuto la tua richiesta.
        Riceverai una conferma all'indirizzo <strong><?= e($booking['email']) ?></strong>.
    </p>

</div>

<div style="max-width:620px; margin:0 auto;">

    <!-- Token prenotazione -->
    <div style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:10px;
                padding:1.1rem 1.5rem; margin-bottom:1.75rem; text-align:center;">
        <p style="font-size:.82rem; color:#1d4ed8; text-transform:uppercase;
                  letter-spacing:.06em; margin-bottom:.3rem;">
            Codice prenotazione
        </p>
        <p style="font-family:monospace; font-size:1.15rem; font-weight:700;
                  color:#1e3a5f; letter-spacing:.08em;">
            <?= e(strtoupper(substr($booking['token'], 0, 8))) ?>
        </p>
        <p style="font-size:.78rem; color:#64748b; margin-top:.3rem;">
            Conserva questo codice per qualsiasi comunicazione con l'hotel.
        </p>
    </div>

    <!-- Riepilogo prenotazione -->
    <div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.08);
                overflow:hidden; margin-bottom:1.75rem;">

        <div style="background:#1a1a2e; color:#fff; padding:1rem 1.5rem; font-weight:600;">
            Dettagli del soggiorno
        </div>

        <div style="padding:1.5rem;">

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.5rem;">

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.2rem;">Camera</p>
                    <p style="font-weight:600;"><?= e($booking['room_name']) ?></p>
                </div>

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.2rem;">Stato</p>
                    <span class="badge badge-<?= e($booking['status']) ?>">
                        <?= e(ucfirst($booking['status'])) ?>
                    </span>
                </div>

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.2rem;">Check-in</p>
                    <p style="font-weight:600;"><?= e(format_date($booking['check_in'])) ?></p>
                </div>

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.2rem;">Check-out</p>
                    <p style="font-weight:600;"><?= e(format_date($booking['check_out'])) ?></p>
                </div>

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.2rem;">Ospiti</p>
                    <p style="font-weight:600;"><?= e($booking['guests']) ?></p>
                </div>

                <div>
                    <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                              letter-spacing:.05em; margin-bottom:.2rem;">Durata</p>
                    <p style="font-weight:600;">
                        <?= $nights ?> nott<?= $nights === 1 ? 'e' : 'i' ?>
                    </p>
                </div>

            </div>

            <div style="border-top:1px solid #f1f5f9; padding-top:1.25rem;
                        display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:.9rem; color:#64748b;">Totale pagato</span>
                <span style="font-size:1.3rem; font-weight:700; color:#2563eb;">
                    <?= format_price((float) $booking['total_price']) ?>
                </span>
            </div>

        </div>
    </div>

    <!-- Dati ospite -->
    <div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.08);
                overflow:hidden; margin-bottom:2rem;">

        <div style="background:#1a1a2e; color:#fff; padding:1rem 1.5rem; font-weight:600;">
            Dati ospite
        </div>

        <div style="padding:1.5rem; display:grid; grid-template-columns:1fr 1fr; gap:1rem;">

            <div>
                <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                          letter-spacing:.05em; margin-bottom:.2rem;">Nome</p>
                <p style="font-weight:600;">
                    <?= e($booking['first_name']) ?> <?= e($booking['last_name']) ?>
                </p>
            </div>

            <div>
                <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                          letter-spacing:.05em; margin-bottom:.2rem;">Email</p>
                <p style="font-weight:600;"><?= e($booking['email']) ?></p>
            </div>

            <?php if (!empty($booking['phone'])): ?>
            <div>
                <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                          letter-spacing:.05em; margin-bottom:.2rem;">Telefono</p>
                <p style="font-weight:600;"><?= e($booking['phone']) ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($booking['notes'])): ?>
            <div style="grid-column:1 / -1;">
                <p style="font-size:.78rem; color:#94a3b8; text-transform:uppercase;
                          letter-spacing:.05em; margin-bottom:.2rem;">Note</p>
                <p style="color:#475569;"><?= e($booking['notes']) ?></p>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <div style="text-align:center;">
        <a href="<?= url('/') ?>" class="btn btn-primary">Torna alla home</a>
    </div>

</div>