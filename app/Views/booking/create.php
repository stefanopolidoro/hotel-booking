<?php declare(strict_types=1); ?>

<div style="margin-bottom:1rem;">
    <a href="<?= url('/rooms/' . e($room['id'])) ?>" style="color:#64748b; font-size:.9rem;">
        ← Torna alla camera
    </a>
</div>

<?php if (!empty($errors['general'])): ?>
    <div class="alert alert-error"><?= e($errors['general']) ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns:1fr 360px; gap:2.5rem; align-items:start;">

    <!-- Colonna sinistra: form dati ospite -->
    <div>
        <h1 style="font-size:1.75rem; margin-bottom:.35rem;">Completa la prenotazione</h1>
        <p style="color:#64748b; margin-bottom:1.75rem;">
            Inserisci i tuoi dati per prenotare <strong><?= e($room['name']) ?></strong>
        </p>

        <form method="POST" action="<?= url('/booking/store') ?>">
            <?= csrf_field() ?>

            <input type="hidden" name="room_id"   value="<?= e($room['id']) ?>">
            <input type="hidden" name="check_in"  value="<?= e($checkIn) ?>">
            <input type="hidden" name="check_out" value="<?= e($checkOut) ?>">
            <input type="hidden" name="guests"    value="<?= e($guests) ?>">

            <fieldset style="border:none; padding:0; margin-bottom:1.75rem;">
                <legend style="font-size:1rem; font-weight:600; margin-bottom:1rem; color:#1a1a2e;">
                    Dati personali
                </legend>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">

                    <div class="form-group">
                        <label for="first_name">Nome *</label>
                        <input type="text" id="first_name" name="first_name"
                               value="<?= e($old['first_name'] ?? '') ?>"
                               placeholder="Mario"
                               style="<?= !empty($errors['first_name']) ? 'border-color:#dc2626;' : '' ?>">
                        <?php if (!empty($errors['first_name'])): ?>
                            <span style="color:#dc2626; font-size:.82rem;"><?= e($errors['first_name']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Cognome *</label>
                        <input type="text" id="last_name" name="last_name"
                               value="<?= e($old['last_name'] ?? '') ?>"
                               placeholder="Rossi"
                               style="<?= !empty($errors['last_name']) ? 'border-color:#dc2626;' : '' ?>">
                        <?php if (!empty($errors['last_name'])): ?>
                            <span style="color:#dc2626; font-size:.82rem;"><?= e($errors['last_name']) ?></span>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="form-group" style="margin-bottom:1rem;">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email"
                           value="<?= e($old['email'] ?? '') ?>"
                           placeholder="mario.rossi@email.com"
                           style="<?= !empty($errors['email']) ? 'border-color:#dc2626;' : '' ?>">
                    <?php if (!empty($errors['email'])): ?>
                        <span style="color:#dc2626; font-size:.82rem;"><?= e($errors['email']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group" style="margin-bottom:1rem;">
                    <label for="phone">Telefono <span style="color:#94a3b8;">(opzionale)</span></label>
                    <input type="tel" id="phone" name="phone"
                           value="<?= e($old['phone'] ?? '') ?>"
                           placeholder="+39 333 1234567"
                           style="<?= !empty($errors['phone']) ? 'border-color:#dc2626;' : '' ?>">
                    <?php if (!empty($errors['phone'])): ?>
                        <span style="color:#dc2626; font-size:.82rem;"><?= e($errors['phone']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="notes">Note <span style="color:#94a3b8;">(opzionale)</span></label>
                    <textarea id="notes" name="notes" rows="3"
                              placeholder="Richieste speciali, orario di arrivo previsto…"><?= e($old['notes'] ?? '') ?></textarea>
                </div>

            </fieldset>

            <button type="submit" class="btn btn-primary btn-full" style="font-size:1.05rem; padding:.8rem;">
                Conferma prenotazione
            </button>

            <p style="font-size:.8rem; color:#94a3b8; text-align:center; margin-top:.75rem;">
                Cliccando "Conferma" accetti le nostre condizioni di prenotazione.
            </p>

        </form>
    </div>

    <!-- Colonna destra: riepilogo prenotazione -->
    <div style="background:#fff; border-radius:14px; padding:1.75rem;
                box-shadow:0 4px 24px rgba(0,0,0,.10); position:sticky; top:90px;">

        <h2 style="font-size:1.05rem; margin-bottom:1.25rem; color:#1a1a2e;">
            Riepilogo prenotazione
        </h2>

        <?php if (!empty($room['image'])): ?>
            <img src="<?= url('assets/img/rooms/' . e($room['image'])) ?>"
                 alt="<?= e($room['name']) ?>"
                 style="width:100%; height:160px; object-fit:cover; border-radius:8px; margin-bottom:1rem;">
        <?php else: ?>
            <div class="room-card-img-placeholder"
                 style="height:160px; border-radius:8px; margin-bottom:1rem; font-size:2.5rem;">🛏</div>
        <?php endif; ?>

        <p style="font-weight:600; font-size:1rem; margin-bottom:1rem;"><?= e($room['name']) ?></p>

        <div style="border-top:1px solid #f1f5f9; padding-top:1rem; margin-bottom:1rem;">
            <div style="display:flex; justify-content:space-between; font-size:.9rem; margin-bottom:.5rem;">
                <span style="color:#64748b;">Check-in</span>
                <strong><?= e(format_date($checkIn)) ?></strong>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:.9rem; margin-bottom:.5rem;">
                <span style="color:#64748b;">Check-out</span>
                <strong><?= e(format_date($checkOut)) ?></strong>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:.9rem; margin-bottom:.5rem;">
                <span style="color:#64748b;">Ospiti</span>
                <strong><?= e($guests) ?></strong>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:.9rem;">
                <span style="color:#64748b;">Durata</span>
                <strong><?= $nights ?> nott<?= $nights === 1 ? 'e' : 'i' ?></strong>
            </div>
        </div>

        <div style="border-top:1px solid #f1f5f9; padding-top:1rem;">
            <div style="display:flex; justify-content:space-between; font-size:.9rem; margin-bottom:.4rem; color:#64748b;">
                <span><?= format_price((float) $room['price_per_night']) ?> × <?= $nights ?> nott<?= $nights === 1 ? 'e' : 'i' ?></span>
                <span><?= format_price($total) ?></span>
            </div>
            <div style="display:flex; justify-content:space-between; font-weight:700; font-size:1.1rem; color:#2563eb;">
                <span>Totale</span>
                <span><?= format_price($total) ?></span>
            </div>
        </div>

    </div>

</div>