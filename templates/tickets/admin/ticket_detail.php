<?php $pageTitle = 'Ticket #' . $ticket['id']; ?>
<?php require __DIR__ . '/../../layout/admin_header.php'; ?>
<div class="container">
    <div class="admin-card">
        <h1>Ticket #<?= $ticket['id'] ?>: <?= e($ticket['subject']) ?></h1>
        <p style="color: var(--text-gray); margin-bottom: 2rem;">
            Uživatel: <?= e($ticket['user_username']) ?> | 
            Kategorie: <?= getTicketCategoryName($ticket['category']) ?>
        </p>

        <div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
            <form method="POST" action="/admin/tickets/<?= $ticket['id'] ?>/status">
                <?= CSRF::getTokenField() ?>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <select name="status" class="form-control">
                        <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Otevřený</option>
                        <option value="in_progress" <?= $ticket['status'] === 'in_progress' ? 'selected' : '' ?>>V řešení</option>
                        <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>Uzavřený</option>
                    </select>
                    <button type="submit" class="btn-secondary btn-small">Změnit stav</button>
                </div>
            </form>

            <form method="POST" action="/admin/tickets/<?= $ticket['id'] ?>/assign">
                <?= CSRF::getTokenField() ?>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <select name="assigned_to" class="form-control">
                        <option value="">Nepřiřazeno</option>
                        <?php foreach ($staffMembers as $staff): ?>
                            <option value="<?= $staff['id'] ?>" <?= $ticket['assigned_to'] == $staff['id'] ? 'selected' : '' ?>>
                                <?= e($staff['username']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn-secondary btn-small">Přiřadit</button>
                </div>
            </form>
        </div>

        <div class="ticket-messages">
            <?php foreach ($messages as $message): ?>
                <div class="message <?= in_array($message['role'], ['helper', 'admin', 'owner']) ? 'staff-message' : '' ?>">
                    <div class="message-header">
                        <span class="message-author"><?= e($message['username']) ?> (<?= getRoleName($message['role']) ?>)</span>
                        <span><?= formatDate($message['created_at']) ?></span>
                    </div>
                    <div class="message-body"><?= nl2br(e($message['message'])) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top: 2rem;">
            <h3>Přidat odpověď</h3>
            <form method="POST" action="/admin/tickets/<?= $ticket['id'] ?>/reply">
                <?= CSRF::getTokenField() ?>
                <div class="form-group">
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn-primary">Odeslat odpověď</button>
                <a href="/admin/tickets" class="btn-secondary">← Zpět na seznam</a>
            </form>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>