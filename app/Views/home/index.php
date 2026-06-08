<?php declare(strict_types=1); ?>

<section class="hero">
    <div class="container">
        <h1>Trova la tua camera ideale</h1>
        <p>Scegli le date e scopri le camere disponibili</p>

        <form class="search-form" method="GET" action="<?= url('/') ?>">
            <div class="form-group">
                <label for="check_in">Check-in</label>
                <input
                    type="date"
                    id="check_in"
                    name="check_in"
                    value="<?= e($searchParams['checkIn'] ?? '') ?>"
                    min="<?= date('Y-m-d') ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="check_out">Check-out</label>
                <input
                    type="date"
                    id="check_out"
                    name="check_out"
                    value="<?= e($searchParams['checkOut'] ?? '') ?>"
                    min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                    required>
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
        </form>
    </div>
</section>

<?php if ($searchPerformed): ?>
    <section style="margin-top: 2rem;">

        <?php if ($error ?? null): ?>
            <div class="alert alert-error"><?= e($error) ?></div>

        <?php elseif (empty($rooms)): ?>
            <div class="alert alert-warning">
                Nessuna camera disponibile per le date selezionate. Prova con date diverse.
            </div>

        <?php else: ?>
            <h2 style="margin-bottom: 1rem;">
                <?= count($rooms) ?> camera<?= count($rooms) > 1 ? 'e' : '' ?> disponibil<?= count($rooms) > 1 ? 'i' : 'e' ?>
            </h2>

            <div class="rooms-grid">
                <?php foreach ($rooms as $room): ?>
                    <div class="room-card">

                        <?php if (!empty($room['image'])): ?>
                            <img
                                class="room-card-img"
                                src="<?= url('assets/img/rooms/' . e($room['image'])) ?>"
                                alt="<?= e($room['name']) ?>">
                        <?php else: ?>
                            <div class="room-card-img-placeholder">🛏</div>
                        <?php endif; ?>

                        <div class="room-card-body">
                            <h3><?= e($room['name']) ?></h3>
                            <div class="room-card-meta">
                                <span>👥 <?= e($room['capacity']) ?> ospiti</span>
                                <span>📐 <?= e($room['size_sqm']) ?> m²</span>
                            </div>
                            <div class="room-card-price">
                                <?= format_price((float)$room['price_per_night']) ?>
                                <span>/ notte</span>
                            </div>
                            <a href="<?= url('/rooms/' . $room['id'] . '?' . http_build_query($searchParams)) ?>"
                               class="btn btn-primary btn-full">
                                Vedi dettagli
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </section>
<?php endif; ?>