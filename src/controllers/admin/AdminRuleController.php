<?php
/**
 * Kontroler pro správu pravidel v adminu
 */

class AdminRuleController
{
    public function showEdit(): void
    {
        Auth::requireAdmin();
        
        $rules = Rule::get();
        require __DIR__ . '/../../../templates/tickets/admin/rules_edit.php';
    }

    public function update(): void
    {
        Auth::requireAdmin();
        
        if (!CSRF::verifyToken()) {
            die('Neplatný CSRF token');
        }

        $content = trim($_POST['content'] ?? '');

        if (empty($content)) {
            $_SESSION['error'] = 'Obsah pravidel nesmí být prázdný';
            redirect('/admin/rules');
            return;
        }

        Rule::update($content);
        
        $_SESSION['success'] = 'Pravidla byla aktualizována';
        redirect('/admin/rules');
    }
}