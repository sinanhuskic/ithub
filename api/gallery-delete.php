<?php
require_once dirname(__DIR__) . "/includes/config.php";
require_once dirname(__DIR__) . "/includes/functions.php";
require_once dirname(__DIR__) . "/includes/database.php";
require_once dirname(__DIR__) . "/includes/auth.php";
require_once dirname(__DIR__) . "/includes/models/Gallery.php";

Auth::requireAuth();

$id = $_GET["id"] ?? null;
if (!$id) {
    header("Location: " . url("gallery"));
    exit;
}

try {
    Gallery::delete($id);
    setFlash("flash_success", "Slika je obrisana.");
} catch (Exception $e) {
    setFlash("flash_error", "Greška pri brisanju.");
}
header("Location: " . url("gallery"));
