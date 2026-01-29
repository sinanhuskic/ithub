<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Partner.php";

Auth::requireAuth();

$partners = Partner::all();
$title = "Partneri";
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
    <h1 class="page-title">Partneri</h1>
    <div class="page-actions">
        <a href="<?= url("partner-create") ?>" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Novi partner
        </a>
    </div>
</div>

<?php if (!empty($partners)): ?>
<div class="partners-grid-admin" id="partnersGrid">
    <?php foreach ($partners as $partner): ?>
    <div class="partner-card" data-id="<?= $partner["id"] ?>">
        <div class="partner-card-drag">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="5" r="1"></circle>
                <circle cx="9" cy="12" r="1"></circle>
                <circle cx="9" cy="19" r="1"></circle>
                <circle cx="15" cy="5" r="1"></circle>
                <circle cx="15" cy="12" r="1"></circle>
                <circle cx="15" cy="19" r="1"></circle>
            </svg>
        </div>

        <div class="partner-card-logo">
            <img src="<?= asset($partner["logo_path"]) ?>" alt="<?= e(
    $partner["name"],
) ?>">
        </div>

        <div class="partner-card-info">
            <h3 class="partner-card-name"><?= e($partner["name"]) ?></h3>
            <?php if ($partner["website_url"]): ?>
            <a href="<?= e(
                $partner["website_url"],
            ) ?>" target="_blank" class="partner-card-url">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                </svg>
                <?= e(parse_url($partner["website_url"], PHP_URL_HOST)) ?>
            </a>
            <?php endif; ?>
        </div>

        <div class="partner-card-actions">
            <label class="toggle" title="Aktivan/Neaktivan">
                <input type="checkbox"
                       <?= $partner["active"] ? "checked" : "" ?>
                       data-url="<?= url(
                           "admin/partner-toggle?id=" . $partner["id"],
                       ) ?>">
                <span class="toggle-slider"></span>
            </label>

            <a href="<?= url("partner-edit?id=" . $partner["id"]) ?>"
               class="btn btn-secondary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Uredi
            </a>

            <a href="<?= url("partner-delete?id=" . $partner["id"]) ?>"
               class="btn btn-danger btn-sm"
               data-delete>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                Obriši
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<input type="hidden" id="reorderUrl" value="<?= url(
    "admin/partner-reorder",
) ?>">
<?php else: ?>
<div class="card">
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        <h3>Nema partnera</h3>
        <p>Još niste dodali nijednog partnera.</p>
        <a href="<?= url("partner-create") ?>" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Dodaj partnera
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
            <p class="modal-message">Jeste li sigurni da želite obrisati partnera <strong id="deletePartnerName"></strong>?</p>
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteModal');
    const partnerNameEl = document.getElementById('deletePartnerName');
    const confirmBtn = document.getElementById('deleteConfirmBtn');

    // Open modal on delete button click
    document.querySelectorAll('[data-delete]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            const partnerCard = this.closest('.partner-card');
            const partnerName = partnerCard.querySelector('.partner-card-name').textContent;
            const deleteUrl = this.href;

            partnerNameEl.textContent = partnerName;
            confirmBtn.href = deleteUrl;

            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    // Close modal
    modal.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', function() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        });
    });

    // Close on overlay click
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});
</script>

            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="<?= asset('js/admin.js') ?>"></script>
</body>
</html>
