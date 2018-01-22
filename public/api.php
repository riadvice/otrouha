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

if (PHP_SAPI == 'cli') {
    parse_str(str_replace('/?', '', $argv[1]), $_GET);
}

if (!empty($_GET) && array_key_exists('exam', $_GET)) {
    require_once __DIR__ . '/exam/index.php';
    exit;
}

// Change to application directory to execute the code
chdir(realpath(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app'));

// require bootstrap to init the application
require_once 'src/application/ApiBoot.php';

$app = new \application\ApiBoot();
$app->start();
