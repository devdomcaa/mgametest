<?php
/**
 * Model pro práci s pravidly serveru
 */

class Rule
{
    /**
     * Vrátí aktuální pravidla
     */
    public static function get(): ?array
    {
        return Database::queryOne('SELECT * FROM rules ORDER BY id DESC LIMIT 1');
    }

    /**
     * Aktualizuje pravidla
     */
    public static function update(string $content): bool
    {
        $existing = self::get();
        
        if ($existing) {
            return Database::execute(
                'UPDATE rules SET content = ?, updated_at = NOW() WHERE id = ?',
                [$content, $existing['id']]
            );
        } else {
            return Database::execute(
                'INSERT INTO rules (content) VALUES (?)',
                [$content]
            );
        }
    }
}