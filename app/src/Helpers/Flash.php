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

namespace Helpers;

use Helpers\Base as BaseHelper;

/**
 * Class Flash
 * @package helpers
 */
class Flash extends BaseHelper
{
    const ALERT       = 'alert';
    const ERROR       = 'error';
    const WARNING     = 'warning';
    const SUCCESS     = 'success';
    const INFORMATION = 'information';

    /**
     * @var array
     */
    private $messages;

    /**
     * Initialize Flash Helper.
     */
    public function __construct()
    {
        parent::__construct();
        $this->session  = \Registry::get('session');
        $this->messages = &$this->f3->ref('SESSION.notifications');
    }

    /**
     * @param string $text The message text content.
     * @param string $type The message type.
     */
    public function addMessage($text, $type = self::INFORMATION): void
    {
        $this->messages[$type][] = $text;
    }

    /**
     * Clear all messages
     */
    public function clearMessages(): void
    {
        $this->messages = [];
    }

    /**
     * @return bool
     */
    public function hasMessages()
    {
        return !empty($this->messages);
    }

    /**
     * Displays notifications from the session
     *
     * @return string
     */
    public function showMessages()
    {
        $noty = '';

        if (!empty($this->messages)) {
            foreach ($this->messages as $key => $value) {
                if (count($value) > 0) {
                    foreach ($value as $message) {
                        $noty .= 'noty({text:"' . $message . '", type: "' . $key . '"});' . "\n";
                    }
                }
            }
        }

        $this->clearMessages();

        return $noty;
    }

    /**
     * @param $text
     * @param  string $type
     * @return bool
     */
    public function hasMessage($text, $type = self::INFORMATION)
    {
        return array_key_exists($type, $this->messages) && array_search($text, $this->messages[$type], true) > -1;
    }
}
