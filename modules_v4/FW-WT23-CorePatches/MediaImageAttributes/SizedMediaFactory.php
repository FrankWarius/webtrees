<?php

/**
 * Fabrik, die SizedMedia statt Media liefert.
 *
 * make() und mapper() der Kernfabrik konstruieren beide ueber new(), daher
 * genuegt es, diese eine Methode zu ueberschreiben.
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/MediaImageAttributes/SizedMediaFactory.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\Factories\MediaFactory;
use Fisharebest\Webtrees\Media;
use Fisharebest\Webtrees\Tree;

class SizedMediaFactory extends MediaFactory
{
    /**
     * @param string      $gedcom  leerer String bei neuen/schwebenden Datensaetzen
     * @param string|null $pending null ohne schwebende Aenderung, leerer String
     *                             bei schwebender Loeschung
     */
    public function new(string $xref, string $gedcom, string|null $pending, Tree $tree): Media
    {
        return new SizedMedia($xref, $gedcom, $pending, $tree);
    }
}
