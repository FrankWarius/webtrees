<?php

declare(strict_types=1);
$http404 = 409;

// Geheimnis aus Umgebung (IIS) laden – NICHT im Code lassen
$secret = getenv('OSM_SIG_SECRET') ?: 'CHANGE_ME_TO_LONG_RANDOM_SECRET';

// Query-Parameter
$$path = isset($_GET['path']) ? $_GET['path'] : '';
$exp   = isset($_GET['exp']) ? (int)$_GET['exp'] : 0;
$nonce = $_GET['n']   ?? '';
$tok   = $_GET['tok'] ?? '';

// Pfad analysieren (erwartet: /cache-osm/<style>/<z>/<x>/<y>.png)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
if (!preg_match('~^/cache-osm/(org|fr|de)/([0-9]{1,2})/([0-9]{1,7})/([0-9]{1,7})\.png$~', $uri, $m)) {
    http_response_code($http404);
    exit;
}
$style = $m[1];                     // org|fr|de
$z = $m[2];
$x = $m[3];
$y = $m[4];
$path = "{$z}/{$x}/{$y}.png";

// Ablauf kurz halten (z. B. 60–120s)
$now = time();
if ($exp < $now || $exp > $now + 600) {  // extra Obergrenze
    http_response_code($http404);
    exit;
}
if ($nonce === '' || $tok === '') {
    http_response_code($http404);
    exit;
}

// Token an NONCE + EXP + CLIENT-IP + STYLE binden
$clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
$data = $nonce . '|' . $exp . '|' . $clientIp . '|' . $style;
$calc = base64_encode(hash_hmac('sha256', $data, $secret, true));
$calc = rtrim(strtr($calc, '+/', '-_'), '=');

if (!hash_equals($calc, $tok)) {
    http_response_code($http404);
    exit;
}

// Upstream anhand des Styles bestimmen (ohne Subdomains – stabil)
switch ($style) {
    case 'org':
        $upstream = "https://tile.openstreetmap.org/{$path}";
        break;
    case 'fr':
        $upstream = "https://tile.openstreetmap.fr/osmfr/{$path}";
        break;
    case 'de':
        $upstream = "https://tile.openstreetmap.de/tiles/osmde/{$path}";
        break;
    default:
        http_response_code($http404);
        exit;
}

// Kachel holen
$ch = curl_init($upstream);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 3,
    CURLOPT_TIMEOUT        => 8,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_USERAGENT      => 'WebtreesOSMProxy/2.2 (+wbt.warius.info)',
    CURLOPT_HTTPHEADER     => ['Accept: image/png,image/*;q=0.8,*/*;q=0.5'],
]);
$body = curl_exec($ch);
$code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: 'image/png';
unset($ch);

if ($code >= 200 && $code < 300 && $body !== false) {
    header('Content-Type: ' . $contentType);
    header('Cache-Control: public, max-age=604800, immutable'); // 7 Tage
    echo $body;
    exit;
}

http_response_code($http404);
exit;
