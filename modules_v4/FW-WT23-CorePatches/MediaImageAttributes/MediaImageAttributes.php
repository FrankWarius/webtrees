<?php

/**
 * FW-WT23-CorePatches
 *
 * Einsprungklasse der Anpassung.
 *
 * Setzt zwei zusaetzliche Attribute an den Bildern, die MediaFile erzeugt:
 * loading="lazy" und height. Die Hoehe stammt vom Aufrufer und ist je nach
 * Kontext 100, 150 oder 200 — externe Medien liefern kein Thumbnail, weil
 * webtrees fremde Dateien nicht durch seinen Skalierdienst schickt, und
 * landen sonst in Originalgroesse auf der Seite.
 *
 * MediaFile wird nur an einer Stelle erzeugt, in Media::mediaFiles(). Media
 * wiederum kommt aus einer Fabrik, ueber die sich die Kette austauschen
 * laesst — daher die drei abhaengigen Klassen.
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/MediaImageAttributes/MediaImageAttributes.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Registry;

// Module in modules_v4 bringen keinen Autoloader mit.
require_once __DIR__ . '/SizedMediaFile.php';
require_once __DIR__ . '/SizedMedia.php';
require_once __DIR__ . '/SizedMediaFactory.php';

final class MediaImageAttributes implements Patch
{
    public function apply(ModuleCustomInterface $module): void
    {
        Registry::mediaFactory(new SizedMediaFactory());
    }
}
