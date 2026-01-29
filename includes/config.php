<?php
/**
 * IT Hub Zavidovici - Configuration
 */

// Database Configuration
define("DB_HOST", "localhost");
define("DB_NAME", "ithubba_db");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_CHARSET", "utf8mb4");

// Site Configuration - Auto-detect base URL
$protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") ? "https://" : "http://";
$host = $_SERVER["HTTP_HOST"] ?? "localhost";
$scriptDir = str_replace(chr(92), "/", dirname($_SERVER["SCRIPT_NAME"]));
$baseUrl = ($scriptDir === "/") ? "" : $scriptDir;
define("SITE_URL", $baseUrl);
define("SITE_NAME", "IT Hub Zavidovici");
define("SITE_DOMAIN", $protocol . $host . $baseUrl);

// Error Reporting - OFF for production
error_reporting(0);
ini_set("display_errors", 0);

// Timezone
date_default_timezone_set("Europe/Sarajevo");

// Session Configuration
ini_set("session.cookie_httponly", 1);
ini_set("session.use_only_cookies", 1);
if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
    ini_set("session.cookie_secure", 1);
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Headers
header_remove("X-Powered-By");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
