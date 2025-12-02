<?php
/**
 * Pomocné funkce použitelné v celé aplikaci
 */

/**
 * Escapuje HTML pro bezpečný výstup
 */
function e(?string $string): string
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Přesměruje na danou URL
 */
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

/**
 * Vytvoří slug z textu
 */
function slugify(string $text): string
{
    $text = mb_strtolower($text, 'UTF-8');
    
    // České znaky
    $text = str_replace(
        ['á', 'č', 'ď', 'é', 'ě', 'í', 'ň', 'ó', 'ř', 'š', 'ť', 'ú', 'ů', 'ý', 'ž'],
        ['a', 'c', 'd', 'e', 'e', 'i', 'n', 'o', 'r', 's', 't', 'u', 'u', 'y', 'z'],
        $text
    );
    
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    
    return $text;
}

/**
 * Formátuje datum do českého formátu
 */
function formatDate(?string $date, string $format = 'd.m.Y H:i'): string
{
    if (!$date) {
        return '';
    }
    return date($format, strtotime($date));
}

/**
 * Zkrátí text na daný počet znaků
 */
function truncate(string $text, int $length = 100, string $suffix = '...'): string
{
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . $suffix;
}

/**
 * Vrátí přeložený název kategorie ticketu
 */
function getTicketCategoryName(string $category): string
{
    $categories = [
        'technical' => 'Technický problém',
        'ban_unban' => 'Ban/Unban',
        'bug_report' => 'Nahlášení bugu',
        'other' => 'Jiné'
    ];
    return $categories[$category] ?? $category;
}

/**
 * Vrátí přeložený název stavu ticketu
 */
function getTicketStatusName(string $status): string
{
    $statuses = [
        'open' => 'Otevřený',
        'in_progress' => 'Řeší se',
        'closed' => 'Uzavřený'
    ];
    return $statuses[$status] ?? $status;
}

/**
 * Vrátí CSS třídu pro badge stavu ticketu
 */
function getTicketStatusClass(string $status): string
{
    $classes = [
        'open' => 'status-open',
        'in_progress' => 'status-progress',
        'closed' => 'status-closed'
    ];
    return $classes[$status] ?? '';
}

/**
 * Vrátí přeložený název role
 */
function getRoleName(string $role): string
{
    $roles = [
        'player' => 'Hráč',
        'helper' => 'Helper',
        'admin' => 'Admin',
        'owner' => 'Owner'
    ];
    return $roles[$role] ?? $role;
}

/**
 * Získá status Minecraft serveru (dummy implementace)
 */
function getServerStatus(): array
{
    // Zde by byla skutečná implementace dotazu na MC server
    // Pro ukázku vrátíme testovací data
    return [
        'online' => true,
        'players_online' => 42,
        'players_max' => 100,
        'version' => '1.20.4',
        'ip' => 'play.mgame.cz'
    ];
}

function getMinecraftUUID(string $nickname): ?string
{
    if (!$nickname) {
        return null;
    }

    $nickname = trim($nickname);

    // Mojang API – nick → UUID
    $url = "https://api.mojang.com/users/profiles/minecraft/" . urlencode($nickname);

    // potlačí warningy kdyby hráč neexistoval
    $json = @file_get_contents($url);

    if (!$json) {
        return null;
    }

    $data = json_decode($json, true);

    // API vrací UUID bez pomlček
    return $data['id'] ?? null;
}
