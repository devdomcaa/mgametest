<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'MGame' ?> - MGame Minecraft Server</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">

</head>
<body>
    <nav class="navbar">
    <div class="container">
        <div class="nav-brand">
            <a href="/" class="nav-logo-link">
                <img src="/public/assets/img/logo.png" alt="MGame logo" class="nav-logo">
            </a>
        </div>
        <ul class="nav-menu">

                <li><a href="/">Domů</a></li>
                <li><a href="/news">Novinky</a></li>
                <li><a href="/rules">Pravidla</a></li>
                <?php if (Auth::check()): ?>
                    <li><a href="/tickets">Tickety</a></li>
                    <?php if (Auth::isStaff()): ?>
                        <li><a href="/admin">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="/logout">Odhlásit (<?= e(Auth::username()) ?>)</a></li>
                <?php else: ?>
                    <li><a href="/login">Přihlásit se</a></li>
                    <li><a href="/register" class="btn-primary">Registrace</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= e($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= e($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <?php unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>

    <main class="main-content">