<?php $pageTitle = 'Registrace'; ?>
<?php require __DIR__ . '/layout/header.php'; ?>
<div class="container">
    <div class="form-card">
        <h2>Registrace</h2>
        <form method="POST" action="/register">
            <?= CSRF::getTokenField() ?>
            <div class="form-group">
                <label>Minecraft nick</label>
                <input type="text" name="username" class="form-control" value="<?= e($_SESSION['old']['username'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= e($_SESSION['old']['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Heslo</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Potvrzení hesla</label>
                <input type="password" name="password_confirm" class="form-control" required>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%;">Registrovat</button>
        </form>
        <p style="text-align: center; margin-top: 1rem;">
            Už máte účet? <a href="/login" class="btn-link">Přihlaste se</a>
        </p>
    </div>
</div>
<?php require __DIR__ . '/layout/footer.php'; ?>
<?php unset($_SESSION['old']); ?>