<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Program.php";

Auth::requireAuth();

$id = $_GET["id"] ?? null;
if (!$id) {
    header("Location: " . url("programs"));
    exit();
}

$program = Program::findWithDecoded($id);
if (!$program) {
    header("Location: " . url("programs"));
    exit();
}

$technologies = Program::getAvailableTechnologies();
$statuses = Program::getStatuses();
$levels = Program::getLevels();
$icons = Program::getIcons();
$title = "Uredi program";
$active = "programs";
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
$error = $_SESSION["flash_error"] ?? null;
unset($_SESSION["flash_error"]);
?>

<?php if ($error): ?>
<div class="alert alert-danger">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="8" x2="12" y2="12"></line>
        <line x1="12" y1="16" x2="12.01" y2="16"></line>
    </svg>
    <span><?= e($error) ?></span>
</div>
<?php endif; ?>

<div class="page-header">
    <h1 class="page-title">Uredi program</h1>
    <div class="page-actions">
        <a href="<?= url("programs") ?>" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Nazad
        </a>
    </div>
</div>

<form method="POST" action="<?= url(
    "api/program-update.php?id=" . $program["id"],
) ?>" data-validate>
    <?= Auth::csrfField() ?>

    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <h2 class="card-title">Osnovne informacije</h2>
        </div>

        <div class="form-grid">
            <div class="form-group" style="grid-column: span 2;">
                <label class="form-label required" for="title">Naziv programa</label>
                <input type="text" id="title" name="title" class="form-input"
                       value="<?= e($program["title"]) ?>" required>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label class="form-label" for="description">Kratki opis</label>
                <textarea id="description" name="description" class="form-textarea" rows="3"><?= e(
                    $program["description"],
                ) ?></textarea>
                <span class="form-hint">Maksimalno 200 karaktera</span>
            </div>

            <div class="form-group">
                <label class="form-label" for="duration">Trajanje</label>
                <input type="text" id="duration" name="duration" class="form-input"
                       value="<?= e($program["duration"]) ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="level">Nivo</label>
                <select id="level" name="level" class="form-select">
                    <?php foreach ($levels as $key => $label): ?>
                    <option value="<?= e($key) ?>" <?= $program["level"] ===
$key
    ? "selected"
    : "" ?>>
                        <?= e($label) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-select">
                    <?php foreach ($statuses as $key => $label): ?>
                    <option value="<?= e($key) ?>" <?= $program["status"] ===
$key
    ? "selected"
    : "" ?>>
                        <?= e($label) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="icon">Ikona</label>
                <select id="icon" name="icon" class="form-select">
                    <?php foreach ($icons as $key => $label): ?>
                    <option value="<?= e($key) ?>" <?= $program["icon"] === $key
    ? "selected"
    : "" ?>>
                        <?= e($label) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-grid" style="margin-top: 20px;">
            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="featured" value="1" <?= $program[
                        "featured"
                    ]
                        ? "checked"
                        : "" ?>>
                    <span class="form-check-label">Istaknut program</span>
                </label>
            </div>
            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="active" value="1" <?= $program[
                        "active"
                    ]
                        ? "checked"
                        : "" ?>>
                    <span class="form-check-label">Aktivan (prikaži na sajtu)</span>
                </label>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <h2 class="card-title">Detalji programa</h2>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="period">Period održavanja</label>
                <input type="text" id="period" name="period" class="form-input"
                       value="<?= e($program["period"]) ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="participants">Broj polaznika</label>
                <input type="text" id="participants" name="participants" class="form-input"
                       value="<?= e($program["participants"]) ?>">
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label class="form-label" for="format">Format edukacije</label>
                <input type="text" id="format" name="format" class="form-input"
                       value="<?= e($program["format"]) ?>">
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label class="form-label" for="full_description">Detaljni opis</label>
                <textarea id="full_description" name="full_description" class="form-textarea" rows="5"><?= e(
                    $program["full_description"],
                ) ?></textarea>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label class="form-label" for="requirements">Uslovi za upis</label>
                <textarea id="requirements" name="requirements" class="form-textarea" rows="3"><?= e(
                    $program["requirements"],
                ) ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="registration_url">Link za prijavu</label>
                <input type="text" id="registration_url" name="registration_url" class="form-input"
                       value="<?= e($program["registration_url"] ?? "") ?>"
                       placeholder="npr. prijava?program=<?= e(
                           $program["id"],
                       ) ?>">
                <small style="color: var(--text-muted); margin-top: 6px; display: block;">
                    Ostavite prazno ako program trenutno ne prima prijave. Format: <code>prijava?program=ID</code>
                </small>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <h2 class="card-title">Tehnologije</h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px;">
            <?php
            $selectedTechs = $program["technologies"] ?? [];
            foreach ($technologies as $key => $label): ?>
            <label class="form-check">
                <input type="checkbox" name="technologies[]" value="<?= e(
                    $key,
                ) ?>"
                       <?= in_array($key, $selectedTechs) ? "checked" : "" ?>>
                <span class="form-check-label"><?= e($label) ?></span>
            </label>
            <?php endforeach;
            ?>
        </div>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <h2 class="card-title">Što polaznici dobijaju (Highlights)</h2>
        </div>

        <div id="highlights-container">
            <?php
            $highlights = $program["highlights"] ?? [];
            $highlights = array_pad($highlights, 5, "");
            foreach ($highlights as $index => $highlight): ?>
            <div class="form-group">
                <input type="text" name="highlights[]" class="form-input"
                       value="<?= e($highlight) ?>"
                       placeholder="<?= $index < 3
                           ? "Unesite highlight..."
                           : "Dodaj još jedan..." ?>">
            </div>
            <?php endforeach;
            ?>
        </div>
        <span class="form-hint">Ostavite prazno ako ne želite prikazati</span>
    </div>

    <div class="card">
        <div class="form-group">
            <label class="form-label" for="sort_order">Redoslijed prikaza</label>
            <input type="number" id="sort_order" name="sort_order" class="form-input"
                   value="<?= e(
                       $program["sort_order"],
                   ) ?>" min="0" style="max-width: 150px;">
            <span class="form-hint">Manji broj = viši prioritet</span>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Sačuvaj promjene
            </button>
            <a href="<?= url(
                "programs",
            ) ?>" class="btn btn-secondary">Odustani</a>
        </div>
    </div>
</form>

            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="<?= asset("js/admin.js") ?>"></script>
</body>
</html>
