<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Program.php";

Auth::requireAuth();

$programs = Program::all();
$title = "Programi";
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
    <h1 class="page-title">Programi</h1>
    <div class="page-actions">
        <a href="<?= url("program-create") ?>" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Novi program
        </a>
    </div>
</div>

<?php if (!empty($programs)): ?>
<div class="programs-grid-admin" id="programsGrid">
    <?php foreach ($programs as $index => $program): ?>
    <div class="program-card-admin" data-id="<?= $program["id"] ?>">
        <div class="program-card-drag" title="Prevuci za promjenu redoslijeda">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="5" r="1"></circle>
                <circle cx="9" cy="12" r="1"></circle>
                <circle cx="9" cy="19" r="1"></circle>
                <circle cx="15" cy="5" r="1"></circle>
                <circle cx="15" cy="12" r="1"></circle>
                <circle cx="15" cy="19" r="1"></circle>
            </svg>
        </div>

        <div class="program-card-content">
            <div class="program-card-header">
                <div class="program-card-number"><?= $index + 1 ?></div>
                <div class="program-card-status">
                    <?php
                    $statusClass = "badge-info";
                    if ($program["status"] === "Završen") {
                        $statusClass = "badge-success";
                    } elseif ($program["status"] === "Uskoro") {
                        $statusClass = "badge-warning";
                    } elseif ($program["status"] === "U pripremi") {
                        $statusClass = "badge-primary";
                    } elseif ($program["status"] === "Prijave u toku") {
                        $statusClass = "badge-info";
                    }
                    ?>
                    <span class="badge <?= $statusClass ?>"><?= e(
    $program["status"],
) ?></span>
                </div>
            </div>

            <div class="program-card-body">
                <h3 class="program-card-title"><?= e($program["title"]) ?></h3>
                <?php if ($program["description"]): ?>
                <p class="program-card-desc"><?= e(
                    substr($program["description"], 0, 100),
                ) ?>...</p>
                <?php endif; ?>

                <div class="program-card-meta">
                    <div class="program-card-meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 20V10"></path>
                            <path d="M18 20V4"></path>
                            <path d="M6 20v-4"></path>
                        </svg>
                        <span><?= e($program["level"]) ?></span>
                    </div>
                    <?php if ($program["featured"]): ?>
                    <div class="program-card-meta-item featured">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <span>Istaknut</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="program-card-footer">
                <label class="toggle" title="Aktivan/Neaktivan">
                    <input type="checkbox"
                           <?= $program["active"] ? "checked" : "" ?>
                           data-url="<?= url(
                               "api/program-toggle?id=" . $program["id"],
                           ) ?>">
                    <span class="toggle-slider"></span>
                </label>

                <div class="program-card-actions">
                    <a href="<?= url("program-edit?id=" . $program["id"]) ?>"
                       class="btn btn-secondary btn-sm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Uredi
                    </a>
                    <a href="<?= url(
                        "api/program-delete?id=" . $program["id"],
                    ) ?>"
                       class="btn btn-danger btn-sm"
                       data-delete>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<input type="hidden" id="reorderUrl" value="<?= url("api/program-reorder") ?>">
<?php else: ?>
<div class="card">
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="16 18 22 12 16 6"></polyline>
            <polyline points="8 6 2 12 8 18"></polyline>
        </svg>
        <h3>Nema programa</h3>
        <p>Još niste dodali nijedan program.</p>
        <a href="<?= url("program-create") ?>" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Dodaj program
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Potvrda brisanja</h3>
            <button type="button" class="modal-close" data-modal-close>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-icon modal-icon-danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
            </div>
            <p class="modal-message">Jeste li sigurni da želite obrisati program <strong id="deleteProgramName"></strong>?</p>
            <p class="modal-hint">Ova akcija se ne može poništiti.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-modal-close>Odustani</button>
            <a href="#" id="deleteConfirmBtn" class="btn btn-danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                Da, obriši
            </a>
        </div>
    </div>
</div>

<style>
/* Programs Grid Admin */
.programs-grid-admin {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.program-card-admin {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    display: flex;
    align-items: stretch;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.3s cubic-bezier(0.25, 1, 0.5, 1);
    position: relative;
}

.program-card-admin:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow-glow);
}

.program-card-admin.dragging {
    z-index: 1000;
    border-color: var(--primary);
    box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4), 0 0 0 2px var(--primary);
    background: linear-gradient(135deg, rgba(20, 20, 35, 0.98), rgba(30, 30, 50, 0.98));
    cursor: grabbing;
    pointer-events: none;
}

.program-card-admin.animate {
    transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1), border-color 0.2s ease, box-shadow 0.2s ease;
}

/* Drag Handle */
.program-card-drag {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    min-width: 48px;
    background: rgba(255, 255, 255, 0.02);
    border-right: 1px solid var(--border-color);
    border-radius: 16px 0 0 16px;
    cursor: grab;
    color: var(--text-muted);
    transition: background 0.2s, color 0.2s;
}

.program-card-drag:hover {
    background: rgba(99, 102, 241, 0.1);
    color: var(--primary-light);
}

.program-card-drag:active {
    cursor: grabbing;
}

.program-card-drag svg {
    width: 20px;
    height: 20px;
}

.program-card-drag:hover svg {
    animation: dragHandleWiggle 0.5s ease-in-out;
}

@keyframes dragHandleWiggle {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(-5deg); }
    75% { transform: rotate(5deg); }
}

/* Card Content */
.program-card-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.program-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.02);
    border-bottom: 1px solid var(--border-color);
}

.program-card-number {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    color: white;
}

.program-card-body {
    padding: 20px;
    flex: 1;
}

.program-card-title {
    font-size: 1.15rem;
    font-weight: 600;
    margin-bottom: 8px;
    line-height: 1.4;
}

.program-card-desc {
    font-size: 0.9rem;
    color: var(--text-muted);
    line-height: 1.5;
    margin-bottom: 16px;
}

.program-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.program-card-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    color: var(--text-secondary);
    background: rgba(255, 255, 255, 0.05);
    padding: 6px 12px;
    border-radius: 8px;
}

.program-card-meta-item svg {
    width: 14px;
    height: 14px;
    opacity: 0.7;
}

.program-card-meta-item.featured {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.15), rgba(245, 158, 11, 0.15));
    color: #fbbf24;
}

.program-card-meta-item.featured svg {
    opacity: 1;
    fill: currentColor;
}

.program-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: rgba(0, 0, 0, 0.1);
    border-top: 1px solid var(--border-color);
}

.program-card-actions {
    display: flex;
    gap: 8px;
}

/* Modal styles */
.modal-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.modal-icon svg {
    width: 32px;
    height: 32px;
}

.modal-icon-danger {
    background: rgba(239, 68, 68, 0.1);
    border: 2px solid rgba(239, 68, 68, 0.3);
}

.modal-icon-danger svg {
    stroke: var(--danger);
}

.modal-message {
    text-align: center;
    font-size: 1rem;
    margin-bottom: 8px;
}

.modal-hint {
    text-align: center;
    font-size: 0.875rem;
    color: var(--text-muted);
}

#deleteModal .modal-body {
    padding: 32px 24px;
}

#deleteModal .modal-footer {
    justify-content: center;
    gap: 16px;
}

/* Responsive */
@media (max-width: 640px) {
    .program-card-admin {
        flex-direction: column;
    }

    .program-card-drag {
        width: 100%;
        min-width: unset;
        height: 40px;
        border-right: none;
        border-bottom: 1px solid var(--border-color);
        border-radius: 16px 16px 0 0;
    }

    .program-card-footer {
        flex-direction: column;
        gap: 12px;
    }

    .program-card-actions {
        width: 100%;
    }

    .program-card-actions .btn {
        flex: 1;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete Modal
    const modal = document.getElementById('deleteModal');
    const programNameEl = document.getElementById('deleteProgramName');
    const confirmBtn = document.getElementById('deleteConfirmBtn');

    document.querySelectorAll('[data-delete]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const programCard = this.closest('.program-card-admin');
            const programName = programCard.querySelector('.program-card-title').textContent;
            const deleteUrl = this.href;

            programNameEl.textContent = programName;
            confirmBtn.href = deleteUrl;

            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    modal.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', function() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        });
    });

    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Drag & Drop Reorder
    initProgramsSortable();
});

/**
 * Programs Drag & Drop
 */
function initProgramsSortable() {
    const grid = document.getElementById('programsGrid');
    if (!grid) return;

    let state = {
        dragging: null,
        startY: 0,
        startIndex: 0,
        currentIndex: 0,
        cards: [],
        rects: [],
        cardHeight: 0
    };

    grid.querySelectorAll('.program-card-admin').forEach(card => {
        const handle = card.querySelector('.program-card-drag');
        if (!handle) return;

        handle.addEventListener('mousedown', (e) => startDrag(e, card));
        handle.addEventListener('touchstart', (e) => startDrag(e, card), { passive: false });
    });

    function startDrag(e, card) {
        e.preventDefault();

        state.dragging = card;
        state.cards = Array.from(grid.querySelectorAll('.program-card-admin'));
        state.startIndex = state.cards.indexOf(card);
        state.currentIndex = state.startIndex;
        state.startY = e.clientY || e.touches[0].clientY;

        state.rects = state.cards.map(c => c.getBoundingClientRect());
        state.cardHeight = state.rects[0].height + 16;

        card.classList.add('dragging');
        card.style.zIndex = '1000';

        state.cards.forEach((c, i) => {
            if (i !== state.startIndex) {
                c.classList.add('animate');
            }
        });

        document.addEventListener('mousemove', onDrag);
        document.addEventListener('mouseup', endDrag);
        document.addEventListener('touchmove', onDrag, { passive: false });
        document.addEventListener('touchend', endDrag);
    }

    function onDrag(e) {
        if (!state.dragging) return;
        e.preventDefault();

        const clientY = e.clientY || (e.touches?.[0]?.clientY ?? 0);
        const deltaY = clientY - state.startY;

        state.dragging.style.transform = `translateY(${deltaY}px) scale(1.01)`;

        const draggedCenterY = state.rects[state.startIndex].top + state.rects[state.startIndex].height / 2 + deltaY;

        let newIndex = state.startIndex;

        for (let i = 0; i < state.cards.length; i++) {
            const cardCenterY = state.rects[i].top + state.rects[i].height / 2;

            if (i < state.startIndex && draggedCenterY < cardCenterY) {
                newIndex = i;
                break;
            } else if (i > state.startIndex && draggedCenterY > cardCenterY) {
                newIndex = i;
            }
        }

        if (newIndex !== state.currentIndex) {
            state.currentIndex = newIndex;

            state.cards.forEach((card, i) => {
                if (i === state.startIndex) return;

                let offset = 0;
                if (state.startIndex < state.currentIndex) {
                    if (i > state.startIndex && i <= state.currentIndex) {
                        offset = -state.cardHeight;
                    }
                } else {
                    if (i >= state.currentIndex && i < state.startIndex) {
                        offset = state.cardHeight;
                    }
                }

                card.style.transform = `translateY(${offset}px)`;
            });
        }
    }

    function endDrag() {
        if (!state.dragging) return;

        document.removeEventListener('mousemove', onDrag);
        document.removeEventListener('mouseup', endDrag);
        document.removeEventListener('touchmove', onDrag);
        document.removeEventListener('touchend', endDrag);

        const draggedCard = state.dragging;
        const fromIndex = state.startIndex;
        const toIndex = state.currentIndex;

        draggedCard.classList.add('animate');
        draggedCard.style.transform = '';

        setTimeout(() => {
            state.cards.forEach(c => {
                c.classList.remove('animate', 'dragging');
                c.style.transform = '';
                c.style.zIndex = '';
            });

            if (fromIndex !== toIndex) {
                const referenceCard = state.cards[toIndex];
                if (fromIndex < toIndex) {
                    referenceCard.after(draggedCard);
                } else {
                    referenceCard.before(draggedCard);
                }
                updateNumbers();
                saveOrder();
            }

            state = {
                dragging: null,
                startY: 0,
                startIndex: 0,
                currentIndex: 0,
                cards: [],
                rects: [],
                cardHeight: 0
            };
        }, 300);
    }

    function updateNumbers() {
        const cards = grid.querySelectorAll('.program-card-admin');
        cards.forEach((card, i) => {
            const numberEl = card.querySelector('.program-card-number');
            if (numberEl) {
                numberEl.textContent = i + 1;
            }
        });
    }

    function saveOrder() {
        const url = document.getElementById('reorderUrl')?.value;
        if (!url) return;

        const cards = grid.querySelectorAll('.program-card-admin[data-id]');
        const order = Array.from(cards).map((card, i) => ({
            id: parseInt(card.dataset.id),
            sort_order: i + 1
        }));

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ order })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                cards.forEach((card, i) => {
                    setTimeout(() => {
                        card.style.borderColor = 'var(--secondary)';
                        card.style.boxShadow = '0 0 16px rgba(16, 185, 129, 0.3)';
                        setTimeout(() => {
                            card.style.borderColor = '';
                            card.style.boxShadow = '';
                        }, 300);
                    }, i * 30);
                });
                showNotification('Redoslijed sačuvan', 'success');
            } else {
                showNotification(data.message || 'Greška pri čuvanju', 'danger');
            }
        })
        .catch(() => {
            showNotification('Greška pri čuvanju', 'danger');
        });
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.innerHTML = `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            ${type === 'success'
                ? '<polyline points="20 6 9 17 4 12"></polyline>'
                : '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>'
            }
        </svg>
        <span>${message}</span>
    `;

    const container = document.querySelector('.page-content');
    if (container) {
        container.insertBefore(notification, container.firstChild);
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
}
</script>

            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="<?= asset("js/admin.js") ?>"></script>
</body>
</html>
