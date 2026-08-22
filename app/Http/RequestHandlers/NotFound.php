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

use Fisharebest\Webtrees\Enums\HttpRequestMethod;
use Fisharebest\Webtrees\Enums\HttpStatusCode;
use Fisharebest\Webtrees\Http\Controllers\HomePage;
use Fisharebest\Webtrees\Http\Routing\Route;
use Fisharebest\Webtrees\Http\Exceptions\HttpNotFoundException;
use Fisharebest\Webtrees\Http\Middleware\BadBotBlocker;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Registry;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
        
        // *** Mod: Layout und Themes verlangen ein Routen-Attribut - die
        // Body-Klasse und das Anmelde-Menue lesen es. Bei einer unbekannten
        // Adresse gibt es keines, deshalb hier ein kuenstliches setzen.
        $request = $request->withAttribute('route', new Route($request->getUri()->getPath(), self::class));

        // Need the request to generate a route/error page.
        Registry::container()->set(ServerRequestInterface::class, $request);
        if ($request->getMethod() !== HttpRequestMethod::GET->value) {
            throw new HttpNotFoundException();
        }

        // *** Mod: keine Umleitung auf die Startseite. Unbekannte Adressen
        // sollen einen Fehlerstatus liefern statt ueber zwei Umleitungen die
        // teuerste Seite der Installation aufzubauen.
        throw new HttpNotFoundException(I18N::translate('This page does not exist.'));
    }
}
