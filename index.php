<?php
/**
 * Proxy index.php - Redirect all requests to public folder
 * Place this file in the ROOT folder (not public)
 */

// Get the request URI
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Path to public folder
$publicPath = __DIR__ . '/public';

// Check if it's a static file request
$requestedFile = $publicPath . $uri;

if ($uri !== '/' && file_exists($requestedFile) && is_file($requestedFile)) {
    // Get the file extension
    $ext = pathinfo($requestedFile, PATHINFO_EXTENSION);
    
    // Set appropriate content type
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
    ];
    
    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);
    }
    
    // Output the file
    readfile($requestedFile);
    exit;
}

// Otherwise, include Laravel's public/index.php
require_once $publicPath . '/index.php';
