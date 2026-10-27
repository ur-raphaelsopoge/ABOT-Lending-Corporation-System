<?php

// Redirect Laravel storage and bootstrap cache to Vercel's writable /tmp directory
$_ENV['APP_STORAGE'] = '/tmp';

if (!is_dir('/tmp/storage')) {
    mkdir('/tmp/storage', 0777, true);
    mkdir('/tmp/storage/framework', 0777, true);
    mkdir('/tmp/storage/framework/views', 0777, true);
    mkdir('/tmp/storage/framework/cache', 0777, true);
    mkdir('/tmp/storage/framework/sessions', 0777, true);
    mkdir('/tmp/storage/logs', 0777, true);
}

// Forward Vercel requests to Laravel's public/index.php
require __DIR__ . '/../public/index.php';