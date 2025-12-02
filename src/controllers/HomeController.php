<?php
/**
 * Kontroler pro hlavní stránku
 */

class HomeController
{
    public function index(): void
    {
        $latestNews = News::getLatest(3);
        $serverStatus = getServerStatus();
        
        require __DIR__ . '/../../templates/home.php';
    }
}