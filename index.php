<?php
/**
 * IT Hub Zavidovići - Homepage
 * Vanilla PHP verzija
 */

// Include core files
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Program.php";
require_once __DIR__ . "/includes/models/Gallery.php";
require_once __DIR__ . "/includes/models/Partner.php";
require_once __DIR__ . "/includes/models/Setting.php";

// Fetch data
try {
    $programs = getProgramsFromDatabase();
    $stats = Setting::getStats();
    $partners = Partner::all(true);
    $gallery = Gallery::all(true);
} catch (Exception $e) {
    $programs = getDefaultPrograms();
    $stats = getDefaultStats();
    $partners = [];
    $gallery = [];
}

$title = "IT Hub Zavidovići | Centar za IT edukaciju i inovacije";
$meta_description = "IT Hub Zavidovići - Centar za IT edukaciju i inovacije";

function getProgramsFromDatabase()
{
    $dbPrograms = Program::allWithDecoded(true);
    $programs = [];
    foreach ($dbPrograms as $p) {
        $programs[] = [
            "title" => $p["title"],
            "description" => $p["description"],
            "duration" => $p["duration"],
            "level" => $p["level"],
            "level_class" => $p["level"] === "Srednji" ? "intermediate" : "",
            "icon" => $p["icon"],
            "techs" => $p["technologies"],
            "featured" => (bool) $p["featured"],
            "registration_url" => $p["registration_url"] ?? "",
            "details" => [
                "period" => $p["period"],
                "format" => $p["format"],
                "polaznici" => $p["participants"],
                "status" => $p["status"],
                "opis" => $p["full_description"],
                "highlights" => $p["highlights"],
                "uslovi" => $p["requirements"],
            ],
        ];
    }
    return $programs ?: getDefaultPrograms();
}

function getDefaultStats()
{
    return [
        ["current" => 40, "planned" => 200, "label" => "Sati edukacije"],
        ["current" => 6, "planned" => 30, "label" => "Polaznika"],
        ["current" => 1, "planned" => 4, "label" => "Programa"],
        ["current" => 3, "planned" => 20, "label" => "Aktivnosti"],
    ];
}

function getDefaultPrograms()
{
    return [
        [
            "title" => "ZeroToHero SaaS",
            "description" =>
                "Od početnika do programera: web development i SaaS tehnologije.",
            "duration" => "40 sati (završen)",
            "level" => "Početnik",
            "level_class" => "",
            "icon" => "code",
            "techs" => [
                "html5",
                "css3",
                "javascript",
                "php",
                "laravel",
                "mysql",
            ],
            "featured" => true,
            "details" => [
                "period" => "04.10.2025. do 20.12.2025.",
                "format" => "10 edukacija × 4 sata, uživo u IT HUB Zavidovići",
                "polaznici" => "6 polaznika",
                "status" => "Završen",
                "opis" =>
                    "Program je vodio polaznike od temeljnih koncepata računarstva do samostalnog rada na stvarnim projektima.",
                "highlights" => [
                    "Praktičan rad na realnim projektima",
                    "Mentorstvo profesionalnog developera",
                    "Od potpunog početnika do samostalnog rada",
                    "Besplatna edukacija",
                ],
            ],
        ],
    ];
}
?>
<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e(
        $meta_description ??
            "IT Hub Zavidovići - Centar za IT edukaciju i inovacije u Zavidovićima. Programi, radionice i kursevi iz programiranja, dizajna i digitalnih vještina.",
    ) ?>">
    <meta name="keywords" content="IT Hub, Zavidovići, programiranje, edukacija, kursevi, radionice, web development, dizajn, digitalne vještine, BiH">
    <meta name="author" content="IT Hub Zavidovići">
    <meta name="robots" content="index, follow">
    <meta name="geo.region" content="BA-ZE">
    <meta name="geo.placename" content="Zavidovići">

    <!-- Canonical URL -->
    <link rel="canonical" href="https://it-hub.ba/">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://it-hub.ba/">
    <meta property="og:title" content="<?= e($title ?? "IT Hub Zavidovići") ?>">
    <meta property="og:description" content="<?= e(
        $meta_description ??
            "IT Hub Zavidovići - Centar za IT edukaciju i inovacije u Zavidovićima.",
    ) ?>">
    <meta property="og:image" content="https://it-hub.ba/public/images/og-image.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="bs_BA">
    <meta property="og:site_name" content="IT Hub Zavidovići">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://it-hub.ba/">
    <meta name="twitter:title" content="<?= e(
        $title ?? "IT Hub Zavidovići",
    ) ?>">
    <meta name="twitter:description" content="<?= e(
        $meta_description ??
            "IT Hub Zavidovići - Centar za IT edukaciju i inovacije u Zavidovićima.",
    ) ?>">
    <meta name="twitter:image" content="https://it-hub.ba/public/images/og-image.jpg">

    <!-- Favicon -->
    <link rel="icon" href="<?= asset(
        "images/favicon.svg",
    ) ?>?v=3" type="image/svg+xml">
    <link rel="apple-touch-icon" href="<?= asset(
        "images/apple-touch-icon.png",
    ) ?>">

    <!-- Theme Color -->
    <meta name="theme-color" content="#6366f1">
    <meta name="msapplication-TileColor" content="#6366f1">

    <title><?= e($title ?? "IT Hub Zavidovići") ?></title>

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "IT Hub Zavidovići",
        "alternateName": "ITHub Zavidovići",
        "url": "https://ithub.ba/",
        "logo": "https://ithub.ba/public/images/logo-icon.svg",
        "image": "https://ithub.ba/public/images/og-image.jpg",
        "description": "Centar za IT edukaciju i inovacije u Zavidovićima. Programi, radionice i kursevi iz programiranja, dizajna i digitalnih vještina.",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Omladinska 10",
            "addressLocality": "Zavidovići",
            "postalCode": "72220",
            "addressCountry": "BA"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+387-62-883-250",
            "contactType": "customer service",
            "availableLanguage": ["Bosnian", "Croatian", "Serbian"]
        },
        "sameAs": [
            "https://www.facebook.com/ithubzavidovici/"
        ],
        "foundingDate": "2025-09-15",
        "areaServed": {
            "@type": "City",
            "name": "Zavidovići"
        }
    }
    </script>
    <!-- Preconnect to Google Fonts for faster loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="<?= asset("css/style.css") ?>">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Skip to Content (Accessibility) -->
    <a href="#main-content" class="skip-to-content">Preskoči na sadržaj</a>

    <!-- Cursor Glow Effect -->
    <div class="cursor-glow"></div>

    <!-- Grid Background -->
    <div class="grid-background"></div>

    <!-- Floating Particles -->
    <div class="particles" id="particles"></div>

    <!-- Navigation -->
<nav class="navbar" id="navbar">
    <div class="nav-container">
        <a href="<?= url("/") ?>" class="logo">
            <img src="<?= asset(
                "images/logo-icon.svg",
            ) ?>" alt="IT Hub" class="logo-icon">
            <span class="logo-text">IT Hub Zavidovići</span>
        </a>
        <ul class="nav-links" id="navLinks">
            <li><a href="#home" class="nav-link active">Početna</a></li>
            <li><a href="#about" class="nav-link">O nama</a></li>
            <li><a href="#tech" class="nav-link">Tehnologije</a></li>
            <li><a href="#programs" class="nav-link">Programi</a></li>
            <li><a href="#gallery" class="nav-link">Galerija</a></li>
            <li><a href="#contact" class="nav-link">Kontakt</a></li>
        </ul>
        <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Otvori meni" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</nav>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay">
    <div class="mobile-menu-panel">
        <div class="mobile-menu-header">
            <span class="mobile-menu-title">Navigacija</span>
            <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Zatvori meni">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <ul class="mobile-menu-links">
            <li><a href="#home" class="mobile-menu-link">Početna</a></li>
            <li><a href="#about" class="mobile-menu-link">O nama</a></li>
            <li><a href="#tech" class="mobile-menu-link">Tehnologije</a></li>
            <li><a href="#programs" class="mobile-menu-link">Programi</a></li>
            <li><a href="#gallery" class="mobile-menu-link">Galerija</a></li>
            <li><a href="#contact" class="mobile-menu-link">Kontakt</a></li>
        </ul>
    </div>
</div>


    <main id="main-content">


<!-- Hero Section -->
<section class="hero" id="home">
    <div class="hero-content">
        <div class="hero-text">
            <div class="badge animate-float">
                <span class="badge-dot"></span>
                Prijave otvorene za nove programe
            </div>
            <h1 class="hero-title">
                <span class="title-line">IT zajednica</span>
                <span class="title-line gradient-text">Zavidovića</span>
                <span class="title-line">počinje ovdje</span>
            </h1>
            <p class="hero-description">
                Više ne moraš napuštati Zavidoviće da bi učio programiranje.
                Budućnost IT-a u našem gradu počinje upravo ovdje.
            </p>
            <div class="hero-buttons">
                <a href="#programs" class="btn btn-primary">
                    <span>Istraži programe</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#contact" class="btn btn-secondary">
                    <span>Kontaktiraj nas</span>
                </a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="code-window">
    <div class="code-header">
        <div class="code-dots">
            <span class="dot red"></span>
            <span class="dot yellow"></span>
            <span class="dot green"></span>
        </div>
        <span class="code-filename">it-hub.js</span>
    </div>
    <div class="code-body">
        <pre><code id="typed-code"></code><span class="cursor-blink">|</span></pre>
    </div>
</div>


        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats">
    <div class="stats-header">
        <span class="stats-year"><?= date("Y") ?></span>
    </div>
    <div class="stats-container">
        <?php foreach ($stats as $stat): ?>
        <div class="stat-item">
            <div class="stat-numbers">
                <span class="stat-current" data-count="<?= $stat[
                    "current"
                ] ?>">0</span>
                <span class="stat-separator">/</span>
                <span class="stat-planned"><?= $stat["planned"] ?></span>
            </div>
            <div class="stat-bar">
                <div class="stat-bar-fill" data-width="<?= round(
                    ($stat["current"] / $stat["planned"]) * 100,
                ) ?>"></div>
            </div>
            <span class="stat-label"><?= e($stat["label"]) ?></span>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="stats-legend">
        * Realizovano / Planirano za <?= date("Y") ?>. godinu
    </div>
</section>

<!-- About Section -->
<!-- About Section -->
<section class="about" id="about">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">O nama</span>
            <h2 class="section-title">Gradimo digitalnu budućnost <span class="gradient-text">zajedno</span></h2>
        </div>
        <div class="about-content">
            <div class="about-text">
                <p class="about-description">
                    IT Hub Zavidovići je centar za IT edukaciju i inovacije gdje mladi ljudi mogu
                    naučiti programiranje, web dizajn, i druge digitalne vještine. Zadatak IT Hub-a
                    je osposobiti novu generaciju tech stručnjaka koji će graditi i
                    unapređivati naš grad, naše Zavidoviće.
                </p>
                <div class="about-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4>Kvalitetna edukacija</h4>
                            <p>Moderni kurikulum prilagođen industriji</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4>Male grupe</h4>
                            <p>Individualni pristup svakom polazniku</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                                <polyline points="2 17 12 22 22 17"/>
                                <polyline points="2 12 12 17 22 12"/>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4>Realni projekti</h4>
                            <p>100% praktičan rad na stvarnim projektima</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 20V10"/>
                                <path d="M12 20V4"/>
                                <path d="M6 20v-6"/>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4>Od početnika do programera</h4>
                            <p>Postupno učenje bez predznanja</p>
                        </div>
                    </div>
                </div>
                <a href="#programs" class="btn btn-primary">Saznaj više</a>
            </div>
            <div class="about-visual">
                <div class="terminal-window">
                    <div class="terminal-header">
                        <div class="terminal-dots">
                            <span></span><span></span><span></span>
                        </div>
                        <span class="terminal-title">terminal</span>
                    </div>
                    <div class="terminal-body" id="terminal-typed"></div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Tech Stack Section -->
<section class="tech-stack" id="tech">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Tech Stack</span>
            <h2 class="section-title">Tehnologije koje <span class="gradient-text">učimo</span></h2>
            <p class="section-subtitle">Moderne tehnologije i alati koji se traže na tržištu</p>
        </div>
        <div class="tech-stack-grid">
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/html5-original.svg",
                    ) ?>" alt="HTML5">
                </div>
                <div class="tech-stack-info">
                    <h3>HTML5</h3>
                    <p>Temelj svakog websajta. Definira strukturu i sadržaj web stranica: naslove, paragrafe, slike, linkove i forme.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/css3-original.svg",
                    ) ?>" alt="CSS3">
                </div>
                <div class="tech-stack-info">
                    <h3>CSS3</h3>
                    <p>Stilizacija i dizajn. Boje, fontovi, raspored elemenata, animacije i sve što čini stranicu vizualno privlačnom.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/javascript-original.svg",
                    ) ?>" alt="JavaScript">
                </div>
                <div class="tech-stack-info">
                    <h3>JavaScript</h3>
                    <p>Programski jezik weba. Dodaje interaktivnost: klikovi, animacije, validacija formi, dinamički sadržaj.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/react-original.svg",
                    ) ?>" alt="React">
                </div>
                <div class="tech-stack-info">
                    <h3>React</h3>
                    <p>Najpopularniji frontend framework. Koriste ga Facebook, Instagram, Netflix za izradu brzih i modernih aplikacija.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/nextjs-original.svg",
                    ) ?>" alt="Next.js">
                </div>
                <div class="tech-stack-info">
                    <h3>Next.js</h3>
                    <p>React framework za produkciju. Server-side rendering, optimizacija performansi i SEO, sve uključeno.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/nodejs-original.svg",
                    ) ?>" alt="Node.js">
                </div>
                <div class="tech-stack-info">
                    <h3>Node.js</h3>
                    <p>JavaScript na serveru. Omogućava pisanje backend koda u istom jeziku kao frontend, full-stack JS development.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/python-original.svg",
                    ) ?>" alt="Python">
                </div>
                <div class="tech-stack-info">
                    <h3>Python</h3>
                    <p>Najlakši jezik za početnike. Koristi se za web development, data science, AI, automatizaciju i još mnogo toga.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/php-original.svg",
                    ) ?>" alt="PHP">
                </div>
                <div class="tech-stack-info">
                    <h3>PHP</h3>
                    <p>Backend jezik koji pokreće 80% weba. WordPress, Facebook, Wikipedia, svi koriste PHP.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/laravel-original.svg",
                    ) ?>" alt="Laravel">
                </div>
                <div class="tech-stack-info">
                    <h3>Laravel</h3>
                    <p>Elegantni PHP framework. MVC arhitektura, Eloquent ORM, Blade templating za profesionalni web development.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/mysql-original.svg",
                    ) ?>" alt="MySQL">
                </div>
                <div class="tech-stack-info">
                    <h3>MySQL</h3>
                    <p>Najpopularnija baza podataka. Čuva i organizira podatke: korisnike, proizvode, narudžbe, sve što aplikacija treba.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/git-original.svg",
                    ) ?>" alt="Git">
                </div>
                <div class="tech-stack-info">
                    <h3>Git</h3>
                    <p>Version control sistem. Prati sve izmjene u kodu, omogućava timski rad i vraćanje na prethodne verzije.</p>
                </div>
            </div>
            <div class="tech-stack-card claude-card">
                <div class="tech-stack-icon claude-icon-wrap">
                    <img src="<?= asset(
                        "images/technology/claude.png",
                    ) ?>" alt="Claude Code">
                </div>
                <div class="tech-stack-info">
                    <h3>Claude Code</h3>
                    <p>AI asistent za programiranje. Pomaže pisati, debugirati i objašnjavati kod. Tvoj 24/7 mentor i pair programming partner.</p>
                </div>
            </div>
        </div>

        <!-- Creative & Office Tools -->
        <div class="tech-stack-divider">
            <span>Kreativni alati i Office</span>
        </div>
        <div class="tech-stack-grid">
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/windows8-original.svg",
                    ) ?>" alt="Windows">
                </div>
                <div class="tech-stack-info">
                    <h3>Windows</h3>
                    <p>Osnove rada na računaru. Organizacija fajlova, instalacija programa, sistemske postavke i produktivnost.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/word.png",
                    ) ?>" alt="Microsoft Word">
                </div>
                <div class="tech-stack-info">
                    <h3>Microsoft Word</h3>
                    <p>Profesionalna obrada teksta. Dokumenti, pisma, izvještaji, formatiranje i stilovi.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/excel.png",
                    ) ?>" alt="Microsoft Excel">
                </div>
                <div class="tech-stack-info">
                    <h3>Microsoft Excel</h3>
                    <p>Tabele i analize. Formule, funkcije, grafikoni, pivot tabele i upravljanje podacima.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/powerpoint.png",
                    ) ?>" alt="Microsoft PowerPoint">
                </div>
                <div class="tech-stack-info">
                    <h3>Microsoft PowerPoint</h3>
                    <p>Profesionalne prezentacije. Slajdovi, animacije, prijelazi i vizualno predstavljanje ideja.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/blender-original.svg",
                    ) ?>" alt="Blender">
                </div>
                <div class="tech-stack-info">
                    <h3>Blender</h3>
                    <p>Profesionalni 3D softver. Modeliranje, animacija, rendering. Od ideje do gotovog 3D modela.</p>
                </div>
            </div>
            <div class="tech-stack-card">
                <div class="tech-stack-icon">
                    <img src="<?= asset(
                        "images/technology/bambu-logo.svg",
                    ) ?>" alt="Bambu Studio">
                </div>
                <div class="tech-stack-info">
                    <h3>Bambu Studio</h3>
                    <p>Slicer za 3D printanje. Priprema modela, podešavanje parametara i slanje na Bambu Lab printere.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs Section -->
<section class="programs" id="programs">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Programi</span>
            <h2 class="section-title">Edukacijski <span class="gradient-text">programi</span></h2>
            <p class="section-subtitle">Izaberi program koji odgovara tvojim ciljevima i započni svoje IT putovanje</p>
        </div>
        <div class="programs-grid" id="programsGrid">
            <?php foreach ($programs as $index => $program): ?>
            <div class="program-card-wrapper<?= $index >= 3
                ? " hidden-program"
                : "" ?>">
            <div class="program-card<?= isset($program["featured"]) &&
            $program["featured"]
                ? " featured"
                : "" ?>" role="button" tabindex="0">
    <div class="program-card-header">
        <div class="program-icon">
            <?php if ($program["icon"] === "code"): ?>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="16 18 22 12 16 6"/>
                <polyline points="8 6 2 12 8 18"/>
            </svg>
            <?php elseif ($program["icon"] === "design"): ?>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 19l7-7 3 3-7 7-3-3z"/>
                <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/>
                <path d="M2 2l7.586 7.586"/>
                <circle cx="11" cy="11" r="2"/>
            </svg>
            <?php else: ?>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                <path d="M2 17l10 5 10-5"/>
                <path d="M2 12l10 5 10-5"/>
            </svg>
            <?php endif; ?>
        </div>
        <div class="program-badge<?= $program["level_class"]
            ? " " . $program["level_class"]
            : "" ?>"><?= e($program["level"]) ?></div>
        <h3 class="program-title"><?= e($program["title"]) ?></h3>
        <p class="program-description"><?= e($program["description"]) ?></p>
        <div class="program-meta">
            <span class="program-duration">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                <?= e($program["duration"]) ?>
            </span>
        </div>
        <div class="program-techs">
            <?php foreach ($program["techs"] as $tech): ?>
                <?php if ($tech === "claude"): ?>
                    <img src="<?= asset(
                        "images/technology/claude.png",
                    ) ?>" alt="Claude" class="tech-claude">
                <?php elseif ($tech === "laravel"): ?>
                    <img src="<?= asset(
                        "images/technology/laravel-original.svg",
                    ) ?>" alt="Laravel">
                <?php elseif ($tech === "windows8"): ?>
                    <img src="<?= asset(
                        "images/technology/windows8-original.svg",
                    ) ?>" alt="Windows">
                <?php elseif ($tech === "word"): ?>
                    <img src="<?= asset(
                        "images/technology/word.png",
                    ) ?>" alt="MS Word">
                <?php elseif ($tech === "excel"): ?>
                    <img src="<?= asset(
                        "images/technology/excel.png",
                    ) ?>" alt="MS Excel">
                <?php elseif ($tech === "powerpoint"): ?>
                    <img src="<?= asset(
                        "images/technology/powerpoint.png",
                    ) ?>" alt="PowerPoint">
                <?php elseif ($tech === "blender"): ?>
                    <img src="<?= asset(
                        "images/technology/blender-original.svg",
                    ) ?>" alt="Blender">
                <?php elseif ($tech === "bambu"): ?>
                    <img src="<?= asset(
                        "images/technology/bambu-logo.svg",
                    ) ?>" alt="Bambu Studio">
                <?php else: ?>
                    <img src="<?= asset(
                        "images/technology/" . e($tech) . "-original.svg",
                    ) ?>" alt="<?= ucfirst(e($tech)) ?>">
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <button type="button" class="program-toggle">
            <span class="toggle-text">Saznaj više</span>
            <svg class="toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </button>
    </div>

    <?php if (isset($program["details"])): ?>
    <div class="program-details">
        <div class="program-details-content">
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Period</span>
                    <span class="detail-value"><?= e(
                        $program["details"]["period"],
                    ) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Format</span>
                    <span class="detail-value"><?= e(
                        $program["details"]["format"],
                    ) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Polaznici</span>
                    <span class="detail-value"><?= e(
                        $program["details"]["polaznici"],
                    ) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value detail-status"><?= e(
                        $program["details"]["status"],
                    ) ?></span>
                </div>
            </div>

            <div class="details-description">
                <p><?= e($program["details"]["opis"]) ?></p>
            </div>

            <?php if (isset($program["details"]["highlights"])): ?>
            <div class="details-highlights">
                <h4>Šta dobijate</h4>
                <ul>
                    <?php foreach (
                        $program["details"]["highlights"]
                        as $highlight
                    ): ?>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <?= e($highlight) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if (!empty($program["details"]["uslovi"])): ?>
            <div class="details-requirements">
                <h4>Uslovi</h4>
                <p><?= e($program["details"]["uslovi"]) ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($program["registration_url"])): ?>
            <a href="<?= e(
                $program["registration_url"],
            ) ?>" class="btn btn-primary program-apply-btn">
                Prijavi se
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
            <?php else: ?>
            <a href="#contact" class="btn btn-primary program-apply-btn">
                Kontaktiraj nas
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

            </div>
            <?php endforeach; ?>
        </div>
        <div class="programs-cta">
            <button type="button" class="btn btn-primary" id="toggleProgramsBtn">
                <span class="btn-text">Prikaži sve programe</span>
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<!-- Gallery Section -->
<section class="gallery" id="gallery">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Galerija</span>
            <h2 class="section-title">Pogledajte naše <span class="gradient-text">prostore</span></h2>
        </div>
        <div class="gallery-grid">
            <?php if (!empty($gallery)): ?>
                <?php foreach ($gallery as $image): ?>
                <div class="gallery-item <?= $image["size_class"] === "large"
                    ? "large"
                    : "" ?>"
                     data-lightbox
                     data-full="<?= asset($image["image_path"]) ?>">
                    <img src="<?= asset(
                        $image["thumb_path"] ?: $image["image_path"],
                    ) ?>"
                         alt="<?= e($image["alt_text"]) ?>"
                         loading="lazy">
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback ako nema slika u bazi -->
                <div class="gallery-item large" data-lightbox data-full="<?= asset(
                    "images/gallery-4.jpg",
                ) ?>">
                    <img src="<?= asset(
                        "images/thumbs/gallery-4-thumb.jpg",
                    ) ?>" alt="Glavni radni prostor IT Hub centra" loading="lazy">
                </div>
                <div class="gallery-item" data-lightbox data-full="<?= asset(
                    "images/gallery-2.jpg",
                ) ?>">
                    <img src="<?= asset(
                        "images/thumbs/gallery-2-thumb.jpg",
                    ) ?>" alt="Prostor za edukaciju" loading="lazy">
                </div>
                <div class="gallery-item" data-lightbox data-full="<?= asset(
                    "images/gallery-3.jpg",
                ) ?>">
                    <img src="<?= asset(
                        "images/thumbs/gallery-3-thumb.jpg",
                    ) ?>" alt="Radne stanice" loading="lazy">
                </div>
                <div class="gallery-item" data-lightbox data-full="<?= asset(
                    "images/gallery-5.jpg",
                ) ?>">
                    <img src="<?= asset(
                        "images/thumbs/gallery-5-thumb.jpg",
                    ) ?>" alt="Prostor za prezentacije" loading="lazy">
                </div>
                <div class="gallery-item" data-lightbox data-full="<?= asset(
                    "images/gallery-1.jpg",
                ) ?>">
                    <img src="<?= asset(
                        "images/thumbs/gallery-1-thumb.jpg",
                    ) ?>" alt="Ulazni prostor IT Hub" loading="lazy">
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Lightbox -->
<div class="lightbox" id="lightbox" aria-hidden="true">
    <button class="lightbox-close" aria-label="Zatvori galeriju">&times;</button>
    <button class="lightbox-prev" aria-label="Prethodna slika">&#10094;</button>
    <button class="lightbox-next" aria-label="Sljedeća slika">&#10095;</button>
    <div class="lightbox-content">
        <div class="lightbox-loader">
            <div class="lightbox-spinner"></div>
        </div>
        <img src="" alt="Slika iz galerije IT Hub Zavidovići" class="lightbox-image">
    </div>
</div>


<!-- Partners Section -->
<!-- Partners Section -->
<section class="partners" id="partners">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Partneri</span>
            <h2 class="section-title">Naši <span class="gradient-text">partneri</span></h2>
        </div>
        <div class="partners-grid">
            <?php if (!empty($partners)): ?>
                <?php foreach ($partners as $partner): ?>
                <a href="<?= e(
                    $partner["website_url"] ?? "#",
                ) ?>" target="_blank" rel="noopener noreferrer" class="partner-logo">
                    <img src="<?= asset($partner["logo_path"]) ?>" alt="<?= e(
    $partner["name"],
) ?>">
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback ako nema partnera u bazi -->
                <a href="https://zdk.ba/" target="_blank" rel="noopener noreferrer" class="partner-logo">
                    <img src="<?= asset(
                        "images/partners/ze-do-kanton.png",
                    ) ?>" alt="Zeničko dobojski kanton">
                </a>
                <a href="https://zavidovici.ba/" target="_blank" rel="noopener noreferrer" class="partner-logo">
                    <img src="<?= asset(
                        "images/partners/grad-zavidovici.png",
                    ) ?>" alt="Grad Zavidovići">
                </a>
                <a href="https://rez.ba/" target="_blank" rel="noopener noreferrer" class="partner-logo">
                    <img src="<?= asset(
                        "images/partners/rez.png",
                    ) ?>" alt="REZ">
                </a>
                <a href="https://lda-zavidovici.org/" target="_blank" rel="noopener noreferrer" class="partner-logo">
                    <img src="<?= asset(
                        "images/partners/ald.png",
                    ) ?>" alt="ALD">
                </a>
                <a href="https://www.facebook.com/profile.php?id=100068365270277" target="_blank" rel="noopener noreferrer" class="partner-logo">
                    <img src="<?= asset(
                        "images/partners/cozy-hub.png",
                    ) ?>" alt="Cozy Hub">
                </a>
                <a href="https://www.zencode.ba/" target="_blank" rel="noopener noreferrer" class="partner-logo">
                    <img src="<?= asset(
                        "images/partners/zc-hub.png",
                    ) ?>" alt="ZenCode">
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>


<!-- CTA Section -->
<!-- CTA Section -->
<section class="cta">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Budućnost Zavidovića <span class="gradient-text">počinje ovdje</span></h2>
            <p class="cta-text">
                Budi dio priče koja mijenja naš grad. Učimo, gradimo i rastemo zajedno.
            </p>
            <div class="cta-buttons">
                <a href="#contact" class="btn btn-primary btn-lg">Započni sada</a>
            </div>
        </div>
        <div class="cta-decoration">
            <div class="cta-circle"></div>
            <div class="cta-circle"></div>
            <div class="cta-circle"></div>
        </div>
    </div>
</section>


<!-- Contact Section -->
<!-- Contact Section -->
<section class="contact" id="contact">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Kontakt</span>
            <h2 class="section-title">Javite nam se</h2>
            <p class="section-subtitle">Imate pitanja? Kontaktirajte nas putem telefona ili društvenih mreža.</p>
        </div>
        <div class="contact-content contact-content-centered">
            <div class="contact-info">
                <div class="contact-phone-big">
                    <a href="tel:+38762883250">+387 62 883 250</a>
                </div>
                <div class="contact-buttons">
                    <a href="viber://chat?number=%2B38762883250" class="contact-action-btn viber" aria-label="Kontaktirajte nas putem Vibera">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.4 0C9.473.028 5.333.344 3.02 2.467 1.302 4.187.696 6.7.633 9.817.57 12.933.488 18.776 6.12 20.36h.003l-.004 2.416s-.037.977.61 1.177c.777.242 1.234-.5 1.98-1.302.407-.44.972-1.084 1.397-1.58 3.85.326 6.812-.416 7.15-.525.776-.252 5.176-.816 5.892-6.657.74-6.02-.36-9.83-2.34-11.546-.596-.55-3.006-2.3-8.375-2.323 0 0-.395-.025-1.037-.017zm.058 1.693c.545-.004.88.017.88.017 4.542.02 6.717 1.388 7.222 1.846 1.675 1.435 2.53 4.868 1.906 9.897v.002c-.604 4.878-4.174 5.184-4.832 5.395-.28.09-2.882.737-6.153.524 0 0-2.436 2.94-3.197 3.704-.12.12-.26.167-.352.144-.13-.033-.166-.188-.165-.414l.02-4.018c-4.762-1.32-4.485-6.292-4.43-8.895.054-2.604.543-4.738 1.996-6.173 1.96-1.773 5.474-2.018 7.11-2.03zm.38 2.602c-.167 0-.303.135-.304.302 0 .167.133.303.3.305 1.624.01 2.946.537 4.028 1.592 1.073 1.046 1.62 2.468 1.633 4.334.002.167.14.3.307.3.166-.002.3-.138.3-.304-.014-1.984-.618-3.596-1.816-4.764-1.19-1.16-2.692-1.753-4.447-1.765zm-3.96.695c-.19-.032-.4.005-.616.117l-.01.002c-.43.247-.816.562-1.146.932-.002.004-.006.004-.008.008-.267.323-.42.638-.46.948-.008.046-.01.093-.007.14 0 .136.022.27.065.4l.013.01c.135.48.473 1.276 1.205 2.604.42.768.903 1.5 1.446 2.186.27.344.56.673.87.984l.132.132c.31.308.64.6.984.87.686.543 1.418 1.027 2.186 1.447 1.328.733 2.126 1.07 2.604 1.206l.01.014c.13.042.265.064.402.063.046.002.092 0 .138-.008.31-.036.627-.19.948-.46.004 0 .003-.002.008-.005.37-.33.683-.72.93-1.148l.003-.01c.225-.432.15-.842-.18-1.12-.004 0-.698-.58-1.037-.83-.36-.255-.73-.492-1.113-.71-.51-.285-1.032-.106-1.248.174l-.447.564c-.23.283-.657.246-.657.246-3.12-.796-3.955-3.955-3.955-3.955s-.037-.426.248-.656l.563-.448c.277-.215.456-.737.17-1.248-.217-.383-.454-.756-.71-1.115-.25-.34-.826-1.033-.83-1.035-.137-.165-.31-.265-.502-.297zm4.49.88c-.158.002-.29.124-.3.282-.01.167.115.312.282.324 1.16.085 2.017.466 2.645 1.15.63.688.93 1.524.906 2.57-.002.168.13.306.3.31.166.003.305-.13.31-.297.025-1.175-.334-2.193-1.067-2.994-.74-.81-1.777-1.253-3.05-1.346h-.024zm.463 1.63c-.16.002-.29.127-.3.287-.008.167.12.31.288.32.523.028.875.175 1.113.422.24.245.388.62.416 1.164.01.167.15.295.318.287.167-.008.295-.15.287-.317-.03-.644-.215-1.178-.58-1.557-.367-.378-.893-.574-1.52-.607h-.018z"/></svg>
                        <span>Viber</span>
                    </a>
                    <a href="https://wa.me/38762883250" class="contact-action-btn whatsapp" target="_blank" aria-label="Kontaktirajte nas putem WhatsAppa">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <span>WhatsApp</span>
                    </a>
                    <a href="tel:+38762883250" class="contact-action-btn phone" aria-label="Pozovite nas na telefon">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 00-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"/></svg>
                        <span>Pozovi</span>
                    </a>
                </div>
                <div class="contact-location">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <span>Omladinska 10, Zavidovići</span>
                </div>
                <div class="contact-social">
                    <a href="https://www.facebook.com/ithubzavidovici/" class="social-link-big" aria-label="Facebook" target="_blank">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                        </svg>
                        <span>Pratite nas na Facebooku</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>




    </main>

    <!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <a href="<?= url("/") ?>" class="logo">
                    <img src="<?= asset(
                        "images/logo-icon.svg",
                    ) ?>" alt="IT Hub Zavidovići" class="logo-icon">
                    <span class="logo-text">IT Hub Zavidovići</span>
                </a>
                <p class="footer-about">
                    Centar za IT edukaciju i inovacije u Zavidovićima.
                    Zajedno gradimo digitalnu budućnost kroz moderne tehnologije i kreativnost.
                </p>
            </div>
            <div class="footer-links">
                <div class="footer-column">
                    <h4>Navigacija</h4>
                    <ul>
                        <li><a href="#home">Početna</a></li>
                        <li><a href="#about">O nama</a></li>
                        <li><a href="#tech">Tehnologije</a></li>
                        <li><a href="#programs">Programi</a></li>
                        <li><a href="#gallery">Galerija</a></li>
                        <li><a href="#contact">Kontakt</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Kontakt</h4>
                    <ul>
                        <li><a href="tel:+38762883250">+387 62 883 250</a></li>
                        <li>Omladinska 10, Zavidovići</li>
                    </ul>
                    <div class="footer-contact-buttons">
                        <a href="viber://chat?number=%2B38762883250" class="contact-btn viber" title="Viber" aria-label="Kontaktirajte nas putem Vibera">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.4 0C9.473.028 5.333.344 3.02 2.467 1.302 4.187.696 6.7.633 9.817.57 12.933.488 18.776 6.12 20.36h.003l-.004 2.416s-.037.977.61 1.177c.777.242 1.234-.5 1.98-1.302.407-.44.972-1.084 1.397-1.58 3.85.326 6.812-.416 7.15-.525.776-.252 5.176-.816 5.892-6.657.74-6.02-.36-9.83-2.34-11.546-.596-.55-3.006-2.3-8.375-2.323 0 0-.395-.025-1.037-.017zm.058 1.693c.545-.004.88.017.88.017 4.542.02 6.717 1.388 7.222 1.846 1.675 1.435 2.53 4.868 1.906 9.897v.002c-.604 4.878-4.174 5.184-4.832 5.395-.28.09-2.882.737-6.153.524 0 0-2.436 2.94-3.197 3.704-.12.12-.26.167-.352.144-.13-.033-.166-.188-.165-.414l.02-4.018c-4.762-1.32-4.485-6.292-4.43-8.895.054-2.604.543-4.738 1.996-6.173 1.96-1.773 5.474-2.018 7.11-2.03zm.38 2.602c-.167 0-.303.135-.304.302 0 .167.133.303.3.305 1.624.01 2.946.537 4.028 1.592 1.073 1.046 1.62 2.468 1.633 4.334.002.167.14.3.307.3.166-.002.3-.138.3-.304-.014-1.984-.618-3.596-1.816-4.764-1.19-1.16-2.692-1.753-4.447-1.765zm-3.96.695c-.19-.032-.4.005-.616.117l-.01.002c-.43.247-.816.562-1.146.932-.002.004-.006.004-.008.008-.267.323-.42.638-.46.948-.008.046-.01.093-.007.14 0 .136.022.27.065.4l.013.01c.135.48.473 1.276 1.205 2.604.42.768.903 1.5 1.446 2.186.27.344.56.673.87.984l.132.132c.31.308.64.6.984.87.686.543 1.418 1.027 2.186 1.447 1.328.733 2.126 1.07 2.604 1.206l.01.014c.13.042.265.064.402.063.046.002.092 0 .138-.008.31-.036.627-.19.948-.46.004 0 .003-.002.008-.005.37-.33.683-.72.93-1.148l.003-.01c.225-.432.15-.842-.18-1.12-.004 0-.698-.58-1.037-.83-.36-.255-.73-.492-1.113-.71-.51-.285-1.032-.106-1.248.174l-.447.564c-.23.283-.657.246-.657.246-3.12-.796-3.955-3.955-3.955-3.955s-.037-.426.248-.656l.563-.448c.277-.215.456-.737.17-1.248-.217-.383-.454-.756-.71-1.115-.25-.34-.826-1.033-.83-1.035-.137-.165-.31-.265-.502-.297zm4.49.88c-.158.002-.29.124-.3.282-.01.167.115.312.282.324 1.16.085 2.017.466 2.645 1.15.63.688.93 1.524.906 2.57-.002.168.13.306.3.31.166.003.305-.13.31-.297.025-1.175-.334-2.193-1.067-2.994-.74-.81-1.777-1.253-3.05-1.346h-.024zm.463 1.63c-.16.002-.29.127-.3.287-.008.167.12.31.288.32.523.028.875.175 1.113.422.24.245.388.62.416 1.164.01.167.15.295.318.287.167-.008.295-.15.287-.317-.03-.644-.215-1.178-.58-1.557-.367-.378-.893-.574-1.52-.607h-.018z"/></svg>
                        </a>
                        <a href="https://wa.me/38762883250" class="contact-btn whatsapp" title="WhatsApp" target="_blank" aria-label="Kontaktirajte nas putem WhatsAppa">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        <a href="tel:+38762883250" class="contact-btn phone" title="Pozovi" aria-label="Pozovite nas na telefon">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 00-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"/></svg>
                        </a>
                        <a href="https://www.facebook.com/ithubzavidovici/" class="contact-btn facebook" title="Facebook" target="_blank" aria-label="Posjetite nas na Facebooku">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date(
                "Y",
            ) ?> IT Hub Zavidovići. Sva prava zadržana.</p>
        </div>
    </div>
</footer>


    <script src="<?= asset("js/app.js") ?>"></script>
</body>
</html>
