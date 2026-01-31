<?php
require_once dirname(__DIR__) . "/includes/config.php";
require_once dirname(__DIR__) . "/includes/functions.php";
require_once dirname(__DIR__) . "/includes/database.php";
require_once dirname(__DIR__) . "/includes/auth.php";
require_once dirname(__DIR__) . "/includes/models/Application.php";

Auth::requireAuth();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . url("applications"));
    exit();
}

$id = $_POST["id"] ?? null;

if (!$id) {
    setFlash("flash_error", "Nevažeći podaci.");
    header("Location: " . url("applications"));
    exit();
}

try {
    Application::delete($id);
    setFlash("flash_success", "Prijava je obrisana.");
} catch (Exception $e) {
    setFlash("flash_error", "Greška pri brisanju prijave.");
}

header("Location: " . url("applications"));
