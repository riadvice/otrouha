<?php

/**
 * Copyright (C) 2018 RIADVICE SUARL <otrouha@riadvice.tn>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Application;

use Helpers\Time;
use Utils\Environment;

/**
 * fat-free framework application initialisation.
 */
class CdnBoot extends Boot
{
    /**
     * @var CdnBoot
     */
    public static $self;

    public function __construct()
    {
        // Will be used in cases where we need to init the session later in the CDN
        self::$self        = $this;
        $this->logFileName = 'cdn';

        parent::__construct();

        $this->handleException();
        $this->createDatabaseConnection();
        $this->loadRoutesAndAssets();
    }

    protected function detectEnvironment(): void
    {
        // read config and overrides
        // @see http://fatfreeframework.com/framework-variables#configuration-files
        // set the environment dynamically depending on the server IP address
        $host = $this->f3->get('HOST');

        if (strpos($host, 'otrouha.dev') !== false || $this->isCli) {
            if (preg_match('/^exam(\=\w{1,}){0,}(\&cli){0,}(\&test\=[\w\,]{1,}){0,}/i', $this->f3->get('QUERY')) === 0) {
                $this->f3->set('application.environment', Environment::DEVELOPMENT);
            } else {
                $this->f3->set('application.environment', Environment::TEST);
            }
        } else {
            $this->f3->set('application.environment', Environment::PRODUCTION);
        }

        $this->environment = $this->f3->get('application.environment');
    }

    protected function loadConfiguration(): void
    {
        // @fixme: remove once https://github.com/bcosca/fatfree-core/issues/164 is fixed
        $this->f3->set('SEED', 'lms');
        $this->f3->config('config/default.ini');
        $this->f3->config('config/cdn.ini');

        if (file_exists('config/config-' . $this->environment . '.ini')) {
            $this->f3->config('config/config-' . $this->environment . '.ini');
        }

        // custom error handler if debugging
        $this->debug = $this->f3->get('DEBUG');
    }

    protected function handleException(): void
    {
        //@fixme: improve this for CDN
        // default error pages if site is not being debugged
        if (empty($this->debug)) {
            $this->f3->set(
                'ONERROR',
                function (): void {
                    header('Expires:  ' . Time::http(time() + \Base::instance()->get('error.ttl')));
                    if (\Base::instance()->get('ERROR.code') == '404') {
                        include_once 'ui/error/404.phtml';
                    } else {
                        include_once 'ui/error/error.phtml';
                    }
                }
            );
        }
    }

    protected function loadRoutesAndAssets(): void
    {
        // setup routes
        // @see http://fatfreeframework.com/routing-engine
        // firstly load routes from ini file then load custom environment routes
        $this->f3->config('config/routes-cdn.ini');

        if (file_exists('config/routes-' . $this->environment . '.ini')) {
            $this->f3->config('config/routes-' . $this->environment . '.ini');
        }

        // load routes access policy
        $this->f3->config('config/access-cdn.ini');
    }
}
