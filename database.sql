-- MGame Web - Databázové schéma
-- Vytvoření databáze
CREATE DATABASE IF NOT EXISTS mgame_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mgame_web;


ALTER TABLE news
ADD COLUMN author_id INT NULL AFTER id,
ADD COLUMN image_url VARCHAR(255) NULL AFTER excerpt,
ADD FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL;

-- Tabulka uživatelů
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('player', 'helper', 'admin', 'owner') DEFAULT 'player',
    is_banned BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabulka novinek
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content TEXT NOT NULL,
    is_published BOOLEAN DEFAULT TRUE,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_published (is_published, published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabulka pravidel
CREATE TABLE rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabulka ticketů
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject VARCHAR(255) NOT NULL,
    category ENUM('technical', 'ban_unban', 'bug_report', 'other') NOT NULL,
    status ENUM('open', 'in_progress', 'closed') DEFAULT 'open',
    assigned_to INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    closed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_assigned (assigned_to)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabulka zpráv v ticketech
CREATE TABLE ticket_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_ticket (ticket_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- SEED DATA - Demonstrační data
-- ========================================

-- Uživatelé (heslo pro všechny: "heslo123")
INSERT INTO users (username, email, password_hash, role, is_banned) VALUES
('Admin', 'admin@mgame.cz', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'owner', FALSE),
('Helper1', 'helper@mgame.cz', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'helper', FALSE),
('Hrac1', 'hrac1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'player', FALSE),
('Hrac2', 'hrac2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'player', FALSE),
('BannedPlayer', 'banned@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'player', TRUE);

-- Novinky
INSERT INTO news (title, slug, excerpt, content, is_published, published_at) VALUES
('Vítejte na MGame serveru!', 'vitejte-na-mgame-serveru', 'Jsme rádi, že jste se k nám připojili. Přečtěte si vše důležité o našem serveru.', 
'<h2>Vítejte na MGame</h2><p>Jsme nadšení, že jste se připojili k naší rostoucí komunitě! MGame je Minecraft server zaměřený na survival s prvky ekonomiky a PvP.</p><p><strong>Co u nás najdete:</strong></p><ul><li>Stabilní server s minimálním lagováním</li><li>Přátelskou komunitu hráčů</li><li>Pravidelné eventy a soutěže</li><li>Ochrana pozemků</li><li>Obchody a ekonomický systém</li></ul><p>Připojte se a začněte svou cestu!</p>', 
TRUE, NOW()),

('Nová aktualizace 1.20.4', 'nova-aktualizace-1-20-4', 'Server byl aktualizován na nejnovější verzi Minecraftu s řadou vylepšení.', 
'<h2>Aktualizace na verzi 1.20.4</h2><p>Dnes jsme úspěšně aktualizovali server na Minecraft 1.20.4!</p><p><strong>Změny:</strong></p><ul><li>Nové bloky a předměty z verze 1.20.4</li><li>Optimalizace výkonu serveru</li><li>Opravy chyb v pluginech</li><li>Nové vlastní itemy v obchodě</li></ul><p>Pokud narazíte na nějaké problémy, neváhejte vytvořit ticket.</p>', 
TRUE, NOW()),

('Pravidla pro PvP zóny', 'pravidla-pro-pvp-zony', 'Přehled pravidel pro hráče, kteří chtějí bojovat v PvP zónách.', 
'<h2>PvP Pravidla</h2><p>PvP je povoleno pouze v označených zónách. Mimo tyto zóny je PvP zakázáno.</p><p><strong>Pravidla:</strong></p><ul><li>Zákaz používání zakázaných modifikací</li><li>Zákaz teamingu ve Free-For-All arenách</li><li>Respektujte ostatní hráče</li></ul>', 
TRUE, NOW());

-- Pravidla serveru
INSERT INTO rules (content) VALUES
('<h1>Pravidla serveru MGame</h1>

<h2>1. Základní pravidla</h2>
<ul>
<li><strong>1.1</strong> Respektujte ostatní hráče a administrátory</li>
<li><strong>1.2</strong> Žádné nadávky, rasismus nebo hate speech</li>
<li><strong>1.3</strong> Zákaz spamování v chatu</li>
<li><strong>1.4</strong> Zákaz reklamy na jiné servery</li>
</ul>

<h2>2. Herní pravidla</h2>
<ul>
<li><strong>2.1</strong> Zákaz griefingu mimo PvP zóny</li>
<li><strong>2.2</strong> Zákaz používání cheatů a neoprávněných modifikací</li>
<li><strong>2.3</strong> Zákaz zneužívání bugů - nahlaste je pomocí ticketu</li>
<li><strong>2.4</strong> PvP je povoleno pouze v označených zónách</li>
</ul>

<h2>3. Ekonomika</h2>
<ul>
<li><strong>3.1</strong> Zákaz scamování ostatních hráčů</li>
<li><strong>3.2</strong> Zákaz zneužívání ekonomického systému</li>
</ul>

<h2>4. Tresty</h2>
<p>Porušení pravidel může vést k:</p>
<ul>
<li>Varování</li>
<li>Dočasný ban (1 den - 30 dní)</li>
<li>Permanentní ban</li>
</ul>

<p><em>Neznalost pravidel neomlouvá jejich porušení.</em></p>');

-- Tickety
INSERT INTO tickets (user_id, subject, category, status, assigned_to) VALUES
(3, 'Nemůžu se přihlásit na server', 'technical', 'open', NULL),
(4, 'Žádost o unban', 'ban_unban', 'in_progress', 2),
(3, 'Bug s teleportací', 'bug_report', 'closed', 2);

-- Zprávy v ticketech
INSERT INTO ticket_messages (ticket_id, user_id, message) VALUES
(1, 3, 'Ahoj, při pokusu o přihlášení se mi zobrazuje "Connection refused". Můžete mi pomoct?'),

(2, 4, 'Byl jsem zabanován za griefing, ale bylo to nedorozumění. Můžu dostat unban?'),
(2, 2, 'Ahoj, prošel jsem tvůj případ. Můžeš popsat situaci detailněji?'),
(2, 4, 'Stavěl jsem na volném pozemku, nevěděl jsem, že patří někomu jinému.'),

(3, 3, 'Po použití /home se mi občas inventář vymaže.'),
(3, 2, 'Díky za report, bug byl opraven v poslední aktualizaci.'),
(3, 3, 'Super, díky moc!');