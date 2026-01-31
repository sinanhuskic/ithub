<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Application.php";
require_once __DIR__ . "/includes/models/Program.php";

Auth::requireAuth();

$programId = $_GET["program"] ?? null;
if (!$programId) {
    header("Location: " . url("applications"));
    exit();
}

$program = Program::find($programId);
if (!$program) {
    header("Location: " . url("applications"));
    exit();
}

$statusFilter = $_GET["status"] ?? null;
$applications = Application::all($programId);

// Filtriraj po statusu ako je odabran
if ($statusFilter) {
    $applications = array_filter(
        $applications,
        fn($a) => $a["status"] === $statusFilter,
    );
    $applications = array_values($applications);
}

$statuses = Application::getStatuses();
$title = "Prijave - " . $program["title"];
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
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .status-blue { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
        .status-yellow { background: rgba(251, 191, 36, 0.15); color: #fbbf24; }
        .status-purple { background: rgba(168, 85, 247, 0.15); color: #a855f7; }
        .status-red { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
        .status-green { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
        .filter-bar {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
            align-items: center;
        }
        .filter-bar select {
            padding: 8px 12px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.875rem;
        }
        .applications-table {
            width: 100%;
            border-collapse: collapse;
        }
        .applications-table th,
        .applications-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-primary);
        }
        .applications-table th {
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .applications-table tr:hover {
            background: rgba(255,255,255,0.02);
        }
        .applicant-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .applicant-name {
            font-weight: 500;
            color: var(--text-primary);
        }
        .applicant-email {
            font-size: 0.8125rem;
            color: var(--text-muted);
        }
        .view-btn {
            padding: 6px 12px;
            background: var(--accent-primary);
            color: var(--bg-primary);
            border: none;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .view-btn:hover {
            opacity: 0.9;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }
        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        .stats-row {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .stat-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: 12px;
            padding: 16px 20px;
            min-width: 100px;
        }
        .stat-card .number {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        .stat-card .label {
            font-size: 0.8125rem;
            color: var(--text-muted);
        }
        .program-header {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .program-header h2 {
            margin: 0 0 8px 0;
            font-size: 1.25rem;
        }
        .program-header .meta {
            color: var(--text-muted);
            font-size: 0.875rem;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            margin-bottom: 16px;
        }
        .back-link:hover {
            color: var(--accent-primary);
        }
        .back-link svg {
            width: 16px;
            height: 16px;
        }
        .form-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: rgba(0, 209, 178, 0.1);
            border: 1px solid rgba(0, 209, 178, 0.3);
            border-radius: 8px;
            color: var(--accent-primary);
            text-decoration: none;
            font-size: 0.875rem;
        }
        .form-link:hover {
            background: rgba(0, 209, 178, 0.15);
        }
        .form-link svg {
            width: 16px;
            height: 16px;
        }

        /* Mobile cards */
        .mobile-cards {
            display: none;
            gap: 16px;
        }
        .application-card {
            background: linear-gradient(145deg, rgba(30, 32, 40, 0.9), rgba(20, 22, 28, 0.95));
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .application-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        .application-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, rgba(0, 209, 178, 0.1), rgba(99, 102, 241, 0.1));
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .application-card .card-name {
            font-weight: 700;
            font-size: 1.125rem;
            color: #fff;
            letter-spacing: -0.01em;
        }
        .application-card .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .application-card .card-row {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }
        .application-card .card-row svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            color: var(--accent-primary);
            opacity: 0.8;
        }
        .application-card .card-row a {
            color: var(--accent-primary);
            text-decoration: none;
            font-weight: 500;
        }
        .application-card .card-row a:hover {
            text-decoration: underline;
        }
        .application-card .card-row span {
            color: rgba(255, 255, 255, 0.8);
        }
        .application-card .card-footer {
            padding: 16px 20px;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        .application-card .card-footer .view-btn {
            width: 100%;
            justify-content: center;
            padding: 12px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent-primary), #00b894);
            color: #fff;
            gap: 8px;
        }
        .application-card .card-footer .view-btn:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }
        .application-card .card-footer .view-btn svg {
            width: 18px;
            height: 18px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .stats-row {
                gap: 12px;
            }
            .stat-card {
                padding: 12px 16px;
                min-width: 80px;
            }
            .stat-card .number {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 1024px) {
            .desktop-table {
                display: none;
            }
            .mobile-cards {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
            }
            .stats-row {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
            }
            .stat-card {
                min-width: auto;
                text-align: center;
            }
            .program-header {
                padding: 16px;
            }
            .program-header h2 {
                font-size: 1.1rem;
            }
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-bar select {
                width: 100%;
            }
            .form-link {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 640px) {
            .mobile-cards {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
            .stat-card .number {
                font-size: 1.1rem;
            }
            .stat-card .label {
                font-size: 0.75rem;
            }
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
                <span class="mobile-title">Prijave</span>
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

                <a href="<?= url("applications") ?>" class="back-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Nazad na programe
                </a>

                <div class="program-header">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                        <div>
                            <h2><?= e($program["title"]) ?></h2>
                            <p class="meta">
                                Period: <?= e(
                                    $program["period"] ?: "Nije definisan",
                                ) ?> |
                                Status: <?= e($program["status"]) ?>
                            </p>
                        </div>
                        <a href="<?= url(
                            "prijava?program=" . $program["id"],
                        ) ?>" target="_blank" class="form-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                <polyline points="15 3 21 3 21 9"/>
                                <line x1="10" y1="14" x2="21" y2="3"/>
                            </svg>
                            Otvori formu za prijavu
                        </a>
                    </div>
                </div>

                <!-- Stats -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="number"><?= Application::count(
                            $programId,
                        ) ?></div>
                        <div class="label">Ukupno</div>
                    </div>
                    <div class="stat-card">
                        <div class="number"><?= Application::count(
                            $programId,
                            "nova",
                        ) ?></div>
                        <div class="label">Novih</div>
                    </div>
                    <div class="stat-card">
                        <div class="number"><?= Application::count(
                            $programId,
                            "pregledana",
                        ) ?></div>
                        <div class="label">Pregledanih</div>
                    </div>
                    <div class="stat-card">
                        <div class="number"><?= Application::count(
                            $programId,
                            "pozvana",
                        ) ?></div>
                        <div class="label">Pozvanih</div>
                    </div>
                    <div class="stat-card">
                        <div class="number"><?= Application::count(
                            $programId,
                            "primljena",
                        ) ?></div>
                        <div class="label">Primljenih</div>
                    </div>
                    <div class="stat-card">
                        <div class="number"><?= Application::count(
                            $programId,
                            "odbijena",
                        ) ?></div>
                        <div class="label">Odbijenih</div>
                    </div>
                </div>

                <!-- Filters -->
                <form method="GET" class="filter-bar">
                    <input type="hidden" name="program" value="<?= $programId ?>">
                    <select name="status" onchange="this.form.submit()">
                        <option value="">Svi statusi</option>
                        <?php foreach ($statuses as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $statusFilter === $key
    ? "selected"
    : "" ?>>
                            <?= e($label) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <span style="color: var(--text-muted); font-size: 0.875rem;">
                        Prikazano: <?= count($applications) ?> prijava
                    </span>
                </form>

                <!-- Table - Desktop -->
                <div class="card desktop-table">
                    <?php if (empty($applications)): ?>
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        </svg>
                        <p>Nema prijava<?= $statusFilter
                            ? " sa ovim statusom"
                            : "" ?></p>
                    </div>
                    <?php else: ?>
                    <table class="applications-table">
                        <thead>
                            <tr>
                                <th>Kandidat</th>
                                <th>Škola</th>
                                <th>Telefon</th>
                                <th>Status</th>
                                <th>Datum prijave</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                            <tr>
                                <td>
                                    <div class="applicant-info">
                                        <span class="applicant-name"><?= e(
                                            $app["full_name"],
                                        ) ?></span>
                                        <span class="applicant-email"><?= e(
                                            $app["email"],
                                        ) ?></span>
                                    </div>
                                </td>
                                <td><?= e($app["school"] ?: "-") .
                                    ($app["grade"]
                                        ? ", " . e($app["grade"])
                                        : "") ?></td>
                                <td><a href="tel:<?= e($app["phone"]) ?>"><?= e(
    $app["phone"],
) ?></a></td>
                                <td>
                                    <span class="status-badge status-<?= Application::getStatusColor(
                                        $app["status"],
                                    ) ?>">
                                        <?= e(
                                            Application::getStatusLabel(
                                                $app["status"],
                                            ),
                                        ) ?>
                                    </span>
                                </td>
                                <td><?= date(
                                    "d.m.Y. H:i",
                                    strtotime($app["created_at"]),
                                ) ?></td>
                                <td>
                                    <a href="<?= url(
                                        "application-view?id=" . $app["id"],
                                    ) ?>" class="view-btn">Pregledaj</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>

                <!-- Cards - Mobile -->
                <div class="mobile-cards">
                    <?php if (empty($applications)): ?>
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        </svg>
                        <p>Nema prijava<?= $statusFilter
                            ? " sa ovim statusom"
                            : "" ?></p>
                    </div>
                    <?php else: ?>
                    <?php foreach ($applications as $app): ?>
                    <div class="application-card">
                        <div class="card-header">
                            <div class="card-name"><?= e(
                                $app["full_name"],
                            ) ?></div>
                            <span class="status-badge status-<?= Application::getStatusColor(
                                $app["status"],
                            ) ?>">
                                <?= e(
                                    Application::getStatusLabel($app["status"]),
                                ) ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="card-row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                <a href="mailto:<?= e($app["email"]) ?>"><?= e(
    $app["email"],
) ?></a>
                            </div>
                            <div class="card-row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                <a href="tel:<?= e($app["phone"]) ?>"><?= e(
    $app["phone"],
) ?></a>
                            </div>
                            <?php if ($app["school"]): ?>
                            <div class="card-row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                <span><?= e($app["school"]) .
                                    ($app["grade"]
                                        ? ", " . e($app["grade"])
                                        : "") ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="card-row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <span><?= date(
                                    "d.m.Y. H:i",
                                    strtotime($app["created_at"]),
                                ) ?></span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="<?= url(
                                "application-view?id=" . $app["id"],
                            ) ?>" class="view-btn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Pregledaj prijavu
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <script src="<?= asset("js/admin.js") ?>"></script>
</body>
</html>
