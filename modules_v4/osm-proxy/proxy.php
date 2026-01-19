<?php

// SECRET
$secret = getenv('OSM_SIG_SECRET') ?: 'CHANGE_ME_TO_LONG_RANDOM_SECRET';

// REQUIRED parameters
$path = $_GET['path'] ?? '';
$sig  = $_GET['sig']  ?? '';
$exp  = isset($_GET['exp']) ? (int)$_GET['exp'] : 0;

// Validate path format z/x/y.png
if (!preg_match('~^([0-9]{1,2})/([0-9]{1,7})/([0-9]{1,7})\.png$~', $path)) {
    http_response_code(404);
    exit;
}

// Check expiration
if ($exp < time() || $exp > time() + 600) {
    http_response_code(404);
    exit;
}

// Recreate signature
$data = $path . '|' . $exp;

$calc = base64_encode(hash_hmac('sha256', $data, $secret, true));
$calc = rtrim(strtr($calc, '+/', '-_'), '=');

// Verify signature
if (!hash_equals($calc, $sig)) {
    http_response_code(404);
    exit;
}

// Fetch from REAL OSM (ALWAYS .org)
$osmUrl = "https://tile.openstreetmap.org/" . $path;

$ch = curl_init($osmUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 3,
    CURLOPT_TIMEOUT => 8,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_USERAGENT => 'WebtreesOSMProxy/2.2 (+wbt.warius.info)'
]);

$data = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// If ok → send PNG
if ($http >= 200 && $http < 300 && $data) {
    header('Content-Type: image/png');
    header('Cache-Control: public, max-age=604800, immutable');
    echo $data;
    exit;
}

// Otherwise → silent 404
http_response_code(404);
exit;
