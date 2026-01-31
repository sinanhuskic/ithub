<?php
require_once dirname(__DIR__) . "/includes/config.php";
require_once dirname(__DIR__) . "/includes/functions.php";
require_once dirname(__DIR__) . "/includes/database.php";
require_once dirname(__DIR__) . "/includes/models/Program.php";
require_once dirname(__DIR__) . "/includes/models/Application.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . url(""));
    exit();
}

$programId = $_POST["program_id"] ?? null;

if (!$programId) {
    setFlash("flash_error", "Nevažeći program.");
    header("Location: " . url(""));
    exit();
}

$program = Program::find($programId);
if (!$program) {
    setFlash("flash_error", "Program nije pronaden.");
    header("Location: " . url(""));
    exit();
}

// Provjera statusa kandidata (učenik ili ne)
$applicantStatus = $_POST["applicant_status"] ?? "";
$isStudent = $applicantStatus === "ucenik";

// Validacija obaveznih polja za sve kandidate
$required = [
    "full_name" => "Ime i prezime",
    "date_of_birth" => "Datum rođenja",
    "applicant_status" => "Trenutni status",
    "phone" => "Broj telefona",
    "email" => "Email adresa",
    "motivation" => "Motivacija",
    "project_idea" => "Ideja projekta",
    "problem_reaction" => "Reakcija na probleme",
    "five_year_plan" => "Plan za 5 godina",
];

// Dodatna obavezna polja samo za srednjoškolce
if ($isStudent) {
    $required["school"] = "Škola";
    $required["grade"] = "Razred";
    $required["school_average"] = "Prosjek ocjena";
    $required["parent_name"] = "Ime roditelja";
    $required["parent_phone"] = "Telefon roditelja";
}

foreach ($required as $field => $label) {
    if (empty(trim($_POST[$field] ?? ""))) {
        setFlash("flash_error", "Polje '{$label}' je obavezno.");
        header("Location: " . url("prijava?program=" . $programId));
        exit();
    }
}

// Validacija email-a
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    setFlash("flash_error", "Unesite ispravnu email adresu.");
    header("Location: " . url("prijava?program=" . $programId));
    exit();
}

// Validacija agreement checkbox
if (empty($_POST["agreement"])) {
    setFlash("flash_error", "Morate potvrditi izjavu kandidata.");
    header("Location: " . url("prijava?program=" . $programId));
    exit();
}

// Priprema podataka
$data = [
    "program_id" => $programId,

    // Osnovni podaci
    "full_name" => trim($_POST["full_name"]),
    "date_of_birth" => $_POST["date_of_birth"],
    "applicant_status" => $applicantStatus,
    "school" => $isStudent ? $_POST["school"] : null,
    "school_other" => $isStudent ? trim($_POST["school_other"] ?? "") : null,
    "department" => $isStudent ? trim($_POST["department"] ?? "") : null,
    "grade" => $isStudent ? $_POST["grade"] : null,
    "occupation" => !$isStudent ? trim($_POST["occupation"] ?? "") : null,
    "phone" => trim($_POST["phone"]),
    "email" => trim($_POST["email"]),
    "viber" => trim($_POST["viber"] ?? ""),

    // Dostupnost i oprema
    "can_attend_saturday" => (int) ($_POST["can_attend_saturday"] ?? 0),
    "can_attend_sunday" => (int) ($_POST["can_attend_sunday"] ?? 0),
    "has_home_computer" => (int) ($_POST["has_home_computer"] ?? 0),
    "can_pay_claude" => (int) ($_POST["can_pay_claude"] ?? 0),

    // Trenutne obaveze
    "has_other_courses" => (int) ($_POST["has_other_courses"] ?? 0),
    "other_courses_details" => trim($_POST["other_courses_details"] ?? ""),
    "has_job" => (int) ($_POST["has_job"] ?? 0),
    "job_details" => trim($_POST["job_details"] ?? ""),
    "has_other_obligations" => (int) ($_POST["has_other_obligations"] ?? 0),
    "other_obligations_details" => trim(
        $_POST["other_obligations_details"] ?? "",
    ),

    // Motivacija i ideja
    "motivation" => trim($_POST["motivation"]),
    "project_idea" => trim($_POST["project_idea"]),
    "understands_not_classic_course" =>
        (int) ($_POST["understands_not_classic_course"] ?? 0),

    // Pouzdanost i karakter
    "has_quit_before" => trim($_POST["has_quit_before"] ?? ""),
    "guarantees_completion" => (int) ($_POST["guarantees_completion"] ?? 0),
    "problem_reaction" => trim($_POST["problem_reaction"]),

    // Budući planovi
    "five_year_plan" => trim($_POST["five_year_plan"]),
    "understands_local_goal" => (int) ($_POST["understands_local_goal"] ?? 0),

    // Škola (samo za srednjoškolce)
    "school_average" => $isStudent
        ? trim($_POST["school_average"] ?? "")
        : null,
    "understands_school_priority" => $isStudent
        ? (int) ($_POST["understands_school_priority"] ?? 0)
        : null,

    // Saglasnost roditelja (samo za srednjoškolce)
    "parents_informed" => $isStudent
        ? (int) ($_POST["parents_informed"] ?? 0)
        : null,
    "parents_will_pay" => $isStudent
        ? (int) ($_POST["parents_will_pay"] ?? 0)
        : null,
    "parent_name" => $isStudent ? trim($_POST["parent_name"] ?? "") : null,
    "parent_phone" => $isStudent ? trim($_POST["parent_phone"] ?? "") : null,

    // Dodatne informacije
    "previous_experience" => trim($_POST["previous_experience"] ?? ""),
    "additional_info" => trim($_POST["additional_info"] ?? ""),

    // Meta
    "ip_address" => $_SERVER["REMOTE_ADDR"] ?? null,
    "status" => "nova",
];

try {
    Application::create($data);
    setFlash(
        "flash_success",
        "Hvala na prijavi! Vaša prijava je uspješno poslana. Kontaktirat ćemo vas uskoro.",
    );
    header("Location: " . url("prijava?program=" . $programId));
} catch (Exception $e) {
    setFlash(
        "flash_error",
        "Greška pri slanju prijave. Molimo pokušajte ponovo.",
    );
    header("Location: " . url("prijava?program=" . $programId));
}
