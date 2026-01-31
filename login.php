<?php
/**
 * IT Hub Zavidovići - Admin Login
 */
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/models/User.php";

// Redirect if already logged in
Auth::guest();

// Handle login POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!Auth::verifyCsrfToken($_POST["_token"] ?? "")) {
        $_SESSION["login_error"] = "Nevažeći sigurnosni token.";
    } else {
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        $user = User::findByEmail($email);

        if ($user && User::verifyPassword($password, $user["password"])) {
            Auth::login($user);
            header("Location: " . url("dashboard"));
            exit();
        } else {
            $_SESSION["login_error"] = "Pogrešan email ili lozinka.";
        }
    }
}

$title = "Prijava - Admin Panel";
?>
<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? "Prijava - Admin Panel") ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= asset(
        "images/favicon.svg",
    ) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #10b981;
            --accent: #06b6d4;
            --bg-dark: #0a0a0f;
            --bg-darker: #050507;
            --bg-card: rgba(15, 15, 25, 0.9);
            --text-primary: #ffffff;
            --text-secondary: #a1a1aa;
            --text-muted: #71717a;
            --border-color: rgba(99, 102, 241, 0.2);
            --gradient-primary: linear-gradient(135deg, #6366f1 0%, #06b6d4 50%, #10b981 100%);
            --shadow-glow: 0 0 40px rgba(99, 102, 241, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .grid-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(rgba(99, 102, 241, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99, 102, 241, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: -1;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow-glow);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-logo img {
            height: 48px;
            margin-bottom: 16px;
            filter: brightness(0) saturate(100%) invert(48%) sepia(79%) saturate(2476%)
                hue-rotate(200deg) brightness(103%) contrast(97%);
        }

        .login-logo h1 {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-logo p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            font-size: 1rem;
            font-family: inherit;
            color: var(--text-primary);
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-input::placeholder {
            color: var(--text-muted);
        }

        .btn-login {
            width: 100%;
            padding: 14px 24px;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            color: white;
            background: var(--gradient-primary);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.875rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-message svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 24px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: var(--primary-light);
        }
    </style>
</head>
<body>
    <div class="grid-background"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <img src="<?= asset("images/logo-icon.svg") ?>" alt="IT Hub">
                <h1>IT Hub Admin</h1>
                <p>Prijavite se za pristup admin panelu</p>
            </div>

            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION["login_error"])): ?>
                <div class="error-message">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span><?= e($_SESSION["login_error"]) ?></span>
                </div>
                <?php unset($_SESSION["login_error"]); ?>
            <?php endif;
            ?>

            <form method="POST" action="<?= url("login") ?>">
                <?= Auth::csrfField() ?>

                <div class="form-group">
                    <label class="form-label" for="email">Email adresa</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        placeholder="email@primjer.ba"
                        required
                        autocomplete="email"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Lozinka</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Unesite lozinku"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <button type="submit" class="btn-login">Prijavi se</button>
            </form>

            <a href="<?= url("/") ?>" class="back-link">
                &larr; Nazad na stranicu
            </a>
        </div>
    </div>
</body>
</html>
