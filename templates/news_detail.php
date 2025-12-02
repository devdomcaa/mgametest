<?php $pageTitle = $newsItem['title']; ?>
<?php require __DIR__ . '/layout/header.php'; ?>

<div class="container">
    <article class="admin-card">
        <h1><?= e($newsItem['title']) ?></h1>
        <div class="news-meta" style="margin-bottom: 2rem;">
            <span class="news-date"><?= formatDate($newsItem['published_at']) ?></span>
        </div>
        <div class="news-content">
            <?= $newsItem['content'] ?>
        </div>
        <div style="margin-top: 2rem;">
            <a href="/news" class="btn-secondary">← Zpět na novinky</a>
        </div>
    </article>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>