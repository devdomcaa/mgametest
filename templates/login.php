<?php $pageTitle = 'Přihlášení'; ?>
<?php require __DIR__ . '/layout/header.php'; ?>
<div class="container">
    <div class="form-card">
        <h2>Přihlášení</h2>
        <form method="POST" action="/login">
            <?= CSRF::getTokenField() ?>
            <div class="form-group">
                <label>Minecraft nick</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Heslo</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%;">Přihlásit se</button>
        </form>
        <p style="text-align: center; margin-top: 1rem;">
            Nemáte účet? <a href="/register" class="btn-link">Zaregistrujte se</a>
        </p>
    </div>
</div>
<?php require __DIR__ . '/layout/footer.php'; ?>