<?php

/**
 * Familie, die schon dann sichtbar ist, wenn ein Mitglied sichtbar ist.
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/FamilyVisibility/PartlyVisibleFamily.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\Enums\AccessLevel;
use Fisharebest\Webtrees\Family;
use Fisharebest\Webtrees\Gedcom;
use Fisharebest\Webtrees\Individual;
use Fisharebest\Webtrees\Registry;

use function preg_match_all;

class PartlyVisibleFamily extends Family
{
    /**
     * Der Kern verbirgt die Familie, sobald ein Mitglied nicht sichtbar ist.
     * Hier umgekehrt: die Familie bleibt sichtbar, sobald ein Mitglied
     * sichtbar ist. Die uebrigen erscheinen als "vertraulich".
     *
     * Die Regel bezieht sich ausschliesslich auf die Familie als Datensatz.
     * Ob ein einzelnes Mitglied mit Namen erscheint, entscheidet weiterhin
     * Individual::canShow() unveraendert.
     */
    protected function canShowByType(AccessLevel $access_level): bool
    {
        preg_match_all('/\n1 (?:CHIL|HUSB|WIFE) @(' . Gedcom::REGEX_XREF . ')@/', $this->gedcom, $matches);

        // Familie ohne Mitgliedsverweise: Verhalten des Kerns beibehalten.
        if ($matches[1] === []) {
            return true;
        }

        foreach ($matches[1] as $match) {
            $individual = Registry::individualFactory()->make($match, $this->tree);

            if ($individual instanceof Individual && $individual->canShow($access_level)) {
                return true;
            }
        }

        return false;
    }
}
