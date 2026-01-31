<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Application.php";

Auth::requireAuth();

$id = $_GET["id"] ?? null;
if (!$id) {
    header("Location: " . url("applications"));
    exit();
}

$app = Application::find($id);
if (!$app) {
    header("Location: " . url("applications"));
    exit();
}

// Ako je nova prijava, oznaci kao pregledanu
if ($app["status"] === "nova") {
    Application::updateStatus($id, "pregledana");
    $app["status"] = "pregledana";
}

$statuses = Application::getStatuses();
$title = "Prijava - " . $app["full_name"];
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
        .app-header {
            background: linear-gradient(135deg, rgba(0, 209, 178, 0.1), rgba(99, 102, 241, 0.08));
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 28px 32px;
            margin-bottom: 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .app-header-info h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #fff, rgba(255,255,255,0.8));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .app-header-info .meta {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }
        .app-header-info .meta strong {
            color: var(--accent-primary);
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-blue { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
        .status-yellow { background: rgba(251, 191, 36, 0.2); color: #fcd34d; border: 1px solid rgba(251, 191, 36, 0.3); }
        .status-purple { background: rgba(168, 85, 247, 0.2); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.3); }
        .status-red { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .status-green { background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); }

        .app-section {
            background: linear-gradient(145deg, rgba(30, 32, 40, 0.9), rgba(20, 22, 28, 0.95));
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            padding: 0;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .app-section-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
            margin: 0;
            padding: 16px 24px;
            background: linear-gradient(135deg, rgba(0, 209, 178, 0.15), rgba(99, 102, 241, 0.1));
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .app-section-title::before {
            content: '';
            width: 4px;
            height: 18px;
            background: linear-gradient(180deg, var(--accent-primary), #6366f1);
            border-radius: 2px;
        }
        .app-section > .app-grid,
        .app-section > .app-field {
            padding: 24px;
        }
        .app-section > .app-grid {
            padding-top: 24px;
        }
        .app-field {
            margin-bottom: 20px;
        }
        .app-field:last-child {
            margin-bottom: 0;
        }
        .app-field-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.45);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .app-field-value {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .app-field-value a {
            color: var(--accent-primary);
            text-decoration: none;
            font-weight: 500;
        }
        .app-field-value a:hover {
            text-decoration: underline;
        }
        .app-field-value.yes {
            color: #4ade80;
            font-weight: 600;
        }
        .app-field-value.no {
            color: #f87171;
            font-weight: 600;
        }
        .app-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .actions-panel {
            background: linear-gradient(145deg, rgba(30, 32, 40, 0.95), rgba(20, 22, 28, 0.98));
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 24px;
            position: sticky;
            top: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        .actions-panel h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .actions-panel h3::before {
            content: '';
            width: 4px;
            height: 16px;
            background: linear-gradient(180deg, var(--accent-primary), #6366f1);
            border-radius: 2px;
        }
        .form-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.5);
            display: block;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-select {
            width: 100%;
            padding: 12px 16px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 20px;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .status-select:hover, .status-select:focus {
            border-color: var(--accent-primary);
            outline: none;
        }
        .notes-textarea {
            width: 100%;
            padding: 14px 16px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            font-size: 0.9rem;
            font-family: inherit;
            min-height: 140px;
            resize: vertical;
            margin-bottom: 16px;
            transition: border-color 0.2s;
        }
        .notes-textarea:hover, .notes-textarea:focus {
            border-color: var(--accent-primary);
            outline: none;
        }
        .notes-textarea::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }
        .btn-save {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, var(--accent-primary), #00b894);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 209, 178, 0.3);
        }
        .content-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 28px;
        }
        .delete-btn {
            background: transparent;
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            padding: 12px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            width: 100%;
            margin-top: 20px;
            transition: all 0.2s;
        }
        .delete-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.5);
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 24px;
        }
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }
        .back-btn svg {
            width: 18px;
            height: 18px;
        }

        @media (max-width: 1024px) {
            .content-layout {
                grid-template-columns: 1fr;
            }
            .actions-panel {
                position: static;
            }
        }
        @media (max-width: 640px) {
            .app-header {
                padding: 20px;
                border-radius: 16px;
            }
            .app-header-info h1 {
                font-size: 1.35rem;
            }
            .app-section-content {
                padding: 20px;
            }
            .app-grid {
                grid-template-columns: 1fr;
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
                        <a href="<?= url("dashboard") ?>">
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
                        <a href="<?= url("programs") ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="16 18 22 12 16 6"></polyline>
                                <polyline points="8 6 2 12 8 18"></polyline>
                            </svg>
                            <span>Programi</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url("applications") ?>" class="active">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                <path d="M9 14l2 2 4-4"></path>
                            </svg>
                            <span>Prijave</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url("gallery") ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            <span>Galerija</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url("partners") ?>">
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
                        <a href="<?= url("settings") ?>">
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
                <span class="mobile-title">Prijava</span>
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

                <a href="<?= url(
                    "applications-list?program=" . $app["program_id"],
                ) ?>" class="back-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Nazad na prijave
                </a>

                <div class="app-header">
                    <div class="app-header-info">
                        <h1><?= e($app["full_name"]) ?></h1>
                        <p class="meta">
                            Program: <strong><?= e(
                                $app["program_title"],
                            ) ?></strong> |
                            Prijavljeno: <?= date(
                                "d.m.Y. H:i",
                                strtotime($app["created_at"]),
                            ) ?>
                        </p>
                    </div>
                    <span class="status-badge status-<?= Application::getStatusColor(
                        $app["status"],
                    ) ?>">
                        <?= e(Application::getStatusLabel($app["status"])) ?>
                    </span>
                </div>

                <div class="content-layout">
                    <div class="app-details">
                        <!-- Osnovni podaci -->
                        <div class="app-section">
                            <h3 class="app-section-title">1. Osnovni podaci</h3>
                            <div class="app-grid">
                                <div class="app-field">
                                    <div class="app-field-label">Ime i prezime</div>
                                    <div class="app-field-value"><?= e(
                                        $app["full_name"],
                                    ) ?></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Datum rođenja</div>
                                    <div class="app-field-value"><?= date(
                                        "d.m.Y.",
                                        strtotime($app["date_of_birth"]),
                                    ) ?></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Škola</div>
                                    <div class="app-field-value"><?= e(
                                        $app["school"],
                                    ) .
                                        ($app["school_other"]
                                            ? " - " . e($app["school_other"])
                                            : "") ?></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Smjer</div>
                                    <div class="app-field-value"><?= e(
                                        $app["department"],
                                    ) ?:
                                        "-" ?></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Razred</div>
                                    <div class="app-field-value"><?= e(
                                        $app["grade"],
                                    ) ?></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Telefon</div>
                                    <div class="app-field-value"><a href="tel:<?= e(
                                        $app["phone"],
                                    ) ?>"><?= e($app["phone"]) ?></a></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Email</div>
                                    <div class="app-field-value"><a href="mailto:<?= e(
                                        $app["email"],
                                    ) ?>"><?= e($app["email"]) ?></a></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Viber</div>
                                    <div class="app-field-value"><?= e(
                                        $app["viber"],
                                    ) ?:
                                        "-" ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Dostupnost i oprema -->
                        <div class="app-section">
                            <h3 class="app-section-title">2. Dostupnost i oprema</h3>
                            <div class="app-grid">
                                <div class="app-field">
                                    <div class="app-field-label">Može subotom 8-12h?</div>
                                    <div class="app-field-value <?= $app[
                                        "can_attend_saturday"
                                    ]
                                        ? "yes"
                                        : "no" ?>"><?= $app[
    "can_attend_saturday"
]
    ? "DA"
    : "NE" ?></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Može nedjeljom ako treba?</div>
                                    <div class="app-field-value <?= $app[
                                        "can_attend_sunday"
                                    ]
                                        ? "yes"
                                        : "no" ?>"><?= $app["can_attend_sunday"]
    ? "DA"
    : "NE" ?></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Ima računar kod kuće?</div>
                                    <div class="app-field-value <?= $app[
                                        "has_home_computer"
                                    ]
                                        ? "yes"
                                        : "no" ?>"><?= $app["has_home_computer"]
    ? "DA"
    : "NE" ?></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Može platiti Claude Pro?</div>
                                    <div class="app-field-value <?= $app[
                                        "can_pay_claude"
                                    ]
                                        ? "yes"
                                        : "no" ?>"><?= $app["can_pay_claude"]
    ? "DA"
    : "NE" ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Trenutne obaveze -->
                        <div class="app-section">
                            <h3 class="app-section-title">3. Trenutne obaveze</h3>
                            <div class="app-field">
                                <div class="app-field-label">Ima druge kurseve?</div>
                                <div class="app-field-value"><?= $app[
                                    "has_other_courses"
                                ]
                                    ? "DA - " . e($app["other_courses_details"])
                                    : "NE" ?></div>
                            </div>
                            <div class="app-field">
                                <div class="app-field-label">Ima posao?</div>
                                <div class="app-field-value"><?= $app["has_job"]
                                    ? "DA - " . e($app["job_details"])
                                    : "NE" ?></div>
                            </div>
                            <div class="app-field">
                                <div class="app-field-label">Ima druge obaveze?</div>
                                <div class="app-field-value"><?= $app[
                                    "has_other_obligations"
                                ]
                                    ? "DA - " .
                                        e($app["other_obligations_details"])
                                    : "NE" ?></div>
                            </div>
                        </div>

                        <!-- Motivacija i ideja -->
                        <div class="app-section">
                            <h3 class="app-section-title">4. Motivacija i ideja</h3>
                            <div class="app-field">
                                <div class="app-field-label">Zašto želi učestvovati?</div>
                                <div class="app-field-value"><?= nl2br(
                                    e($app["motivation"]),
                                ) ?></div>
                            </div>
                            <div class="app-field">
                                <div class="app-field-label">Ideja projekta</div>
                                <div class="app-field-value"><?= nl2br(
                                    e($app["project_idea"]),
                                ) ?></div>
                            </div>
                            <div class="app-field">
                                <div class="app-field-label">Razumije da nije klasični kurs?</div>
                                <div class="app-field-value <?= $app[
                                    "understands_not_classic_course"
                                ]
                                    ? "yes"
                                    : "no" ?>"><?= $app[
    "understands_not_classic_course"
]
    ? "DA"
    : "NE" ?></div>
                            </div>
                        </div>

                        <!-- Pouzdanost i karakter -->
                        <div class="app-section">
                            <h3 class="app-section-title">5. Pouzdanost i karakter</h3>
                            <div class="app-field">
                                <div class="app-field-label">Da li je odustajao prije?</div>
                                <div class="app-field-value"><?= $app[
                                    "has_quit_before"
                                ]
                                    ? nl2br(e($app["has_quit_before"]))
                                    : "Nije odgovorio/la" ?></div>
                            </div>
                            <div class="app-field">
                                <div class="app-field-label">Garantuje da će završiti?</div>
                                <div class="app-field-value <?= $app[
                                    "guarantees_completion"
                                ]
                                    ? "yes"
                                    : "no" ?>"><?= $app["guarantees_completion"]
    ? "DA"
    : "NE" ?></div>
                            </div>
                            <div class="app-field">
                                <div class="app-field-label">Kako reaguje na probleme?</div>
                                <div class="app-field-value"><?= nl2br(
                                    e($app["problem_reaction"]),
                                ) ?></div>
                            </div>
                        </div>

                        <!-- Budući planovi -->
                        <div class="app-section">
                            <h3 class="app-section-title">6. Budući planovi</h3>
                            <div class="app-field">
                                <div class="app-field-label">Gdje se vidi za 5 godina?</div>
                                <div class="app-field-value"><?= nl2br(
                                    e($app["five_year_plan"]),
                                ) ?></div>
                            </div>
                            <div class="app-field">
                                <div class="app-field-label">Razumije cilj programa (ostanak u ZDK)?</div>
                                <div class="app-field-value <?= $app[
                                    "understands_local_goal"
                                ]
                                    ? "yes"
                                    : "no" ?>"><?= $app[
    "understands_local_goal"
]
    ? "DA"
    : "NE" ?></div>
                            </div>
                        </div>

                        <!-- Škola -->
                        <div class="app-section">
                            <h3 class="app-section-title">7. Škola</h3>
                            <div class="app-grid">
                                <div class="app-field">
                                    <div class="app-field-label">Prosjek ocjena</div>
                                    <div class="app-field-value"><?= e(
                                        $app["school_average"],
                                    ) ?></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Razumije da škola ima prioritet?</div>
                                    <div class="app-field-value <?= $app[
                                        "understands_school_priority"
                                    ]
                                        ? "yes"
                                        : "no" ?>"><?= $app[
    "understands_school_priority"
]
    ? "DA"
    : "NE" ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Saglasnost roditelja -->
                        <div class="app-section">
                            <h3 class="app-section-title">8. Saglasnost roditelja</h3>
                            <div class="app-grid">
                                <div class="app-field">
                                    <div class="app-field-label">Roditelji upoznati?</div>
                                    <div class="app-field-value <?= $app[
                                        "parents_informed"
                                    ]
                                        ? "yes"
                                        : "no" ?>"><?= $app["parents_informed"]
    ? "DA"
    : "NE" ?></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Roditelji će platiti Claude?</div>
                                    <div class="app-field-value <?= $app[
                                        "parents_will_pay"
                                    ]
                                        ? "yes"
                                        : "no" ?>"><?= $app["parents_will_pay"]
    ? "DA"
    : "NE" ?></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Ime roditelja</div>
                                    <div class="app-field-value"><?= e(
                                        $app["parent_name"],
                                    ) ?></div>
                                </div>
                                <div class="app-field">
                                    <div class="app-field-label">Telefon roditelja</div>
                                    <div class="app-field-value"><a href="tel:<?= e(
                                        $app["parent_phone"],
                                    ) ?>"><?= e(
    $app["parent_phone"],
) ?></a></div>
                                </div>
                            </div>
                        </div>

                        <!-- Dodatne informacije -->
                        <div class="app-section">
                            <h3 class="app-section-title">9. Dodatne informacije</h3>
                            <div class="app-field">
                                <div class="app-field-label">Prethodno iskustvo</div>
                                <div class="app-field-value"><?= $app[
                                    "previous_experience"
                                ]
                                    ? nl2br(e($app["previous_experience"]))
                                    : "-" ?></div>
                            </div>
                            <div class="app-field">
                                <div class="app-field-label">Dodatne napomene kandidata</div>
                                <div class="app-field-value"><?= $app[
                                    "additional_info"
                                ]
                                    ? nl2br(e($app["additional_info"]))
                                    : "-" ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Panel -->
                    <div>
                        <div class="actions-panel">
                            <h3>Akcije</h3>

                            <form method="POST" action="<?= url(
                                "api/application-status",
                            ) ?>">
                                <input type="hidden" name="id" value="<?= $app[
                                    "id"
                                ] ?>">
                                <label class="form-label">Status prijave</label>
                                <select name="status" class="status-select" onchange="this.form.submit()">
                                    <?php foreach (
                                        $statuses
                                        as $key => $label
                                    ): ?>
                                    <option value="<?= $key ?>" <?= $app[
    "status"
] === $key
    ? "selected"
    : "" ?>><?= e($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>

                            <form method="POST" action="<?= url(
                                "api/application-notes",
                            ) ?>">
                                <input type="hidden" name="id" value="<?= $app[
                                    "id"
                                ] ?>">
                                <label class="form-label">Admin bilješke</label>
                                <textarea name="notes" class="notes-textarea" placeholder="Interne bilješke o kandidatu..."><?= e(
                                    $app["admin_notes"],
                                ) ?></textarea>
                                <button type="submit" class="btn-save">Sačuvaj bilješke</button>
                            </form>

                            <form method="POST" action="<?= url(
                                "api/application-delete",
                            ) ?>" onsubmit="return confirm('Da li ste sigurni da želite obrisati ovu prijavu?');">
                                <input type="hidden" name="id" value="<?= $app[
                                    "id"
                                ] ?>">
                                <button type="submit" class="delete-btn">Obriši prijavu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <script src="<?= asset("js/admin.js") ?>"></script>
</body>
</html>
