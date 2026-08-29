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

namespace Fisharebest\Webtrees\Http\Middleware;

use Fisharebest\Webtrees\Enums\HttpStatusCode;
use Fisharebest\Webtrees\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function parse_url;
use function preg_match;
use function preg_quote;
use function response;
use function str_replace;
use function str_starts_with;
use function strlen;
use function substr;

use const PHP_URL_PATH;

/**
 * *** Mod: Sperrt Anfragen, die sich nicht an robots.txt halten.
 *
 * Gesperrt wird nicht, wer auf einer Namensliste steht, sondern wer sich
 * falsch verhaelt. Erkennungsmerkmal ist das Attribut, das BadBotBlocker
 * setzt: bestaetigter Bot ueber DNS oder ASN, oder Verdacht wegen fehlender
 * Cookies und weniger Header. Wer ein Sitzungscookie mitschickt, ist damit
 * ausgenommen - angemeldete Nutzer koennen strukturell nicht getroffen werden.
 *
 * Die Liste der gesperrten Pfade ist zugleich die Quelle fuer robots.txt.
 * Die View liest die beiden Konstanten, damit ausgelieferte Regel und
 * durchgesetzte Regel nicht auseinanderlaufen koennen.
 *
 * Steht im Stapel direkt hinter BadBotBlocker und damit vor Datenbank und
 * Sitzung: eine abgewiesene Anfrage baut keine Verbindung auf.
 */
class RobotsTxtBlocker implements MiddlewareInterface
{
    /**
     * Gesperrte Pfade unterhalb der Wurzel. Ohne fuehrenden Schraegstrich.
     */
    public const array DISALLOWED_PATHS = [
        'admin',
        'manager',
        'moderator',
        'editor',
        'account',
        'login',
        'family.php',
        'individual.php',
        'mediafirewall.php',
        'mediaviewer.php',
        'module.php',
        'source.php',
        'public/*.png',
        'module/statistics_chart',
        'module/tree',
    ];

    /**
     * Gesperrte Pfade innerhalb eines Stammbaums, also unterhalb von
     * /tree/<name>/. Ohne fuehrenden Schraegstrich.
     */
    public const array DISALLOWED_TREE_PATHS = [
        'ancestors',
        'anniversary-ics',
        'branches',
        'compact',
        'contact',
        'descendants',
        'family-book',
        'family-list',
        'fan-chart',
        'hourglass',
        'individual-list',
        'lifespans',
        'location-list',
        'media-download',
        'media-list',
        'media-objects',
        'media-thumbnail',
        'pedigree',
        'place-list',
        'relationships',
        'report',
        'repository-list',
        'search',
        'source-list',
        'timeline',
        '*?ajax=1',
    ];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getAttribute(BadBotBlocker::ROBOT_ATTRIBUTE_NAME) === null) {
            return $handler->handle($request);
        }

        if ($this->violatesRobotsTxt($request)) {
            return response('Forbidden: robots-txt', HttpStatusCode::Forbidden);
        }

        return $handler->handle($request);
    }

    /**
     * Trifft die Anfrage eine der Sperrregeln?
     */
    private function violatesRobotsTxt(ServerRequestInterface $request): bool
    {
        $target = $this->requestTarget($request);

        foreach (self::DISALLOWED_PATHS as $path) {
            if ($this->matchesRule($target, '/' . $path)) {
                return true;
            }
        }

        foreach (self::DISALLOWED_TREE_PATHS as $path) {
            if ($this->matchesRule($target, '/tree/*/' . $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Pfad und Abfrage der Anfrage, ohne das Basisverzeichnis der Installation.
     *
     * Die Abfrage gehoert dazu, weil eine Regel wie "*?ajax=1" sonst nie
     * greifen wuerde.
     */
    private function requestTarget(ServerRequestInterface $request): string
    {
        $base_url  = Validator::attributes($request)->string('base_url', '');
        $base_path = parse_url($base_url, PHP_URL_PATH) ?? '';
        $target    = $request->getUri()->getPath();

        if ($base_path !== '' && $base_path !== '/' && str_starts_with($target, $base_path)) {
            $target = substr($target, strlen($base_path));
        }

        $query = $request->getUri()->getQuery();

        if ($query !== '') {
            $target .= '?' . $query;
        }

        return $target;
    }

    /**
     * Regeln in robots.txt sind Praefixe: "/tree/X/timeline" sperrt auch
     * "/tree/X/timeline-15". Der Stern steht fuer eine beliebige Zeichenfolge.
     */
    private function matchesRule(string $target, string $rule): bool
    {
        $pattern = '#^' . str_replace('\*', '.*', preg_quote($rule, '#')) . '#';

        return preg_match($pattern, $target) === 1;
    }
}