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

namespace Actions;

use Acl\Access;
use Core\Session;
use Helpers\Assets;
use Helpers\HTML;
use Helpers\I18n;
use Log\LogWriterTrait;
use Template;

/**
 * Base Controller Class.
 */
abstract class Base extends \Prefab
{
    use LogWriterTrait;

    /**
     * f3 instance.
     *
     * @var \Base f3
     */
    protected $f3;

    /**
     * f3 instance.
     *
     * @var Session f3
     */
    protected $session;

    /**
     * f3 instance.
     *
     * @var I18n f3
     */
    protected $i18n;

    /**
     * Assets instance.
     *
     * @var Assets
     */
    protected $assets;

    /**
     * The view name to render.
     *
     * @var string
     */
    protected $view;

    /**
     * @var Access
     */
    private $access;

    /**
     * @var string
     */
    private $templatesDir;

    const JSON = 'Content-Type: application/json; charset=utf-8';
    const XML  = 'Content-Type: text/xml; charset=UTF-8';
    const CSV  = 'Content-Type: text/csv; charset=UTF-8';

    /**
     * initialize controller.
     */
    public function __construct()
    {
        $this->f3      = \Base::instance();
        $this->session = \Registry::get('session');
        $this->i18n    = I18n::instance();
        $this->assets  = Assets::instance();
        $this->access  = Access::instance();

        $this->initLogger();

        $this->templatesDir = $this->f3->get('ROOT') . $this->f3->get('BASE') . '/../app/ui/';
        $this->f3->set('title', 'Otrouha');

        $this->f3->set('init.js', ['Locale', 'Plugins', 'Common']);
    }

    public function beforeroute(): void
    {
        $this->access->authorize($this->session->getRole(), function ($route, $subject): void {
            $this->onAccessAuthorizeDeny($route, $subject);
        });
        if ($this->session->isLoggedIn() && $this->f3->get('ALIAS') === $this->f3->get('ALIASES.login')) {
            $this->f3->reroute($this->f3->get('ALIASES.dashboard'));
        } elseif ($this->f3->VERB === 'POST' && !$this->session->validateToken()) {
            $this->f3->reroute($this->f3->get('PATH'));
        }
        // Rerouted paged uri having the page value less than one
        if ($this->f3->exists('PARAMS.page')) {
            if ($this->f3->get('PARAMS.page') < 1) {
                $uri = $this->f3->get('PATH');
                $uri = preg_replace('/\/' . $this->f3->get('PARAMS.page') . '$/', '/1', $uri);
                $this->f3->reroute($uri);
            }
        }
    }

    public function onAccessAuthorizeDeny($route, $subject): void
    {
        $this->logger->warning('Access denied to route ' . $route . ' for subject ' . ($subject ?: 'unknown'));
        $this->f3->reroute('@login');
    }

    protected function setPartial($name)
    {
        return "$name.phtml";
    }

    /**
     * @param null   $view
     * @param null   $partial
     * @param string $mime
     */
    public function render($view = null, $partial = null, $mime = 'text/html'): void
    {
        // automatically load the partial from the class namespace
        if ($partial === null) {
            $partial = str_replace(['\\_'], '/', str_replace('actions\\', '', $this->f3->snakecase(get_class($this))));
        }
        $this->f3->set('partial', $this->setPartial($partial));
        if ($view === null) {
            $view = $this->view ?: $this->f3->get('view.default');
        }
        // This required to register the template extensions before rendering it
        // We do it at this time because we are sure that we want to render starting from here
        HTML::instance();
        // add controller assets to assets.css and assets.js hive properties
        echo Template::instance()->render($view . '.phtml', $mime);
    }

    /**
     * @param string $json
     */
    public function renderJson($json): void
    {
        header(self::JSON);
        echo is_string($json) ? $json : json_encode($json);
    }

    public function renderCsv($object): void
    {
        header(self::CSV);
        header('Content-Disposition: attachement; filename="' . $this->f3->hash($this->f3->get('TIME') . '.csv"'));
        echo $object;
    }
}
