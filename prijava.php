<?php
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/models/Program.php";

// Dohvati program ID iz URL-a
$programId = $_GET["program"] ?? null;

if (!$programId) {
    header("Location: " . url(""));
    exit();
}

$program = Program::findWithDecoded($programId);

if (!$program || !$program["active"]) {
    header("Location: " . url(""));
    exit();
}

// Provjeri da li program ima registration_url (znači da prima prijave)
if (empty($program["registration_url"])) {
    header("Location: " . url(""));
    exit();
}

$success = flash("flash_success");
$error = flash("flash_error");
?>
<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava - <?= e($program["title"]) ?> | IT Hub Zavidovići</title>
    <link rel="icon" type="image/svg+xml" href="<?= asset(
        "images/favicon.svg",
    ) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #10b981;
            --accent: #06b6d4;
            --accent-pink: #ec4899;
            --bg-dark: #0a0a0f;
            --bg-darker: #050507;
            --bg-card: rgba(15, 15, 25, 0.8);
            --bg-card-hover: rgba(25, 25, 40, 0.9);
            --text-primary: #ffffff;
            --text-secondary: #a1a1aa;
            --text-muted: #71717a;
            --border-color: rgba(99, 102, 241, 0.2);
            --border-glow: rgba(99, 102, 241, 0.5);
            --gradient-primary: linear-gradient(135deg, #6366f1 0%, #06b6d4 50%, #10b981 100%);
            --gradient-text: linear-gradient(90deg, #6366f1, #06b6d4, #10b981);
            --shadow-glow: 0 0 40px rgba(99, 102, 241, 0.3);
            --transition-fast: 0.2s ease;
            --transition-normal: 0.3s ease;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Grid Background */
        .grid-background {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-image:
                linear-gradient(rgba(99, 102, 241, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99, 102, 241, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 0;
        }

        /* Particles */
        .particles {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        .particle {
            position: absolute;
            width: 4px; height: 4px;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0;
            animation: float-particle 15s infinite;
        }
        @keyframes float-particle {
            0%, 100% { transform: translateY(100vh); opacity: 0; }
            10% { opacity: 0.3; }
            90% { opacity: 0.3; }
            100% { transform: translateY(-100vh); opacity: 0; }
        }
        .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 2s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 4s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 1s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 3s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 5s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 2.5s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 1.5s; }
        .particle:nth-child(9) { left: 90%; animation-delay: 3.5s; }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            z-index: 1000;
            padding: 20px 0;
            background: rgba(10, 10, 15, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
        }
        .nav-container {
            width: 100%;
            padding: 0 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .logo-icon {
            height: 32px;
            filter: brightness(0) saturate(100%) invert(48%) sepia(79%) saturate(2476%) hue-rotate(200deg) brightness(103%) contrast(97%);
        }
        .logo-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.2rem;
            font-weight: 700;
            background: var(--gradient-text);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .back-link {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all var(--transition-fast);
        }
        .back-link:hover {
            color: var(--text-primary);
            background: rgba(99, 102, 241, 0.1);
        }
        .back-link svg { width: 18px; height: 18px; }

        /* Main Container */
        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 24px 40px;
            position: relative;
            z-index: 1;
        }

        /* Wizard Container */
        .wizard-container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Step Indicator */
        .step-indicator {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }
        .step-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--border-color);
            transition: all var(--transition-normal);
        }
        .step-dot.active {
            background: var(--primary);
            box-shadow: 0 0 10px var(--primary);
        }
        .step-dot.completed {
            background: var(--secondary);
        }

        /* Wizard Card */
        .wizard-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 48px 40px;
            position: relative;
            overflow: hidden;
        }
        .wizard-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 4px;
            background: var(--gradient-primary);
        }

        /* Step Content */
        .step {
            display: none;
            animation: fadeIn 0.4s ease;
        }
        .step.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Intro Step */
        .intro-content {
            text-align: center;
        }
        .intro-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            background: var(--gradient-primary);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-glow);
        }
        .intro-icon svg {
            width: 40px;
            height: 40px;
            stroke: white;
        }
        .intro-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .intro-title .gradient-text {
            background: var(--gradient-text);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .intro-subtitle {
            color: var(--text-secondary);
            margin-bottom: 32px;
            font-size: 1.1rem;
        }
        .intro-features {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 40px;
            text-align: left;
        }
        .intro-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: rgba(99, 102, 241, 0.05);
            border-radius: 12px;
            color: var(--text-secondary);
        }
        .intro-feature svg {
            width: 20px;
            height: 20px;
            stroke: var(--secondary);
            flex-shrink: 0;
        }
        .intro-feature-link {
            text-decoration: none;
            cursor: pointer;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(6, 182, 212, 0.1) 100%);
            border: 2px solid var(--primary);
            border-radius: 12px;
            padding: 16px 20px;
            margin-top: 8px;
            transition: all var(--transition-normal);
            position: relative;
            overflow: visible;
            flex-direction: column;
            gap: 10px;
        }
        .intro-feature-link .link-top-row {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
        }
        .intro-feature-link .link-top-row span {
            flex: 1;
            text-align: center;
        }
        .intro-feature-link .badge {
            background: var(--gradient-primary);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 6px;
            letter-spacing: 0.5px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
        }
        .intro-feature-link:hover {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.25) 0%, rgba(6, 182, 212, 0.2) 100%);
            border-color: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
        }
        .intro-feature-link svg:first-child {
            stroke: var(--primary);
            width: 24px;
            height: 24px;
        }
        .intro-feature-link span {
            color: var(--text-primary);
            font-weight: 600;
        }
        .intro-feature-link .external-icon {
            width: 18px;
            height: 18px;
            stroke: var(--primary);
            transition: all var(--transition-fast);
        }
        .intro-feature-link:hover .external-icon {
            stroke: var(--accent);
            transform: translate(2px, -2px);
        }

        /* Step Header */
        .step-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: var(--gradient-primary);
            border-radius: 14px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 16px;
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
        }
        .step-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .step-description {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 24px;
        }
        .form-label {
            display: block;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 10px;
        }
        .form-label.required::after {
            content: " *";
            color: var(--accent-pink);
        }
        .form-input, .form-select, .form-textarea {
            display: block;
            width: 100%;
            padding: 14px 18px;
            background: rgba(99, 102, 241, 0.05);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 1rem;
            font-family: inherit;
            transition: all var(--transition-normal);
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }
        .form-input::placeholder, .form-textarea::placeholder {
            color: var(--text-muted);
        }
        .form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%236366f1' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 18px;
            padding-right: 45px;
        }
        .form-select option {
            background: var(--bg-dark);
            color: var(--text-primary);
        }
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .form-hint {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 10px;
            padding: 10px 14px;
            background: rgba(6, 182, 212, 0.05);
            border-radius: 8px;
            border-left: 3px solid var(--accent);
        }
        .form-hint svg {
            width: 16px;
            height: 16px;
            stroke: var(--accent);
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Radio & Checkbox */
        .radio-group, .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .radio-group.horizontal {
            flex-direction: row;
            flex-wrap: wrap;
        }
        .radio-option, .checkbox-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            background: rgba(99, 102, 241, 0.05);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            cursor: pointer;
            transition: all var(--transition-normal);
        }
        .radio-group.horizontal .radio-option {
            flex: 1;
            min-width: 120px;
            justify-content: center;
        }
        .radio-option:hover, .checkbox-option:hover {
            border-color: var(--border-glow);
            background: rgba(99, 102, 241, 0.1);
        }
        .radio-option input, .checkbox-option input {
            width: 20px;
            height: 20px;
            accent-color: var(--primary);
            cursor: pointer;
        }
        .radio-option:has(input:checked), .checkbox-option:has(input:checked) {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.15);
        }
        .radio-option span, .checkbox-option span {
            font-size: 0.95rem;
            color: var(--text-secondary);
        }
        .radio-option:has(input:checked) span, .checkbox-option:has(input:checked) span {
            color: var(--primary-light);
        }

        /* Custom Date Picker */
        .custom-date-picker { position: relative; }
        .date-display {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            background: rgba(99, 102, 241, 0.05);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            cursor: pointer;
            transition: all var(--transition-normal);
        }
        .date-display:hover { border-color: var(--border-glow); }
        .date-display.active {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }
        .date-display svg { width: 20px; height: 20px; stroke: var(--primary); }
        .date-text { color: var(--text-muted); }
        .date-text.has-value { color: var(--text-primary); }
        .date-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 100%;
            min-width: 300px;
            background: linear-gradient(165deg, rgba(15, 15, 25, 0.98) 0%, rgba(10, 10, 18, 0.98) 100%);
            border: 1px solid rgba(99, 102, 241, 0.25);
            border-radius: 16px;
            padding: 16px;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all var(--transition-normal);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }
        .date-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .date-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .date-selectors { display: flex; gap: 8px; flex: 1; justify-content: center; }

        /* Custom Dropdown */
        .custom-dropdown {
            position: relative;
        }
        .custom-dropdown-trigger {
            padding: 8px 14px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all var(--transition-fast);
            white-space: nowrap;
        }
        .custom-dropdown-trigger:hover {
            background: rgba(99, 102, 241, 0.2);
            border-color: var(--primary);
        }
        .custom-dropdown-trigger.active {
            background: rgba(99, 102, 241, 0.2);
            border-color: var(--primary);
        }
        .custom-dropdown-trigger svg {
            width: 14px;
            height: 14px;
            stroke: var(--primary-light);
            transition: transform var(--transition-fast);
        }
        .custom-dropdown-trigger.active svg {
            transform: rotate(180deg);
        }
        .custom-dropdown-menu {
            position: absolute;
            top: calc(100% + 4px);
            left: 50%;
            transform: translateX(-50%);
            min-width: 100%;
            max-height: 200px;
            overflow-y: auto;
            background: rgba(15, 15, 25, 0.98);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 10px;
            padding: 6px;
            z-index: 200;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-fast);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }
        .custom-dropdown-menu.show {
            opacity: 1;
            visibility: visible;
        }
        .custom-dropdown-menu::-webkit-scrollbar {
            width: 6px;
        }
        .custom-dropdown-menu::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-dropdown-menu::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }
        .custom-dropdown-item {
            padding: 8px 12px;
            color: var(--text-secondary);
            font-size: 0.85rem;
            cursor: pointer;
            border-radius: 6px;
            transition: all var(--transition-fast);
            text-align: center;
        }
        .custom-dropdown-item:hover {
            background: rgba(99, 102, 241, 0.15);
            color: var(--text-primary);
        }
        .custom-dropdown-item.selected {
            background: var(--gradient-primary);
            color: white;
            font-weight: 600;
        }
        .date-nav {
            width: 32px;
            height: 32px;
            background: rgba(99, 102, 241, 0.1);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-fast);
        }
        .date-nav:hover { background: rgba(99, 102, 241, 0.2); }
        .date-nav svg { width: 16px; height: 16px; stroke: var(--primary-light); }
        .date-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            margin-bottom: 8px;
        }
        .date-weekdays span {
            text-align: center;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-muted);
            padding: 6px 0;
        }
        .date-days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; }
        .date-day {
            aspect-ratio: 1;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: var(--text-secondary);
            font-size: 0.85rem;
            cursor: pointer;
            transition: all var(--transition-fast);
        }
        .date-day:hover:not(.disabled) { background: rgba(99, 102, 241, 0.15); }
        .date-day.selected {
            background: var(--gradient-primary);
            color: white;
            font-weight: 600;
        }
        .date-day.disabled { opacity: 0.3; cursor: not-allowed; }
        .date-day.other-month { opacity: 0.3; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 32px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-normal);
            font-family: inherit;
        }
        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-glow);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(99, 102, 241, 0.4);
        }
        .btn-secondary {
            background: rgba(99, 102, 241, 0.1);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
        }
        .btn-secondary:hover {
            background: rgba(99, 102, 241, 0.2);
            color: var(--text-primary);
        }
        .btn-large {
            padding: 18px 48px;
            font-size: 1.1rem;
        }
        .btn svg { width: 20px; height: 20px; }

        /* Navigation */
        .step-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }
        .step-navigation.center {
            justify-content: center;
        }

        /* Progress Text */
        .progress-text {
            text-align: center;
            margin-top: 24px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Agreement Box */
        .agreement-box {
            padding: 20px;
            background: rgba(99, 102, 241, 0.05);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 24px;
        }
        .agreement-box:has(input:checked) {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
        }
        .agreement-box .checkbox-option {
            padding: 0;
            background: none;
            border: none;
        }
        .agreement-box .checkbox-option span {
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: var(--secondary);
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        /* Validation Error States */
        .form-input.error, .form-select.error, .form-textarea.error, .date-display.error {
            border-color: #ef4444 !important;
            background: rgba(239, 68, 68, 0.05) !important;
        }
        .form-input.error:focus, .form-select.error:focus, .form-textarea.error:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15) !important;
        }
        .radio-group.error .radio-option, .checkbox-group.error .checkbox-option {
            border-color: #ef4444;
        }
        .error-message {
            color: #f87171;
            font-size: 0.8rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .error-message svg {
            width: 14px;
            height: 14px;
            stroke: #f87171;
        }


        /* Responsive */
        @media (max-width: 640px) {
            .wizard-card { padding: 32px 24px; }
            .form-row { grid-template-columns: 1fr; }
            .radio-group.horizontal { flex-direction: column; }
            .radio-group.horizontal .radio-option { justify-content: flex-start; }
            .step-navigation { flex-direction: column-reverse; gap: 12px; }
            .step-navigation .btn { width: 100%; }
            .btn-large { padding: 16px 32px; }
            .intro-title { font-size: 1.5rem; }
            .logo-text { display: none; }
        }
    </style>
</head>
<body>
    <div class="grid-background"></div>
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <nav class="navbar">
        <div class="nav-container">
            <a href="<?= url("") ?>" class="logo">
                <img src="<?= asset(
                    "images/logo-icon.svg",
                ) ?>" alt="IT Hub" class="logo-icon">
                <span class="logo-text">IT Hub Zavidovići</span>
            </a>
            <a href="<?= url("") ?>" class="back-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="2" y1="12" x2="22" y2="12"/>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                </svg>
                <span>Posjeti web sajt</span>
            </a>
        </div>
    </nav>

    <main class="main-container">
        <div class="wizard-container">
            <!-- Step Indicator -->
            <div class="step-indicator" id="stepIndicator">
                <div class="step-dot active"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
            </div>

            <?php if ($success): ?>
            <div class="alert alert-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <span><?= e($success) ?></span>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span><?= e($error) ?></span>
            </div>
            <?php endif; ?>

            <div class="wizard-card">
                <form method="POST" action="<?= url(
                    "api/application-store.php",
                ) ?>" id="applicationForm">
                    <input type="hidden" name="program_id" value="<?= e(
                        $program["id"],
                    ) ?>">

                    <!-- Step 0: Intro -->
                    <div class="step active" data-step="0">
                        <div class="intro-content">
                            <div class="intro-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                            </div>
                            <h1 class="intro-title">
                                Prijava za<br>
                                <span class="gradient-text"><?= e(
                                    $program["title"],
                                ) ?></span>
                            </h1>
                            <p class="intro-subtitle"><?= e(
                                $program["period"],
                            ) ?></p>

                            <div class="intro-features">
                                <div class="intro-feature">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    <span>Popunjavanje traje oko 5 minuta</span>
                                </div>
                                <div class="intro-feature">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    <span>Sva polja označena sa * su obavezna</span>
                                </div>
                                <div class="intro-feature">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    <span>Odgovori iskreno - nema pogrešnih odgovora</span>
                                </div>
                                <a href="<?= url(
                                    "public/pdf/Opis programa i kriteriji za odabir polaznika.pdf",
                                ) ?>" target="_blank" rel="noopener noreferrer" class="intro-feature intro-feature-link">
                                    <div class="link-top-row">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                            <line x1="16" y1="13" x2="8" y2="13"/>
                                            <line x1="16" y1="17" x2="8" y2="17"/>
                                            <polyline points="10 9 9 9 8 9"/>
                                        </svg>
                                        <span>Opis programa i kriteriji za odabir polaznika</span>
                                        <svg class="external-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                            <polyline points="15 3 21 3 21 9"/>
                                            <line x1="10" y1="14" x2="21" y2="3"/>
                                        </svg>
                                    </div>
                                    <span class="badge">OBAVEZNO PROČITAJ PRIJE PRIJAVE</span>
                                </a>
                            </div>

                            <div class="step-navigation center">
                                <button type="button" class="btn btn-primary btn-large" onclick="nextStep()">
                                    Zainteresovan/a sam
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M5 12h14M12 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 1: Osnovni podaci -->
                    <div class="step" data-step="1">
                        <div class="step-header">
                            <div class="step-number">1</div>
                            <h2 class="step-title">Osnovni podaci</h2>
                            <p class="step-description">Unesite vaše lične podatke</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Ime i prezime</label>
                            <input type="text" name="full_name" class="form-input" placeholder="Vaše puno ime i prezime" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Datum rođenja</label>
                            <div class="custom-date-picker" id="datePicker">
                                <input type="hidden" id="date_of_birth" name="date_of_birth" required>
                                <div class="date-display" id="dateDisplay">
                                    <span class="date-text" id="dateText">Odaberi datum</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                </div>
                                <div class="date-dropdown" id="dateDropdown">
                                    <div class="date-header">
                                        <button type="button" class="date-nav" id="prevMonth">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="15 18 9 12 15 6"/>
                                            </svg>
                                        </button>
                                        <div class="date-selectors">
                                            <div class="custom-dropdown" id="monthDropdown">
                                                <div class="custom-dropdown-trigger" id="monthTrigger">
                                                    <span id="monthText">Januar</span>
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="6 9 12 15 18 9"/>
                                                    </svg>
                                                </div>
                                                <div class="custom-dropdown-menu" id="monthMenu"></div>
                                            </div>
                                            <div class="custom-dropdown" id="yearDropdown">
                                                <div class="custom-dropdown-trigger" id="yearTrigger">
                                                    <span id="yearText">2008</span>
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="6 9 12 15 18 9"/>
                                                    </svg>
                                                </div>
                                                <div class="custom-dropdown-menu" id="yearMenu"></div>
                                            </div>
                                        </div>
                                        <button type="button" class="date-nav" id="nextMonth">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="9 18 15 12 9 6"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="date-weekdays">
                                        <span>Po</span><span>Ut</span><span>Sr</span><span>Če</span><span>Pe</span><span>Su</span><span>Ne</span>
                                    </div>
                                    <div class="date-days" id="dateDays"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Trenutni status</label>
                            <div class="radio-group">
                                <label class="radio-option">
                                    <input type="radio" name="applicant_status" value="ucenik" required onchange="toggleStudentFields(true)">
                                    <span>Učenik/ca srednje škole</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="applicant_status" value="student" onchange="toggleStudentFields(false)">
                                    <span>Student/ica</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="applicant_status" value="zaposlen" onchange="toggleStudentFields(false)">
                                    <span>Zaposlen/a</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="applicant_status" value="nezaposlen" onchange="toggleStudentFields(false)">
                                    <span>Nezaposlen/a</span>
                                </label>
                            </div>
                        </div>

                        <div id="studentFields" style="display: none;">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Razred</label>
                                    <select name="grade" id="grade" class="form-select">
                                        <option value="">Odaberi</option>
                                        <option value="I razred">I razred</option>
                                        <option value="II razred">II razred</option>
                                        <option value="III razred">III razred</option>
                                        <option value="IV razred">IV razred</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Škola</label>
                                    <select name="school" id="school" class="form-select">
                                        <option value="">Odaberi</option>
                                        <option value="Tehnička škola">Tehnička škola</option>
                                        <option value="Gimnazija">Gimnazija</option>
                                        <option value="Drugo">Druga škola</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="school_other_group" style="display: none;">
                                <label class="form-label">Naziv škole</label>
                                <input type="text" name="school_other" class="form-input" placeholder="Unesite naziv škole">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Smjer</label>
                                <input type="text" name="department" class="form-input" placeholder="npr. Elektrotehničar računara">
                            </div>
                        </div>

                        <div id="nonStudentFields" style="display: none;">
                            <div class="form-group">
                                <label class="form-label">Zanimanje / Fakultet</label>
                                <input type="text" name="occupation" class="form-input" placeholder="npr. Ekonomski fakultet, Programer...">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Telefon</label>
                                <input type="tel" name="phone" class="form-input" placeholder="+387 6x xxx xxx" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Viber (ako je različit)</label>
                                <input type="tel" name="viber" class="form-input" placeholder="+387 6x xxx xxx">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Email</label>
                            <input type="email" name="email" class="form-input" placeholder="vas@email.com" required>
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                                </svg>
                                Nazad
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">
                                Dalje
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Dostupnost i oprema -->
                    <div class="step" data-step="2">
                        <div class="step-header">
                            <div class="step-number">2</div>
                            <h2 class="step-title">Dostupnost i oprema</h2>
                            <p class="step-description">Provjeravamo tehničke uslove</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Da li možeš KATEGORIČKI GARANTOVATI prisustvo subotom od 8:00 do 12:00 tokom cijelog programa (3 mjeseca)?</label>
                            <div class="radio-group horizontal">
                                <label class="radio-option">
                                    <input type="radio" name="can_attend_saturday" value="1" required>
                                    <span>DA</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="can_attend_saturday" value="0">
                                    <span>NE</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Da li si spreman/a doći u nedjelju u istom terminu ako mentor ne može u subotu?</label>
                            <div class="radio-group horizontal">
                                <label class="radio-option">
                                    <input type="radio" name="can_attend_sunday" value="1" required>
                                    <span>DA</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="can_attend_sunday" value="0">
                                    <span>NE</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Da li imaš vlastiti računar kod kuće?</label>
                            <div class="radio-group horizontal">
                                <label class="radio-option">
                                    <input type="radio" name="has_home_computer" value="1" required>
                                    <span>DA</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="has_home_computer" value="0">
                                    <span>NE</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Da li možeš obezbijediti Claude AI Pro (20$/mjesečno) za 3 mjeseca?</label>
                            <div class="radio-group horizontal">
                                <label class="radio-option">
                                    <input type="radio" name="can_pay_claude" value="1" required>
                                    <span>DA</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="can_pay_claude" value="0">
                                    <span>NE</span>
                                </label>
                            </div>
                            <div class="form-hint">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                                <span>Jedini trošak za kandidata. Ilegalne verzije nisu dozvoljene.</span>
                            </div>
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                                </svg>
                                Nazad
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">
                                Dalje
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Trenutne obaveze -->
                    <div class="step" data-step="3">
                        <div class="step-header">
                            <div class="step-number">3</div>
                            <h2 class="step-title">Trenutne obaveze</h2>
                            <p class="step-description">Želimo razumjeti tvoje vrijeme</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Da li pohađaš neke dodatne kurseve ili edukacije?</label>
                            <div class="radio-group horizontal">
                                <label class="radio-option">
                                    <input type="radio" name="has_other_courses" value="1" required onchange="toggleField('other_courses_details', true)">
                                    <span>DA</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="has_other_courses" value="0" onchange="toggleField('other_courses_details', false)">
                                    <span>NE</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="other_courses_details_group" style="display: none;">
                            <label class="form-label">Koje kurseve pohađaš?</label>
                            <textarea name="other_courses_details" class="form-textarea" placeholder="Navedi kurseve..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Da li trenutno radiš?</label>
                            <div class="radio-group horizontal">
                                <label class="radio-option">
                                    <input type="radio" name="has_job" value="1" required onchange="toggleField('job_details', true)">
                                    <span>DA</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="has_job" value="0" onchange="toggleField('job_details', false)">
                                    <span>NE</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="job_details_group" style="display: none;">
                            <label class="form-label">Šta radiš i koliko sati sedmično?</label>
                            <textarea name="job_details" class="form-textarea" placeholder="Opiši posao..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Imaš li druge značajne obaveze?</label>
                            <div class="radio-group horizontal">
                                <label class="radio-option">
                                    <input type="radio" name="has_other_obligations" value="1" required onchange="toggleField('other_obligations_details', true)">
                                    <span>DA</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="has_other_obligations" value="0" onchange="toggleField('other_obligations_details', false)">
                                    <span>NE</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="other_obligations_details_group" style="display: none;">
                            <label class="form-label">Koje obaveze?</label>
                            <textarea name="other_obligations_details" class="form-textarea" placeholder="Navedi obaveze..."></textarea>
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                                </svg>
                                Nazad
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">
                                Dalje
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Motivacija -->
                    <div class="step" data-step="4">
                        <div class="step-header">
                            <div class="step-number">4</div>
                            <h2 class="step-title">Motivacija i ideja</h2>
                            <p class="step-description">Reci nam šta te pokreće</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Šta te zanima u programiranju? Zašto želiš učestvovati?</label>
                            <textarea name="motivation" class="form-textarea" placeholder="Opiši svoju motivaciju..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Imaš li konkretnu ideju šta bi htio/htjela napraviti?</label>
                            <textarea name="project_idea" class="form-textarea" placeholder="Opiši svoju ideju za projekat..." required></textarea>
                            <div class="form-hint">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                                <span>Ne mora biti savršena ideja - zanima nas šta te pokreće.</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Da li razumiješ da ovo NIJE klasični kurs i da se očekuje samostalan rad?</label>
                            <div class="radio-group horizontal">
                                <label class="radio-option">
                                    <input type="radio" name="understands_not_classic_course" value="1" required>
                                    <span>DA</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="understands_not_classic_course" value="0">
                                    <span>NE</span>
                                </label>
                            </div>
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                                </svg>
                                Nazad
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">
                                Dalje
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Step 5: Pouzdanost -->
                    <div class="step" data-step="5">
                        <div class="step-header">
                            <div class="step-number">5</div>
                            <h2 class="step-title">Pouzdanost i karakter</h2>
                            <p class="step-description">Kako se nosiš sa izazovima</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Da li si ikada odustao/la od nečega na pola puta? Zašto?</label>
                            <textarea name="has_quit_before" class="form-textarea" placeholder="Opiši situaciju ako imaš..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Da li GARANTUJEŠ da ćeš završiti program do kraja?</label>
                            <div class="radio-group horizontal">
                                <label class="radio-option">
                                    <input type="radio" name="guarantees_completion" value="1" required>
                                    <span>DA</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="guarantees_completion" value="0">
                                    <span>NE</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Kako reaguješ kada naiđeš na problem koji ne znaš riješiti?</label>
                            <textarea name="problem_reaction" class="form-textarea" placeholder="Opiši svoj pristup..." required></textarea>
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                                </svg>
                                Nazad
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">
                                Dalje
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Step 6: Budući planovi -->
                    <div class="step" data-step="6">
                        <div class="step-header">
                            <div class="step-number">6</div>
                            <h2 class="step-title">Budući planovi</h2>
                            <p class="step-description">Gdje se vidiš u budućnosti</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Gdje se vidiš za 5 godina? Planiraš li ostati u Zavidovićima?</label>
                            <textarea name="five_year_plan" class="form-textarea" placeholder="Opiši svoje planove..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Razumiješ li da je cilj programa formiranje IT zajednice u Zavidovićima?</label>
                            <div class="radio-group horizontal">
                                <label class="radio-option">
                                    <input type="radio" name="understands_local_goal" value="1" required>
                                    <span>DA</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="understands_local_goal" value="0">
                                    <span>NE</span>
                                </label>
                            </div>
                            <div class="form-hint">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                                <span>Fakultet nije problem - misli se na trajno preseljenje.</span>
                            </div>
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                                </svg>
                                Nazad
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">
                                Dalje
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Step 7: Škola (samo za srednjoškolce) -->
                    <div class="step" data-step="7" id="schoolStep">
                        <div class="step-header">
                            <div class="step-number">7</div>
                            <h2 class="step-title">Škola</h2>
                            <p class="step-description">Tvoj školski uspjeh</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Kakav je tvoj prosjek ocjena?</label>
                            <input type="text" name="school_average" id="school_average" class="form-input" placeholder="npr. 4.2 ili Vrlo dobar">
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Razumiješ li da škola ostaje prioritet?</label>
                            <div class="radio-group horizontal">
                                <label class="radio-option">
                                    <input type="radio" name="understands_school_priority" value="1">
                                    <span>DA</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="understands_school_priority" value="0">
                                    <span>NE</span>
                                </label>
                            </div>
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                                </svg>
                                Nazad
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">
                                Dalje
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Step 8: Roditelji (samo za srednjoškolce) -->
                    <div class="step" data-step="8" id="parentsStep">
                        <div class="step-header">
                            <div class="step-number">8</div>
                            <h2 class="step-title">Saglasnost roditelja</h2>
                            <p class="step-description">Za maloljetne kandidate</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Jesu li roditelji upoznati sa tvojom prijavom?</label>
                            <div class="radio-group horizontal">
                                <label class="radio-option">
                                    <input type="radio" name="parents_informed" value="1">
                                    <span>DA</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="parents_informed" value="0">
                                    <span>NE</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Jesu li spremni finansirati Claude AI?</label>
                            <div class="radio-group horizontal">
                                <label class="radio-option">
                                    <input type="radio" name="parents_will_pay" value="1">
                                    <span>DA</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="parents_will_pay" value="0">
                                    <span>NE</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Ime roditelja/staratelja</label>
                                <input type="text" name="parent_name" id="parent_name" class="form-input" placeholder="Ime i prezime">
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Telefon roditelja</label>
                                <input type="tel" name="parent_phone" id="parent_phone" class="form-input" placeholder="+387 6x xxx xxx">
                            </div>
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                                </svg>
                                Nazad
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">
                                Dalje
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Step 9: Završetak -->
                    <div class="step" data-step="9">
                        <div class="step-header">
                            <div class="step-number">9</div>
                            <h2 class="step-title">Završetak prijave</h2>
                            <p class="step-description">Još samo par koraka</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Prethodno iskustvo sa programiranjem?</label>
                            <textarea name="previous_experience" class="form-textarea" placeholder="Opiši ako imaš iskustva..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Još nešto što bismo trebali znati?</label>
                            <textarea name="additional_info" class="form-textarea" placeholder="Dodatne informacije..."></textarea>
                        </div>

                        <div class="agreement-box">
                            <label class="checkbox-option">
                                <input type="checkbox" name="agreement" required>
                                <span>Potvrđujem da su svi podaci tačni i razumijem uslove programa. Razumijem da IT Hub obezbjeđuje prostor, opremu i mentorstvo besplatno, a da sam ja dužan/na obezbijediti Claude AI nalog.</span>
                            </label>
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                                </svg>
                                Nazad
                            </button>
                            <button type="submit" class="btn btn-primary btn-large">
                                Pošalji prijavu
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <p class="progress-text" id="progressText">Korak <span id="currentStep">0</span> od <span id="totalSteps">9</span></p>
        </div>
    </main>

    <script>
        let currentStep = 0;
        let totalSteps = 9;
        let isStudent = false;
        const steps = document.querySelectorAll('.step');
        const dots = document.querySelectorAll('.step-dot');

        function updateStepIndicator() {
            dots.forEach((dot, i) => {
                dot.classList.remove('active', 'completed');
                if (i === currentStep) dot.classList.add('active');
                else if (i < currentStep) dot.classList.add('completed');
            });
            document.getElementById('currentStep').textContent = currentStep;
            document.getElementById('progressText').style.display = currentStep === 0 ? 'none' : 'block';
        }

        function showStep(step) {
            steps.forEach(s => s.classList.remove('active'));
            const targetStep = document.querySelector(`[data-step="${step}"]`);
            if (targetStep) {
                targetStep.classList.add('active');
            }
            updateStepIndicator();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function getVisibleSteps() {
            // Steps 7 (school) and 8 (parents) only for students
            let visibleSteps = [0, 1, 2, 3, 4, 5, 6];
            if (isStudent) {
                visibleSteps.push(7, 8);
            }
            visibleSteps.push(9);
            return visibleSteps;
        }

        function validateCurrentStep() {
            const currentStepEl = document.querySelector(`[data-step="${currentStep}"]`);
            if (!currentStepEl) return true;

            // Skip validation for intro step
            if (currentStep === 0) return true;

            let isValid = true;
            let firstError = null;

            // Clear previous errors
            currentStepEl.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
            currentStepEl.querySelectorAll('.error-message').forEach(el => el.remove());

            // Get all required fields in current step (only visible ones)
            const requiredInputs = currentStepEl.querySelectorAll('input[required], select[required], textarea[required]');

            requiredInputs.forEach(input => {
                // Skip if parent is hidden
                if (input.closest('[style*="display: none"]') || input.closest('[style*="display:none"]')) {
                    return;
                }

                let fieldValid = true;
                let errorMsg = 'Ovo polje je obavezno';

                if (input.type === 'radio') {
                    const radioGroup = currentStepEl.querySelectorAll(`input[name="${input.name}"]`);
                    const isChecked = Array.from(radioGroup).some(r => r.checked);
                    if (!isChecked) {
                        fieldValid = false;
                        // Mark the radio group container
                        const container = input.closest('.radio-group');
                        if (container && !container.classList.contains('error')) {
                            container.classList.add('error');
                            addErrorMessage(container, errorMsg);
                        }
                    }
                } else if (input.type === 'checkbox') {
                    if (!input.checked) {
                        fieldValid = false;
                        input.closest('.checkbox-option')?.classList.add('error');
                    }
                } else if (input.type === 'hidden' && input.id === 'date_of_birth') {
                    // Special handling for date picker
                    if (!input.value) {
                        fieldValid = false;
                        const dateDisplay = document.getElementById('dateDisplay');
                        if (dateDisplay) {
                            dateDisplay.classList.add('error');
                            addErrorMessage(dateDisplay.parentElement, errorMsg);
                        }
                    }
                } else if (input.type === 'email') {
                    if (!input.value.trim()) {
                        fieldValid = false;
                        errorMsg = 'Ovo polje je obavezno';
                    } else if (!isValidEmail(input.value)) {
                        fieldValid = false;
                        errorMsg = 'Unesite ispravnu email adresu';
                    }
                    if (!fieldValid) {
                        input.classList.add('error');
                        addErrorMessage(input.parentElement, errorMsg);
                    }
                } else {
                    if (!input.value.trim()) {
                        fieldValid = false;
                        input.classList.add('error');
                        addErrorMessage(input.parentElement, errorMsg);
                    }
                }

                if (!fieldValid) {
                    isValid = false;
                    if (!firstError) {
                        firstError = input;
                    }
                }
            });

            if (!isValid && firstError) {
                // Scroll to first error
                const container = firstError.closest('.form-group') || firstError;
                container.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            return isValid;
        }

        function addErrorMessage(parent, message) {
            if (parent.querySelector('.error-message')) return;
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>${message}</span>`;
            parent.appendChild(errorDiv);
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function clearFieldError(input) {
            input.classList.remove('error');
            const parent = input.closest('.form-group') || input.parentElement;
            parent?.querySelector('.error-message')?.remove();

            // For radio groups
            const radioGroup = input.closest('.radio-group');
            if (radioGroup) {
                radioGroup.classList.remove('error');
                radioGroup.parentElement?.querySelector('.error-message')?.remove();
            }

            // For date picker
            if (input.id === 'date_of_birth') {
                document.getElementById('dateDisplay')?.classList.remove('error');
                input.parentElement?.querySelector('.error-message')?.remove();
            }
        }

        function nextStep() {
            // Validate current step before proceeding
            if (!validateCurrentStep()) {
                return;
            }

            const visibleSteps = getVisibleSteps();
            const currentIndex = visibleSteps.indexOf(currentStep);
            if (currentIndex < visibleSteps.length - 1) {
                currentStep = visibleSteps[currentIndex + 1];
                showStep(currentStep);
            }
        }

        function prevStep() {
            const visibleSteps = getVisibleSteps();
            const currentIndex = visibleSteps.indexOf(currentStep);
            if (currentIndex > 0) {
                currentStep = visibleSteps[currentIndex - 1];
                showStep(currentStep);
            }
        }

        function toggleStudentFields(show) {
            isStudent = show;
            document.getElementById('studentFields').style.display = show ? 'block' : 'none';
            document.getElementById('nonStudentFields').style.display = show ? 'none' : 'block';

            // Update required fields
            const gradeField = document.getElementById('grade');
            const schoolField = document.getElementById('school');
            const schoolAvgField = document.getElementById('school_average');
            const parentNameField = document.getElementById('parent_name');
            const parentPhoneField = document.getElementById('parent_phone');

            if (gradeField) gradeField.required = show;
            if (schoolField) schoolField.required = show;
            if (schoolAvgField) schoolAvgField.required = show;
            if (parentNameField) parentNameField.required = show;
            if (parentPhoneField) parentPhoneField.required = show;

            document.querySelectorAll('input[name="parents_informed"]').forEach(r => r.required = show);
            document.querySelectorAll('input[name="parents_will_pay"]').forEach(r => r.required = show);
            document.querySelectorAll('input[name="understands_school_priority"]').forEach(r => r.required = show);

            // Update total steps display
            totalSteps = show ? 9 : 7;
            document.getElementById('totalSteps').textContent = totalSteps;

            // Update step dots visibility
            const dotsContainer = document.getElementById('stepIndicator');
            const allDots = dotsContainer.querySelectorAll('.step-dot');
            allDots.forEach((dot, i) => {
                if (!show && (i === 7 || i === 8)) {
                    dot.style.display = 'none';
                } else {
                    dot.style.display = 'block';
                }
            });
        }

        function toggleField(fieldId, show) {
            const group = document.getElementById(fieldId + '_group');
            if (group) group.style.display = show ? 'block' : 'none';
        }

        // School select change handler
        document.getElementById('school')?.addEventListener('change', function() {
            const otherGroup = document.getElementById('school_other_group');
            if (otherGroup) otherGroup.style.display = this.value === 'Drugo' ? 'block' : 'none';
        });

        // Date Picker
        (function() {
            const monthNames = ['Januar', 'Februar', 'Mart', 'April', 'Maj', 'Juni', 'Juli', 'August', 'Septembar', 'Oktobar', 'Novembar', 'Decembar'];
            const datePicker = document.getElementById('datePicker');
            const dateDisplay = document.getElementById('dateDisplay');
            const dateText = document.getElementById('dateText');
            const dateDropdown = document.getElementById('dateDropdown');
            const dateInput = document.getElementById('date_of_birth');
            const dateDaysEl = document.getElementById('dateDays');
            const prevMonthBtn = document.getElementById('prevMonth');
            const nextMonthBtn = document.getElementById('nextMonth');

            // Custom dropdowns
            const monthTrigger = document.getElementById('monthTrigger');
            const monthText = document.getElementById('monthText');
            const monthMenu = document.getElementById('monthMenu');
            const yearTrigger = document.getElementById('yearTrigger');
            const yearText = document.getElementById('yearText');
            const yearMenu = document.getElementById('yearMenu');

            const today = new Date();
            const minYear = 1990;
            const maxYear = today.getFullYear();
            let currentDate = new Date(today.getFullYear() - 18, today.getMonth(), 1);
            let selectedDate = null;
            let currentMonth = currentDate.getMonth();
            let currentYear = currentDate.getFullYear();

            function populateDropdowns() {
                // Populate months
                monthMenu.innerHTML = '';
                monthNames.forEach((name, i) => {
                    const item = document.createElement('div');
                    item.className = 'custom-dropdown-item';
                    item.textContent = name;
                    item.dataset.value = i;
                    if (i === currentMonth) item.classList.add('selected');
                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
                        selectMonth(i);
                    });
                    monthMenu.appendChild(item);
                });

                // Populate years
                yearMenu.innerHTML = '';
                for (let y = maxYear; y >= minYear; y--) {
                    const item = document.createElement('div');
                    item.className = 'custom-dropdown-item';
                    item.textContent = y;
                    item.dataset.value = y;
                    if (y === currentYear) item.classList.add('selected');
                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
                        selectYear(y);
                    });
                    yearMenu.appendChild(item);
                }
            }

            function selectMonth(month) {
                currentMonth = month;
                currentDate.setMonth(month);
                monthText.textContent = monthNames[month];
                closeAllDropdowns();
                updateDropdownSelection();
                renderCalendar();
            }

            function selectYear(year) {
                currentYear = year;
                currentDate.setFullYear(year);
                yearText.textContent = year;
                closeAllDropdowns();
                updateDropdownSelection();
                renderCalendar();
            }

            function updateDropdownSelection() {
                // Update month items
                monthMenu.querySelectorAll('.custom-dropdown-item').forEach(item => {
                    item.classList.toggle('selected', parseInt(item.dataset.value) === currentMonth);
                });
                // Update year items
                yearMenu.querySelectorAll('.custom-dropdown-item').forEach(item => {
                    item.classList.toggle('selected', parseInt(item.dataset.value) === currentYear);
                });
                // Scroll selected year into view
                const selectedYear = yearMenu.querySelector('.selected');
                if (selectedYear) {
                    selectedYear.scrollIntoView({ block: 'center' });
                }
            }

            function closeAllDropdowns() {
                monthMenu.classList.remove('show');
                yearMenu.classList.remove('show');
                monthTrigger.classList.remove('active');
                yearTrigger.classList.remove('active');
            }

            function toggleMonthDropdown(e) {
                e.stopPropagation();
                const isOpen = monthMenu.classList.contains('show');
                closeAllDropdowns();
                if (!isOpen) {
                    monthMenu.classList.add('show');
                    monthTrigger.classList.add('active');
                }
            }

            function toggleYearDropdown(e) {
                e.stopPropagation();
                const isOpen = yearMenu.classList.contains('show');
                closeAllDropdowns();
                if (!isOpen) {
                    yearMenu.classList.add('show');
                    yearTrigger.classList.add('active');
                    // Scroll to selected year
                    setTimeout(() => {
                        const selectedYear = yearMenu.querySelector('.selected');
                        if (selectedYear) selectedYear.scrollIntoView({ block: 'center' });
                    }, 10);
                }
            }

            function renderCalendar() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                currentMonth = month;
                currentYear = year;
                monthText.textContent = monthNames[month];
                yearText.textContent = year;

                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                let startDay = firstDay.getDay();
                startDay = startDay === 0 ? 6 : startDay - 1;

                dateDaysEl.innerHTML = '';
                const prevLast = new Date(year, month, 0).getDate();

                for (let i = startDay - 1; i >= 0; i--) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'date-day other-month disabled';
                    btn.textContent = prevLast - i;
                    dateDaysEl.appendChild(btn);
                }

                for (let d = 1; d <= lastDay.getDate(); d++) {
                    const date = new Date(year, month, d);
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'date-day';
                    btn.textContent = d;
                    if (selectedDate && date.toDateString() === selectedDate.toDateString()) {
                        btn.classList.add('selected');
                    }
                    btn.addEventListener('click', () => selectDate(date));
                    dateDaysEl.appendChild(btn);
                }

                const total = startDay + lastDay.getDate();
                const rem = total % 7 === 0 ? 0 : 7 - (total % 7);
                for (let d = 1; d <= rem; d++) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'date-day other-month disabled';
                    btn.textContent = d;
                    dateDaysEl.appendChild(btn);
                }

                updateDropdownSelection();
            }

            function selectDate(date) {
                selectedDate = date;
                const d = String(date.getDate()).padStart(2, '0');
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const y = date.getFullYear();
                dateText.textContent = `${d}.${m}.${y}.`;
                dateText.classList.add('has-value');
                dateInput.value = `${y}-${m}-${d}`;
                dateDropdown.classList.remove('show');
                dateDisplay.classList.remove('active');
                closeAllDropdowns();
                renderCalendar();
            }

            // Event listeners
            dateDisplay.addEventListener('click', (e) => {
                e.stopPropagation();
                closeAllDropdowns();
                const isOpen = dateDropdown.classList.contains('show');
                dateDropdown.classList.toggle('show', !isOpen);
                dateDisplay.classList.toggle('active', !isOpen);
                if (!isOpen) renderCalendar();
            });

            monthTrigger.addEventListener('click', toggleMonthDropdown);
            yearTrigger.addEventListener('click', toggleYearDropdown);

            prevMonthBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                closeAllDropdowns();
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar();
            });

            nextMonthBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                closeAllDropdowns();
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
            });

            document.addEventListener('click', (e) => {
                if (!datePicker.contains(e.target)) {
                    dateDropdown.classList.remove('show');
                    dateDisplay.classList.remove('active');
                    closeAllDropdowns();
                }
            });

            populateDropdowns();
            renderCalendar();
        })();

        // Clear errors on input
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', () => clearFieldError(input));
            input.addEventListener('change', () => clearFieldError(input));
        });

        // Initialize
        updateStepIndicator();
        document.getElementById('progressText').style.display = 'none';
    </script>
</body>
</html>
