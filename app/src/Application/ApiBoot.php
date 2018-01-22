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
use Tracy\Debugger;

// load composer autoload
require_once '../vendor/autoload.php';

/**
 * fat-free framework application initialisation.
 */
class ApiBoot extends Boot
{
    public function __construct()
    {
        $this->logFileName = 'api';

        parent::__construct();

        $this->setupMailer();
        $this->handleException();
        $this->createDatabaseConnection();
        $this->prepareSession();
        $this->detectCli();
        $this->loadRoutesAndAssets();
    }

    protected function loadConfiguration(): void
    {
        // @fixme: remove once https://github.com/bcosca/fatfree-core/issues/164 is fixed
        $this->f3->config('config/default.ini');
        if (file_exists('config/config-' . $this->environment . '.ini')) {
            $this->f3->config('config/config-' . $this->environment . '.ini');
        }

        // Upload configuration
        $this->f3->config('config/api.ini');

        // custom error handler if debugging
        $this->debug = $this->f3->get('DEBUG');
    }

    protected function handleException(): void
    {
        // Tracy consumes about 300 Ko of memory
        if (Debugger::$productionMode) {
            Debugger::$onFatalError = [function ($exception): void {
                $mailer = \Registry::get('mailer');
                $mailer->sendExceptionEmail($exception);
            }];
        }

        // default error pages if site is not being debugged
        if (!$this->isCli && empty($this->debug)) {
            $this->f3->set(
                'ONERROR',
                function (): void {
                    header('Expires:  ' . Time::http(time() + \Base::instance()->get('error.ttl')));
                    if (\Base::instance()->get('ERROR.code') == '404') {
                        include_once 'ui/api/404.phtml';
                    } else {
                        include_once 'ui/api/error.phtml';
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
        $this->f3->config('config/routes-api.ini');
    }
}
