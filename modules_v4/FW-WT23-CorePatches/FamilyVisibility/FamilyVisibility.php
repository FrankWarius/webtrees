<?php

/**
 * Sichtbarkeit von Familien mit lebenden Mitgliedern.
 *
 * Der Kern verbirgt eine Familie, sobald ein einziges Mitglied nicht
 * sichtbar ist — die Familienseite endet dann in 403/404. Diese Anpassung
 * dreht die Regel um: die Familie bleibt sichtbar, sobald mindestens ein
 * Mitglied sichtbar ist. Die uebrigen Mitglieder rendert webtrees ueber den
 * normalen Weg als "vertraulich".
 *
 * Umgesetzt ueber die Familienfabrik, damit der Kern unveraendert bleibt.
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/FamilyVisibility/FamilyVisibility.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Registry;

// Module in modules_v4 bringen keinen Autoloader mit.
require_once __DIR__ . '/PartlyVisibleFamily.php';
require_once __DIR__ . '/PartlyVisibleFamilyFactory.php';

class FamilyVisibility implements Patch
{
    /**
     * Registriert die eigene Fabrik. Registry::familyFactory() haelt die
     * Fabrik in einer statischen Eigenschaft und uebernimmt einen
     * uebergebenen Wert — die Zuweisung greift also ab hier fuer alle
     * folgenden Familienobjekte.
     */
    public function apply(ModuleCustomInterface $module): void
    {
        Registry::familyFactory(new PartlyVisibleFamilyFactory());
    }
}
