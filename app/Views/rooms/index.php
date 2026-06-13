<?php declare(strict_types=1); ?>

<div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.75rem;">
    <div>
        <h1 style="font-size:1.75rem; margin-bottom:.25rem;">Le nostre camere</h1>
        <p style="color:#64748b;">
            <?php if ($searchPerformed && empty($error)): ?>
                <?= count($rooms) ?> camera<?= count($rooms) !== 1 ? 'e' : '' ?>
                disponibil<?= count($rooms) !== 1 ? 'i' : 'e' ?>
                dal <strong><?= e(format_date($searchParams['checkIn'])) ?></strong>
                al <strong><?= e(format_date($searchParams['checkOut'])) ?></strong>
                per <strong><?= e($searchParams['guests']) ?></strong>
                ospite<?= $searchParams['guests'] !== 1 ? 'i' : '' ?>
            <?php else: ?>
                Scegli la camera perfetta per il tuo soggiorno
            <?php endif; ?>
        </p>
    </div>
</div>

<form class="search-form" method="GET" action="<?= url('/rooms') ?>"
      style="margin-bottom:2rem; padding:1rem 1.25rem;">
    <div class="form-group">
        <label for="check_in">Check-in</label>
        <input type="date" id="check_in" name="check_in"
               value="<?= e($searchParams['checkIn'] ?? '') ?>"
               min="<?= date('Y-m-d') ?>">
    </div>
    <div class="form-group">
        <label for="check_out">Check-out</label>
        <input type="date" id="check_out" name="check_out"
               value="<?= e($searchParams['checkOut'] ?? '') ?>"
               min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
    </div>
    <div class="form-group">
        <label for="guests">Ospiti</label>
        <select id="guests" name="guests">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <option value="<?= $i ?>"
                    <?= isset($searchParams['guests']) && $searchParams['guests'] == $i ? 'selected' : '' ?>>
                    <?= $i ?> <?= $i === 1 ? 'ospite' : 'ospiti' ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="form-group" style="flex:0">
        <label>&nbsp;</label>
        <button type="submit" class="btn btn-primary">Cerca</button>
    </div>
    <?php if ($searchPerformed): ?>
        <div class="form-group" style="flex:0">
            <label>&nbsp;</label>
            <a href="<?= url('/rooms') ?>" class="btn btn-secondary">Tutte le camere</a>
        </div>
    <?php endif; ?>
</form>

<?php if ($error): ?>
    <div class="alert alert-error"><?= e($error) ?></div>

<?php elseif (empty($rooms)): ?>
    <div class="alert alert-warning">
        Nessuna camera disponibile per le date selezionate.
        <a href="<?= url('/rooms') ?>">Mostra tutte le camere</a>
    </div>

<?php else: ?>
    <div class="rooms-grid">
        <?php foreach ($rooms as $room): ?>
            <div class="room-card">

                <?php if (!empty($room['image'])): ?>
                    <img class="room-card-img"
                         src="<?= url('assets/img/rooms/' . e($room['image'])) ?>"
                         alt="<?= e($room['name']) ?>">
                <?php else: ?>
                    <div class="room-card-img-placeholder">🛏</div>
                <?php endif; ?>

                <div class="room-card-body">
                    <h3><?= e($room['name']) ?></h3>

                    <div class="room-card-meta">
                        <span>👥 max <?= e($room['capacity']) ?> ospiti</span>
                        <span>📐 <?= e($room['size_sqm']) ?> m²</span>
                    </div>

                    <p style="font-size:.88rem; color:#475569; margin-bottom:.85rem; line-height:1.5;">
                        <?= e(mb_substr($room['description'], 0, 90)) ?>…
                    </p>

                    <div class="room-card-price">
                        <?= format_price((float) $room['price_per_night']) ?>
                        <span>/ notte</span>
                    </div>

                    <?php
                        $query = !empty($searchParams) ? '?' . http_build_query([
                            'check_in'  => $searchParams['checkIn'],
                            'check_out' => $searchParams['checkOut'],
                            'guests'    => $searchParams['guests'],
                        ]) : '';
                    ?>
                    <a href="<?= url('/rooms/' . $room['id'] . $query) ?>"
                       class="btn btn-primary btn-full">
                        Vedi dettagli
                    </a>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>