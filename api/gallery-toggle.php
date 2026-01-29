<?php
require_once dirname(__DIR__) . "/includes/config.php";
require_once dirname(__DIR__) . "/includes/functions.php";
require_once dirname(__DIR__) . "/includes/database.php";
require_once dirname(__DIR__) . "/includes/auth.php";
require_once dirname(__DIR__) . "/includes/models/Gallery.php";

Auth::requireAuth();

$id = $_GET["id"] ?? null;
$isAjax = isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest";

if (!$id) {
    if ($isAjax) { echo json_encode(["success" => false]); exit; }
    header("Location: " . url("gallery"));
    exit;
}

Gallery::toggleActive($id);

if ($isAjax) {
    echo json_encode(["success" => true]);
} else {
    header("Location: " . url("gallery"));
}
