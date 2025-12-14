<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ensure /tmp directories exist for Vercel
@mkdir('/tmp/storage/framework/cache', 0755, true);
@mkdir('/tmp/storage/framework/sessions', 0755, true);
@mkdir('/tmp/storage/framework/views', 0755, true);
@mkdir('/tmp/storage/logs', 0755, true);

// Set storage path to /tmp for Vercel
$_ENV['VIEW_COMPILED_PATH'] = '/tmp';
$_ENV['APP_STORAGE_PATH'] = '/tmp/storage';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
try {
    /** @var Application $app */
    $app = require_once __DIR__.'/../bootstrap/app.php';

    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    // Log error to stderr for Vercel logs
    error_log('Laravel Error: ' . $e->getMessage());
    error_log('File: ' . $e->getFile() . ':' . $e->getLine());
    error_log('Trace: ' . $e->getTraceAsString());

    http_response_code(500);
    echo json_encode([
        'error' => 'Application Error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    exit(1);
}
