<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Partner.php";

Auth::requireAuth();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . url("partners"));
    exit;
}

if (!Auth::verifyCsrfToken($_POST["_token"] ?? "")) {
    setFlash("flash_error", "Nevažeći sigurnosni token.");
    header("Location: " . url("partner-create"));
    exit;
}

$name = trim($_POST["name"] ?? "");
$websiteUrl = trim($_POST["website_url"] ?? "");

if (!$name) {
    setFlash("flash_error", "Naziv partnera je obavezan.");
    header("Location: " . url("partner-create"));
    exit;
}

$logoPath = "";
if (!empty($_FILES["logo"]["name"])) {
    try {
        $logoPath = Partner::uploadLogo($_FILES["logo"]);
    } catch (Exception $e) {
        setFlash("flash_error", $e->getMessage());
        header("Location: " . url("partner-create"));
        exit;
    }
}

try {
    Partner::create([
        "name" => $name,
        "logo_path" => $logoPath,
        "website_url" => $websiteUrl,
        "sort_order" => Partner::getMaxSortOrder() + 1,
        "active" => isset($_POST["active"]) ? 1 : 0,
    ]);
    setFlash("flash_success", "Partner je uspješno dodan.");
    header("Location: " . url("partners"));
} catch (Exception $e) {
    setFlash("flash_error", "Greška pri kreiranju partnera.");
    header("Location: " . url("partner-create"));
}
