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

namespace Models;

use Test\Scenario;
use Faker\Factory as Faker;

/**
 * Class UserTest
 * @package models
 */
class UserTest extends Scenario
{
    protected $group = 'User Model';

    /**
     * @param  \Base $f3
     * @return array
     */
    public function testPasswordHash($f3)
    {
        $test           = $this->newTest();
        $password       = 'secure_password';
        $user           = new User();
        $user->password = $password;

        $crypt = \Bcrypt::instance();

        $test->expect(strlen($user->salt) === 22, 'User salt has been generated');
        $test->expect($crypt->verify($password, $user->password), 'User password is hashed correctly');

        return $test->results();
    }

    /**
     * @param  \Base $f3
     * @return array
     */
    public function testUserCreation($f3)
    {
        $test           = $this->newTest();
        $faker          = Faker::create();
        $user           = new User(\Registry::get('db'));
        $user->email    = $faker->email;
        $user->password = $faker->password(8);
        $user->save();

        $test->expect($user->id != 0, 'User mocked & saved to the database');

        return $test->results();
    }
}
