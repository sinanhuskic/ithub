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
$status = $_POST["status"] ?? null;

if (!$id || !$status) {
    setFlash("flash_error", "Nevažeći podaci.");
    header("Location: " . url("applications"));
    exit();
}

$validStatuses = array_keys(Application::getStatuses());
if (!in_array($status, $validStatuses)) {
    setFlash("flash_error", "Nevažeći status.");
    header("Location: " . url("application-view?id=" . $id));
    exit();
}

try {
    Application::updateStatus($id, $status);
    setFlash("flash_success", "Status prijave je ažuriran.");
} catch (Exception $e) {
    setFlash("flash_error", "Greška pri ažuriranju statusa.");
}

header("Location: " . url("application-view?id=" . $id));
