<?php

/**
 * FW-WT23-CorePatches
 *
 * Sammelt Anpassungen am Verhalten von webtrees, die sich über die
 * vorgesehenen Haken erledigen lassen, damit der Kern unverändert bleibt.
 *
 * Aufbau: Je Anpassung ein Unterordner. Darin eine gleichnamige Datei mit
 * einer gleichnamigen Klasse, die Patch implementiert — Ordner, Datei und
 * Klasse heißen also gleich. Weitere Dateien einer Anpassung bindet deren
 * Einsprungklasse selbst ein, nicht diese Datei.
 *
 * Eine neue Anpassung kostet genau eine Zeile in patches().
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/module.php
 *
 * ACHTUNG: Der Ordnername darf keinen Punkt enthalten. webtrees übergeht
 * solche Module kommentarlos.
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Module\ModuleCustomTrait;

// Module in modules_v4 bringen keinen Autoloader mit. Hier nur die
// gemeinsame Schnittstelle; alles Weitere lädt loadPatch().
require_once __DIR__ . '/Patch.php';

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

    /**
     * Läuft über die Middleware BootModules, also nach LoadRoutes und vor
     * Router. Änderungen an der Routensammlung greifen daher noch.
     */
    public function boot(): void
    {
        foreach ($this->patches() as $patch) {
            $patch->apply($this);
        }
    }

    /**
     * Aktive Anpassungen, bewusst als Liste und nicht über eine
     * Verzeichnissuche — so ist auf einen Blick erkennbar, was läuft.
     *
     * @return array<Patch>
     */
    private function patches(): array
    {
        return [
            $this->loadPatch('SitemapUrl'),
            $this->loadPatch('ShortMarkdown'),
            $this->loadPatch('RobotsTxt'),
            $this->loadPatch('FamilyVisibility'),
        ];
    }

    /**
     * Lädt die Einsprungklasse einer Anpassung nach der Namenskonvention
     * Ordner = Datei = Klasse und gibt eine Instanz zurück.
     */
    private function loadPatch(string $name): Patch
    {
        require_once __DIR__ . '/' . $name . '/' . $name . '.php';

        $className = __NAMESPACE__ . '\\' . $name;

        return new $className();
    }
};
