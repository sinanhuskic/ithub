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
    "about_description" => trim($_POST["about_description"] ?? ""),
    "about_feature_1_title" => trim($_POST["about_feature_1_title"] ?? ""),
    "about_feature_1_desc" => trim($_POST["about_feature_1_desc"] ?? ""),
    "about_feature_2_title" => trim($_POST["about_feature_2_title"] ?? ""),
    "about_feature_2_desc" => trim($_POST["about_feature_2_desc"] ?? ""),
    "about_feature_3_title" => trim($_POST["about_feature_3_title"] ?? ""),
    "about_feature_3_desc" => trim($_POST["about_feature_3_desc"] ?? ""),
    "about_feature_4_title" => trim($_POST["about_feature_4_title"] ?? ""),
    "about_feature_4_desc" => trim($_POST["about_feature_4_desc"] ?? ""),
];

try {
    Setting::updateMultiple($settings, "about");
    setFlash("flash_success", "O nama sekcija je ažurirana.");
} catch (Exception $e) {
    setFlash("flash_error", "Greška pri ažuriranju.");
}
header("Location: " . url("settings"));
