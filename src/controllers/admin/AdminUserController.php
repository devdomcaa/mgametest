<?php
/**
 * Kontroler pro správu uživatelů v adminu
 */

class AdminUserController
{
    public function index(): void
    {
        Auth::requireAdmin();
        
        $users = User::getAll();
        require __DIR__ . '/../../../templates/admin/user_list.php';
    }

    public function updateRole(int $id): void
    {
        Auth::requireAdmin();
        
        // Pouze owner může měnit role
        if (!Auth::hasRole('owner')) {
            http_response_code(403);
            die('Pouze owner může měnit role');
        }
        
        if (!CSRF::verifyToken()) {
            die('Neplatný CSRF token');
        }

        $role = $_POST['role'] ?? '';
        
        if (!in_array($role, ['player', 'helper', 'admin', 'owner'])) {
            $_SESSION['error'] = 'Neplatná role';
            redirect('/admin/users');
            return;
        }

        User::updateRole($id, $role);
        
        $_SESSION['success'] = 'Role byla změněna';
        redirect('/admin/users');
    }

    public function toggleBan(int $id): void
    {
        Auth::requireAdmin();
        
        if (!CSRF::verifyToken()) {
            die('Neplatný CSRF token');
        }

        $user = User::find($id);
        
        if (!$user) {
            http_response_code(404);
            die('Uživatel nebyl nalezen');
        }

        $newBanStatus = !$user['is_banned'];
        User::setBanned($id, $newBanStatus);
        
        $message = $newBanStatus ? 'Uživatel byl zabanován' : 'Uživatel byl odbanován';
        $_SESSION['success'] = $message;
        redirect('/admin/users');
    }
}