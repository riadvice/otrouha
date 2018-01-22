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

namespace Cdn;

use Application\CdnBoot;
use Test\Scenario;
use Fake\UserFaker;

class CdnDistributorTest extends Scenario
{
    protected $group = 'CDN Images';

    /**
     * @param \Base $f3
     *
     * @return array
     */
    public function testGetImages($f3)
    {
        // @todo: check CDN Configuration
        $test = $this->newTest();
        new CdnBoot();

        $f3->mock('GET /logo/' . 32 . ',' . 32);

        $test->expect(count($f3->get('cdn_render')) > 0, 'Website logo image rendered');
        $f3->clear('cdn_render');

        // Load user avatar
        $user = UserFaker::get('my_profile_user');
        UserFaker::loginUser($user);

        $f3->mock('GET /avatar/' . $user->id . '/' . 32 . ',' . 32);
        $test->expect(count($f3->get('cdn_render')) > 0, 'User avatar image rendered');

        return $test->results();
    }
}
