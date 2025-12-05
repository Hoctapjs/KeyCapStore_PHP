<?php
/**
 * Laravel Cache Clear & Fix URL Script
 * DELETE THIS FILE AFTER USE!
 */

$basePath = __DIR__ . '/..';

echo "<h1>Laravel Fix Script</h1><pre>";

// 1. Update .env file APP_URL
$envFile = $basePath . '/.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    // Check current APP_URL
    if (preg_match('/APP_URL=(.*)/', $envContent, $matches)) {
        echo "Current APP_URL: " . trim($matches[1]) . "\n";
    }
    
    // Update APP_URL to correct domain
    $newEnv = preg_replace(
        '/APP_URL=.*/', 
        'APP_URL=https://keycap.nguyenlethanhphong.io.vn', 
        $envContent
    );
    
    if (file_put_contents($envFile, $newEnv)) {
        echo "✓ APP_URL updated to: https://keycap.nguyenlethanhphong.io.vn\n";
    } else {
        echo "✗ Failed to update .env file\n";
    }
} else {
    echo "✗ .env file not found\n";
}

// 2. Clear bootstrap cache
$cacheFiles = glob($basePath . '/bootstrap/cache/*.php');
foreach ($cacheFiles as $file) {
    if (basename($file) !== '.gitignore') {
        @unlink($file);
        echo "Deleted: bootstrap/cache/" . basename($file) . "\n";
    }
}

// 3. Clear view cache
$viewCache = $basePath . '/storage/framework/views';
if (is_dir($viewCache)) {
    $files = glob($viewCache . '/*.php');
    foreach ($files as $file) {
        @unlink($file);
    }
    echo "✓ View cache cleared\n";
}

// 4. Clear config cache  
$configCache = $basePath . '/bootstrap/cache/config.php';
if (file_exists($configCache)) {
    @unlink($configCache);
    echo "✓ Config cache cleared\n";
}

echo "\n</pre>";
echo "<h2 style='color:green'>Done! Refresh your website now.</h2>";
echo "<p style='color:red;font-weight:bold'>⚠️ DELETE THIS FILE (fix-url.php) NOW!</p>";
echo "<p><a href='/'>Go to Homepage</a></p>";
