<?php
/**
 * Kontroler pro správu novinek v adminu
 */

class AdminNewsController
{
    public function index(): void
    {
        Auth::requireAdmin();
        
        $news = News::getAll();
        require __DIR__ . '/../../../templates/tickets/admin/news_list.php';
    }

    public function showCreate(): void
    {
        Auth::requireAdmin();
        require __DIR__ . '/../../../templates/tickets/admin/news_form.php';
    }

    public function create(): void
    {
        Auth::requireAdmin();
        
        if (!CSRF::verifyToken()) {
            die('Neplatný CSRF token');
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'image_url'    => trim($_POST['image_url'] ?? ''),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
        ];

        News::create($data);
        
        $_SESSION['success'] = 'Novinka byla vytvořena';
        redirect('/admin/news');
    }

    public function showEdit(int $id): void
    {
        Auth::requireAdmin();
        
        $newsItem = News::find($id);
        
        if (!$newsItem) {
            http_response_code(404);
            die('Novinka nebyla nalezena');
        }
        
        require __DIR__ . '/../../../templates/tickets/admin/news_form.php';
    }

    public function edit(int $id): void
    {
        Auth::requireAdmin();
        
        if (!CSRF::verifyToken()) {
            die('Neplatný CSRF token');
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'image_url'    => trim($_POST['image_url'] ?? ''),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
        ];

        News::update($id, $data);
        
        $_SESSION['success'] = 'Novinka byla aktualizována';
        redirect('/admin/news');
    }

    public function delete(int $id): void
    {
        Auth::requireAdmin();
        
        if (!CSRF::verifyToken()) {
            die('Neplatný CSRF token');
        }

        News::delete($id);
        
        $_SESSION['success'] = 'Novinka byla smazána';
        redirect('/admin/news');
    }
}