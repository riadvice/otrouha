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
use Enum\Locale;
use Models\Setting;
use Validation\Validator;

/**
 * Class Settings
 * @package actions\core
 */
class Settings extends BaseAction
{
    public function __construct()
    {
        parent::__construct();

        $this->assets->addJs('core/settings.js');

        $this->f3->push('init.js', 'Settings');
    }

    public function load(): void
    {
        $this->loadData();
        $this->render();
    }

    public function save(): void
    {
        $v    = new Validator();
        $form = $this->f3->get('POST');

        $this->filterSettings($form);

        $v->notEmpty()->verify('organisation', $form['organisation'], ['notEmpty' => $this->i18n->err('settings.organisation')]);
        $v->email()->verify('email', $form['email'], ['email' => $this->i18n->err('settings.email')]);
        $v->url()->verify('website', $form['website'], ['url' => $this->i18n->err('settings.website')]);
        $v->notEmpty()->verify('address', $form['address'], ['notEmpty' => $this->i18n->err('settings.address')]);
        $v->phone()->verify('telephone', $form['telephone'], ['phone' => $this->i18n->err('settings.phone')]);
        $v->equals(true)->verify('locale', Locale::contains($form['locale']), ['equals' => $this->i18n->err('settings.locale')]);
        $v->equals(true)->verify('theme', Theme::contains($form['theme']), ['equals' => $this->i18n->err('settings.theme')]);

        $this->render();
    }

    private function loadData(): void
    {
        $setting = new Setting();

        foreach (Setting::$ALLOWED_KEYS as $key => $value) {
            $setting->load(['name = ?', [$value]]);
            $this->f3->set('data.' . $value, $setting->value);
        }
    }

    private function loadPost($form): void
    {
        foreach (Setting::$ALLOWED_KEYS as $key => $value) {
            $this->f3->set('data.' . $value, $form[$value]);
        }
    }

    private function filterSettings(&$array): void
    {
        foreach ($array as $key => $value) {
            if (!in_array($key, Setting::$ALLOWED_KEYS, true)) {
                unset($array[$key]);
            }
        }
    }
}
