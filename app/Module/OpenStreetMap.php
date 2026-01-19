<?php

/**
 * webtrees: online genealogy
 * Copyright (C) 2025 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace Fisharebest\Webtrees\Module;

use Fisharebest\Webtrees\I18N;

/**
 * Class OpenStreetMap - use maps within webtrees
 */
class OpenStreetMap extends AbstractModule implements ModuleMapProviderInterface
{
    use ModuleMapProviderTrait;

    /**
     * Name of the map provider.
     *
     * @return string
     */
    public function description(): string
    {
        $link = '<a href="https://www.openstreetmap.org" dir="ltr">www.openstreetmap.org</a>';

        // I18N: %s is a link/URL
        return I18N::translate('Create maps using %s.', $link);
    }

    /**
     * Name of the map provider.
     *
     * @return string
     */
    public function title(): string
    {
        return I18N::translate('OpenStreetMap™');
    }

    /**
     * Parameters to create a TileLayer in LeafletJs.
     *
     * @return array<object>
     */
    public function leafletJsTileLayers(): array
    {
        $vaild = 300; // Sekunden

        $secret = getenv('OSM_SIG_SECRET') ?: 'CHANGE_ME_TO_LONG_RANDOM_SECRET';
        $exp    = time() + $vaild;
        $nonce  = bin2hex(random_bytes(12));
        $ip     = $_SERVER['REMOTE_ADDR'] ?? '';

        // HINWEIS: Token enthält KEIN path – das ist absichtlich pro Seite identisch
        // aber enthält die Client-IP und wird unten pro style gebildet.
        $makeTok = function (string $style) use ($secret, $exp, $nonce, $ip): string {
            $data = $nonce . '|' . $exp . '|' . $ip . '|' . $style;
            $tok  = base64_encode(hash_hmac('sha256', $data, $secret, true));
            return rtrim(strtr($tok, '+/', '-_'), '=');
        };

        // Query-Strings pro Style
        $qOrg = http_build_query(['exp' => $exp, 'n' => $nonce, 'tok' => $makeTok('org')]);
        $qDe  = http_build_query(['exp' => $exp, 'n' => $nonce, 'tok' => $makeTok('de')]);
        $qFr  = http_build_query(['exp' => $exp, 'n' => $nonce, 'tok' => $makeTok('fr')]);

        $proxyUrl = '/osm-proxy';

        return [
            (object) [
                'attribution' => 'Map data ©<a href="https://www.openstreetmap.org">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0">CC-BY-SA</a>',
                'default'     => false,
                'label'       => 'Mapnik',
                'maxZoom'     => 19,
                'minZoom'     => 2,
                'subdomains'  => ['a', 'b', 'c'],
                'url'         => $proxyUrl . '/org/{z}/{x}/{y}.png?' . $qOrg,
                'localName'   => 'OpenStreetMapsMapnik',
            ],
            (object) [
                'attribution' => 'Map data ©<a href="https://www.openstreetmap.org">Karte hergestellt aus OpenStreetMap-Daten</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0">CC-BY-SA</a>',
                'default'     => true,
                'label'       => 'Deutsch',
                'maxZoom'     => 20,
                'minZoom'     => 2,
                'subdomains'  => ['a', 'b', 'c'],
                'url'         => $proxyUrl . '/de/tiles/osmde/{z}/{x}/{y}.png?' . $qDe,
                'localName'   => 'OpenStreetMapsDeutsch',
            ],
            (object) [
                'attribution' => 'Map data ©<a href="https://www.openstreetmap.org">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0">CC-BY-SA</a>',
                'default'     => false,
                'label'       => 'Français',
                'maxZoom'     => 20,
                'minZoom'     => 2,
                'subdomains'  => ['a', 'b', 'c'],
                'url'         => $proxyUrl . '/fr/osmfr/{z}/{x}/{y}.png?' . $qFr,
                'localName'   => 'OpenStreetMapsFrench',
            ],
        ];
    }
}
