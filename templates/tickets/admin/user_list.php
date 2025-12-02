<?php $pageTitle = 'Správa uživatelů'; ?>
<?php require __DIR__ . '/../../layout/admin_header.php'; ?>
<div class="container">
    <h1 class="section-title">Správa uživatelů</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Registrace</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= e($user['username']) ?></td>
                        <td><?= e($user['email']) ?></td>
                        <td><?= getRoleName($user['role']) ?></td>
                        <td><?= $user['is_banned'] ? '<span class="badge status-closed">Zabanován</span>' : '<span class="badge status-open">Aktivní</span>' ?></td>
                        <td><?= formatDate($user['created_at']) ?></td>
                        <td>
                            <?php if (Auth::hasRole('owner')): ?>
                                <form method="POST" action="/admin/users/<?= $user['id'] ?>/role" style="display: inline;">
                                    <?= CSRF::getTokenField() ?>
                                    <select name="role" class="form-control" style="width: auto; display: inline-block; padding: 0.3rem;">
                                        <option value="player" <?= $user['role'] === 'player' ? 'selected' : '' ?>>Hráč</option>
                                        <option value="helper" <?= $user['role'] === 'helper' ? 'selected' : '' ?>>Helper</option>
                                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="owner" <?= $user['role'] === 'owner' ? 'selected' : '' ?>>Owner</option>
                                    </select>
                                    <button type="submit" class="btn-secondary btn-small">Změnit</button>
                                </form>
                            <?php endif; ?>
                            <form method="POST" action="/admin/users/<?= $user['id'] ?>/ban" style="display: inline;" onsubmit="return confirm('Opravdu změnit ban status?')">
                                <?= CSRF::getTokenField() ?>
                                <button type="submit" class="btn-danger btn-small"><?= $user['is_banned'] ? 'Unban' : 'Ban' ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>