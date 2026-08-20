<?php

/**
 * FW-WT23-CorePatches
 *
 * Einsprungklasse der Anpassung.
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/ShortDateTime/ShortDateTime.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\View;

/**
 * Kürzt die Zeitstempel in Listen und Verwaltungsseiten.
 *
 * Der Kern formatiert mit "LLLL" — "Dienstag, 17. September 2024 17:12".
 * Hier "llll" — "Di., 17. Sept. 2024 17:12". Sonst unverändert.
 *
 * Betrifft nur components/datetime.phtml. components/datetime-diff.phtml
 * bleibt auf der Kernfassung.
 */
final class ShortDateTime implements Patch
{
    public function apply(ModuleCustomInterface $module): void
    {
        // Eigener Namensraum je Patch. View::registerNamespace() überschreibt
        // einen bereits belegten Namen kommentarlos — ein gemeinsamer Name
        // würde den jeweils zuvor registrierten Patch aushebeln.
        $namespace = $module->name() . '-ShortDateTime';

        View::registerNamespace($namespace, __DIR__ . '/views/');
        View::registerCustomView('::components/datetime', $namespace . '::components/datetime');
    }
}
