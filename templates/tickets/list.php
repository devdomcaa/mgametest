<?php $pageTitle = 'Tickety'; ?>
<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 class="section-title" style="margin: 0;">Moje tickety</h1>
        <a href="/tickets/create" class="btn-primary">Vytvořit ticket</a>
    </div>
    
    <?php if (empty($tickets)): ?>
        <p>Zatím nemáte žádné tickety.</p>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Předmět</th>
                        <th>Kategorie</th>
                        <th>Stav</th>
                        <?php if (Auth::isStaff()): ?>
                            <th>Uživatel</th>
                            <th>Přiřazeno</th>
                        <?php endif; ?>
                        <th>Vytvořeno</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td>#<?= $ticket['id'] ?></td>
                            <td><?= e($ticket['subject']) ?></td>
                            <td><?= getTicketCategoryName($ticket['category']) ?></td>
                            <td><span class="badge <?= getTicketStatusClass($ticket['status']) ?>"><?= getTicketStatusName($ticket['status']) ?></span></td>
                            <?php if (Auth::isStaff()): ?>
                                <td><?= e($ticket['user_username']) ?></td>
                                <td><?= $ticket['assigned_username'] ? e($ticket['assigned_username']) : '-' ?></td>
                            <?php endif; ?>
                            <td><?= formatDate($ticket['created_at']) ?></td>
                            <td>
                                <a href="/tickets/<?= $ticket['id'] ?>" class="btn-secondary btn-small">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>

