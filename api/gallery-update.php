<?php
require_once dirname(__DIR__) . "/includes/config.php";
require_once dirname(__DIR__) . "/includes/functions.php";
require_once dirname(__DIR__) . "/includes/database.php";
require_once dirname(__DIR__) . "/includes/auth.php";
require_once dirname(__DIR__) . "/includes/models/Gallery.php";

Auth::requireAuth();

$id = $_GET["id"] ?? null;
$isAjax = isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest";

if (!$id || $_SERVER["REQUEST_METHOD"] !== "POST") {
    if ($isAjax) { echo json_encode(["success" => false]); exit; }
    header("Location: " . url("gallery"));
    exit;
}

$data = [
    "alt_text" => trim($_POST["alt_text"] ?? ""),
    "size_class" => $_POST["size_class"] ?? "normal",
];

try {
    Gallery::update($id, $data);
    if ($isAjax) {
        echo json_encode(["success" => true]);
    } else {
        setFlash("flash_success", "Slika je ažurirana.");
        header("Location: " . url("gallery"));
    }
} catch (Exception $e) {
    if ($isAjax) {
        echo json_encode(["success" => false, "message" => "Greška"]);
    } else {
        setFlash("flash_error", "Greška pri ažuriranju.");
        header("Location: " . url("gallery"));
    }
}
