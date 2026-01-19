<?php

declare(strict_types=1);

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
