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

namespace Actions\Dashboard;

use Actions\Base as BaseAction;
use Models\Setting;
use Models\User;

/**
 * Class Summary
 * @package actions\dashboard
 */
class Summary extends BaseAction
{
    /**
     * @param \Base $f3
     * @param array $params
     */
    public function execute($f3, $params): void
    {
        // Add JS assets and init them
        $this->assets->addJs('plugins/countdown/jquery.countdown.min.js');
        $f3->push('init.js', 'Dashboard');

        $this->loadData($f3);

        $this->render();
    }

    /**
     * @param \Base $f3
     */
    private function loadData($f3): void
    {
        $user_id = $this->session->get('user.id');
        $setting = new Setting();
        foreach (Setting::$ALLOWED_KEYS as $key => $value) {
            $setting->load(['name = ?', [$value]]);
            $f3->set('data.' . $value, $setting->value);
        }

        $user = new User();
        $user->load(['id = ?', [$user_id]]);
        $f3->set('data.title', $user->title);
    }
}
