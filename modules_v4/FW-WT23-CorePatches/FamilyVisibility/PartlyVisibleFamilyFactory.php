<?php

/**
 * Fabrik, die PartlyVisibleFamily statt Family liefert.
 *
 * make() und mapper() der Kernfabrik konstruieren beide ueber new(), daher
 * genuegt es, diese eine Methode zu ueberschreiben.
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/FamilyVisibility/PartlyVisibleFamilyFactory.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\Factories\FamilyFactory;
use Fisharebest\Webtrees\Family;
use Fisharebest\Webtrees\Tree;

class PartlyVisibleFamilyFactory extends FamilyFactory
{
    /**
     * @param string      $gedcom  leerer String bei neuen/schwebenden Datensaetzen
     * @param string|null $pending null ohne schwebende Aenderung, leerer String
     *                             bei schwebender Loeschung
     */
    public function new(string $xref, string $gedcom, string|null $pending, Tree $tree): Family
    {
        return new PartlyVisibleFamily($xref, $gedcom, $pending, $tree);
    }
}
