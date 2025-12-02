<?php
/**
 * Kontroler pro správu ticketů v adminu
 */

class AdminTicketController
{
    public function index(): void
    {
        Auth::requireStaff();
        
        $status = $_GET['status'] ?? null;
        $assignedTo = $_GET['assigned_to'] ?? null;
        
        $tickets = Ticket::getAll($status, $assignedTo);
        $staffMembers = User::getStaffMembers();
        
        require __DIR__ . '/../../../templates/tickets/admin/ticket_list.php';
    }

    public function detail(int $id): void
    {
        Auth::requireStaff();
        
        $ticket = Ticket::find($id);
        
        if (!$ticket) {
            http_response_code(404);
            die('Ticket nebyl nalezen');
        }

        $messages = Ticket::getMessages($id);
        $staffMembers = User::getStaffMembers();
        
        require __DIR__ . '/../../../templates/tickets/admin/ticket_detail.php';
    }

    public function updateStatus(int $id): void
    {
        Auth::requireStaff();
        
        if (!CSRF::verifyToken()) {
            die('Neplatný CSRF token');
        }

        $status = $_POST['status'] ?? '';
        
        if (!in_array($status, ['open', 'in_progress', 'closed'])) {
            $_SESSION['error'] = 'Neplatný stav';
            redirect("/admin/tickets/$id");
            return;
        }

        Ticket::updateStatus($id, $status);
        
        $_SESSION['success'] = 'Stav ticketu byl změněn';
        redirect("/admin/tickets/$id");
    }

    public function assign(int $id): void
    {
        Auth::requireStaff();
        
        if (!CSRF::verifyToken()) {
            die('Neplatný CSRF token');
        }

        $staffId = $_POST['assigned_to'] ?? null;
        $staffId = $staffId ? (int)$staffId : null;

        Ticket::assign($id, $staffId);
        
        $_SESSION['success'] = 'Ticket byl přiřazen';
        redirect("/admin/tickets/$id");
    }

    public function addReply(int $id): void
    {
        Auth::requireStaff();
        
        if (!CSRF::verifyToken()) {
            die('Neplatný CSRF token');
        }

        $message = trim($_POST['message'] ?? '');

        if (empty($message)) {
            $_SESSION['error'] = 'Zpráva nesmí být prázdná';
            redirect("/admin/tickets/$id");
            return;
        }

        Ticket::addMessage($id, Auth::id(), $message);
        
        $_SESSION['success'] = 'Odpověď byla přidána';
        redirect("/admin/tickets/$id");
    }
}