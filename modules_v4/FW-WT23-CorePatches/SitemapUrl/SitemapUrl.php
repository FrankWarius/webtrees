<?php

/**
 * FW-WT23-CorePatches
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/SitemapUrl/SitemapUrl.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\Http\Controllers\SitemapIndexXml;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Registry;

/**
 * Liefert die Sitemap-Übersicht unter /newsitemap.xml aus statt unter
 * /sitemap.xml.
 *
 * Hintergrund: Die Google Search Console nimmt unter dieser Domain die
 * Adresse /sitemap.xml nicht mehr an.
 */
final class SitemapUrl implements Patch
{
    private const string ADRESSE = '/newsitemap.xml';

    /**
     * RouteCollection::add() schlüsselt nach Controller-Klasse. Ein zweiter
     * Aufruf mit demselben Controller ersetzt den Eintrag aus LoadRoutes,
     * legt also keinen zusätzlichen an. Die alte Adresse ist danach nicht
     * mehr erreichbar.
     *
     * Da route(SitemapIndexXml::class) dieselbe Sammlung befragt, übernimmt
     * auch die robots.txt die neue Adresse von selbst.
     */
    public function apply(ModuleCustomInterface $module): void
    {
        Registry::routeFactory()->routeMap()->add(self::ADRESSE, SitemapIndexXml::class);
    }
}
