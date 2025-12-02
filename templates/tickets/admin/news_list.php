<?php $pageTitle = 'Správa novinek'; ?>
<?php require __DIR__ . '/../../layout/admin_header.php'; ?>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 class="section-title" style="margin: 0;">Správa novinek</h1>
        <a href="/admin/news/create" class="btn-primary">Přidat novinku</a>
    </div>

    <?php if (empty($news)): ?>
        <p>Zatím nejsou žádné novinky.</p>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Název</th>
                        <th>Publikováno</th>
                        <th>Datum</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($news as $item): ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td><?= e($item['title']) ?></td>
                            <td><?= $item['is_published'] ? '✓ Ano' : '✗ Ne' ?></td>
                            <td><?= formatDate($item['created_at']) ?></td>
                            <td>
                                <a href="/admin/news/<?= $item['id'] ?>/edit" class="btn-secondary btn-small">Upravit</a>
                                <form method="POST" action="/admin/news/<?= $item['id'] ?>/delete" style="display: inline;" onsubmit="return confirm('Opravdu smazat?')">
                                    <?= CSRF::getTokenField() ?>
                                    <button type="submit" class="btn-danger btn-small">Smazat</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
