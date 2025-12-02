<?php
/**
 * Kontroler pro autentizaci (login, registrace, logout)
 */

class AuthController
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            redirect('/');
        }
        
        require __DIR__ . '/../../templates/login.php';
    }

    public function login(): void
    {
        if (!CSRF::verifyToken()) {
            die('Neplatný CSRF token');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (Auth::login($username, $password)) {
            redirect('/');
        } else {
            $_SESSION['error'] = 'Neplatné přihlašovací údaje';
            redirect('/login');
        }
    }

    public function showRegister(): void
    {
        if (Auth::check()) {
            redirect('/');
        }
        
        require __DIR__ . '/../../templates/register.php';
    }

    public function register(): void
    {
        if (!CSRF::verifyToken()) {
            die('Neplatný CSRF token');
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // Validace
        $errors = [];

        if (empty($username) || strlen($username) < 3) {
            $errors[] = 'Username musí mít alespoň 3 znaky';
        }

        if (User::existsByUsername($username)) {
            $errors[] = 'Tento username je již použitý';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Neplatný email';
        }

        if (User::existsByEmail($email)) {
            $errors[] = 'Tento email je již registrován';
        }

        if (strlen($password) < 6) {
            $errors[] = 'Heslo musí mít alespoň 6 znaků';
        }

        if ($password !== $passwordConfirm) {
            $errors[] = 'Hesla se neshodují';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            redirect('/register');
            return;
        }

        // Vytvoření uživatele
        User::create($username, $email, $password);
        
        // Automatické přihlášení
        Auth::login($username, $password);
        
        $_SESSION['success'] = 'Registrace proběhla úspěšně!';
        redirect('/');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/');
    }
}