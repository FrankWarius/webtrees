<?php

/**
 * Example theme.  Here we are extending an existing theme.
 * Instead, you could extend AbstractModule and implement ModuleThemeInterface directly.
 */

declare(strict_types=1);

namespace MyWariusTheme;

use Fisharebest\Webtrees\Module\MinimalTheme;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Module\ModuleCustomTrait;
use Fisharebest\Webtrees\Module\ModuleGlobalInterface;
use Fisharebest\Webtrees\View;

class PureTheme extends MinimalTheme implements ModuleCustomInterface, ModuleGlobalInterface
{
    use ModuleCustomTrait;

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Pure';
    }

    /**
     * Bootstrap the module
     */
    public function boot(): void
    {
        // Register a namespace for our views.
        View::registerNamespace($this->name(), $this->resourcesFolder() . 'views/');

        // Replace an existing view with our own version.
        //???   View::registerCustomView('::chart-box', $this->name() . '::chart-box');
    }

    /**
     * Where does this module store its resources
     *
     * @return string
     */
    public function resourcesFolder(): string
    {
        return __DIR__ . '/resources/';
    }

    /**
     * Add our own stylesheet to the existing stylesheets.
     *
     * @return array
     */
    public function stylesheets(): array
    {
        $stylesheets = parent::stylesheets();

        // NOTE - a future version of webtrees will allow the modules to be stored in a private folder.
        // Only files in the /public/ folder will be accessible via the webserver.
        // Since modules cannot copy their files to the /public/ folder, they need to provide them via a callback.
        $stylesheets[] = $this->assetUrl('css/Pure.css');
        $stylesheets[] = $this->assetUrl('css/Patch-23.css');

        return $stylesheets;
    }

    /**
     * Farbschema fuer Bootstrap. Fest auf hell, weil das Theme
     * durchgehend mit festen Farbwerten arbeitet und im Dunkelmodus
     * Schwarz auf Schwarz ergaebe.
     */
    public function bootstrapColorScheme(): string
    {
        return 'light';
    }

    /**
     * Aus ModuleGlobalInterface. Wird im Layout vor den Skripten
     * ausgegeben, hier nicht gebraucht.
     */
    public function headContent(): string
    {
        return '';
    }

    /**
     * Aus ModuleGlobalInterface. Wird im Layout nach vendor.min.js und
     * webtrees.min.js ausgegeben, Leaflet steht also bereit.
     *
     * addInitHook laeuft bei jeder Karte, die danach erzeugt wird — wir
     * brauchen die Instanz nicht. Greift der Haken nicht, wurde die Karte
     * schon vorher aufgebaut; dann ist der Weg ueber bodyContent zu spaet.
     *
     * Testweise eingebaut, um Zoomstufe und Massstab beurteilen zu koennen.
     */
    public function bodyContent(): string
    {
        return '<script>
            if (window.L !== undefined) {
                L.Map.addInitHook(function () {
                    L.control.scale({imperial: false}).addTo(this);
                });
            }
        </script>';
    }
};