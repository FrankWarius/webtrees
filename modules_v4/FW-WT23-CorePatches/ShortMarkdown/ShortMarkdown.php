<?php

/**
 * FW-WT23-CorePatches
 *
 * Einsprungklasse der Anpassung. Bindet die eigenen Abhängigkeiten selbst
 * ein, damit der Ordner für sich steht.
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/ShortMarkdown/ShortMarkdown.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Registry;

require_once __DIR__ . '/ShortLinkRenderer.php';
require_once __DIR__ . '/ShortMarkdownFactory.php';

/**
 * Kürzt die Anzeige externer Links auf Schema, Host und Pfad und öffnet sie
 * in einem neuen Fenster.
 */
final class ShortMarkdown implements Patch
{
    /**
     * Registry::markdownFactory() dient zugleich als Setter, wenn ein Objekt
     * übergeben wird. Die Factory liegt in einer statischen Eigenschaft, nicht
     * im Container — der Austausch wirkt daher unmittelbar.
     */
    public function apply(ModuleCustomInterface $module): void
    {
        Registry::markdownFactory(new ShortMarkdownFactory());
    }
}
