<?php
/**
 * Model pro práci s tickety
 */
class Ticket
{
    /**
     * Základní SELECT s joinem na uživatele
     */
    private static function baseSelect(): string
    {
        return '
            SELECT
                t.*,
                u.username AS user_username,
                a.username AS assigned_username
            FROM tickets t
            JOIN users u ON t.user_id = u.id
            LEFT JOIN users a ON t.assigned_to = a.id
        ';
    }

    /**
     * Vrátí všechny tickety, případně filtrovány podle stavu / přiřazeného uživatele
     */
    public static function getAll(?string $status = null, ?int $assignedTo = null): array
    {
        $sql = self::baseSelect() . ' WHERE 1=1';
        $params = [];

        if ($status !== null && $status !== '') {
            $sql .= ' AND t.status = ?';
            $params[] = $status;
        }

        if ($assignedTo !== null && $assignedTo !== '') {
            $sql .= ' AND t.assigned_to = ?';
            $params[] = $assignedTo;
        }

        $sql .= ' ORDER BY t.created_at DESC';

        return Database::query($sql, $params);
    }

    /**
     * Vrátí tickety konkrétního uživatele
     */
    public static function getByUser(int $userId): array
    {
        $sql = self::baseSelect() . ' WHERE t.user_id = ? ORDER BY t.created_at DESC';

        return Database::query($sql, [$userId]);
    }

    /**
     * Vrátí tickety přiřazené konkrétnímu staff členovi
     */
    public static function getAssignedTo(int $userId): array
    {
        $sql = self::baseSelect() . ' WHERE t.assigned_to = ? ORDER BY t.created_at DESC';

        return Database::query($sql, [$userId]);
    }

    /**
     * Najde ticket podle ID
     */
    public static function find(int $id): ?array
    {
        $sql = self::baseSelect() . ' WHERE t.id = ?';

        return Database::queryOne($sql, [$id]);
    }

    /**
     * Založí nový ticket a první zprávu
     */
    public static function create(int $userId, string $subject, string $category, string $message): int
    {
        Database::execute(
            'INSERT INTO tickets (user_id, subject, category) VALUES (?, ?, ?)',
            [$userId, $subject, $category]
        );

        $ticketId = (int) Database::lastInsertId();

        self::addMessage($ticketId, $userId, $message);

        return $ticketId;
    }

    /**
     * Zjistí, zda má uživatel přístup k ticketu
     */
    public static function canUserAccess(int $ticketId, ?int $userId, ?string $role): bool
    {
        // Helper/Admin/Owner vidí všechny tickety
        if (in_array($role, ['helper', 'admin', 'owner'], true)) {
            return true;
        }

        if ($userId === null) {
            return false;
        }

        $row = Database::queryOne(
            'SELECT id FROM tickets WHERE id = ? AND user_id = ?',
            [$ticketId, $userId]
        );

        return $row !== null;
    }

    /**
     * Vrátí zprávy k ticketu včetně autora a role
     */
    public static function getMessages(int $ticketId): array
    {
        return Database::query(
            'SELECT tm.*, u.username, u.role
             FROM ticket_messages tm
             JOIN users u ON tm.user_id = u.id
             WHERE tm.ticket_id = ?
             ORDER BY tm.created_at ASC',
            [$ticketId]
        );
    }

    /**
     * Přidá odpověď do ticketu
     */
    public static function addMessage(int $ticketId, int $userId, string $message): bool
    {
        return Database::execute(
            'INSERT INTO ticket_messages (ticket_id, user_id, message) VALUES (?, ?, ?)',
            [$ticketId, $userId, $message]
        );
    }

    /**
     * Aktualizuje stav ticketu
     */
    public static function updateStatus(int $id, string $status): bool
    {
        if (!in_array($status, ['open', 'in_progress', 'closed'], true)) {
            return false;
        }

        $closedAtSql = $status === 'closed' ? 'NOW()' : 'NULL';

        $sql = "UPDATE tickets
                SET status = ?, closed_at = $closedAtSql
                WHERE id = ?";

        return Database::execute($sql, [$status, $id]);
    }

    /**
     * Přiřadí ticket staff členovi (nebo zruší přiřazení při NULL)
     */
    public static function assign(int $id, ?int $staffId): bool
    {
        return Database::execute(
            'UPDATE tickets SET assigned_to = ? WHERE id = ?',
            [$staffId, $id]
        );
    }

    /**
     * Vrátí počet otevřených ticketů
     */
    public static function countOpen(): int
    {
        $row = Database::queryOne(
            "SELECT COUNT(*) AS count FROM tickets WHERE status = 'open'"
        );

        return (int) ($row['count'] ?? 0);
    }
}
