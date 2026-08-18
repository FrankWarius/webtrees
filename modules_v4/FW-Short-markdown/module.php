<?php

/**
 * FW-Short-markdown
 *
 * Ersetzt die Markdown-Factory von webtrees, damit externe Links in einem
 * neuen Fenster geöffnet und im Text gekürzt dargestellt werden.
 *
 * Ablage: modules_v4/FW-Short-markdown/module.php
 */

declare(strict_types=1);

namespace FrankWarius\ShortMarkdown;

use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Module\ModuleCustomTrait;
use Fisharebest\Webtrees\Registry;

// Module in modules_v4 bringen keinen Autoloader mit, daher direkt einbinden.
require_once __DIR__ . '/src/ExternerLinkRenderer.php';
require_once __DIR__ . '/src/MarkdownFabrikMitKurzenLinks.php';

return new class extends AbstractModule implements ModuleCustomInterface {
    use ModuleCustomTrait;

    public function title(): string
    {
        return 'FW Short markdown';
    }

    public function description(): string
    {
        return 'Kürzt die Anzeige externer Links auf Schema, Host und Pfad und öffnet sie in einem neuen Fenster.';
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
     * Eigene Markdown-Factory eintragen. Registry::markdownFactory() dient
     * zugleich als Setter, wenn ein Objekt übergeben wird.
     */
    public function boot(): void
    {
        Registry::markdownFactory(new MarkdownFabrikMitKurzenLinks());
    }
};
