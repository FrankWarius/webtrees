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

namespace Fisharebest\Webtrees;

/**
 * A third-party server that a visitor's browser contacts directly.
 *
 * Used by the privacy policy, so that it can name the actual recipients of
 * the visitor's request data instead of naming the module only. A module that
 * offers a choice of servers has one endpoint per server.
 */
final readonly class ExternalEndpoint
{
    /**
     * @param string $host       Host name as contacted, e.g. "tile.openstreetmap.de".
     *                           May contain a placeholder where the URL does.
     * @param string $operator   Who runs the server.
     * @param string $country    ISO 3166-1 alpha-2 code of the operator's seat.
     * @param string $purpose    Why the server is contacted. Translated.
     * @param string $privacyUrl The operator's privacy statement. Where none
     *                           exists, a page naming the operator.
     */
    public function __construct(
        public string $host,
        public string $operator,
        public string $country,
        public string $purpose,
        public string $privacyUrl,
    ) {
    }
}
