<?php $pageTitle = 'Novinky'; ?>
<?php require __DIR__ . '/layout/header.php'; ?>
<div class="container">
    <h1 class="section-title">Všechny novinky</h1>
    <?php if (empty($news)): ?>
        <p>Zatím nejsou žádné novinky.</p>
    <?php else: ?>
        <div class="news-grid">
            <?php foreach ($news as $item): ?>
                <div class="news-card">
                    <h3 class="news-title"><?= e($item['title']) ?></h3>
                    <div class="news-meta">
                        <span class="news-date"><?= formatDate($item['published_at']) ?></span>
                    </div>
                    <p class="news-excerpt"><?= e($item['excerpt']) ?></p>
                    <a href="/news/<?= e($item['slug']) ?>" class="btn-link">Číst více →</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/layout/footer.php'; ?>


