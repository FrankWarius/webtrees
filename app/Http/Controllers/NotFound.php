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

namespace Fisharebest\Webtrees\Http\Controllers;

use Fisharebest\Webtrees\Enums\HttpStatusCode;
use Fisharebest\Webtrees\Http\Exceptions\HttpNotFoundException;
use Fisharebest\Webtrees\Http\Middleware\BadBotBlocker;
use Fisharebest\Webtrees\Registry;
use Fisharebest\Webtrees\Services\TreeService;
use Fisharebest\Webtrees\Site;
use Fisharebest\Webtrees\Tree;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function response;

final class NotFound
{
    public function get(ServerRequestInterface $request): ResponseInterface
    {
        return $this->notFound($request);
    }

    public function post(ServerRequestInterface $request): ResponseInterface
    {
        return $this->notFound($request);
    }

    /**
     * *** Mod: robots get an empty response, and the error page needs a tree.
     */
    private function notFound(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getAttribute(BadBotBlocker::ROBOT_ATTRIBUTE_NAME) !== null) {
            return response('', HttpStatusCode::NotFound);
        }

        // The router only supplies a tree when the route has a {tree} parameter,
        // and an unknown URL has none. Without it the header has no navigation.
        $tree_service = Registry::container()->get(TreeService::class);
        $default_tree = $tree_service->all()[Site::getPreference('DEFAULT_GEDCOM')] ?? $tree_service->all()->first();

        if ($default_tree instanceof Tree) {
            Registry::container()->set(
                ServerRequestInterface::class,
                $request->withAttribute('tree', $default_tree)
            );
        }

        throw new HttpNotFoundException();
    }
}
