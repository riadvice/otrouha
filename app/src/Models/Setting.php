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
 * Class Setting
 * @property int $id
 * @property string $name
 * @property string $value
 * @property \DateTime $created_on
 * @property \DateTime $updated_on
 * @package  models
 */
class Setting extends BaseModel
{
    protected $table = 'settings';

    public static $ALLOWED_KEYS = ['organisation', 'email', 'address', 'website', 'telephone', 'locale'];
}
