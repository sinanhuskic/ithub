<?php
/**
 * IT Hub Zavidovići - Admin Dashboard
 */
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";

Auth::requireAuth();

// Get statistics
$stats = [
    "programs" => db()->count("programs"),
    "gallery" => db()->count("gallery"),
    "partners" => db()->count("partners"),
    "active_programs" => db()->count("programs", "active = 1"),
];

$title = "Dashboard";
$active = "dashboard";
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
                
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Dobrodošli nazad! Evo pregleda vašeg sajta.</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <a href="<?= url("programs") ?>" class="stat-card stat-card-link">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="16 18 22 12 16 6"></polyline>
                <polyline points="8 6 2 12 8 18"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <h3><?= $stats["programs"] ?? 0 ?></h3>
            <p>Ukupno programa</p>
        </div>
    </a>

    <a href="<?= url("gallery") ?>" class="stat-card stat-card-link">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <polyline points="21 15 16 10 5 21"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <h3><?= $stats["gallery"] ?? 0 ?></h3>
            <p>Slika u galeriji</p>
        </div>
    </a>

    <a href="<?= url("partners") ?>" class="stat-card stat-card-link">
        <div class="stat-icon cyan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div class="stat-info">
            <h3><?= $stats["partners"] ?? 0 ?></h3>
            <p>Partnera</p>
        </div>
    </a>

    <a href="<?= url("programs") ?>" class="stat-card stat-card-link">
        <div class="stat-icon pink">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <h3><?= $stats["active_programs"] ?? 0 ?></h3>
            <p>Aktivnih programa</p>
        </div>
    </a>
</div>

<!-- Quick Links -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Brze akcije</h2>
    </div>
    <div class="quick-links">
        <a href="<?= url("program-create") ?>" class="quick-link">
            <div class="quick-link-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </div>
            <span>Dodaj program</span>
        </a>
        <a href="<?= url("gallery") ?>" class="quick-link">
            <div class="quick-link-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
            </div>
            <span>Upload slike</span>
        </a>
        <a href="<?= url("partner-create") ?>" class="quick-link">
            <div class="quick-link-icon cyan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
            </div>
            <span>Dodaj partnera</span>
        </a>
        <a href="<?= url("settings") ?>" class="quick-link">
            <div class="quick-link-icon pink">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
            </div>
            <span>Postavke</span>
        </a>
    </div>
</div>

<style>
/* Page Subtitle */
.page-subtitle {
    color: var(--text-muted);
    font-size: 0.95rem;
    margin-top: 4px;
}

/* Stat Card as Link */
.stat-card-link {
    text-decoration: none;
    color: inherit;
    cursor: pointer;
}

.stat-card-link:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: var(--shadow-glow);
}

/* Quick Link Icons */
.quick-link-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(99, 102, 241, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.quick-link-icon svg {
    width: 20px;
    height: 20px;
    stroke: var(--primary);
}

.quick-link-icon.green {
    background: rgba(16, 185, 129, 0.1);
}
.quick-link-icon.green svg {
    stroke: var(--secondary);
}

.quick-link-icon.cyan {
    background: rgba(6, 182, 212, 0.1);
}
.quick-link-icon.cyan svg {
    stroke: var(--accent);
}

.quick-link-icon.pink {
    background: rgba(236, 72, 153, 0.1);
}
.quick-link-icon.pink svg {
    stroke: var(--accent-pink);
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        margin-bottom: 20px;
    }

    .page-subtitle {
        font-size: 0.9rem;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .stat-card {
        padding: 16px;
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        margin: 0 auto;
    }

    .stat-icon svg {
        width: 20px;
        height: 20px;
    }

    .stat-info h3 {
        font-size: 1.5rem;
    }

    .stat-info p {
        font-size: 0.8rem;
    }

    .quick-links {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .quick-link {
        flex-direction: column;
        text-align: center;
        padding: 16px 12px;
        gap: 10px;
    }

    .quick-link span {
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .stat-card {
        flex-direction: row;
        text-align: left;
    }

    .stat-icon {
        margin: 0;
    }

    .quick-links {
        grid-template-columns: 1fr;
    }

    .quick-link {
        flex-direction: row;
        text-align: left;
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
