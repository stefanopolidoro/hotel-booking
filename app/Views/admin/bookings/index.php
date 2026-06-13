<?php declare(strict_types=1); ?>

<div class="admin-header">
    <h1>Prenotazioni</h1>
    <p style="color:#64748b; margin-top:.25rem;">
        <?= count($bookings) ?> prenotazion<?= count($bookings) !== 1 ? 'i' : 'e' ?>
        <?= $status !== '' ? 'con stato <strong>' . e(ucfirst($status)) . '</strong>' : 'totali' ?>
        <?= $search !== '' ? '— ricerca: <strong>' . e($search) . '</strong>' : '' ?>
    </p>
</div>

<!-- Filtri -->
<div style="background:#fff; border-radius:10px; padding:1.1rem 1.25rem;
            box-shadow:0 2px 8px rgba(0,0,0,.06); margin-bottom:1.5rem;">
    <form method="GET" action="<?= url('/admin/bookings') ?>"
          style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">

        <div class="form-group" style="flex:1; min-width:180px;">
            <label for="search">Cerca</label>
            <input type="text"
                   id="search"
                   name="search"
                   value="<?= e($search) ?>"
                   placeholder="Nome, email o token…">
        </div>

        <div class="form-group" style="min-width:160px;">
            <label for="status">Stato</label>
            <select id="status" name="status">
                <option value="">Tutti gli stati</option>
                <option value="pending"   <?= $status === 'pending'   ? 'selected' : '' ?>>In attesa</option>
                <option value="confirmed" <?= $status === 'confirmed' ? 'selected' : '' ?>>Confermata</option>
                <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>Cancellata</option>
            </select>
        </div>

        <div style="display:flex; gap:.5rem; padding-bottom:.05rem;">
            <button type="submit" class="btn btn-primary btn-sm">Filtra</button>
            <?php if ($status !== '' || $search !== ''): ?>
                <a href="<?= url('/admin/bookings') ?>" class="btn btn-secondary btn-sm">
                    Azzera
                </a>
            <?php endif; ?>
        </div>

    </form>
</div>

<!-- Tabella -->
<?php if (empty($bookings)): ?>
    <div class="alert alert-info">
        Nessuna prenotazione trovata con i filtri selezionati.
    </div>
<?php else: ?>
    <div style="background:#fff; border-radius:12px;
                box-shadow:0 2px 8px rgba(0,0,0,.06); overflow:hidden;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ospite</th>
                        <th>Camera</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Notti</th>
                        <th>Totale</th>
                        <th>Stato</th>
                        <th>Ricevuta</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                        <?php $nights = nights_between($b['check_in'], $b['check_out']); ?>
                        <tr>
                            <td style="color:#94a3b8; font-size:.85rem;">
                                #<?= (int) $b['id'] ?>
                            </td>

                            <td>
                                <strong>
                                    <?= e($b['first_name']) ?> <?= e($b['last_name']) ?>
                                </strong>
                                <br>
                                <span style="font-size:.82rem; color:#64748b;">
                                    <?= e($b['email']) ?>
                                </span>
                            </td>

                            <td><?= e($b['room_name']) ?></td>

                            <td><?= e(format_date($b['check_in'])) ?></td>

                            <td><?= e(format_date($b['check_out'])) ?></td>

                            <td style="text-align:center;">
                                <?= $nights ?>
                            </td>

                            <td><?= format_price((float) $b['total_price']) ?></td>

                            <td>
                                <span class="badge badge-<?= e($b['status']) ?>">
                                    <?= e(ucfirst($b['status'])) ?>
                                </span>
                            </td>

                            <td style="font-size:.82rem; color:#94a3b8;">
                                <?= e(format_date(substr($b['created_at'], 0, 10))) ?>
                            </td>

                            <td>
                                <a href="<?= url('/admin/bookings/' . $b['id']) ?>"
                                   class="btn btn-secondary btn-sm">
                                    Dettaglio
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>