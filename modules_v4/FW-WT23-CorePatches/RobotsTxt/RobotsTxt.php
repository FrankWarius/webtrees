<?php

/**
 * FW-WT23-CorePatches
 *
 * Einsprungklasse der Anpassung.
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/RobotsTxt/RobotsTxt.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\View;

/**
 * Ersetzt die Vorlage für robots.txt.
 *
 * Gegenüber der Kernfassung: kein Block mit den 1.600 Einträgen aus
 * BAD_ROBOTS, dafür zusätzliche Sperren für Alt-Adressen, Listenseiten und
 * Ajax-Aufrufe, und Crawl-delay 2 statt 10.
 */
final class RobotsTxt implements Patch
{
    public function apply(ModuleCustomInterface $module): void
    {
        View::registerNamespace($module->name(), __DIR__ . '/views/');
        View::registerCustomView('::robots-txt', $module->name() . '::robots-txt');
    }
}
