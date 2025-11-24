<?php

/**
 * File config/app.php
 */

// Base URL Configuration -> untuk localhost:0000 sesuaikan port
define('BASE_URL', 'http://localhost/applied-informatics');

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
