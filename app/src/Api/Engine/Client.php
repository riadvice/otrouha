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

namespace api\engine;

use Core\Session;
use Helpers\I18n;
use Log\LogWriterTrait;

abstract class Client extends \Prefab
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
     * The view name to render.
     *
     * @var string
     */
    protected $view;

    /**
     * The referrer URL
     *
     * @var string
     */
    protected $referrer;

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
        $this->initLogger();
    }

    /**
     * @param \Base $f3
     * @param       $params
     *
     * @return bool
     */
    public function beforeroute($f3, $params)
    {
        $this->referrer = $f3->get('HEADERS.Referer');
    }

    /**
     * @param $statusCode
     * @param $json
     */
    public function renderJson($statusCode, $json): void
    {
        header('HTTP/1.1 ' . $statusCode);
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
