<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> - MGame Admin</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">

</head>
<body class="admin-body">
    <nav class="admin-navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="/admin">
                    <span class="logo">MGame Admin</span>
                </a>
            </div>
            <ul class="nav-menu">
                <li><a href="/admin">Dashboard</a></li>
                <?php if (Auth::isAdmin()): ?>
                    <li><a href="/admin/news">Novinky</a></li>
                <?php endif; ?>
                <li><a href="/admin/tickets">Tickety</a></li>
                <?php if (Auth::isAdmin()): ?>
                    <li><a href="/admin/users">Uživatelé</a></li>
                    <li><a href="/admin/rules">Pravidla</a></li>
                <?php endif; ?>
                <li><a href="/">← Zpět na web</a></li>
                <li><a href="/logout">Odhlásit</a></li>
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

    <main class="admin-content">