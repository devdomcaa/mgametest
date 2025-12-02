<?php

class News
{
    /**
     * Vrátí nejnovější publikované novinky pro homepage
     */
    public static function getLatest(int $limit = 3): array
    {
        $limit = max(1, (int) $limit);

        $sql = "SELECT 
                    n.*,
                    u.username AS author_name,
                    u.uuid     AS author_uuid
                FROM news n
                LEFT JOIN users u ON u.id = n.author_id
                WHERE n.is_published = 1
                ORDER BY n.published_at DESC
                LIMIT $limit";

        return Database::query($sql);
    }

    /**
     * Vrátí publikované novinky – seznam všech
     */
    public static function getPublished(int $limit = 50): array
    {
        $limit = max(1, (int) $limit);

        $sql = "SELECT 
                    n.*,
                    u.username AS author_name,
                    u.uuid     AS author_uuid
                FROM news n
                LEFT JOIN users u ON u.id = n.author_id
                WHERE n.is_published = 1
                ORDER BY n.published_at DESC
                LIMIT $limit";

        return Database::query($sql);
    }

    /**
     * Vrátí všechny novinky (admin)
     */
    public static function getAll(): array
    {
        return Database::query(
            "SELECT n.*, u.username AS author_name 
             FROM news n 
             LEFT JOIN users u ON u.id = n.author_id
             ORDER BY n.created_at DESC"
        );
    }

    /**
     * Najde novinku podle ID
     */
    public static function find(int $id): ?array
    {
        return Database::queryOne(
            "SELECT 
                n.*,
                u.username AS author_name,
                u.uuid     AS author_uuid
             FROM news n
             LEFT JOIN users u ON u.id = n.author_id
             WHERE n.id = ?",
            [$id]
        );
    }

    /**
     * Najde novinku podle slug
     */
    public static function findBySlug(string $slug): ?array
    {
        return Database::queryOne(
            "SELECT 
                n.*,
                u.username AS author_name,
                u.uuid     AS author_uuid
             FROM news n
             LEFT JOIN users u ON u.id = n.author_id
             WHERE n.slug = ? AND n.is_published = 1",
            [$slug]
        );
    }

    /**
     * Vytvoří novou novinku
     */
    public static function create(array $data): bool
    {
        $title   = trim($data['title'] ?? '');
        $excerpt = trim($data['excerpt'] ?? '');
        $content = trim($data['content'] ?? '');
        $imageUrl = trim($data['image_url'] ?? '');
        $isPublished = !empty($data['is_published']) ? 1 : 0;

        if ($title === '' || $content === '') {
            return false;
        }

        // slug
        $baseSlug = slugify($title);
        if ($baseSlug === '') {
            $baseSlug = 'novinka';
        }
        $slug = self::generateUniqueSlug($baseSlug);

        $publishedAt = $isPublished ? date('Y-m-d H:i:s') : null;
        $authorId    = Auth::id(); // uloží ID přihlášeného admina

        $sql = "INSERT INTO news (author_id, title, slug, excerpt, image_url, content, is_published, published_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        return Database::execute($sql, [
            $authorId,
            $title,
            $slug,
            $excerpt,
            $imageUrl,
            $content,
            $isPublished,
            $publishedAt,
        ]);
    }

    /**
     * Aktualizuje existující novinku
     */
    public static function update(int $id, array $data): bool
    {
        $title   = trim($data['title'] ?? '');
        $excerpt = trim($data['excerpt'] ?? '');
        $content = trim($data['content'] ?? '');
        $imageUrl = trim($data['image_url'] ?? '');
        $isPublished = !empty($data['is_published']) ? 1 : 0;

        if ($title === '' || $content === '') {
            return false;
        }

        // slug
        $baseSlug = slugify($title);
        if ($baseSlug === '') {
            $baseSlug = 'novinka';
        }
        $slug = self::generateUniqueSlug($baseSlug, $id);

        $publishedAt = $isPublished ? date('Y-m-d H:i:s') : null;

        $sql = "UPDATE news
                SET title = ?, slug = ?, excerpt = ?, image_url = ?, content = ?, is_published = ?, published_at = ?
                WHERE id = ?";

        return Database::execute($sql, [
            $title,
            $slug,
            $excerpt,
            $imageUrl,
            $content,
            $isPublished,
            $publishedAt,
            $id,
        ]);
    }

    /**
     * Smazání novinky
     */
    public static function delete(int $id): bool
    {
        return Database::execute("DELETE FROM news WHERE id = ?", [$id]);
    }

    /**
     * Kolik je publikovaných novinek
     */
    public static function countPublished(): int
    {
        $row = Database::queryOne(
            "SELECT COUNT(*) AS count FROM news WHERE is_published = 1"
        );

        return (int) ($row['count'] ?? 0);
    }

    /**
     * Vytvoří unikátní slug
     */
    private static function generateUniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = $baseSlug;
        $i = 2;

        while (self::slugExists($slug, $ignoreId)) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        return $slug;
    }

    /**
     * Zjistí, zda slug existuje
     */
    private static function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        if ($ignoreId !== null) {
            $row = Database::queryOne(
                "SELECT id FROM news WHERE slug = ? AND id != ?",
                [$slug, $ignoreId]
            );
        } else {
            $row = Database::queryOne(
                "SELECT id FROM news WHERE slug = ?",
                [$slug]
            );
        }

        return $row !== null;
    }
}
