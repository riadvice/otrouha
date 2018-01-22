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

namespace Test;

class TestGroup
{
    protected $classes = [];

    protected $quiet = false;

    /**
     * @param $f3 \Base
     * @return array
     */
    public function run($f3)
    {
        if ($this->quiet) {
            $f3->set('QUIET', true);
        }
        $results = [];
        foreach ($this->classes as $class) {
            /** @var Scenario $object */
            $object  = new $class();
            $results = array_merge($results, $object->run($f3));
        }
        if ($this->quiet) {
            $f3->set('QUIET', false);
        }

        return $results;
    }
}
