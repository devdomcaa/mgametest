<?php $pageTitle = 'Domů'; ?>
<?php require __DIR__ . '/layout/header.php'; ?>

<div class="hero hero--bg">
    <div class="container">
        <h1 class="hero-title">Vítejte na MGame</h1>
        <p class="hero-subtitle">Nejlepší Minecraft server pro survival a PvP</p>
        <div class="server-info">
            <div class="server-status <?= $serverStatus['online'] ? 'online' : 'offline' ?>">
                <span class="status-indicator"></span>
                <?php if ($serverStatus['online']): ?>
                    <span>Online: <?= $serverStatus['players_online'] ?>/<?= $serverStatus['players_max'] ?> hráčů</span>
                <?php else: ?>
                    <span>Server je offline</span>
                <?php endif; ?>
            </div>
            <div class="server-ip">
                <strong>IP:</strong> <?= e($serverStatus['ip']) ?>
            </div>
            <div class="server-version">
                <strong>Verze:</strong> <?= e($serverStatus['version']) ?>
            </div>
        </div>
    </div>
</div>

<div class="container">
    
<section class="about-section">
    <div class="container">
        <h2 class="section-title">O nás</h2>
        <div class="about-grid">
            <div class="about-text">
                <p>
                    MGame je komunitní Minecraft server zaměřený na pohodový survival a férové PvP.
                    Budujeme stabilní a dlouhodobý svět, kde mají hráči prostor tvořit vlastní projekty,
                    města a ekonomiku.
                </p>
                <p>
                    Cílíme na prostředí, kam se rádi vrací jak casual hráči, tak i zkušení borci. Aktivní
                    admin tým, promyšlená ochrana pozemků a eventy zajišťují, že se u nás nudit nebudeš.
                </p>
            </div>
            <div class="about-image">
                <img src="/public/assets/img/skin.png" alt="MGame skin">
            </div>
        </div>
    </div>
</section>

    <section class="features-section">
        <h2 class="section-title">Co u nás najdete</h2>
        <div class="features-grid">
            <div class="feature-card">
                <h3>⚔️ PvP Arena</h3>
                <p>Bojuj s ostatními hráči v našich speciálních arenách</p>
            </div>
            <div class="feature-card">
                <h3>🏰 Survival</h3>
                <p>Stav, objevuj a přežívej v našem survival světě</p>
            </div>
            <div class="feature-card">
                <h3>💰 Ekonomika</h3>
                <p>Obchoduj s ostatními a buduj své impérium</p>
            </div>
            <div class="feature-card">
                <h3>🛡️ Ochrana</h3>
                <p>Tvoje stavby jsou v bezpečí díky ochraně pozemků</p>
            </div>
        </div>
    </section>

<section class="news-section">
    <h2 class="section-title">Poslední novinky</h2>

    <?php if (empty($latestNews)): ?>
        <p>Zatím nejsou žádné novinky.</p>
    <?php else: ?>
        <div class="news-grid">
            <?php foreach ($latestNews as $news): ?>
                <?php
                    // Obrázek novinky (DB nebo placeholder)
                    $imageUrl = !empty($news['image_url'])
                        ? $news['image_url']
                        : '/public/assets/img/news-placeholder.jpg';

                    // Jméno autora (JOIN z users) nebo fallback
                    $authorName = !empty($news['author_name'])
                        ? $news['author_name']
                        : 'Admin';

                    // Získáme UUID podle nicku (neřešíme DB uuid, je klidně null)
                    $authorUUID = getMinecraftUUID($authorName);

                    if (!empty($authorUUID)) {
                        $avatarUrl = 'https://crafatar.com/avatars/' . $authorUUID . '?size=64&overlay';
                    } else {
                        $avatarUrl = '/public/assets/img/default-head.png';
                    }

                    $ts = strtotime($news['published_at']);
                ?>

                <article class="news-card">

                    <!-- Obrázek novinky -->
                    <div class="news-image-wrapper">
                        <img src="<?= e($imageUrl) ?>" alt="<?= e($news['title']) ?>" class="news-image">
                    </div>

                    <!-- Text novinky -->
                    <div class="news-body">
                        <h3 class="news-title"><?= e($news['title']) ?></h3>
                        <p class="news-excerpt"><?= e($news['excerpt']) ?></p>
                    </div>

                    <!-- Autor + čas + tlačítko -->
                    <footer class="news-footer">
                        <div class="news-author">
                            <div
                                class="news-author-avatar"
                                style="background-image: url('<?= e($avatarUrl) ?>');"
                            ></div>

                            <div class="news-author-info">
                                <div class="news-author-name"><?= e($authorName) ?></div>
                                <div class="news-meta">
                                    <span class="news-time"><?= date('H:i', $ts) ?></span>
                                    <span class="news-date"><?= date('d.m.Y', $ts) ?></span>
                                </div>
                            </div>
                        </div>

                        <a href="/news/<?= e($news['slug']) ?>" class="news-readmore-btn">
                            <span class="news-readmore-icon">🔍</span>
                            <span>Číst více</span>
                        </a>
                    </footer>

                </article>
            <?php endforeach; ?>
        </div>

        <div class="text-center">
            <a href="/news" class="btn-secondary">Zobrazit všechny novinky</a>
        </div>
    <?php endif; ?>
</section>


</div>

<?php require __DIR__ . '/layout/footer.php'; ?>