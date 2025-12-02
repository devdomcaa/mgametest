<?php
/**
 * Kontroler pro ticket systém (veřejná část)
 */

class TicketController
{
    public function index(): void
    {
        Auth::requireLogin();
        
        $userId = Auth::id();
        $role = Auth::role();
        
        // Hráč vidí jen své tickety, staff vidí všechny
        if (Auth::isStaff()) {
            $tickets = Ticket::getAll();
        } else {
            $tickets = Ticket::getByUser($userId);
        }
        
        require __DIR__ . '/../../templates/tickets/list.php';
    }

    public function showCreate(): void
    {
        Auth::requireLogin();
        require __DIR__ . '/../../templates/tickets/create.php';
    }

    public function create(): void
    {
        Auth::requireLogin();
        
        if (!CSRF::verifyToken()) {
            die('Neplatný CSRF token');
        }

        $subject = trim($_POST['subject'] ?? '');
        $category = $_POST['category'] ?? '';
        $message = trim($_POST['message'] ?? '');

        // Validace
        if (empty($subject) || empty($category) || empty($message)) {
            $_SESSION['error'] = 'Všechna pole jsou povinná';
            redirect('/tickets/create');
            return;
        }

        $ticketId = Ticket::create(Auth::id(), $subject, $category, $message);
        
        $_SESSION['success'] = 'Ticket byl úspěšně vytvořen';
        redirect("/tickets/$ticketId");
    }

    public function detail(int $id): void
    {
        Auth::requireLogin();
        
        // Kontrola oprávnění
        if (!Ticket::canUserAccess($id, Auth::id(), Auth::role())) {
            http_response_code(403);
            die('Nemáte oprávnění zobrazit tento ticket');
        }

        $ticket = Ticket::find($id);
        
        if (!$ticket) {
            http_response_code(404);
            die('Ticket nebyl nalezen');
        }

        $messages = Ticket::getMessages($id);
        
        require __DIR__ . '/../../templates/tickets/detail.php';
    }

    public function addReply(int $id): void
    {
        Auth::requireLogin();
        
        if (!CSRF::verifyToken()) {
            die('Neplatný CSRF token');
        }

        // Kontrola oprávnění
        if (!Ticket::canUserAccess($id, Auth::id(), Auth::role())) {
            http_response_code(403);
            die('Nemáte oprávnění přidávat odpovědi');
        }

        $ticket = Ticket::find($id);
        
        if (!$ticket) {
            http_response_code(404);
            die('Ticket nebyl nalezen');
        }

        // Nelze odpovídat na uzavřené tickety
        if ($ticket['status'] === 'closed') {
            $_SESSION['error'] = 'Nelze odpovídat na uzavřený ticket';
            redirect("/tickets/$id");
            return;
        }

        $message = trim($_POST['message'] ?? '');

        if (empty($message)) {
            $_SESSION['error'] = 'Zpráva nesmí být prázdná';
            redirect("/tickets/$id");
            return;
        }

        Ticket::addMessage($id, Auth::id(), $message);
        
        $_SESSION['success'] = 'Odpověď byla přidána';
        redirect("/tickets/$id");
    }
}