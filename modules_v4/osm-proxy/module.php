<?php

declare(strict_types=1);

require_once __DIR__ . '/OSMProxyModule.php';

use Fisharebest\Webtrees\Module\ModuleInterface;
use OSMProxy\OSMProxyModule;

/**
 * Register module classes.
 *
 * @return ModuleInterface[]
 */
return [
    new OSMProxyModule(),
];
