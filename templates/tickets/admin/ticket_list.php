<?php $pageTitle = 'Správa ticketů'; ?>
<?php require __DIR__ . '/../../layout/admin_header.php'; ?>
<div class="container">
    <h1 class="section-title">Správa ticketů</h1>

    <div class="admin-card">
        <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <div class="form-group" style="margin: 0;">
                <select name="status" class="form-control">
                    <option value="">Všechny stavy</option>
                    <option value="open" <?= ($_GET['status'] ?? '') === 'open' ? 'selected' : '' ?>>Otevřené</option>
                    <option value="in_progress" <?= ($_GET['status'] ?? '') === 'in_progress' ? 'selected' : '' ?>>V řešení</option>
                    <option value="closed" <?= ($_GET['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Uzavřené</option>
                </select>
            </div>
            <div class="form-group" style="margin: 0;">
                <select name="assigned_to" class="form-control">
                    <option value="">Všichni</option>
                    <?php foreach ($staffMembers as $staff): ?>
                        <option value="<?= $staff['id'] ?>" <?= ($_GET['assigned_to'] ?? '') == $staff['id'] ? 'selected' : '' ?>>
                            <?= e($staff['username']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-secondary">Filtrovat</button>
        </form>
    </div>

    <?php if (empty($tickets)): ?>
        <p>Žádné tickety.</p>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Předmět</th>
                        <th>Uživatel</th>
                        <th>Kategorie</th>
                        <th>Stav</th>
                        <th>Přiřazeno</th>
                        <th>Vytvořeno</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td>#<?= $ticket['id'] ?></td>
                            <td><?= e(truncate($ticket['subject'], 40)) ?></td>
                            <td><?= e($ticket['user_username']) ?></td>
                            <td><?= getTicketCategoryName($ticket['category']) ?></td>
                            <td><span class="badge <?= getTicketStatusClass($ticket['status']) ?>"><?= getTicketStatusName($ticket['status']) ?></span></td>
                            <td><?= $ticket['assigned_username'] ? e($ticket['assigned_username']) : '-' ?></td>
                            <td><?= formatDate($ticket['created_at']) ?></td>
                            <td>
                                <a href="/admin/tickets/<?= $ticket['id'] ?>" class="btn-secondary btn-small">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>