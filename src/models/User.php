<?php
/**
 * Model pro práci s uživateli
 */

class User
{
    /**
     * Vytvoří nového uživatele
     */
    public static function create(string $username, string $email, string $password): bool
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        return Database::execute(
            'INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)',
            [$username, $email, $passwordHash, 'player']
        );
    }

    /**
     * Najde uživatele podle ID
     */
    public static function find(int $id): ?array
    {
        return Database::queryOne('SELECT * FROM users WHERE id = ?', [$id]);
    }

    /**
     * Najde uživatele podle username
     */
    public static function findByUsername(string $username): ?array
    {
        return Database::queryOne('SELECT * FROM users WHERE username = ?', [$username]);
    }

    /**
     * Vrátí všechny uživatele
     */
    public static function getAll(): array
    {
        return Database::query('SELECT * FROM users ORDER BY created_at DESC');
    }

    /**
     * Aktualizuje roli uživatele
     */
    public static function updateRole(int $id, string $role): bool
    {
        return Database::execute(
            'UPDATE users SET role = ? WHERE id = ?',
            [$role, $id]
        );
    }

    /**
     * Zablokuje/odblokuje uživatele
     */
    public static function setBanned(int $id, bool $banned): bool
    {
        return Database::execute(
            'UPDATE users SET is_banned = ? WHERE id = ?',
            [$banned ? 1 : 0, $id]
        );
    }

    /**
     * Získá staff členy (helper, admin, owner)
     */
    public static function getStaffMembers(): array
    {
        return Database::query(
            "SELECT * FROM users WHERE role IN ('helper', 'admin', 'owner') ORDER BY username"
        );
    }

    /**
     * Zkontroluje, zda existuje uživatel s daným username
     */
    public static function existsByUsername(string $username): bool
    {
        $result = Database::queryOne(
            'SELECT COUNT(*) as count FROM users WHERE username = ?',
            [$username]
        );
        return $result['count'] > 0;
    }

    /**
     * Zkontroluje, zda existuje uživatel s daným emailem
     */
    public static function existsByEmail(string $email): bool
    {
        $result = Database::queryOne(
            'SELECT COUNT(*) as count FROM users WHERE email = ?',
            [$email]
        );
        return $result['count'] > 0;
    }

    /**
     * Vrátí počet všech uživatelů
     */
    public static function count(): int
    {
        $result = Database::queryOne('SELECT COUNT(*) as count FROM users');
        return (int) $result['count'];
    }
}