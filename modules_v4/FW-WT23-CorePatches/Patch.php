<?php

/**
 * FW-WT23-CorePatches
 *
 * Gemeinsame Schnittstelle aller Anpassungen.
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/Patch.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\Module\ModuleCustomInterface;

interface Patch
{
    /**
     * Wird aus boot() des Moduls aufgerufen, also nach dem Laden der Routen
     * und vor deren Auswertung.
     *
     * Das Modul wird übergeben, weil einige Anpassungen dessen Namen oder
     * Ressourcenordner brauchen — etwa für Views.
     */
    public function apply(ModuleCustomInterface $module): void;
}
