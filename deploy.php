<?php
/**
 * Git Deploy Script za Shared Hosting
 * Webhook URL: https://ithub.ba/deploy.php?key=TVOJ_SECRET_KEY
 */

// Sigurnosni ključ - PROMIJENI OVO!
$secretKey = 'PROMIJENI_OVO_SECRET_123';

// Provjera autorizacije
if (($_GET['key'] ?? '') !== $secretKey) {
    http_response_code(403);
    die('Unauthorized');
}

// Logging
$logFile = __DIR__ . '/deploy.log';
$log = function($msg) use ($logFile) {
    $time = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$time] $msg\n", FILE_APPEND);
    echo "$msg\n";
};

header('Content-Type: text/plain');
$log("=== Deploy started ===");

// Git pull
$output = [];
$returnCode = 0;

// Opcija A: Ako imaš Git na serveru
exec('cd ' . __DIR__ . ' && git pull origin main 2>&1', $output, $returnCode);

if ($returnCode === 0) {
    $log("Git pull successful:");
    foreach ($output as $line) {
        $log("  $line");
    }
} else {
    $log("Git pull failed (code: $returnCode):");
    foreach ($output as $line) {
        $log("  $line");
    }
}

// Fix permisija nakon pull-a
exec('find ' . __DIR__ . ' -type f -name "*.php" -exec chmod 644 {} \;');
exec('find ' . __DIR__ . ' -type d -exec chmod 755 {} \;');
exec('chmod 755 ' . __DIR__ . '/public/uploads');

$log("=== Deploy finished ===");
