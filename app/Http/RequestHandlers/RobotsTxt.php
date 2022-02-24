<?php

/**
 * webtrees: online genealogy
 * Copyright (C) 2022 webtrees development team
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

use Fisharebest\Webtrees\Http\Middleware\BadBotBlocker;
use Fisharebest\Webtrees\Module\SiteMapModule;
use Fisharebest\Webtrees\Registry;
use Fisharebest\Webtrees\Services\ModuleService;
use Fisharebest\Webtrees\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use const PHP_URL_PATH;

/**
 * Generate a robots exclusion file.
 *
 * @link https://robotstxt.org
 */
class RobotsTxt implements RequestHandlerInterface
{
    private const DISALLOWED_PATHS = [
        'admin',
        'manager',
        'moderator',
        'editor',
        'account',
    ];

    private ModuleService $module_service;

    /**
     * @param ModuleService $module_service
     */
    public function __construct(ModuleService $module_service)
    {
        $this->module_service = $module_service;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $base_url = Validator::attributes($request)->string('base_url');

        $data = [
            'bad_user_agents'  => BadBotBlocker::BAD_ROBOTS,
            'base_url'         => $base_url,
            'base_path'        => parse_url($base_url, PHP_URL_PATH) ?? '',
            'disallowed_paths' => self::DISALLOWED_PATHS,
            'sitemap_url'      => '',
        ];

        $sitemap_module = $this->module_service->findByInterface(SiteMapModule::class)->first();

        if ($sitemap_module instanceof SiteMapModule) {
            $data['sitemap_url'] = route('sitemap-index');
        }

        return Registry::responseFactory()->response(view('robots-txt', $data))
            ->withHeader('content-type', 'text/plain');
    }
}
