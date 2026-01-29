<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Program.php";

Auth::requireAuth();

$id = $_GET["id"] ?? null;
if (!$id) {
    header("Location: " . url("programs"));
    exit;
}

try {
    Program::delete($id);
    setFlash("flash_success", "Program je obrisan.");
} catch (Exception $e) {
    setFlash("flash_error", "Greška pri brisanju.");
}
header("Location: " . url("programs"));
