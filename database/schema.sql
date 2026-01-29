-- ============================================
-- IT Hub Zavidovići - Database Schema
-- ============================================

-- Kreiranje baze podataka
CREATE DATABASE IF NOT EXISTS ithub_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ithub_db;

-- ============================================
-- Tabela: users (Administratori)
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabela: programs (Programi/Kursevi)
-- ============================================
CREATE TABLE IF NOT EXISTS programs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    duration VARCHAR(100),
    level VARCHAR(50) DEFAULT 'Početnik',
    icon VARCHAR(50) DEFAULT 'code',
    featured BOOLEAN DEFAULT FALSE,
    technologies JSON,
    period VARCHAR(255),
    format TEXT,
    participants VARCHAR(255),
    status VARCHAR(50) DEFAULT 'U pripremi',
    full_description TEXT,
    highlights JSON,
    requirements TEXT,
    sort_order INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabela: gallery (Galerija slika)
-- ============================================
CREATE TABLE IF NOT EXISTS gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    image_path VARCHAR(255) NOT NULL,
    thumb_path VARCHAR(255),
    alt_text VARCHAR(255),
    size_class VARCHAR(50) DEFAULT 'normal',
    sort_order INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabela: partners (Partneri/Sponzori)
-- ============================================
CREATE TABLE IF NOT EXISTS partners (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    logo_path VARCHAR(255) NOT NULL,
    website_url VARCHAR(255),
    sort_order INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabela: settings (Postavke sajta)
-- ============================================
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_group VARCHAR(50) DEFAULT 'general',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Početni podaci: Admin korisnik
-- Lozinka: admin123 (promijeniti nakon prvog logina!)
-- ============================================
INSERT INTO users (name, email, password) VALUES
('Administrator', 'admin@ithub.ba', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ============================================
-- Početni podaci: Postavke
-- ============================================
INSERT INTO settings (setting_key, setting_value, setting_group) VALUES
-- Kontakt informacije
('phone', '+387 62 883 250', 'contact'),
('phone_raw', '+38762883250', 'contact'),
('email', 'info@ithub.ba', 'contact'),
('address_street', 'Omladinska 10', 'contact'),
('address_city', 'Zavidovići', 'contact'),
('address_postal', '72220', 'contact'),
('address_country', 'BA', 'contact'),
('facebook_url', 'https://www.facebook.com/ithubzavidovici/', 'contact'),
('whatsapp', 'https://wa.me/38762883250', 'contact'),
('viber', 'viber://chat?number=%2B38762883250', 'contact'),
('website', 'https://ithub.ba/', 'contact'),

-- O nama
('about_description', 'IT Hub Zavidovići je centar za IT edukaciju i inovacije gdje mladi ljudi uče programiranje, dizajn i digitalne vještine. Naša misija je osposobiti novu generaciju IT stručnjaka.', 'about'),
('about_feature_1_title', 'Kvalitetna edukacija', 'about'),
('about_feature_1_desc', 'Moderni kurikulum prilagođen industriji', 'about'),
('about_feature_2_title', 'Iskusni mentori', 'about'),
('about_feature_2_desc', 'Profesionalci sa dugogodišnjim iskustvom', 'about'),
('about_feature_3_title', 'Praktičan rad', 'about'),
('about_feature_3_desc', 'Fokus na realnim projektima', 'about'),
('about_feature_4_title', 'Moderna oprema', 'about'),
('about_feature_4_desc', 'Najnovija tehnologija i alati', 'about'),

-- Statistika
('stat_1_current', '40', 'stats'),
('stat_1_planned', '200', 'stats'),
('stat_1_label', 'Sati edukacije', 'stats'),
('stat_2_current', '6', 'stats'),
('stat_2_planned', '30', 'stats'),
('stat_2_label', 'Polaznika', 'stats'),
('stat_3_current', '1', 'stats'),
('stat_3_planned', '4', 'stats'),
('stat_3_label', 'Programa', 'stats'),
('stat_4_current', '3', 'stats'),
('stat_4_planned', '20', 'stats'),
('stat_4_label', 'Aktivnosti', 'stats');

-- ============================================
-- Početni podaci: Programi (migracija postojećih)
-- ============================================
INSERT INTO programs (title, description, duration, level, icon, featured, technologies, period, format, participants, status, full_description, highlights, requirements, sort_order, active) VALUES
(
    'ZeroToHero SaaS',
    'Od početnika do programera: web development i SaaS tehnologije. Praktičan rad, realni projekti.',
    '40 sati (završen)',
    'Početnik',
    'code',
    TRUE,
    '["html5", "css3", "javascript", "php", "laravel", "mysql"]',
    '04.10.2025. do 20.12.2025.',
    '10 edukacija × 4 sata, uživo u IT HUB Zavidovići',
    '6 polaznika',
    'Završen',
    'Program je vodio polaznike od temeljnih koncepata web developmenta do naprednih tehnika izgradnje SaaS aplikacija. Fokus je bio na praktičnom radu sa realnim projektima.',
    '["Praktičan rad na realnim projektima", "Mentorstvo profesionalnog developera", "Portfolio projekat na kraju kursa", "Certifikat o završetku programa", "Pristup zajednici polaznika"]',
    NULL,
    1,
    TRUE
),
(
    'Awaken The Giant Within',
    'AI revolucija: naučite koristiti ChatGPT, Claude i druge AI alate za produktivnost i kreativnost.',
    '12 sati (završen)',
    'Početnik',
    'brain',
    FALSE,
    '["chatgpt", "claude", "midjourney"]',
    '23.11.2025. do 14.12.2025.',
    '4 edukacije × 3 sata, uživo u IT HUB Zavidovići',
    '5 polaznika, uz mogućnost dodatnih prijava',
    'Završen',
    'Intenzivni program koji vas uvodi u svijet umjetne inteligencije. Naučite kako efikasno koristiti AI alate za svakodnevne zadatke.',
    '["Praktični primjeri korištenja AI", "Hands-on radionice", "Kreiranje AI workflow-a", "Certifikat o završetku"]',
    NULL,
    2,
    TRUE
),
(
    'Office Mastery',
    'Profesionalna obuka za Microsoft Office paket: Word, Excel, PowerPoint za poslovnu primjenu.',
    '20 sati',
    'Početnik',
    'file-text',
    FALSE,
    '["word", "excel", "powerpoint"]',
    'Uskoro',
    '5 edukacija × 4 sata',
    'Prijave u toku',
    'Uskoro',
    'Kompletna obuka za Microsoft Office paket sa fokusom na poslovnu primjenu i produktivnost.',
    '["Praktične vježbe", "Poslovni dokumenti", "Napredne Excel formule", "Profesionalne prezentacije"]',
    'Osnovno poznavanje rada na računaru',
    3,
    TRUE
),
(
    'Print Your Vision',
    '3D modeliranje i printanje: od ideje do fizičkog proizvoda. Naučite kreirati i printati 3D modele.',
    '15 sati',
    'Početnik',
    'box',
    FALSE,
    '["blender", "fusion360", "3dprint"]',
    'U pripremi',
    '5 edukacija × 3 sata',
    'Ograničen broj mjesta',
    'U pripremi',
    'Uvod u svijet 3D modeliranja i printanja. Od osnovnih koncepata do kreiranja vlastitih 3D modela.',
    '["Osnove 3D modeliranja", "Rad sa 3D printerom", "Vlastiti projekat", "Praktične vježbe"]',
    'Nije potrebno prethodno iskustvo',
    4,
    TRUE
);

-- ============================================
-- Početni podaci: Partneri
-- ============================================
INSERT INTO partners (name, logo_path, website_url, sort_order, active) VALUES
('Zeničko-dobojski kanton', 'images/partners/ze-do-kanton.png', 'https://zdk.ba/', 1, TRUE),
('Općina Zavidovići', 'images/partners/opcina-zavidovici.png', 'https://zavidovici.ba/', 2, TRUE),
('JU Centar za kulturu i informisanje', 'images/partners/cki-zavidovici.png', 'https://ckizavidovici.ba/', 3, TRUE),
('Omladinski centar \"Pod istim suncem\"', 'images/partners/oc-pis.png', 'https://ocpis.org/', 4, TRUE),
('Udruženje Kult', 'images/partners/udruzenje-kult.png', 'https://mladi.org/', 5, TRUE),
('Mozaik fondacija', 'images/partners/mozaik.png', 'https://mozaik.ba/', 6, TRUE);

-- ============================================
-- Početni podaci: Galerija
-- ============================================
INSERT INTO gallery (image_path, thumb_path, alt_text, size_class, sort_order, active) VALUES
('images/gallery/gallery-4.jpg', 'images/gallery/thumbs/gallery-4-thumb.jpg', 'Glavni radni prostor IT Hub centra sa modernom opremom', 'large', 1, TRUE),
('images/gallery/gallery-1.jpg', 'images/gallery/thumbs/gallery-1-thumb.jpg', 'Polaznici na radionici web developmenta', 'normal', 2, TRUE),
('images/gallery/gallery-2.jpg', 'images/gallery/thumbs/gallery-2-thumb.jpg', 'Prezentacija završnih projekata', 'normal', 3, TRUE),
('images/gallery/gallery-3.jpg', 'images/gallery/thumbs/gallery-3-thumb.jpg', 'Tim IT Hub Zavidovići', 'normal', 4, TRUE),
('images/gallery/gallery-5.jpg', 'images/gallery/thumbs/gallery-5-thumb.jpg', 'Networking event u IT Hub-u', 'normal', 5, TRUE);
