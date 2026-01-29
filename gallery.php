<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Gallery.php";

Auth::requireAuth();

$images = Gallery::all();
$title = "Galerija";
$active = "gallery";
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
    <h1 class="page-title">Galerija</h1>
    <div class="page-actions">
        <button type="button" class="btn btn-primary" data-modal="uploadModal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="17 8 12 3 7 8"></polyline>
                <line x1="12" y1="3" x2="12" y2="15"></line>
            </svg>
            Nova slika
        </button>
    </div>
</div>

<?php if (!empty($images)): ?>
<div class="gallery-cards" id="galleryGrid">
    <?php foreach ($images as $image): ?>
    <div class="gallery-card" data-id="<?= $image["id"] ?>">
        <div class="gallery-card-drag" title="Prevuci za promjenu redoslijeda">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="5" r="1"></circle>
                <circle cx="9" cy="12" r="1"></circle>
                <circle cx="9" cy="19" r="1"></circle>
                <circle cx="15" cy="5" r="1"></circle>
                <circle cx="15" cy="12" r="1"></circle>
                <circle cx="15" cy="19" r="1"></circle>
            </svg>
        </div>

        <div class="gallery-card-image">
            <img src="<?= asset(
                $image["thumb_path"] ?: $image["image_path"],
            ) ?>"
                 alt="<?= e($image["alt_text"]) ?>">
            <?php if ($image["size_class"] === "large"): ?>
            <span class="gallery-card-badge">2x2</span>
            <?php endif; ?>
        </div>

        <div class="gallery-card-info">
            <p class="gallery-card-alt"><?= e($image["alt_text"]) ?:
                '<span class="text-muted">Bez opisa</span>' ?></p>
        </div>

        <div class="gallery-card-actions">
            <label class="toggle" title="Aktivna/Neaktivna">
                <input type="checkbox"
                       <?= $image["active"] ? "checked" : "" ?>
                       data-url="<?= url(
                           "api/gallery-toggle?id=" . $image["id"],
                       ) ?>">
                <span class="toggle-slider"></span>
            </label>

            <button type="button" class="btn btn-secondary btn-sm" data-modal="editModal-<?= $image[
                "id"
            ] ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Uredi
            </button>

            <button type="button" class="btn btn-danger btn-sm" data-delete data-image-alt="<?= e(
                $image["alt_text"],
            ) ?:
                "ovu sliku" ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                Obriši
            </button>
        </div>
    </div>

    <!-- Edit Modal for this image -->
    <div class="modal" id="editModal-<?= $image["id"] ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Uredi sliku</h3>
                <button type="button" class="modal-close" data-modal-close>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form method="POST" action="<?= url(
                "api/gallery-update?id=" . $image["id"],
            ) ?>">
                <?= Auth::csrfField() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="image-preview-large">
                            <img src="<?= asset(
                                $image["image_path"],
                            ) ?>" alt="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="alt_text_<?= $image[
                            "id"
                        ] ?>">Alt tekst (opis slike)</label>
                        <input type="text" id="alt_text_<?= $image[
                            "id"
                        ] ?>" name="alt_text"
                               class="form-input" value="<?= e(
                                   $image["alt_text"],
                               ) ?>"
                               placeholder="Npr. Polaznici na radionici web developmenta">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="size_class_<?= $image[
                            "id"
                        ] ?>">Veličina u galeriji</label>
                        <select id="size_class_<?= $image[
                            "id"
                        ] ?>" name="size_class" class="form-select">
                            <option value="normal" <?= $image["size_class"] ===
                            "normal"
                                ? "selected"
                                : "" ?>>Normalna (1x1)</option>
                            <option value="large" <?= $image["size_class"] ===
                            "large"
                                ? "selected"
                                : "" ?>>Velika (2x2)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-modal-close>Odustani</button>
                    <button type="submit" class="btn btn-primary">Sačuvaj</button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<input type="hidden" id="reorderUrl" value="<?= url(
    "api/gallery-reorder",
) ?>">
<?php else: ?>
<div class="card">
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <circle cx="8.5" cy="8.5" r="1.5"></circle>
            <polyline points="21 15 16 10 5 21"></polyline>
        </svg>
        <h3>Nema slika</h3>
        <p>Još niste dodali nijednu sliku u galeriju.</p>
        <button type="button" class="btn btn-primary" data-modal="uploadModal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="17 8 12 3 7 8"></polyline>
                <line x1="12" y1="3" x2="12" y2="15"></line>
            </svg>
            Upload slike
        </button>
    </div>
</div>
<?php endif; ?>

<!-- Upload Modal -->
<div class="modal" id="uploadModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Upload nove slike</h3>
            <button type="button" class="modal-close" data-modal-close>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form method="POST" action="<?= url(
            "api/gallery-upload",
        ) ?>" enctype="multipart/form-data">
            <?= Auth::csrfField() ?>
            <div class="modal-body">
                <div class="form-group">
                    <label class="file-upload">
                        <input type="file" name="image" accept="image/*" required data-preview="uploadPreview">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <p>Kliknite ili prevucite sliku ovdje</p>
                        <span class="form-hint">JPG, PNG, GIF, WebP - max 10MB</span>
                    </label>
                    <img id="uploadPreview" src="" alt="" class="upload-preview-image">
                </div>
                <div class="form-group">
                    <label class="form-label" for="upload_alt_text">Alt tekst (opis slike)</label>
                    <input type="text" id="upload_alt_text" name="alt_text" class="form-input"
                           placeholder="Npr. Polaznici na radionici web developmenta">
                </div>
                <div class="form-group">
                    <label class="form-label" for="upload_size_class">Veličina u galeriji</label>
                    <select id="upload_size_class" name="size_class" class="form-select">
                        <option value="normal">Normalna (1x1)</option>
                        <option value="large">Velika (2x2)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Odustani</button>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

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
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
            </div>
            <p class="modal-message">Jeste li sigurni da želite obrisati <strong id="deleteImageAlt"></strong>?</p>
            <p class="modal-hint">Slika će biti trajno obrisana sa servera. Ova akcija se ne može poništiti.</p>
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
/* Gallery Cards */
.gallery-cards {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.gallery-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.gallery-card:hover {
    border-color: rgba(99, 102, 241, 0.4);
}

.gallery-card.animate {
    transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1),
                border-color 0.2s ease,
                box-shadow 0.2s ease;
}

.gallery-card.dragging {
    z-index: 1000;
    border-color: var(--primary);
    box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4), 0 0 0 2px var(--primary);
    background: linear-gradient(135deg, rgba(20, 20, 35, 0.98), rgba(30, 30, 50, 0.98));
    cursor: grabbing;
    pointer-events: none;
}

.gallery-card-drag {
    cursor: grab;
    padding: 8px;
    color: var(--text-muted);
    transition: color 0.2s ease;
    flex-shrink: 0;
}

.gallery-card-drag:hover {
    color: var(--primary);
}

.gallery-card-drag:active {
    cursor: grabbing;
}

.gallery-card-drag svg {
    width: 20px;
    height: 20px;
}

.gallery-card-image {
    width: 120px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    position: relative;
    background: rgba(0, 0, 0, 0.2);
}

.gallery-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-card-badge {
    position: absolute;
    top: 6px;
    right: 6px;
    background: var(--primary);
    color: white;
    font-size: 10px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 4px;
}

.gallery-card-info {
    flex: 1;
    min-width: 0;
}

.gallery-card-alt {
    font-size: 0.95rem;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 0;
}

.gallery-card-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

/* Image preview in edit modal */
.image-preview-large {
    width: 100%;
    max-height: 300px;
    border-radius: 12px;
    overflow: hidden;
    background: rgba(0, 0, 0, 0.2);
    margin-bottom: 16px;
}

.image-preview-large img {
    width: 100%;
    height: auto;
    max-height: 300px;
    object-fit: contain;
}

/* Upload zone in modal */
#uploadModal .file-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: 180px;
    padding: 32px 24px;
    border: 2px dashed var(--border-color);
    border-radius: 12px;
    background: rgba(99, 102, 241, 0.05);
    cursor: pointer;
    transition: all 0.2s ease;
}

#uploadModal .file-upload:hover {
    border-color: var(--primary);
    background: rgba(99, 102, 241, 0.1);
}

#uploadModal .file-upload svg {
    width: 48px;
    height: 48px;
    color: var(--primary);
    margin-bottom: 16px;
}

#uploadModal .file-upload p {
    font-size: 1rem;
    color: var(--text-primary);
    margin: 0 0 8px 0;
}

#uploadModal .file-upload .form-hint {
    font-size: 0.85rem;
    color: var(--text-muted);
}

#uploadModal .file-upload input[type="file"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

/* Upload preview */
.upload-preview-image {
    display: none;
    width: 100%;
    max-height: 200px;
    margin-top: 16px;
    border-radius: 8px;
    object-fit: contain;
    background: rgba(0, 0, 0, 0.2);
}

.upload-preview-image[src]:not([src=""]) {
    display: block;
}

/* Delete modal styles */
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
@media (max-width: 768px) {
    .gallery-card {
        flex-wrap: wrap;
        padding: 16px;
    }

    .gallery-card-image {
        width: 80px;
        height: 60px;
    }

    .gallery-card-info {
        flex: 1 1 calc(100% - 140px);
    }

    .gallery-card-actions {
        width: 100%;
        justify-content: flex-end;
        padding-top: 12px;
        border-top: 1px solid var(--border-color);
        margin-top: 4px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete modal
    const deleteModal = document.getElementById('deleteModal');
    const deleteImageAlt = document.getElementById('deleteImageAlt');
    const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');

    document.querySelectorAll('[data-delete]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.gallery-card');
            const imageId = card.dataset.id;
            const altText = this.dataset.imageAlt || 'ovu sliku';

            deleteImageAlt.textContent = altText;
            deleteConfirmBtn.href = '<?= url(
                "api/gallery-delete?id=",
            ) ?>' + imageId;

            deleteModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    // Close modals
    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    // Close on overlay click
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.active').forEach(modal => {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
    });

    // Gallery drag & drop
    initGallerySortable();
});

/**
 * Gallery Drag & Drop
 */
function initGallerySortable() {
    const grid = document.getElementById('galleryGrid');
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

    grid.querySelectorAll('.gallery-card').forEach(card => {
        const handle = card.querySelector('.gallery-card-drag');
        if (!handle) return;

        handle.addEventListener('mousedown', (e) => startDrag(e, card));
        handle.addEventListener('touchstart', (e) => startDrag(e, card), { passive: false });
    });

    function startDrag(e, card) {
        e.preventDefault();

        state.dragging = card;
        state.cards = Array.from(grid.querySelectorAll('.gallery-card'));
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
                saveGalleryOrder();
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

    function saveGalleryOrder() {
        const url = document.getElementById('reorderUrl')?.value;
        if (!url) return;

        const cards = grid.querySelectorAll('.gallery-card[data-id]');
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
                showGalleryNotification('Redoslijed sačuvan', 'success');
            } else {
                showGalleryNotification(data.message || 'Greška pri čuvanju', 'danger');
            }
        })
        .catch(() => {
            showGalleryNotification('Greška pri čuvanju', 'danger');
        });
    }
}

function showGalleryNotification(message, type = 'success') {
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

    <script src="<?= asset('js/admin.js') ?>"></script>
</body>
</html>
