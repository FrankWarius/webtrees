<?php

/**
 * webtrees: online genealogy
 * Copyright (C) 2026 webtrees development team
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

use function strtolower;
use function substr;

/**
 * Class OpenStreetMap - use maps within webtrees
 */
class OpenStreetMap extends AbstractModule implements ModuleMapProviderInterface
{
    use ModuleMapProviderTrait;

    private const OSM_REFERRER_POLICY = 'strict-origin-when-cross-origin';

    private const LAYER_MAPNIK = 'OpenStreetMapsMapnik';
    private const LAYER_GERMAN = 'OpenStreetMapsDeutsch';
    private const LAYER_FRENCH = 'OpenStreetMapsFrench';

    // The German and French tile servers render place names in those languages.
    // Use them by default when the interface is in the matching language.
    private const DEFAULT_LAYER_BY_LANGUAGE = [
        'de' => self::LAYER_GERMAN,
        'fr' => self::LAYER_FRENCH,
    ];

    /**
     * Name of the map provider.
     */
    public function description(): string
    {
        $link = '<a href="https://www.openstreetmap.org" dir="ltr">www.openstreetmap.org</a>';

        // I18N: %s is a link/URL
        return I18N::translate('Create maps using %s.', $link);
    }

    /**
     * Name of the map provider.
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
        $default_layer = $this->defaultLayer();

        return [
            (object) [
                'attribution' => 'Map data ©<a href="https://www.openstreetmap.org">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0">CC-BY-SA</a>',
                'default'     => $default_layer === self::LAYER_MAPNIK,
                'label'       => 'Mapnik',
                'referrerPolicy' => self::OSM_REFERRER_POLICY,
                'maxZoom'     => 19,
                'minZoom'     => 2,
                'url'         => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                'localName'   => self::LAYER_MAPNIK,
            ],
            (object) [
                'attribution' => 'Map data ©<a href="https://www.openstreetmap.org">Karte hergestellt aus OpenStreetMap-Daten</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0">CC-BY-SA</a>',
                'default'     => $default_layer === self::LAYER_GERMAN,
                'label'       => 'Deutsch',
                'referrerPolicy' => self::OSM_REFERRER_POLICY,
                'maxZoom'     => 18,
                'minZoom'     => 2,
                'url'         => 'https://tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png',
                'localName'   => self::LAYER_GERMAN,
            ],
            (object) [
                'attribution' => 'Map data ©<a href="https://www.openstreetmap.org">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0">CC-BY-SA</a>',
                'default'     => $default_layer === self::LAYER_FRENCH,
                'label'       => 'Français',
                'referrerPolicy' => self::OSM_REFERRER_POLICY,
                'maxZoom'     => 20,
                'minZoom'     => 2,
                // The French server requires the subdomain form.
                // Only the .org and .de servers serve tiles from the canonical hostname.
                'subdomains'  => ['a', 'b', 'c'],
                'url'         => 'https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png',
                'localName'   => self::LAYER_FRENCH,
            ],
        ];
    }

    /**
     * The tile layer to use when the visitor has not chosen one.
     *
     * Note that this only applies to visitors without a stored preference;
     * a layer chosen in the map's layer control takes precedence.
     */
    private function defaultLayer(): string
    {
        $language = strtolower(substr(I18N::languageTag(), 0, 2));

        return self::DEFAULT_LAYER_BY_LANGUAGE[$language] ?? self::LAYER_MAPNIK;
    }
}