<?php
/**
 * Kontroler pro novinky
 */

class NewsController
{
    public function index(): void
    {
        $news = News::getPublished(50);
        require __DIR__ . '/../../templates/news.php';
    }

    public function detail(string $slug): void
    {
        $newsItem = News::findBySlug($slug);
        
        if (!$newsItem) {
            http_response_code(404);
            die('Novinka nebyla nalezena');
        }
        
        require __DIR__ . '/../../templates/news_detail.php';
    }
}