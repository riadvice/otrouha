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

/**
 * Time and Date Helper Class.
 */
class Time
{
    /**
     * format a database-specific date/time string.
     *
     * @param string|int|\DateTime $unixTime (optional) the unix time (null = now)
     * @param string               $dbms     (optional) the database software the timestamp is for
     *
     * @return bool|string date in format of database driver
     *
     * @todo add a switch for the f3 database driver and set the timestamp
     */
    public static function db($unixTime = null, $dbms = null)
    {
        // use current time if bad time value or unset
        if (is_string($unixTime)) {
            $date     = new \DateTime($unixTime);
            $unixTime = $date->getTimestamp();
        } elseif ($unixTime instanceof \DateTime) {
            $unixTime = $unixTime->getTimestamp();
        }
        $unixTime = (int) $unixTime;
        if ($unixTime <= 0) {
            $unixTime = time();
        }

        // format date/time according to database driver
        $dbms = empty($dbms) ? \Base::instance()->get('db.driver') : $dbms;
        switch ($dbms) {
            default:
            case 'mysql':
                return date('Y-m-d H:i:s', $unixTime);
        }
    }

    /**
     * Utility to convert timestamp into a http header date/time.
     *
     * @param int    $unixtime time php time value
     * @param string $zone     timezone, default GMT
     *
     * @return string
     */
    public static function http($unixtime = null, $zone = 'GMT')
    {

        // use current time if bad time value or unset
        $unixtime = (int) $unixtime;
        if ($unixtime <= 0) {
            $unixtime = time();
        }

        // if its not a 3 letter timezone set it to GMT
        if (strlen($zone) != 3) {
            $zone = 'GMT';
        } else {
            $zone = strtoupper($zone);
        }

        return gmdate('D, d M Y H:i:s', $unixtime) . ' ' . $zone;
    }

    public static function formattedTime($dateTime = null)
    {
        $formatTime = ' G:i';
        $dateMonth  = lcfirst(date('F', strtotime($dateTime)));
        $dateYear   = lcfirst(date(' j, Y ', strtotime($dateTime)));
        $time       = date($formatTime, strtotime($dateTime));

        return [$dateMonth, $dateYear, $time];
    }
}
