<?php

/**
 * File config/app.php
 */

// Base URL Configuration -> untuk localhost:0000 sesuaikan port
define('BASE_URL', 'http://localhost/applied-informatics');
// define('BASE_URL', 'http://localhost/applied-informatics');

// Session Timeout Configuration (in seconds)
// Idle Timeout: Auto logout jika tidak ada aktivitas dalam waktu ini
define('SESSION_IDLE_TIMEOUT', 60 * 60); // 30 menit

// Absolute Timeout: Auto logout setelah login sekian lama (maksimal durasi login)
define('SESSION_ABSOLUTE_TIMEOUT', 12 * 60 * 60); // 8 jam

// Session Regeneration Interval: Regenerate session ID setiap sekian waktu untuk security
define('SESSION_REGENERATION_INTERVAL', 15 * 60); // 15 menit

function base_url($path = '')
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function asset_url($path = '')
{
    return BASE_URL . '/public/assets/' . ltrim($path, '/');
}

function upload_url($path = '')
{
    return BASE_URL . '/public/uploads/' . ltrim($path, '/');
}
