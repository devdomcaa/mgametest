<?php require __DIR__ . '/layout/header.php'; ?>
<div class="container">
    <div class="admin-card">
        <?php if ($rules): ?>
            <?= $rules['content'] ?>
        <?php else: ?>
            <p>Pravidla zatím nebyla nastavena.</p>
        <?php endif; ?>
    </div>
</div>
<?php require __DIR__ . '/layout/footer.php'; ?>