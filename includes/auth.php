<?php
/**
 * IT Hub Zavidovici - Authentication Class
 */

class Auth
{
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION["admin_user_id"]) && !empty($_SESSION["admin_user_id"]);
    }

    public static function user()
    {
        if (!self::check()) {
            return null;
        }
        return [
            "id" => $_SESSION["admin_user_id"],
            "name" => $_SESSION["admin_user_name"] ?? "Admin",
            "email" => $_SESSION["admin_user_email"] ?? "",
        ];
    }

    public static function login($user)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);
        $_SESSION["admin_user_id"] = $user["id"];
        $_SESSION["admin_user_name"] = $user["name"];
        $_SESSION["admin_user_email"] = $user["email"];
        $_SESSION["admin_login_time"] = time();
    }

    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
    }

    public static function requireAuth()
    {
        if (!self::check()) {
            header("Location: " . url("login"));
            exit;
        }
        self::noCacheHeaders();
    }

    public static function guest()
    {
        if (self::check()) {
            header("Location: " . url("dashboard"));
            exit;
        }
    }

    public static function noCacheHeaders()
    {
        header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
    }

    public static function generateCsrfToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }
        return $_SESSION["csrf_token"];
    }

    public static function verifyCsrfToken($token)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION["csrf_token"]) && hash_equals($_SESSION["csrf_token"], $token);
    }

    public static function csrfField()
    {
        $token = self::generateCsrfToken();
        return "<input type=\"hidden\" name=\"_token\" value=\"" . e($token) . "\">";
    }
}
