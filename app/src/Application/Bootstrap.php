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
use Models\Locale;
use Models\Setting;
use Tracy\Debugger;

// load composer autoload
require_once '../vendor/autoload.php';

/**
 * fat-free framework application initialisation.
 */
class Bootstrap extends Boot
{
    public function __construct()
    {
        $this->logFileName = 'app';
        $this->logSession  = true;

        parent::__construct();

        $this->setupMailer();
        $this->handleException();
        $this->createDatabaseConnection();
        $this->prepareSession();
        $this->loadAppSetting();
        $this->detectCli();
        $this->loadRoutesAndAssets();
    }

    protected function loadConfiguration(): void
    {
        // @fixme: remove once https://github.com/bcosca/fatfree-core/issues/164 is fixed
        $this->f3->set('SEED', 'otrouha');
        $this->f3->config('config/default.ini');
        if (file_exists('config/config-' . $this->environment . '.ini')) {
            $this->f3->config('config/config-' . $this->environment . '.ini');
        }

        // Upload configuration
        $this->f3->config('config/upload.ini');

        // custom error handler if debugging
        $this->debug = $this->f3->get('DEBUG');
    }

    protected function handleException(): void
    {
        // Tracy consumes about 300 Ko of memory
        Debugger::enable($this->debug !== 3 ? Debugger::PRODUCTION : Debugger::DEVELOPMENT, __DIR__ . '/../../' . $this->f3->get('LOGS'));
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
                        include_once 'ui/error/404.phtml';
                    } else {
                        include_once 'ui/error/error.phtml';
                    }
                }
            );
        }
    }

    protected function loadAppSetting(): void
    {
        // Load locale
        foreach (['locale' => 'LANGUAGE', 'theme' => 'THEME', 'organisation' => 'ORGANISATION'] as $entry => $default) {
            if ($entry === 'locale') {
                $exists = $this->session->get($entry);
            } else {
                $exists = \Cache::instance()->get($entry);
            }
            if (!$exists) {
                $setting = new Setting();
                $setting->load(['name = ?', [$entry]]);
                if ($setting->valid()) {
                    $value = $setting->value;
                } else {
                    $setting->name = $entry;
                    $value         = $setting->value = explode(',', $this->f3->get($default))[0];
                    $setting->save();
                }
                if ($entry === 'locale') {
                    $this->session->set($entry, $value);
                } else {
                    \Cache::instance()->set($entry, $value);
                }
            }
        }
        $this->f3->set('LANGUAGE', $this->session->get('locale'));
    }

    protected function loadRoutesAndAssets(): void
    {
        // setup routes
        // @see http://fatfreeframework.com/routing-engine
        // firstly load routes from ini file then load custom environment routes
        $this->f3->config('config/routes.ini');

        if (file_exists('config/routes-' . $this->environment . '.ini')) {
            $this->f3->config('config/routes-' . $this->environment . '.ini');
        }

        // load routes access policy
        $this->f3->config('config/access.ini');

        // setup assets
        // @see http://fatfreeframework.com/framework-variables#Customsections
        // assets are save in configuration so we do no need to overload the memory with classes
        $this->f3->config('config/assets.ini');
    }
}
