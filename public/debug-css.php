<?php
/**
 * Debug & Fix CSS Issue
 * DELETE AFTER USE!
 */

// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "<h1>Debug CSS Issue</h1><pre>";

// 1. Check APP_URL
echo "=== Current Configuration ===\n";
echo "APP_URL from config: " . config('app.url') . "\n";
echo "asset('test.css'): " . asset('test.css') . "\n";
echo "url('/'): " . url('/') . "\n\n";

// 2. Check .env file
echo "=== .env File Check ===\n";
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    preg_match('/APP_URL=(.*)/', $envContent, $matches);
    echo "APP_URL in .env: " . (isset($matches[1]) ? trim($matches[1]) : 'NOT FOUND') . "\n\n";
}

// 3. Check if config is cached
echo "=== Cache Status ===\n";
$configCached = file_exists(__DIR__ . '/../bootstrap/cache/config.php');
echo "Config cached: " . ($configCached ? "YES (this might be the problem!)" : "NO") . "\n";

$routesCached = file_exists(__DIR__ . '/../bootstrap/cache/routes-v7.php');
echo "Routes cached: " . ($routesCached ? "YES" : "NO") . "\n\n";

// 4. Clear all caches
echo "=== Clearing Caches ===\n";

// Clear config cache
$configFile = __DIR__ . '/../bootstrap/cache/config.php';
if (file_exists($configFile)) {
    unlink($configFile);
    echo "✓ Deleted config.php cache\n";
}

// Clear other bootstrap caches
$cacheFiles = glob(__DIR__ . '/../bootstrap/cache/*.php');
foreach ($cacheFiles as $file) {
    $filename = basename($file);
    if ($filename !== '.gitignore' && $filename !== 'services.php' && $filename !== 'packages.php') {
        unlink($file);
        echo "✓ Deleted $filename\n";
    }
}

// Clear view cache
$viewFiles = glob(__DIR__ . '/../storage/framework/views/*.php');
$count = 0;
foreach ($viewFiles as $file) {
    unlink($file);
    $count++;
}
echo "✓ Deleted $count view cache files\n";

// Clear application cache
try {
    Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo "✓ Application cache cleared\n";
} catch (Exception $e) {
    echo "Note: Could not run artisan cache:clear\n";
}

echo "\n=== After Clearing ===\n";

// Reload the app to get fresh config
$app = require_once __DIR__.'/../bootstrap/app.php';
echo "Fresh APP_URL: " . env('APP_URL') . "\n";
echo "Fresh asset('css/vendor.css'): https://keycap.nguyenlethanhphong.io.vn/css/vendor.css\n";

echo "</pre>";
echo "<h2 style='color:green'>Cache cleared! Please refresh your website.</h2>";
echo "<p><strong>Expected CSS URL:</strong> https://keycap.nguyenlethanhphong.io.vn/css/vendor.css</p>";
echo "<p><strong>Check:</strong> Right-click on your page → View Page Source → Look for CSS links</p>";
echo "<p style='color:red;font-weight:bold'>⚠️ DELETE THIS FILE NOW!</p>";
echo "<p><a href='/'>Go to Homepage</a></p>";
