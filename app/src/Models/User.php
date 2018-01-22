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

use Models\Base as BaseModel;

/**
 * Class User
 * @property int       $id
 * @property string    $email
 * @property string    $first_name
 * @property string    $last_name
 * @property string    $full_name
 * @property string    $title
 * @property string    $locale
 * @property string    $password_token
 * @property string    $password
 * @property string    $salt
 * @property string    $status
 * @property string    $token
 * @property array     $settings
 * @property \DateTime $registered_on
 * @property \DateTime $update_on
 * @property \DateTime $last_login
 * @package models
 */
class User extends BaseModel
{
    protected $table = 'users';

    public function __construct($db = null, $table = null, $fluid = null, $ttl = 0)
    {
        parent::__construct($db, $table, $fluid, $ttl);

        $this->virtual('full_name', function ($this) {
            return $this->first_name . ' ' . $this->last_name;
        });

        $this->onset('password', function ($self, $value) {
            $crypt = \Bcrypt::instance();

            $self->salt = substr(md5(uniqid(mt_rand(), true)), 0, 22);

            return $crypt->hash($value, $self->salt);
        });
    }

    /**
     * Get user record by email value
     *
     * @param  string     $email
     * @return \DB\Cortex
     */
    public function getByEmail($email)
    {
        return $this->load(['email = ?', $email]);
    }

    /**
     * Check if email already in use
     *
     * @param  string $email
     * @return bool
     */
    public function emailExists($email)
    {
        return count($this->db->exec('SELECT 1 FROM users WHERE email= ?', $email)) > 0;
    }
}
