<?php declare(strict_types=1); ?>

<div class="admin-header">
    <h1>Dashboard</h1>
    <p style="color:#64748b; margin-top:.25rem;">
        Benvenuto, <strong><?= e($_SESSION['admin_email'] ?? 'Admin') ?></strong> —
        <?= date('l d F Y') ?>
    </p>
</div>

<!-- Statistiche prenotazioni -->
<div class="stats-grid">

    <div class="stat-card">
        <div class="label">Prenotazioni totali</div>
        <div class="value"><?= (int) ($stats['total'] ?? 0) ?></div>
    </div>

    <div class="stat-card">
        <div class="label">Questo mese</div>
        <div class="value"><?= (int) ($stats['this_month'] ?? 0) ?></div>
    </div>

    <div class="stat-card">
        <div class="label">In attesa</div>
        <div class="value" style="color:#d97706;"><?= (int) ($stats['pending'] ?? 0) ?></div>
    </div>

    <div class="stat-card">
        <div class="label">Confermate</div>
        <div class="value" style="color:#16a34a;"><?= (int) ($stats['confirmed'] ?? 0) ?></div>
    </div>

    <div class="stat-card">
        <div class="label">Cancellate</div>
        <div class="value" style="color:#dc2626;"><?= (int) ($stats['cancelled'] ?? 0) ?></div>
    </div>

    <div class="stat-card">
        <div class="label">Fatturato mese</div>
        <div class="value" style="font-size:1.4rem;">
            <?= format_price((float) ($stats['monthly_revenue'] ?? 0)) ?>
        </div>
    </div>

</div>

<!-- Statistiche camere -->
<div class="stats-grid" style="margin-bottom:2.5rem;">

    <div class="stat-card">
        <div class="label">Camere totali</div>
        <div class="value"><?= (int) $totalRooms ?></div>
    </div>

    <div class="stat-card">
        <div class="label">Camere attive</div>
        <div class="value" style="color:#16a34a;"><?= (int) $activeRooms ?></div>
    </div>

    <div class="stat-card">
        <div class="label">Camere inattive</div>
        <div class="value" style="color:#dc2626;">
            <?= (int) ($totalRooms - $activeRooms) ?>
        </div>
    </div>

</div>

<!-- Ultime prenotazioni -->
<div style="background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.06); overflow:hidden;">

    <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;
                display:flex; justify-content:space-between; align-items:center;">
        <h2 style="font-size:1.05rem;">Ultime prenotazioni</h2>
        <a href="<?= url('/admin/bookings') ?>" class="btn btn-secondary btn-sm">
            Vedi tutte
        </a>
    </div>

    <?php if (empty($latestBookings)): ?>
        <div style="padding:2rem; text-align:center; color:#94a3b8;">
            Nessuna prenotazione ancora.
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ospite</th>
                        <th>Camera</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Totale</th>
                        <th>Stato</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($latestBookings, 0, 8) as $b): ?>
                        <tr>
                            <td style="color:#94a3b8; font-size:.85rem;">
                                #<?= (int) $b['id'] ?>
                            </td>
                            <td>
                                <strong><?= e($b['first_name']) ?> <?= e($b['last_name']) ?></strong>
                                <br>
                                <span style="font-size:.82rem; color:#64748b;"><?= e($b['email']) ?></span>
                            </td>
                            <td><?= e($b['room_name']) ?></td>
                            <td><?= e(format_date($b['check_in'])) ?></td>
                            <td><?= e(format_date($b['check_out'])) ?></td>
                            <td><?= format_price((float) $b['total_price']) ?></td>
                            <td>
                                <span class="badge badge-<?= e($b['status']) ?>">
                                    <?= e(ucfirst($b['status'])) ?>
                                </span>
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
    <?php endif; ?>

</div>