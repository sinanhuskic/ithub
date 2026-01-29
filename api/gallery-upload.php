<?php
require_once dirname(__DIR__) . "/includes/config.php";
require_once dirname(__DIR__) . "/includes/functions.php";
require_once dirname(__DIR__) . "/includes/database.php";
require_once dirname(__DIR__) . "/includes/auth.php";
require_once dirname(__DIR__) . "/includes/models/Gallery.php";

Auth::requireAuth();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . url("gallery"));
    exit;
}

if (!Auth::verifyCsrfToken($_POST["_token"] ?? "")) {
    setFlash("flash_error", "Nevažeći sigurnosni token.");
    header("Location: " . url("gallery"));
    exit;
}

if (empty($_FILES["image"]["name"])) {
    setFlash("flash_error", "Morate odabrati sliku.");
    header("Location: " . url("gallery"));
    exit;
}

$altText = trim($_POST["alt_text"] ?? "");
$sizeClass = $_POST["size_class"] ?? "normal";

try {
    Gallery::uploadImage($_FILES["image"], $altText, $sizeClass);
    setFlash("flash_success", "Slika je uspješno uploadana.");
} catch (Exception $e) {
    setFlash("flash_error", $e->getMessage());
}
header("Location: " . url("gallery"));
