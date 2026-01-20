<?php

declare(strict_types=1);

$HTTP_ERR = 409;
$secret   = getenv('OSM_SIG_SECRET') ?: 'E)p=ra;0X^aW5PogT<h<NbP7QfmO{IG9';

// ---------------------------------------------------
// 1) Parameter
// ---------------------------------------------------
$path  = $_GET['path'] ?? '';
$exp   = isset($_GET['exp']) ? (int)$_GET['exp'] : 0;
$nonce = $_GET['n']   ?? '';
$tok   = $_GET['tok'] ?? '';
$now   = time();

// Session-Cookie
$sid = $_COOKIE['__Secure-WT-ID'] ?? '';

// Normalize path
$path = urldecode($path);
$path = ltrim($path, "./\\");
$path = preg_replace('~[\\/]+~', '/', $path);

// ---------------------------------------------------
// 2) Validierung
// ---------------------------------------------------
if ($path === '' || strpos($path, '..') !== false) {
    header("X-Debug-Upstream: (invalid-path)");
    http_response_code($HTTP_ERR);
    exit;
}

if ($exp < $now || $exp > $now + 600 || !$nonce || !$tok || !$sid) {
    header("X-Debug-Upstream: (invalid-token-params)");
    http_response_code($HTTP_ERR);
    exit;
}

// style = erstes Segment
$style = explode('/', $path)[0] ?? '';

if ($style === '') {
    header("X-Debug-Upstream: (no-style)");
    http_response_code($HTTP_ERR);
    exit;
}

// ---------------------------------------------------
// 3) Token prüfen: nonce|exp|sid|style
// ---------------------------------------------------
$data = $nonce . '|' . $exp . '|' . $sid . '|' . $style;
$calc = rtrim(strtr(base64_encode(hash_hmac('sha256', $data, $secret, true)), '+/', '-_'), '=');

if (!hash_equals($calc, $tok)) {
    header("X-Debug-Status: (bad-token)");
    header("X-Debug-Token: calc: $calc - tok: $tok");
    header("X-Debug-Nonce: $nonce");
    header("X-Debug-Exp: $exp");
    header("X-Debug-SID: $sid");
    header("X-Debug-style: $style");
    http_response_code($HTTP_ERR);
    exit;
}

// ---------------------------------------------------
// 4) Upstream bauen
// Path enthält style + OSM-Subpaths → direkt anfügen
// ---------------------------------------------------
$upstream = "https://tile.openstreetmap.$path";

// Debug immer setzen
header("X-Debug-Upstream: $upstream");

// ---------------------------------------------------
// 5) Tile abrufen
// ---------------------------------------------------
$ch = curl_init($upstream);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 4,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_USERAGENT      => 'WebtreesOSMProxy/2.2 (+wbt.warius.info)',
]);
$body        = curl_exec($ch);
$code        = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: 'image/png';
unset($ch);

// ---------------------------------------------------
// 6) Ausliefern
// ---------------------------------------------------
if ($code >= 200 && $code < 300 && $body) {
    header("Content-Type: $contentType");
    header("Cache-Control: public, max-age=604800, immutable");
    header("Content-Length: " . strlen($body));
    echo $body;
    exit;
}

http_response_code($HTTP_ERR);
exit;
