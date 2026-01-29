<?php
require_once dirname(__DIR__) . "/includes/config.php";
require_once dirname(__DIR__) . "/includes/functions.php";
require_once dirname(__DIR__) . "/includes/database.php";
require_once dirname(__DIR__) . "/includes/auth.php";
require_once dirname(__DIR__) . "/includes/models/Setting.php";

Auth::requireAuth();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . url("settings"));
    exit;
}

if (!Auth::verifyCsrfToken($_POST["_token"] ?? "")) {
    setFlash("flash_error", "Nevažeći sigurnosni token.");
    header("Location: " . url("settings"));
    exit;
}

$settings = [];
for ($i = 1; $i <= 4; $i++) {
    $settings["stat_{$i}_current"] = (int)($_POST["stat_{$i}_current"] ?? 0);
    $settings["stat_{$i}_planned"] = (int)($_POST["stat_{$i}_planned"] ?? 0);
    $settings["stat_{$i}_label"] = trim($_POST["stat_{$i}_label"] ?? "");
}

try {
    Setting::updateMultiple($settings, "stats");
    setFlash("flash_success", "Statistike su ažurirane.");
} catch (Exception $e) {
    setFlash("flash_error", "Greška pri ažuriranju.");
}
header("Location: " . url("settings"));
