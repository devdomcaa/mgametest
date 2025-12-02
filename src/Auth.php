<?php
/**
 * Třída pro autentizaci a autorizaci uživatelů
 */

class Auth
{
    /**
     * Přihlásí uživatele
     */
    public static function login(string $username, string $password): bool
    {
        $user = Database::queryOne(
            'SELECT * FROM users WHERE username = ? AND is_banned = FALSE',
            [$username]
        );

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }

        return false;
    }

    /**
     * Odhlásí uživatele
     */
    public static function logout(): void
    {
        unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role']);
        session_destroy();
    }

    /**
     * Zkontroluje, zda je uživatel přihlášen
     */
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Vrátí ID přihlášeného uživatele
     */
    public static function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Vrátí username přihlášeného uživatele
     */
    public static function username(): ?string
    {
        return $_SESSION['username'] ?? null;
    }

    /**
     * Vrátí roli přihlášeného uživatele
     */
    public static function role(): ?string
    {
        return $_SESSION['role'] ?? null;
    }

    /**
     * Zkontroluje, zda má uživatel danou roli
     */
    public static function hasRole(string $role): bool
    {
        return self::role() === $role;
    }

    /**
     * Zkontroluje, zda má uživatel alespoň jednu z uvedených rolí
     */
    public static function hasAnyRole(array $roles): bool
    {
        return in_array(self::role(), $roles);
    }

    /**
     * Zkontroluje, zda má uživatel přístup do adminu (helper+)
     */
    public static function isStaff(): bool
    {
        return self::hasAnyRole(['helper', 'admin', 'owner']);
    }

    /**
     * Zkontroluje, zda má uživatel plný admin přístup
     */
    public static function isAdmin(): bool
    {
        return self::hasAnyRole(['admin', 'owner']);
    }

    /**
     * Vyžaduje přihlášení - přesměruje na login, pokud není přihlášen
     */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Vyžaduje staff přístup
     */
    public static function requireStaff(): void
    {
        self::requireLogin();
        if (!self::isStaff()) {
            http_response_code(403);
            die('Přístup odepřen');
        }
    }

    /**
     * Vyžaduje admin přístup
     */
    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            http_response_code(403);
            die('Přístup odepřen');
        }
    }
}