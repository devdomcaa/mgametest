<?php
/**
 * Front Controller - Hlavní vstupní bod aplikace
 */

session_start();

// Autoload tříd
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../src/' . $class . '.php',
        __DIR__ . '/../src/models/' . $class . '.php',
        __DIR__ . '/../src/controllers/' . $class . '.php',
        __DIR__ . '/../src/controllers/admin/' . $class . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Načtení helpers
require_once __DIR__ . '/../src/helpers.php';

// Jednoduché routing
$uri = $_SERVER['REQUEST_URI'];
$uri = strtok($uri, '?'); // Odstranění query stringu
$method = $_SERVER['REQUEST_METHOD'];

// Routing
try {
    // Homepage
    if ($uri === '/' || $uri === '') {
        $controller = new HomeController();
        $controller->index();
    }
    
    // Novinky
    elseif ($uri === '/news') {
        $controller = new NewsController();
        $controller->index();
    }
    elseif (preg_match('#^/news/([a-z0-9-]+)$#', $uri, $matches)) {
        $controller = new NewsController();
        $controller->detail($matches[1]);
    }
    
    // Pravidla
    elseif ($uri === '/rules') {
        $rules = Rule::get();
        $pageTitle = 'Pravidla';
        require __DIR__ . '/../templates/rules.php';
    }
    
    // Autentizace
    elseif ($uri === '/login' && $method === 'GET') {
        $controller = new AuthController();
        $controller->showLogin();
    }
    elseif ($uri === '/login' && $method === 'POST') {
        $controller = new AuthController();
        $controller->login();
    }
    elseif ($uri === '/register' && $method === 'GET') {
        $controller = new AuthController();
        $controller->showRegister();
    }
    elseif ($uri === '/register' && $method === 'POST') {
        $controller = new AuthController();
        $controller->register();
    }
    elseif ($uri === '/logout') {
        $controller = new AuthController();
        $controller->logout();
    }
    
    // Tickety
    elseif ($uri === '/tickets') {
        $controller = new TicketController();
        $controller->index();
    }
    elseif ($uri === '/tickets/create' && $method === 'GET') {
        $controller = new TicketController();
        $controller->showCreate();
    }
    elseif ($uri === '/tickets/create' && $method === 'POST') {
        $controller = new TicketController();
        $controller->create();
    }
    elseif (preg_match('#^/tickets/(\d+)$#', $uri, $matches) && $method === 'GET') {
        $controller = new TicketController();
        $controller->detail((int)$matches[1]);
    }
    elseif (preg_match('#^/tickets/(\d+)/reply$#', $uri, $matches) && $method === 'POST') {
        $controller = new TicketController();
        $controller->addReply((int)$matches[1]);
    }
    
    // Admin - Dashboard
    elseif ($uri === '/admin' || $uri === '/admin/') {
        $controller = new DashboardController();
        $controller->index();
    }
    
    // Admin - Novinky
    elseif ($uri === '/admin/news') {
        $controller = new AdminNewsController();
        $controller->index();
    }
    elseif ($uri === '/admin/news/create' && $method === 'GET') {
        $controller = new AdminNewsController();
        $controller->showCreate();
    }
    elseif ($uri === '/admin/news/create' && $method === 'POST') {
        $controller = new AdminNewsController();
        $controller->create();
    }
    elseif (preg_match('#^/admin/news/(\d+)/edit$#', $uri, $matches) && $method === 'GET') {
        $controller = new AdminNewsController();
        $controller->showEdit((int)$matches[1]);
    }
    elseif (preg_match('#^/admin/news/(\d+)/edit$#', $uri, $matches) && $method === 'POST') {
        $controller = new AdminNewsController();
        $controller->edit((int)$matches[1]);
    }
    elseif (preg_match('#^/admin/news/(\d+)/delete$#', $uri, $matches) && $method === 'POST') {
        $controller = new AdminNewsController();
        $controller->delete((int)$matches[1]);
    }
    
    // Admin - Tickety
    elseif ($uri === '/admin/tickets') {
        $controller = new AdminTicketController();
        $controller->index();
    }
    elseif (preg_match('#^/admin/tickets/(\d+)$#', $uri, $matches) && $method === 'GET') {
        $controller = new AdminTicketController();
        $controller->detail((int)$matches[1]);
    }
    elseif (preg_match('#^/admin/tickets/(\d+)/status$#', $uri, $matches) && $method === 'POST') {
        $controller = new AdminTicketController();
        $controller->updateStatus((int)$matches[1]);
    }
    elseif (preg_match('#^/admin/tickets/(\d+)/assign$#', $uri, $matches) && $method === 'POST') {
        $controller = new AdminTicketController();
        $controller->assign((int)$matches[1]);
    }
    elseif (preg_match('#^/admin/tickets/(\d+)/reply$#', $uri, $matches) && $method === 'POST') {
        $controller = new AdminTicketController();
        $controller->addReply((int)$matches[1]);
    }
    
    // Admin - Uživatelé
    elseif ($uri === '/admin/users') {
        $controller = new AdminUserController();
        $controller->index();
    }
    elseif (preg_match('#^/admin/users/(\d+)/role$#', $uri, $matches) && $method === 'POST') {
        $controller = new AdminUserController();
        $controller->updateRole((int)$matches[1]);
    }
    elseif (preg_match('#^/admin/users/(\d+)/ban$#', $uri, $matches) && $method === 'POST') {
        $controller = new AdminUserController();
        $controller->toggleBan((int)$matches[1]);
    }
    
    // Admin - Pravidla
    elseif ($uri === '/admin/rules' && $method === 'GET') {
        $controller = new AdminRuleController();
        $controller->showEdit();
    }
    elseif ($uri === '/admin/rules' && $method === 'POST') {
        $controller = new AdminRuleController();
        $controller->update();
    }
    
    // 404
    else {
        http_response_code(404);
        echo '<h1>404 - Stránka nebyla nalezena</h1>';
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo '<h1>Chyba serveru</h1>';
    if ($_ENV['APP_DEBUG'] ?? false) {
        echo '<pre>' . $e->getMessage() . '</pre>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    }
}