<?php declare(strict_types=1); ?>

<div style="margin-bottom:1rem;">
    <a href="<?= url('/rooms') ?>" style="color:#64748b; font-size:.9rem;">
        ← Torna alle camere
    </a>
</div>

<div style="display:grid; grid-template-columns:1fr 380px; gap:2.5rem; align-items:start;">

    <!-- Colonna sinistra: foto + dettagli -->
    <div>
        <?php if (!empty($room['image'])): ?>
            <img src="<?= url('assets/img/rooms/' . e($room['image'])) ?>"
                 alt="<?= e($room['name']) ?>"
                 style="width:100%; height:380px; object-fit:cover; border-radius:12px; margin-bottom:1.75rem;">
        <?php else: ?>
            <div class="room-card-img-placeholder"
                 style="height:380px; border-radius:12px; margin-bottom:1.75rem; font-size:4rem;">
                🛏
            </div>
        <?php endif; ?>

        <h1 style="font-size:1.9rem; margin-bottom:.5rem;"><?= e($room['name']) ?></h1>

        <div style="display:flex; gap:1.5rem; color:#64748b; font-size:.92rem; margin-bottom:1.25rem;">
            <span>👥 Fino a <?= e($room['capacity']) ?> ospiti</span>
            <span>📐 <?= e($room['size_sqm']) ?> m²</span>
            <span>💶 <?= format_price((float) $room['price_per_night']) ?> / notte</span>
        </div>

        <p style="color:#334155; line-height:1.75; margin-bottom:1.75rem;">
            <?= e($room['description']) ?>
        </p>

        <?php if (!empty($amenities)): ?>
            <h2 style="font-size:1.1rem; margin-bottom:.85rem;">Servizi inclusi</h2>
            <ul style="display:flex; flex-wrap:wrap; gap:.5rem; list-style:none; margin-bottom:1.75rem;">
                <?php foreach ($amenities as $amenity): ?>
                    <li style="background:#eff6ff; color:#1d4ed8; padding:.3rem .85rem;
                                border-radius:999px; font-size:.85rem; font-weight:500;">
                        <?= e($amenity) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- Colonna destra: box prenotazione -->
    <div style="background:#fff; border-radius:14px; padding:1.75rem;
                box-shadow:0 4px 24px rgba(0,0,0,.10); position:sticky; top:90px;">

        <div style="font-size:1.5rem; font-weight:700; color:#2563eb; margin-bottom:1.5rem;">
            <?= format_price((float) $room['price_per_night']) ?>
            <span style="font-size:.95rem; font-weight:400; color:#64748b;">/ notte</span>
        </div>

        <form method="GET" action="<?= url('/booking/create') ?>">
            <input type="hidden" name="room_id" value="<?= e($room['id']) ?>">

            <div class="form-group" style="margin-bottom:1rem;">
                <label for="check_in">Check-in</label>
                <input type="date" id="check_in" name="check_in"
                       value="<?= e($checkIn) ?>"
                       min="<?= date('Y-m-d') ?>"
                       required>
            </div>

            <div class="form-group" style="margin-bottom:1rem;">
                <label for="check_out">Check-out</label>
                <input type="date" id="check_out" name="check_out"
                       value="<?= e($checkOut) ?>"
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                       required>
            </div>

            <div class="form-group" style="margin-bottom:1.5rem;">
                <label for="guests">Ospiti</label>
                <select id="guests" name="guests">
                    <?php for ($i = 1; $i <= (int)$room['capacity']; $i++): ?>
                        <option value="<?= $i ?>" <?= $guests == $i ? 'selected' : '' ?>>
                            <?= $i ?> <?= $i === 1 ? 'ospite' : 'ospiti' ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Riepilogo totale calcolato in JS -->
            <div id="price-summary" style="background:#f8fafc; border-radius:8px;
                 padding:1rem; margin-bottom:1.25rem; display:none;">
                <div style="display:flex; justify-content:space-between; font-size:.9rem; color:#475569; margin-bottom:.4rem;">
                    <span id="summary-nights"></span>
                    <span id="summary-subtotal"></span>
                </div>
                <div style="display:flex; justify-content:space-between; font-weight:700; font-size:1.05rem;">
                    <span>Totale</span>
                    <span id="summary-total" style="color:#2563eb;"></span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                Prenota ora
            </button>
        </form>

    </div>
</div>

<script>
(function () {
    // Prezzo per notte passato dal PHP come numero puro
    const pricePerNight = <?= (float) $room['price_per_night'] ?>;

    const checkInEl  = document.getElementById('check_in');
    const checkOutEl = document.getElementById('check_out');
    const summary    = document.getElementById('price-summary');
    const nightsEl   = document.getElementById('summary-nights');
    const subtotalEl = document.getElementById('summary-subtotal');
    const totalEl    = document.getElementById('summary-total');

    function formatPrice(amount) {
        return '€ ' + amount.toFixed(2).replace('.', ',');
    }

    function updateSummary() {
        const checkIn  = new Date(checkInEl.value);
        const checkOut = new Date(checkOutEl.value);

        if (!checkInEl.value || !checkOutEl.value || checkOut <= checkIn) {
            summary.style.display = 'none';
            return;
        }

        // Calcolo notti: differenza in millisecondi → giorni
        const nights = Math.round((checkOut - checkIn) / (1000 * 60 * 60 * 24));
        const total  = pricePerNight * nights;

        nightsEl.textContent   = nights + (nights === 1 ? ' notte' : ' notti');
        subtotalEl.textContent = formatPrice(pricePerNight) + ' × ' + nights;
        totalEl.textContent    = formatPrice(total);
        summary.style.display  = 'block';
    }

    checkInEl.addEventListener('change', updateSummary);
    checkOutEl.addEventListener('change', updateSummary);

    // Calcola subito se le date arrivano pre-compilate dalla ricerca
    updateSummary();
})();
</script>