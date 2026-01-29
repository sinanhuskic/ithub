<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Partner.php";

Auth::requireAuth();

$id = $_GET["id"] ?? null;
if (!$id) { header("Location: " . url("partners")); exit; }

$partner = Partner::find($id);
if (!$partner) { header("Location: " . url("partners")); exit; }

$title = "Uredi partnera";
$active = "partners";
?>
<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin Panel') ?> - IT Hub Zavidovići</title>
    <link rel="icon" type="image/svg+xml" href="<?= asset('images/favicon.svg') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="<?= url('dashboard') ?>" class="sidebar-logo">
                    <img src="<?= asset('images/logo-icon.svg') ?>" alt="IT Hub">
                    <span>IT Hub Admin</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="<?= url('dashboard') ?>" class="<?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>">
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
                        <a href="<?= url('programs') ?>" class="<?= ($active ?? '') === 'programs' ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="16 18 22 12 16 6"></polyline>
                                <polyline points="8 6 2 12 8 18"></polyline>
                            </svg>
                            <span>Programi</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url('gallery') ?>" class="<?= ($active ?? '') === 'gallery' ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            <span>Galerija</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url('partners') ?>" class="<?= ($active ?? '') === 'partners' ? 'active' : '' ?>">
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
                        <a href="<?= url('settings') ?>" class="<?= ($active ?? '') === 'settings' ? 'active' : '' ?>">
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
                        <?= strtoupper(substr(Auth::user()['name'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="user-details">
                        <span class="user-name"><?= e(Auth::user()['name'] ?? 'Admin') ?></span>
                        <span class="user-email"><?= e(Auth::user()['email'] ?? '') ?></span>
                    </div>
                </div>
                <a href="<?= url('logout') ?>" class="logout-btn" title="Odjava">
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
                <span class="mobile-title"><?= e($title ?? 'Admin Panel') ?></span>
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
    <h1 class="page-title">Uredi partnera</h1>
    <div class="page-actions">
        <a href="<?= url("partners") ?>" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Nazad
        </a>
    </div>
</div>

<form method="POST" action="<?= url(
    "partner-update?id=" . $partner["id"],
) ?>" enctype="multipart/form-data" data-validate>
    <?= Auth::csrfField() ?>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Informacije o partneru</h2>
        </div>

        <div class="form-group">
            <label class="form-label required" for="name">Naziv partnera</label>
            <input type="text" id="name" name="name" class="form-input"
                   value="<?= e($partner["name"]) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Logotip</label>
            <div class="logo-edit-container">
                <div class="logo-current">
                    <img src="<?= asset($partner["logo_path"]) ?>" alt="<?= e(
    $partner["name"],
) ?>" id="currentLogo">
                </div>
                <label class="file-upload logo-upload-box">
                    <input type="file" name="logo" accept="image/*" data-preview="currentLogo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    <p>Klikni za upload novog logotipa</p>
                    <span>ili prevuci sliku ovdje</span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="website_url">Website URL</label>
            <input type="url" id="website_url" name="website_url" class="form-input"
                   value="<?= e(
                       $partner["website_url"],
                   ) ?>" placeholder="https://example.com">
        </div>

        <div style="display: flex; gap: 12px; margin-top: 32px;">
            <button type="submit" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Sačuvaj promjene
            </button>
            <a href="<?= url(
                "partners",
            ) ?>" class="btn btn-secondary">Odustani</a>
        </div>
    </div>
</form>

<style>
.logo-edit-container {
    display: flex;
    gap: 16px;
    align-items: stretch;
}

.logo-current {
    width: 90px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    background: rgba(255, 255, 255, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px;
    flex-shrink: 0;
}

.logo-current img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.logo-upload-box {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 16px 20px;
    margin: 0;
}

.logo-upload-box svg {
    width: 24px;
    height: 24px;
    margin-bottom: 4px;
}

.logo-upload-box p {
    margin: 0;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.logo-upload-box span {
    font-size: 0.75rem;
    color: var(--text-muted);
}

@media (max-width: 500px) {
    .logo-edit-container {
        flex-direction: column;
    }

    .logo-current {
        width: 100%;
        height: 80px;
    }

    .logo-upload-box {
        width: 100%;
    }
}
</style>

            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="<?= asset('js/admin.js') ?>"></script>
</body>
</html>
