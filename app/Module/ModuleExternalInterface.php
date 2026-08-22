<?php

/**
 * webtrees: online genealogy
 * Copyright (C) 2026 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace Fisharebest\Webtrees\Module;

use Fisharebest\Webtrees\ExternalEndpoint;

/**
 * Interface ModuleExternalInterface - modules whose pages cause the visitor's
 * browser to contact a third-party server.
 */
interface ModuleExternalInterface extends ModuleInterface
{
    /**
     * Third-party servers contacted directly by the visitor's browser, so that
     * the privacy policy can name them.
     *
     * Only browser-side requests belong here. Requests made by webtrees itself
     * do not disclose the visitor to the third party and are out of scope.
     *
     * @return array<ExternalEndpoint>
     */
    public function externalEndpoints(): array;
}
