<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Partner.php";

Auth::requireAuth();

$id = $_GET["id"] ?? null;
if (!$id) {
    header("Location: " . url("partners"));
    exit;
}

try {
    Partner::delete($id);
    setFlash("flash_success", "Partner je obrisan.");
} catch (Exception $e) {
    setFlash("flash_error", "Greška pri brisanju.");
}
header("Location: " . url("partners"));
