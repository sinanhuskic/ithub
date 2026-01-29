<?php
/**
 * IT Hub Zavidovići - Admin Logout
 */
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/auth.php";

Auth::logout();
header("Location: " . url("login"));
exit;
