<?php declare(strict_types=1); ?>

<div class="admin-header" style="display:flex; justify-content:space-between; align-items:center;">
    <div>
        <h1>Camere</h1>
        <p style="color:#64748b; margin-top:.25rem;">
            <?= count($rooms) ?> camera<?= count($rooms) !== 1 ? 'e' : '' ?> totali
        </p>
    </div>
    <a href="<?= url('/admin/rooms/create') ?>" class="btn btn-primary">
        + Nuova camera
    </a>
</div>

<?php if (empty($rooms)): ?>
    <div class="alert alert-info">
        Nessuna camera presente. <a href="<?= url('/admin/rooms/create') ?>">Crea la prima camera</a>.
    </div>
<?php else: ?>
    <div style="background:#fff; border-radius:12px;
                box-shadow:0 2px 8px rgba(0,0,0,.06); overflow:hidden;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Prezzo / notte</th>
                        <th>Capacità</th>
                        <th>m²</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $room): ?>
                        <tr>
                            <td style="color:#94a3b8; font-size:.85rem;">
                                #<?= (int) $room['id'] ?>
                            </td>

                            <td>
                                <?php if (!empty($room['image'])): ?>
                                    <img src="<?= url('assets/img/rooms/' . e($room['image'])) ?>"
                                         alt="<?= e($room['name']) ?>"
                                         style="width:56px; height:40px; object-fit:cover;
                                                border-radius:5px;">
                                <?php else: ?>
                                    <div style="width:56px; height:40px; background:#f1f5f9;
                                                border-radius:5px; display:flex; align-items:center;
                                                justify-content:center; font-size:1.2rem;">🛏</div>
                                <?php endif; ?>
                            </td>

                            <td>
                                <strong><?= e($room['name']) ?></strong>
                                <br>
                                <span style="font-size:.82rem; color:#64748b;">
                                    <?= e(mb_substr($room['description'], 0, 60)) ?>…
                                </span>
                            </td>

                            <td><?= format_price((float) $room['price_per_night']) ?></td>

                            <td>
                                <?= e($room['capacity']) ?>
                                ospite<?= (int)$room['capacity'] !== 1 ? 'i' : '' ?>
                            </td>

                            <td><?= e($room['size_sqm']) ?> m²</td>

                            <td>
                                <?php if ($room['is_active']): ?>
                                    <span class="badge badge-confirmed">Attiva</span>
                                <?php else: ?>
                                    <span class="badge badge-cancelled">Inattiva</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div style="display:flex; gap:.5rem;">
                                    <a href="<?= url('/admin/rooms/' . $room['id'] . '/edit') ?>"
                                       class="btn btn-secondary btn-sm">
                                        Modifica
                                    </a>

                                    <form method="POST"
                                          action="<?= url('/admin/rooms/' . $room['id'] . '/delete') ?>"
                                          onsubmit="return confirm('Eliminare la camera «<?= e($room['name']) ?>»? L\'operazione è irreversibile.')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Elimina
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>