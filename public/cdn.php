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

// Register directories
$BASE_DIR   = realpath(dirname(__DIR__));
$VENDOR_DIR = $BASE_DIR . DIRECTORY_SEPARATOR . 'vendor';
// Change to application directory to execute the code
chdir($APP_DIR = $BASE_DIR . DIRECTORY_SEPARATOR . 'app');

$GLOBALS['classMap'] = [
    'application\Boot'                          => 'src/Application/Boot.php',
    'application\CdnBoot'                       => 'src/Application/CdnBoot.php',
    'core\Session'                              => 'src/Core/Session.php',
    'cdn\Distributor'                           => 'src/Cdn/Distributor.php',
    'enum\CacheKey'                             => 'src/Enum/CacheKey.php',
    'enum\Enum'                                 => 'src/Enum/Enum.php',
    'enum\UserRole'                             => 'src/Enum/UserRole.php',
    'log\LogWriterTrait'                        => 'src/Log/LogWriterTrait.php',
    'utils\Environment'                         => 'src/Utils/Environment.php',
    'Base'                                      => $VENDOR_DIR . '/bcosca/fatfree-core/base.php',
    'Magic'                                     => $VENDOR_DIR . '/bcosca/fatfree-core/magic.php',
    'Image'                                     => $VENDOR_DIR . '/bcosca/fatfree-core/image.php',
    'Log'                                       => $VENDOR_DIR . '/bcosca/fatfree-core/log.php',
    'Web'                                       => $VENDOR_DIR . '/bcosca/fatfree-core/web.php',
    'DB\SQL'                                    => $VENDOR_DIR . '/bcosca/fatfree-core/db/sql.php',
    'DB\Cursor'                                 => $VENDOR_DIR . '/bcosca/fatfree-core/db/cursor.php',
    'DB\SQL\Session'                            => $VENDOR_DIR . '/bcosca/fatfree-core/db/sql/session.php',
    'DB\SQL\Mapper'                             => $VENDOR_DIR . '/bcosca/fatfree-core/db/sql/mapper.php',
    'Psr\Log\LoggerInterface'                   => $VENDOR_DIR . '/psr/log/Psr/Log/LoggerInterface.php',
    'Monolog\Handler\HandlerInterface'          => $VENDOR_DIR . '/monolog/monolog/src/Monolog/Handler/HandlerInterface.php',
    'Monolog\Handler\AbstractHandler'           => $VENDOR_DIR . '/monolog/monolog/src/Monolog/Handler/AbstractHandler.php',
    'Monolog\Handler\AbstractProcessingHandler' => $VENDOR_DIR . '/monolog/monolog/src/Monolog/Handler/AbstractProcessingHandler.php',
    'Monolog\Handler\StreamHandler'             => $VENDOR_DIR . '/monolog/monolog/src/Monolog/Handler/StreamHandler.php',
    'Monolog\Formatter\FormatterInterface'      => $VENDOR_DIR . '/monolog/monolog/src/Monolog/Formatter/FormatterInterface.php',
    'Monolog\Formatter\LineFormatter'           => $VENDOR_DIR . '/monolog/monolog/src/Monolog/Formatter/LineFormatter.php',
    'Monolog\Formatter\NormalizerFormatter'     => $VENDOR_DIR . '/monolog/monolog/src/Monolog/Formatter/NormalizerFormatter.php',
    'Monolog\Logger'                            => $VENDOR_DIR . '/monolog/monolog/src/Monolog/Logger.php'
];

// custom class autoload to load only the ncessary classes
spl_autoload_register(
    function ($className) {
        require_once $GLOBALS['classMap'][$className];
    }
);

$cdn = new \application\CdnBoot();
$cdn->start();
