<?php
/**
 * IT Hub Zavidovići - Helper Functions
 */

/**
 * Escape HTML
 */
function e($string)
{
    return htmlspecialchars($string ?? "", ENT_QUOTES, "UTF-8");
}

/**
 * Asset URL helper
 */
function asset($path)
{
    return SITE_URL . "/public/" . ltrim($path, "/");
}

/**
 * URL helper
 */
function url($path = "")
{
    return SITE_URL . "/" . ltrim($path, "/");
}

/**
 * Redirect helper
 */
function redirect($path)
{
    header("Location: " . url($path));
    exit;
}

/**
 * Get flash message
 */
function flash($key, $default = null)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $value = $_SESSION[$key] ?? $default;
    unset($_SESSION[$key]);
    return $value;
}

/**
 * Set flash message
 */
function setFlash($key, $value)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION[$key] = $value;
}
