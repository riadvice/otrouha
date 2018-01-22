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

namespace Actions\Core;

use Actions\Base as BaseAction;

/**
 * Class LocalesController.
 */
class GetLocale extends BaseAction
{
    /**
     * Loads a json translation files from cache or generates if it does not exist.
     *
     * @param \Base $f3
     * @param array $params
     */
    public function execute($f3, $params): void
    {
        $cache        = \Cache::instance();
        $localePrefix = 'locale.' . $params['locale'];

        // checking if the file is already cached, the cache locale file is generated from the file last modification time
        $cached = $cache->exists($hash = $localePrefix . '.' . $f3->hash(filemtime($f3['LOCALES'] . $params['locale'] . '.php') . $params['locale']));

        if ($cached === false) {
            // we create a new json file from locales data
            $cache->reset($localePrefix);
            $cache->set($hash, json_encode($f3['i18n']));
        }

        // @fixme: move to CDN and make the call lighter
        $this->logger->info('Loading locale: ' . $params['locale'], ['cached' => $cached !== false]);

        $this->renderJson($cache->get($hash));
    }
}
