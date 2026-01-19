<?php

declare(strict_types=1);

namespace OSMProxy;

use Fisharebest\Webtrees\Module\OpenStreetMap;

class OSMProxyModule extends OpenStreetMap {

    /**
     * Überschreibe die Tile-URL.
     * Webtrees ruft diese Funktion für jede Kachel auf.
     */
    public function tileUrl(int $z, int $x, int $y): string {

        $secret = getenv('OSM_SIG_SECRET') ?: 'CHANGE_ME_TO_LONG_RANDOM_SECRET';

        $path = "$z/$x/$y.png";
        $exp  = time() + 120; // 2 Minuten gültig

        $data = $path . '|' . $exp;
        $sig  = base64_encode(hash_hmac('sha256', $data, $secret, true));
        $sig  = rtrim(strtr($sig, '+/', '-_'), '=');

        // Proxy-Endpunkt in diesem Modul
        return "/modules_v4/osm-proxy/proxy.php?path=$path&exp=$exp&sig=$sig";
    }

    /**
     * Modulname im Adminbereich
     */
    public function title(): string {
        return 'OSM Secure Proxy';
    }

    /**
     * Beschreibung
     */
    public function description(): string {
        return 'Provides a secure, token-based proxy for OSM tiles.';
    }
}
