<?php $pageTitle = 'Ticket #' . $ticket['id']; ?>
<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="container">
    <div class="admin-card">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
            <div>
                <h1>Ticket #<?= $ticket['id'] ?>: <?= e($ticket['subject']) ?></h1>
                <p style="color: var(--text-gray);">
                    Kategorie: <?= getTicketCategoryName($ticket['category']) ?> | 
                    Stav: <span class="badge <?= getTicketStatusClass($ticket['status']) ?>"><?= getTicketStatusName($ticket['status']) ?></span>
                </p>
            </div>
            <a href="/tickets" class="btn-secondary">← Zpět</a>
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

        <?php if ($ticket['status'] !== 'closed'): ?>
            <div class="form-card" style="margin: 0; max-width: 100%;">
                <h3>Přidat odpověď</h3>
                <form method="POST" action="/tickets/<?= $ticket['id'] ?>/reply">
                    <?= CSRF::getTokenField() ?>
                    <div class="form-group">
                        <textarea name="message" class="form-control" rows="4" placeholder="Vaše odpověď..." required></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Odeslat odpověď</button>
                </form>
            </div>
        <?php else: ?>
            <p style="color: var(--text-gray); text-align: center;">Tento ticket je uzavřený.</p>
        <?php endif; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>