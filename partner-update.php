<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Partner.php";

Auth::requireAuth();

$id = $_GET["id"] ?? null;
if (!$id || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . url("partners"));
    exit;
}

if (!Auth::verifyCsrfToken($_POST["_token"] ?? "")) {
    setFlash("flash_error", "Nevažeći sigurnosni token.");
    header("Location: " . url("partner-edit?id=" . $id));
    exit;
}

$partner = Partner::find($id);
if (!$partner) {
    setFlash("flash_error", "Partner nije pronađen.");
    header("Location: " . url("partners"));
    exit;
}

$data = [
    "name" => trim($_POST["name"] ?? ""),
    "website_url" => trim($_POST["website_url"] ?? ""),
    "active" => isset($_POST["active"]) ? 1 : 0,
];

if (!empty($_FILES["logo"]["name"])) {
    try {
        $data["logo_path"] = Partner::uploadLogo($_FILES["logo"]);
    } catch (Exception $e) {
        setFlash("flash_error", $e->getMessage());
        header("Location: " . url("partner-edit?id=" . $id));
        exit;
    }
}

try {
    Partner::update($id, $data);
    setFlash("flash_success", "Partner je uspješno ažuriran.");
    header("Location: " . url("partners"));
} catch (Exception $e) {
    setFlash("flash_error", "Greška pri ažuriranju.");
    header("Location: " . url("partner-edit?id=" . $id));
}
