<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Application.php";
require_once __DIR__ . "/includes/models/Program.php";

Auth::requireAuth();

// Dohvati samo programe koji imaju INTERNI link za prijavu (sadrzi /prijava?program=)
$allPrograms = Program::all();
$programsWithInternalRegistration = [];
foreach ($allPrograms as $prog) {
    if (
        !empty($prog["registration_url"]) &&
        strpos($prog["registration_url"], "prijava?program=") !== false
    ) {
        $prog["application_count"] = Application::count($prog["id"]);
        $prog["new_count"] = Application::count($prog["id"], "nova");
        $programsWithInternalRegistration[] = $prog;
    }
}

$title = "Prijave";
$active = "applications";

$success = flash("flash_success");
$error = flash("flash_error");
?>
<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> - IT Hub Zavidovici</title>
    <link rel="icon" type="image/svg+xml" href="<?= asset(
        "images/favicon.svg",
    ) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset("css/admin.css") ?>">
    <style>
        .programs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 24px;
        }
        .program-card {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.9));
            border: 1px solid rgba(0, 209, 178, 0.2);
            border-radius: 20px;
            padding: 28px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            position: relative;
            overflow: hidden;
        }
        .program-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-primary), #06b6d4);
        }
        .program-card:hover {
            border-color: var(--accent-primary);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 30px rgba(0, 209, 178, 0.1);
        }
        .program-card, .program-card:visited, .program-card:link {
            color: inherit;
        }
        .program-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            gap: 12px;
        }
        .program-card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.3;
        }
        .program-card-status {
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 20px;
            background: rgba(0, 209, 178, 0.15);
            color: var(--accent-primary);
            font-weight: 500;
            white-space: nowrap;
        }
        .program-card-stats {
            display: flex;
            gap: 32px;
            margin-bottom: 24px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
        }
        .program-stat {
            text-align: center;
            flex: 1;
        }
        .program-stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }
        .program-stat-number.highlight {
            color: var(--accent-primary);
            text-shadow: 0 0 20px rgba(0, 209, 178, 0.3);
        }
        .program-stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .program-card-meta {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .program-card-meta span {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }
        .program-card-meta span:last-child {
            margin-bottom: 0;
        }
        .program-card-meta svg {
            stroke: var(--accent-primary);
            flex-shrink: 0;
        }
        .program-card-footer {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .view-applications-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--accent-primary);
            font-size: 0.875rem;
            font-weight: 500;
        }
        .view-applications-btn svg {
            width: 18px;
            height: 18px;
            transition: transform 0.2s;
        }
        .program-card:hover .view-applications-btn svg {
            transform: translateX(4px);
        }
        .new-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
            font-size: 0.6875rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 10px;
            margin-left: 8px;
        }
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-muted);
        }
        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        .empty-state h3 {
            color: var(--text-primary);
            margin-bottom: 8px;
        }
        .empty-state p {
            max-width: 400px;
            margin: 0 auto;
            line-height: 1.6;
        }
    </style>
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
                        <a href="<?= url("dashboard") ?>" class="<?= $active ===
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
                        <a href="<?= url("programs") ?>" class="<?= $active ===
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
                        <a href="<?= url(
                            "applications",
                        ) ?>" class="<?= $active === "applications"
    ? "active"
    : "" ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                <path d="M9 14l2 2 4-4"></path>
                            </svg>
                            <span>Prijave</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url("gallery") ?>" class="<?= $active ===
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
                        <a href="<?= url("partners") ?>" class="<?= $active ===
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
                        <a href="<?= url("settings") ?>" class="<?= $active ===
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
            <header class="mobile-header">
                <button class="mobile-menu-toggle" id="sidebarToggle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <span class="mobile-title"><?= e($title) ?></span>
            </header>

            <div class="page-content">
                <?php if ($success): ?>
                <div class="alert alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <span><?= e($success) ?></span>
                </div>
                <?php endif; ?>

                <?php if ($error): ?>
                <div class="alert alert-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span><?= e($error) ?></span>
                </div>
                <?php endif; ?>

                <div class="page-header">
                    <h1 class="page-title">Prijave na programe</h1>
                    <p style="color: var(--text-muted); margin-top: 8px;">Odaberite program za pregled prijava</p>
                </div>

                <?php if (empty($programsWithInternalRegistration)): ?>
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    </svg>
                    <h3>Nema programa sa prijavama</h3>
                    <p>Da biste primali online prijave, uredite program i dodajte interni "Link za prijavu" u formatu: <code>/ithub/prijava?program=ID</code></p>
                </div>
                <?php else: ?>
                <div class="programs-grid">
                    <?php foreach (
                        $programsWithInternalRegistration
                        as $prog
                    ): ?>
                    <a href="<?= url(
                        "applications-list?program=" . $prog["id"],
                    ) ?>" class="program-card">
                        <div class="program-card-header">
                            <h3 class="program-card-title">
                                <?= e($prog["title"]) ?>
                                <?php if ($prog["new_count"] > 0): ?>
                                <span class="new-badge"><?= $prog[
                                    "new_count"
                                ] ?> nova</span>
                                <?php endif; ?>
                            </h3>
                            <span class="program-card-status"><?= e(
                                $prog["status"],
                            ) ?></span>
                        </div>

                        <div class="program-card-stats">
                            <div class="program-stat">
                                <div class="program-stat-number <?= $prog[
                                    "application_count"
                                ] > 0
                                    ? "highlight"
                                    : "" ?>">
                                    <?= $prog["application_count"] ?>
                                </div>
                                <div class="program-stat-label">Ukupno</div>
                            </div>
                            <div class="program-stat">
                                <div class="program-stat-number"><?= Application::count(
                                    $prog["id"],
                                    "nova",
                                ) ?></div>
                                <div class="program-stat-label">Novih</div>
                            </div>
                            <div class="program-stat">
                                <div class="program-stat-number"><?= Application::count(
                                    $prog["id"],
                                    "primljena",
                                ) ?></div>
                                <div class="program-stat-label">Primljenih</div>
                            </div>
                        </div>

                        <div class="program-card-meta">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <?= e(
                                    $prog["period"] ?: "Period nije definisan",
                                ) ?>
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <?= e(
                                    $prog["participants"] ?:
                                    "Polaznici nisu definisani",
                                ) ?>
                            </span>
                        </div>

                        <div class="program-card-footer">
                            <span class="view-applications-btn">
                                Pregledaj prijave
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <script src="<?= asset("js/admin.js") ?>"></script>
</body>
</html>
