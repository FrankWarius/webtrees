<?php

/**
 * FW-Kernanpassungen
 *
 * Sammelt Anpassungen, die sich über die vorgesehenen Haken erledigen lassen,
 * damit der webtrees-Kern unverändert bleibt.
 *
 * Ablage: modules_v4/FW-Kernanpassungen/module.php
 */

declare(strict_types=1);

namespace FrankWarius\Kernanpassungen;

use Fisharebest\Webtrees\Http\Controllers\SitemapIndexXml;
use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Module\ModuleCustomTrait;
use Fisharebest\Webtrees\Registry;

return new class extends AbstractModule implements ModuleCustomInterface {
    use ModuleCustomTrait;

    public function title(): string
    {
        return 'FW WT 2.3 Core Patches';
    }

    public function description(): string
    {
        return 'Anpassungen am Verhalten von webtrees, die ohne Änderung am Kern auskommen.';
    }

    public function customModuleAuthorName(): string
    {
        return 'Frank Warius';
    }

    public function customModuleVersion(): string
    {
        return '1.0.0';
    }

    /**
     * Läuft über die Middleware BootModules, also nach LoadRoutes und vor
     * Router. Änderungen an der Routensammlung greifen daher noch.
     */
    public function boot(): void
    {
        $this->benenneSitemapAdresseUm();
    }

    /**
     * Die Sitemap-Übersicht unter /newsitemap.xml ausliefern statt unter
     * /sitemap.xml.
     *
     * Hintergrund: Die Google Search Console nimmt unter dieser Domain die
     * Adresse /sitemap.xml nicht mehr an.
     *
     * RouteCollection::add() schlüsselt nach Controller-Klasse. Ein zweiter
     * Aufruf mit demselben Controller ersetzt den Eintrag aus LoadRoutes,
     * legt also keinen zusätzlichen an. Die alte Adresse ist danach nicht
     * mehr erreichbar.
     *
     * Da route(SitemapIndexXml::class) dieselbe Sammlung befragt, übernimmt
     * auch die robots.txt die neue Adresse von selbst.
     */
    private function benenneSitemapAdresseUm(): void
    {
        Registry::routeFactory()->routeMap()->add('/newsitemap.xml', SitemapIndexXml::class);
    }
};
