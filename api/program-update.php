<?php
require_once dirname(__DIR__) . "/includes/config.php";
require_once dirname(__DIR__) . "/includes/functions.php";
require_once dirname(__DIR__) . "/includes/database.php";
require_once dirname(__DIR__) . "/includes/auth.php";
require_once dirname(__DIR__) . "/includes/models/Program.php";

Auth::requireAuth();

$id = $_GET["id"] ?? null;
if (!$id || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . url("programs"));
    exit();
}

if (!Auth::verifyCsrfToken($_POST["_token"] ?? "")) {
    setFlash("flash_error", "Nevažeći sigurnosni token.");
    header("Location: " . url("program-edit?id=" . $id));
    exit();
}

$program = Program::find($id);
if (!$program) {
    setFlash("flash_error", "Program nije pronađen.");
    header("Location: " . url("programs"));
    exit();
}

$data = [
    "title" => trim($_POST["title"] ?? ""),
    "description" => trim($_POST["description"] ?? ""),
    "duration" => trim($_POST["duration"] ?? ""),
    "level" => $_POST["level"] ?? "Početnik",
    "icon" => $_POST["icon"] ?? "code",
    "featured" => isset($_POST["featured"]),
    "active" => isset($_POST["active"]),
    "technologies" => $_POST["technologies"] ?? [],
    "period" => trim($_POST["period"] ?? ""),
    "format" => trim($_POST["format"] ?? ""),
    "participants" => trim($_POST["participants"] ?? ""),
    "status" => $_POST["status"] ?? "U pripremi",
    "full_description" => trim($_POST["full_description"] ?? ""),
    "highlights" => array_filter($_POST["highlights"] ?? []),
    "requirements" => trim($_POST["requirements"] ?? ""),
    "registration_url" => trim($_POST["registration_url"] ?? ""),
    "sort_order" => (int) ($_POST["sort_order"] ?? 0),
];

if (!$data["title"]) {
    setFlash("flash_error", "Naziv programa je obavezan.");
    header("Location: " . url("program-edit?id=" . $id));
    exit();
}

try {
    Program::update($id, $data);
    setFlash("flash_success", "Program je uspješno ažuriran.");
    header("Location: " . url("programs"));
} catch (Exception $e) {
    setFlash("flash_error", "Greška pri ažuriranju programa.");
    header("Location: " . url("program-edit?id=" . $id));
}
