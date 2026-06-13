<?php declare(strict_types=1); ?>

<?php
    $isEdit   = $room !== null;
    $action   = $isEdit
        ? url('/admin/rooms/' . $room['id'] . '/update')
        : url('/admin/rooms/store');
    $oldOrRoom = fn(string $key) => $old[$key] ?? ($room[$key] ?? '');
?>

<div class="admin-header">
    <div style="display:flex; align-items:center; gap:1rem;">
        <a href="<?= url('/admin/rooms') ?>"
           style="color:#64748b; font-size:.9rem;">← Torna alle camere</a>
    </div>
    <h1><?= $isEdit ? 'Modifica camera' : 'Nuova camera' ?></h1>
</div>

<div style="background:#fff; border-radius:12px; padding:2rem;
            box-shadow:0 2px 8px rgba(0,0,0,.06); max-width:760px;">

    <form method="POST"
          action="<?= e($action) ?>"
          enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Nome -->
        <div class="form-group" style="margin-bottom:1.25rem;">
            <label for="name">Nome camera *</label>
            <input type="text"
                   id="name"
                   name="name"
                   value="<?= e($oldOrRoom('name')) ?>"
                   placeholder="es. Suite Junior"
                   style="<?= !empty($errors['name']) ? 'border-color:#dc2626;' : '' ?>">
            <?php if (!empty($errors['name'])): ?>
                <span style="color:#dc2626; font-size:.82rem;"><?= e($errors['name']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Descrizione -->
        <div class="form-group" style="margin-bottom:1.25rem;">
            <label for="description">Descrizione *</label>
            <textarea id="description"
                      name="description"
                      rows="4"
                      placeholder="Descrivi la camera, la vista, l'atmosfera…"
                      style="<?= !empty($errors['description']) ? 'border-color:#dc2626;' : '' ?>"><?= e($oldOrRoom('description')) ?></textarea>
            <?php if (!empty($errors['description'])): ?>
                <span style="color:#dc2626; font-size:.82rem;"><?= e($errors['description']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Prezzo, capacità, m² -->
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr;
                    gap:1rem; margin-bottom:1.25rem;">

            <div class="form-group">
                <label for="price_per_night">Prezzo / notte (€) *</label>
                <input type="number"
                       id="price_per_night"
                       name="price_per_night"
                       value="<?= e($oldOrRoom('price_per_night')) ?>"
                       min="1" step="0.01"
                       placeholder="es. 120.00"
                       style="<?= !empty($errors['price_per_night']) ? 'border-color:#dc2626;' : '' ?>">
                <?php if (!empty($errors['price_per_night'])): ?>
                    <span style="color:#dc2626; font-size:.82rem;"><?= e($errors['price_per_night']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="capacity">Capacità (ospiti) *</label>
                <input type="number"
                       id="capacity"
                       name="capacity"
                       value="<?= e($oldOrRoom('capacity')) ?>"
                       min="1" max="20" step="1"
                       placeholder="es. 2"
                       style="<?= !empty($errors['capacity']) ? 'border-color:#dc2626;' : '' ?>">
                <?php if (!empty($errors['capacity'])): ?>
                    <span style="color:#dc2626; font-size:.82rem;"><?= e($errors['capacity']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="size_sqm">Metratura (m²) *</label>
                <input type="number"
                       id="size_sqm"
                       name="size_sqm"
                       value="<?= e($oldOrRoom('size_sqm')) ?>"
                       min="1" step="0.1"
                       placeholder="es. 28.5"
                       style="<?= !empty($errors['size_sqm']) ? 'border-color:#dc2626;' : '' ?>">
                <?php if (!empty($errors['size_sqm'])): ?>
                    <span style="color:#dc2626; font-size:.82rem;"><?= e($errors['size_sqm']) ?></span>
                <?php endif; ?>
            </div>

        </div>

        <!-- Servizi -->
        <div class="form-group" style="margin-bottom:1.25rem;">
            <label for="amenities">Servizi</label>
            <input type="text"
                   id="amenities"
                   name="amenities"
                   value="<?= e($oldOrRoom('amenities')) ?>"
                   placeholder="WiFi, TV, Aria condizionata, Balcone">
            <span style="font-size:.78rem; color:#94a3b8; margin-top:.25rem; display:block;">
                Separati da virgola. Es: WiFi, TV, Minibar
            </span>
        </div>

        <!-- Immagine -->
        <div class="form-group" style="margin-bottom:1.25rem;">
            <label for="image">Foto camera</label>

            <?php if ($isEdit && !empty($room['image'])): ?>
                <div style="margin-bottom:.75rem;">
                    <img src="<?= url('assets/img/rooms/' . e($room['image'])) ?>"
                         alt="Foto attuale"
                         style="height:100px; border-radius:8px; object-fit:cover;">
                    <p style="font-size:.78rem; color:#64748b; margin-top:.3rem;">
                        Foto attuale — carica una nuova per sostituirla
                    </p>
                </div>
            <?php endif; ?>

            <input type="file"
                   id="image"
                   name="image"
                   accept="image/jpeg,image/png,image/webp"
                   style="<?= !empty($errors['image']) ? 'border-color:#dc2626;' : '' ?>">
            <span style="font-size:.78rem; color:#94a3b8; margin-top:.25rem; display:block;">
                JPG, PNG o WebP — max 2 MB
            </span>
            <?php if (!empty($errors['image'])): ?>
                <span style="color:#dc2626; font-size:.82rem;"><?= e($errors['image']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Stato attiva -->
        <div style="margin-bottom:1.75rem;">
            <label style="display:flex; align-items:center; gap:.6rem; cursor:pointer;">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       <?= ($oldOrRoom('is_active') ?? 1) ? 'checked' : '' ?>
                       style="width:1rem; height:1rem;">
                <span>Camera attiva (visibile al pubblico)</span>
            </label>
        </div>

        <!-- Azioni -->
        <div style="display:flex; gap:1rem;">
            <button type="submit" class="btn btn-primary">
                <?= $isEdit ? 'Salva modifiche' : 'Crea camera' ?>
            </button>
            <a href="<?= url('/admin/rooms') ?>" class="btn btn-secondary">
                Annulla
            </a>
        </div>

    </form>
</div>