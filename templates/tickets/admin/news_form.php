<?php 
$isEdit = isset($newsItem);
$pageTitle = $isEdit ? 'Upravit novinku' : 'Přidat novinku'; 
?>
<?php require __DIR__ . '/../../layout/admin_header.php'; ?>
<div class="container">
    <div class="admin-card" style="max-width: 800px; margin: 0 auto;">
        <h2><?= $pageTitle ?></h2>
        <form method="POST">
            <?= CSRF::getTokenField() ?>
            <div class="form-group">
                <label>Titulek</label>
                <input type="text" name="title" class="form-control" value="<?= e($newsItem['title'] ?? '') ?>" required>
            </div>
<div class="form-group">
    <label>URL obrázku (banner)</label>
    <input
        type="text"
        name="image_url"
        class="form-control"
        value="<?= e($newsItem['image_url'] ?? '') ?>"
        placeholder="/public/uploads/news/moje-novinka.jpg"
    >
</div>

            <div class="form-group">
                <label>Krátký popis (excerpt)</label>
                <textarea name="excerpt" class="form-control" rows="3" required><?= e($newsItem['excerpt'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label>Obsah (HTML)</label>
                <textarea name="content" class="form-control" rows="10" required><?= e($newsItem['content'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_published" <?= isset($newsItem) && $newsItem['is_published'] ? 'checked' : '' ?>>
                    Publikováno
                </label>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn-primary"><?= $isEdit ? 'Uložit změny' : 'Vytvořit novinku' ?></button>
                <a href="/admin/news" class="btn-secondary">Zrušit</a>
            </div>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
