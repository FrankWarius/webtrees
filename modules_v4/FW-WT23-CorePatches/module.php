<?php

/**
 * FW-WT23-CorePatches
 *
 * Sammelt Anpassungen am Verhalten von webtrees, die sich über die
 * vorgesehenen Haken erledigen lassen, damit der Kern unverändert bleibt.
 *
 * Je Anpassung ein Unterordner mit einer Klasse, die Patch implementiert.
 * Neue Anpassungen werden in patches() eingetragen — bewusst als Liste und
 * nicht über eine Verzeichnissuche, damit nachvollziehbar bleibt, was aktiv
 * ist.
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

// Module in modules_v4 bringen keinen Autoloader mit, daher direkt einbinden.
require_once __DIR__ . '/Patch.php';
require_once __DIR__ . '/SitemapUrl/SitemapUrl.php';
require_once __DIR__ . '/ShortMarkdown/ShortLinkRenderer.php';
require_once __DIR__ . '/ShortMarkdown/ShortMarkdownFactory.php';
require_once __DIR__ . '/ShortMarkdown/ShortMarkdown.php';

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

    public function customModuleAuthorName(): string
    {
        return 'Frank Warius';
    }

    public function customModuleVersion(): string
    {
        return '1.0.0';
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
     * @return array<Patch>
     */
    private function patches(): array
    {
        return [
            new SitemapUrl(),
            new ShortMarkdown(),
        ];
    }
};
