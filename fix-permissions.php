<?php
/**
 * Fix Permissions Script
 * Pokreni jednom nakon uploada na shared hosting
 * OBRIŠI OVAJ FAJL NAKON KORIŠTENJA!
 */

// Zaštita - samo ako je definisan secret key
$secret = $_GET['key'] ?? '';
if ($secret !== 'PROMIJENI_OVO_123') {
    die('Unauthorized');
}

$baseDir = __DIR__;

// Folderi koji trebaju biti writable (755 ili 775)
$writableDirs = [
    'public/uploads',
    'public/uploads/gallery',
    'public/uploads/partners',
];

// Folderi koji trebaju biti protected (755)
$protectedDirs = [
    'includes',
    'database',
];

echo "<pre>";
echo "=== FIXING PERMISSIONS ===\n\n";

// Fix writable directories
foreach ($writableDirs as $dir) {
    $path = $baseDir . '/' . $dir;
    if (is_dir($path)) {
        if (@chmod($path, 0755)) {
            echo "[OK] $dir -> 755\n";
        } else {
            echo "[FAIL] $dir - chmod failed\n";
        }
    } else {
        // Create if doesn't exist
        if (@mkdir($path, 0755, true)) {
            echo "[CREATED] $dir -> 755\n";
        } else {
            echo "[FAIL] $dir - cannot create\n";
        }
    }
}

// Fix protected directories
foreach ($protectedDirs as $dir) {
    $path = $baseDir . '/' . $dir;
    if (is_dir($path)) {
        if (@chmod($path, 0755)) {
            echo "[OK] $dir -> 755\n";
        } else {
            echo "[FAIL] $dir - chmod failed\n";
        }
    }
}

// Fix all PHP files (644)
echo "\n=== FIXING PHP FILES ===\n";
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($baseDir)
);

$count = 0;
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $filepath = $file->getPathname();
        // Skip this script
        if (basename($filepath) === 'fix-permissions.php') continue;
        
        if (@chmod($filepath, 0644)) {
            $count++;
        }
    }
}
echo "[OK] Fixed $count PHP files -> 644\n";

// Fix .htaccess files (644)
echo "\n=== FIXING .HTACCESS ===\n";
$htaccessFiles = [
    '.htaccess',
    'includes/.htaccess',
];

foreach ($htaccessFiles as $file) {
    $path = $baseDir . '/' . $file;
    if (file_exists($path)) {
        if (@chmod($path, 0644)) {
            echo "[OK] $file -> 644\n";
        } else {
            echo "[FAIL] $file\n";
        }
    }
}

echo "\n=== DONE ===\n";
echo "\n⚠️  OBRIŠI OVAJ FAJL ODMAH! (fix-permissions.php)\n";
echo "</pre>";
