<?php $pageTitle = 'Dashboard'; ?>
<?php require __DIR__ . '/../../layout/admin_header.php'; ?>
<div class="container">
    <h1 class="section-title">Admin Dashboard</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?= $stats['users'] ?></div>
            <div class="stat-label">Celkem uživatelů</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['open_tickets'] ?></div>
            <div class="stat-label">Otevřené tickety</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['published_news'] ?></div>
            <div class="stat-label">Publikované novinky</div>
        </div>
    </div>

    <?php if (isset($stats['my_tickets']) && !empty($stats['my_tickets'])): ?>
        <div class="admin-card">
            <h2>Mé přiřazené tickety</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Předmět</th>
                            <th>Uživatel</th>
                            <th>Stav</th>
                            <th>Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['my_tickets'] as $ticket): ?>
                            <tr>
                                <td>#<?= $ticket['id'] ?></td>
                                <td><?= e($ticket['subject']) ?></td>
                                <td><?= e($ticket['user_username']) ?></td>
                                <td><span class="badge <?= getTicketStatusClass($ticket['status']) ?>"><?= getTicketStatusName($ticket['status']) ?></span></td>
                                <td><a href="/admin/tickets/<?= $ticket['id'] ?>" class="btn-secondary btn-small">Detail</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="admin-card">
        <h2>Rychlé odkazy</h2>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <?php if (Auth::isAdmin()): ?>
                <a href="/admin/news/create" class="btn-primary">Přidat novinku</a>
                <a href="/admin/users" class="btn-secondary">Správa uživatelů</a>
            <?php endif; ?>
            <a href="/admin/tickets" class="btn-secondary">Všechny tickety</a>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
