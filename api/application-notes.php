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
$notes = $_POST["notes"] ?? "";

if (!$id) {
    setFlash("flash_error", "Nevažeći podaci.");
    header("Location: " . url("applications"));
    exit();
}

try {
    Application::updateNotes($id, trim($notes));
    setFlash("flash_success", "Bilješke su sačuvane.");
} catch (Exception $e) {
    setFlash("flash_error", "Greška pri čuvanju bilješke.");
}

header("Location: " . url("application-view?id=" . $id));
