<?php $pageTitle = 'Nový ticket'; ?>
<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="container">
    <div class="form-card" style="max-width: 700px;">
        <h2>Vytvořit nový ticket</h2>
        <form method="POST" action="/tickets/create">
            <?= CSRF::getTokenField() ?>
            <div class="form-group">
                <label>Předmět</label>
                <input type="text" name="subject" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kategorie</label>
                <select name="category" class="form-control" required>
                    <option value="technical">Technický problém</option>
                    <option value="ban_unban">Ban/Unban</option>
                    <option value="bug_report">Nahlášení bugu</option>
                    <option value="other">Jiné</option>
                </select>
            </div>
            <div class="form-group">
                <label>Popis problému</label>
                <textarea name="message" class="form-control" rows="6" required></textarea>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn-primary">Vytvořit ticket</button>
                <a href="/tickets" class="btn-secondary">Zrušit</a>
            </div>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>