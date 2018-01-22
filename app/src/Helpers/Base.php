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

namespace Helpers;

use Core\Session;
use Log\LogWriterTrait;

/**
 * Base Helper Class.
 */
class Base extends \Prefab
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
     * initialize controller.
     */
    public function __construct()
    {
        $this->f3      = \Base::instance();
        $this->session = \Registry::get('session');
        $this->initLogger();
    }
}
