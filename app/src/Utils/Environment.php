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

namespace Utils;

class Environment
{
    const TEST        = 'test';
    const DEVELOPMENT = 'development';
    const PRODUCTION  = 'production';

    /**
     * @return bool
     */
    public static function isProduction()
    {
        return \Base::instance()->get('application.environment') === self::PRODUCTION;
    }

    /**
     * @return bool
     */
    public static function isNotProduction()
    {
        return !self::isProduction();
    }

    /**
     * @return bool
     */
    public static function isTest()
    {
        return \Base::instance()->get('application.environment') === self::TEST;
    }
}
