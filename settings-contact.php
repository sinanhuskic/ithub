<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Setting.php";

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

$settings = [
    "contact_phone" => trim($_POST["contact_phone"] ?? ""),
    "contact_email" => trim($_POST["contact_email"] ?? ""),
    "contact_address" => trim($_POST["contact_address"] ?? ""),
    "contact_city" => trim($_POST["contact_city"] ?? ""),
    "contact_facebook" => trim($_POST["contact_facebook"] ?? ""),
    "contact_instagram" => trim($_POST["contact_instagram"] ?? ""),
    "contact_linkedin" => trim($_POST["contact_linkedin"] ?? ""),
];

try {
    Setting::updateMultiple($settings, "contact");
    setFlash("flash_success", "Kontakt informacije su ažurirane.");
} catch (Exception $e) {
    setFlash("flash_error", "Greška pri ažuriranju.");
}
header("Location: " . url("settings"));
