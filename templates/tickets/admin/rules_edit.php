<?php $pageTitle = 'Úprava pravidel'; ?>
<?php require __DIR__ . '/../../layout/admin_header.php'; ?>
<div class="container">
    <div class="admin-card" style="max-width: 800px; margin: 0 auto;">
        <h2>Úprava pravidel serveru</h2>
        <form method="POST" action="/admin/rules">
            <?= CSRF::getTokenField() ?>
            <div class="form-group">
                <label>Obsah pravidel (HTML)</label>
                <textarea name="content" class="form-control" rows="20" required><?= e($rules['content'] ?? '') ?></textarea>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn-primary">Uložit změny</button>
                <a href="/admin" class="btn-secondary">Zrušit</a>
            </div>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>