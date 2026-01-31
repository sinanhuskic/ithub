<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Setting.php";

Auth::requireAuth();

$contact = Setting::getContact();
$about = Setting::getAbout();
$stats = Setting::getStats();
$title = "Postavke";
$active = "settings";
?>
<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? "Admin Panel") ?> - IT Hub Zavidovići</title>
    <link rel="icon" type="image/svg+xml" href="<?= asset(
        "images/favicon.svg",
    ) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset("css/admin.css") ?>">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="<?= url("dashboard") ?>" class="sidebar-logo">
                    <img src="<?= asset(
                        "images/logo-icon.svg",
                    ) ?>" alt="IT Hub">
                    <span>IT Hub Admin</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="<?= url("dashboard") ?>" class="<?= ($active ??
    "") ===
"dashboard"
    ? "active"
    : "" ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url("programs") ?>" class="<?= ($active ??
    "") ===
"programs"
    ? "active"
    : "" ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="16 18 22 12 16 6"></polyline>
                                <polyline points="8 6 2 12 8 18"></polyline>
                            </svg>
                            <span>Programi</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url("gallery") ?>" class="<?= ($active ??
    "") ===
"gallery"
    ? "active"
    : "" ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            <span>Galerija</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url("partners") ?>" class="<?= ($active ??
    "") ===
"partners"
    ? "active"
    : "" ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Partneri</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url(
                            "applications",
                        ) ?>" class="<?= ($active ?? "") === "applications"
    ? "active"
    : "" ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <span>Prijave</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url("settings") ?>" class="<?= ($active ??
    "") ===
"settings"
    ? "active"
    : "" ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            <span>Postavke</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <?= strtoupper(
                            substr(Auth::user()["name"] ?? "A", 0, 1),
                        ) ?>
                    </div>
                    <div class="user-details">
                        <span class="user-name"><?= e(
                            Auth::user()["name"] ?? "Admin",
                        ) ?></span>
                        <span class="user-email"><?= e(
                            Auth::user()["email"] ?? "",
                        ) ?></span>
                    </div>
                </div>
                <a href="<?= url(
                    "logout",
                ) ?>" class="logout-btn" title="Odjava">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Mobile Header -->
            <header class="mobile-header">
                <button class="mobile-menu-toggle" id="sidebarToggle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <span class="mobile-title"><?= e(
                    $title ?? "Admin Panel",
                ) ?></span>
            </header>

            <!-- Page Content -->
            <div class="page-content">

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$success = $_SESSION["flash_success"] ?? null;
$error = $_SESSION["flash_error"] ?? null;
unset($_SESSION["flash_success"], $_SESSION["flash_error"]);
?>

<?php if ($success): ?>
<div class="alert alert-success" data-auto-hide="5000">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="20 6 9 17 4 12"></polyline>
    </svg>
    <span><?= e($success) ?></span>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger" data-auto-hide="5000">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="8" x2="12" y2="12"></line>
        <line x1="12" y1="16" x2="12.01" y2="16"></line>
    </svg>
    <span><?= e($error) ?></span>
</div>
<?php endif; ?>

<div class="page-header">
    <h1 class="page-title">Postavke</h1>
</div>

<!-- Contact Settings -->
<form method="POST" action="<?= url(
    "api/settings-contact.php",
) ?>" class="card" style="margin-bottom: 24px;">
    <?= Auth::csrfField() ?>
    <div class="card-header">
        <h2 class="card-title">Kontakt informacije</h2>
    </div>

    <div class="form-grid">
        <div class="form-group">
            <label class="form-label" for="phone">Telefon</label>
            <input type="text" id="phone" name="phone" class="form-input"
                   value="<?= e(
                       $contact["phone"] ?? "",
                   ) ?>" placeholder="+387 62 883 250">
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-input"
                   value="<?= e(
                       $contact["email"] ?? "",
                   ) ?>" placeholder="info@ithub.ba">
        </div>

        <div class="form-group">
            <label class="form-label" for="address_street">Ulica</label>
            <input type="text" id="address_street" name="address_street" class="form-input"
                   value="<?= e(
                       $contact["address_street"] ?? "",
                   ) ?>" placeholder="Omladinska 10">
        </div>

        <div class="form-group">
            <label class="form-label" for="address_city">Grad</label>
            <input type="text" id="address_city" name="address_city" class="form-input"
                   value="<?= e(
                       $contact["address_city"] ?? "",
                   ) ?>" placeholder="Zavidovići">
        </div>

        <div class="form-group">
            <label class="form-label" for="address_postal">Poštanski broj</label>
            <input type="text" id="address_postal" name="address_postal" class="form-input"
                   value="<?= e(
                       $contact["address_postal"] ?? "",
                   ) ?>" placeholder="72220">
        </div>

        <div class="form-group">
            <label class="form-label" for="address_country">Država (kod)</label>
            <input type="text" id="address_country" name="address_country" class="form-input"
                   value="<?= e(
                       $contact["address_country"] ?? "BA",
                   ) ?>" placeholder="BA" maxlength="2">
        </div>

        <div class="form-group" style="grid-column: span 2;">
            <label class="form-label" for="facebook_url">Facebook URL</label>
            <input type="url" id="facebook_url" name="facebook_url" class="form-input"
                   value="<?= e(
                       $contact["facebook_url"] ?? "",
                   ) ?>" placeholder="https://www.facebook.com/ithubzavidovici/">
        </div>

        <div class="form-group">
            <label class="form-label" for="whatsapp">WhatsApp link</label>
            <input type="url" id="whatsapp" name="whatsapp" class="form-input"
                   value="<?= e(
                       $contact["whatsapp"] ?? "",
                   ) ?>" placeholder="https://wa.me/38762883250">
        </div>

        <div class="form-group">
            <label class="form-label" for="viber">Viber link</label>
            <input type="text" id="viber" name="viber" class="form-input"
                   value="<?= e(
                       $contact["viber"] ?? "",
                   ) ?>" placeholder="viber://chat?number=%2B38762883250">
        </div>

        <div class="form-group" style="grid-column: span 2;">
            <label class="form-label" for="website">Website URL</label>
            <input type="url" id="website" name="website" class="form-input"
                   value="<?= e(
                       $contact["website"] ?? "",
                   ) ?>" placeholder="https://ithub.ba/">
        </div>
    </div>

    <button type="submit" class="btn btn-primary" style="margin-top: 16px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        Sačuvaj kontakt
    </button>
</form>

<!-- About Settings -->
<form method="POST" action="<?= url(
    "api/settings-about.php",
) ?>" class="card" style="margin-bottom: 24px;">
    <?= Auth::csrfField() ?>
    <div class="card-header">
        <h2 class="card-title">O nama</h2>
    </div>

    <div class="form-group">
        <label class="form-label" for="about_description">Opis organizacije</label>
        <textarea id="about_description" name="about_description" class="form-textarea" rows="4"><?= e(
            $about["about_description"] ?? "",
        ) ?></textarea>
    </div>

    <h3 style="font-size: 1rem; margin: 24px 0 16px; color: var(--text-secondary);">Feature stavke</h3>

    <div class="form-grid">
        <div class="form-group">
            <label class="form-label" for="about_feature_1_title">Feature 1 - Naslov</label>
            <input type="text" id="about_feature_1_title" name="about_feature_1_title" class="form-input"
                   value="<?= e(
                       $about["about_feature_1_title"] ?? "",
                   ) ?>" placeholder="Kvalitetna edukacija">
        </div>
        <div class="form-group">
            <label class="form-label" for="about_feature_1_desc">Feature 1 - Opis</label>
            <input type="text" id="about_feature_1_desc" name="about_feature_1_desc" class="form-input"
                   value="<?= e(
                       $about["about_feature_1_desc"] ?? "",
                   ) ?>" placeholder="Moderni kurikulum prilagođen industriji">
        </div>

        <div class="form-group">
            <label class="form-label" for="about_feature_2_title">Feature 2 - Naslov</label>
            <input type="text" id="about_feature_2_title" name="about_feature_2_title" class="form-input"
                   value="<?= e($about["about_feature_2_title"] ?? "") ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="about_feature_2_desc">Feature 2 - Opis</label>
            <input type="text" id="about_feature_2_desc" name="about_feature_2_desc" class="form-input"
                   value="<?= e($about["about_feature_2_desc"] ?? "") ?>">
        </div>

        <div class="form-group">
            <label class="form-label" for="about_feature_3_title">Feature 3 - Naslov</label>
            <input type="text" id="about_feature_3_title" name="about_feature_3_title" class="form-input"
                   value="<?= e($about["about_feature_3_title"] ?? "") ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="about_feature_3_desc">Feature 3 - Opis</label>
            <input type="text" id="about_feature_3_desc" name="about_feature_3_desc" class="form-input"
                   value="<?= e($about["about_feature_3_desc"] ?? "") ?>">
        </div>

        <div class="form-group">
            <label class="form-label" for="about_feature_4_title">Feature 4 - Naslov</label>
            <input type="text" id="about_feature_4_title" name="about_feature_4_title" class="form-input"
                   value="<?= e($about["about_feature_4_title"] ?? "") ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="about_feature_4_desc">Feature 4 - Opis</label>
            <input type="text" id="about_feature_4_desc" name="about_feature_4_desc" class="form-input"
                   value="<?= e($about["about_feature_4_desc"] ?? "") ?>">
        </div>
    </div>

    <button type="submit" class="btn btn-primary" style="margin-top: 16px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        Sačuvaj o nama
    </button>
</form>

<!-- Stats Settings -->
<form method="POST" action="<?= url("api/settings-stats.php") ?>" class="card">
    <?= Auth::csrfField() ?>
    <div class="card-header">
        <h2 class="card-title">Statistika</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
        <!-- Stat 1 -->
        <div style="background: rgba(0,0,0,0.2); padding: 16px; border-radius: 12px;">
            <div class="form-group">
                <label class="form-label" for="stat_1_label">Naziv</label>
                <input type="text" id="stat_1_label" name="stat_1_label" class="form-input"
                       value="<?= e(
                           $stats["stat_1_label"] ?? "Sati edukacije",
                       ) ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="stat_1_current">Trenutno</label>
                <input type="number" id="stat_1_current" name="stat_1_current" class="form-input"
                       value="<?= e($stats["stat_1_current"] ?? 0) ?>" min="0">
            </div>
            <div class="form-group">
                <label class="form-label" for="stat_1_planned">Planirano</label>
                <input type="number" id="stat_1_planned" name="stat_1_planned" class="form-input"
                       value="<?= e($stats["stat_1_planned"] ?? 0) ?>" min="0">
            </div>
        </div>

        <!-- Stat 2 -->
        <div style="background: rgba(0,0,0,0.2); padding: 16px; border-radius: 12px;">
            <div class="form-group">
                <label class="form-label" for="stat_2_label">Naziv</label>
                <input type="text" id="stat_2_label" name="stat_2_label" class="form-input"
                       value="<?= e($stats["stat_2_label"] ?? "Polaznika") ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="stat_2_current">Trenutno</label>
                <input type="number" id="stat_2_current" name="stat_2_current" class="form-input"
                       value="<?= e($stats["stat_2_current"] ?? 0) ?>" min="0">
            </div>
            <div class="form-group">
                <label class="form-label" for="stat_2_planned">Planirano</label>
                <input type="number" id="stat_2_planned" name="stat_2_planned" class="form-input"
                       value="<?= e($stats["stat_2_planned"] ?? 0) ?>" min="0">
            </div>
        </div>

        <!-- Stat 3 -->
        <div style="background: rgba(0,0,0,0.2); padding: 16px; border-radius: 12px;">
            <div class="form-group">
                <label class="form-label" for="stat_3_label">Naziv</label>
                <input type="text" id="stat_3_label" name="stat_3_label" class="form-input"
                       value="<?= e($stats["stat_3_label"] ?? "Programa") ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="stat_3_current">Trenutno</label>
                <input type="number" id="stat_3_current" name="stat_3_current" class="form-input"
                       value="<?= e($stats["stat_3_current"] ?? 0) ?>" min="0">
            </div>
            <div class="form-group">
                <label class="form-label" for="stat_3_planned">Planirano</label>
                <input type="number" id="stat_3_planned" name="stat_3_planned" class="form-input"
                       value="<?= e($stats["stat_3_planned"] ?? 0) ?>" min="0">
            </div>
        </div>

        <!-- Stat 4 -->
        <div style="background: rgba(0,0,0,0.2); padding: 16px; border-radius: 12px;">
            <div class="form-group">
                <label class="form-label" for="stat_4_label">Naziv</label>
                <input type="text" id="stat_4_label" name="stat_4_label" class="form-input"
                       value="<?= e($stats["stat_4_label"] ?? "Aktivnosti") ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="stat_4_current">Trenutno</label>
                <input type="number" id="stat_4_current" name="stat_4_current" class="form-input"
                       value="<?= e($stats["stat_4_current"] ?? 0) ?>" min="0">
            </div>
            <div class="form-group">
                <label class="form-label" for="stat_4_planned">Planirano</label>
                <input type="number" id="stat_4_planned" name="stat_4_planned" class="form-input"
                       value="<?= e($stats["stat_4_planned"] ?? 0) ?>" min="0">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary" style="margin-top: 24px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        Sačuvaj statistiku
    </button>
</form>

            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="<?= asset("js/admin.js") ?>"></script>
</body>
</html>
