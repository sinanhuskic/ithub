<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/Program.php";

Auth::requireAuth();

header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
$order = $input["order"] ?? [];

if (empty($order)) {
    echo json_encode(["success" => false, "message" => "Nema podataka"]);
    exit;
}

try {
    foreach ($order as $item) {
        Program::updateOrder($item["id"], $item["sort_order"]);
    }
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Greška"]);
}
