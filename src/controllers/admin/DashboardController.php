<?php
/**
 * Kontroler pro admin dashboard
 */

class DashboardController
{
    public function index(): void
    {
        Auth::requireStaff();
        
        $stats = [
            'users' => User::count(),
            'open_tickets' => Ticket::countOpen(),
            'published_news' => News::countPublished(),
        ];
        
        // Pro helpera zobrazit přiřazené tickety
        if (Auth::hasRole('helper')) {
            $stats['my_tickets'] = Ticket::getAssignedTo(Auth::id());
        }
        
        require __DIR__ . '/../../../templates/tickets/admin/dashboard.php';
    }
}