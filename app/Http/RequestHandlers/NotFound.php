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

namespace Fisharebest\Webtrees\Http\RequestHandlers;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Fisharebest\Webtrees\Tree;
use Fisharebest\Webtrees\Site;
use Fisharebest\Webtrees\Services\TreeService;
use Fisharebest\Webtrees\Registry;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Http\Routing\Route;
use Fisharebest\Webtrees\Http\Middleware\BadBotBlocker;
use Fisharebest\Webtrees\Http\Exceptions\HttpNotFoundException;
use Fisharebest\Webtrees\Http\Controllers\HomePage;
use Fisharebest\Webtrees\Enums\HttpStatusCode;
use Fisharebest\Webtrees\Enums\HttpRequestMethod;

use function redirect;
use function response;
use function route;

final class NotFound implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Robots don't need pretty error pages
        if ($request->getAttribute(BadBotBlocker::ROBOT_ATTRIBUTE_NAME) !== null) {
            return response('', HttpStatusCode::NotFound);
        }

        $request = $request->withAttribute('route', new Route($request->getUri()->getPath(), self::class));
        $tree_service = Registry::container()->get(TreeService::class);
        $default_tree = $tree_service->all()[Site::getPreference('DEFAULT_GEDCOM')] ?? $tree_service->all()->first();

        if ($default_tree instanceof Tree) {
            $request = $request->withAttribute('tree', $default_tree);
        }

        // Need the request to generate a route/error page.
        Registry::container()->set(ServerRequestInterface::class, $request);

        throw new HttpNotFoundException();
    }
}