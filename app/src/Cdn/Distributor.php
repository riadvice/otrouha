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
use Core\Session;
use Enum\CacheKey;
use Enum\UserRole;
use Log\LogWriterTrait;
use Monolog\Logger;
use Utils\Environment;

class Distributor
{
    use LogWriterTrait;

    /**
     * @var \Cache
     */
    protected $cache;

    /**
     * @var string
     */
    protected $uploadDir;

    /**
     * @var Session
     */
    protected $session;

    public function __construct()
    {
        $this->logger    = new Logger(\Base::instance()->get('LOGS') . 'cdn-' . date('Y-m-d') . '.log');
        $this->uploadDir = \Base::instance()->get('UPLOADS');
        $this->cache     = \Cache::instance();
        $this->initLogger();
    }

    /**
     * @param  \Base  $f3
     * @param         $params
     * @param  bool   $bypass
     * @return string
     */
    public function siteLogo($f3, $params, $bypass = false)
    {
        $size = $params['width'] . 'x' . $params['height'];
        if (!$bypass && !in_array($size, $f3->get('logo.sizes'), true)) {
            $this->logger->warning('Accessing to not allowed logo size: ' . $size);
            $f3->error(404);
        }

        $logo = new \Image();

        if (!$this->cache->exists($name = CacheKey::logoSize($size))) {
            $result = \Registry::get('db')->exec('SELECT value AS logo FROM settings WHERE name = ?', 'logo');
            if (count($result) === 1 && file_exists($fullPath = $this->uploadDir . $result[0]['logo'])) {
                $logo = new \Image($fullPath);
            } else {
                $logo = new \Image('../public/images/default_logo.png');
            }

            $logo->resize($params['width'], $params['height'], false);
            $this->cache->set($name, $logo->dump('png', 0));
            $this->logger->debug('Putting site logo in cache with key: ' . $name);
        }
        $logo->load($this->cache->get($name));

        $this->logger->debug('Serving site logo from cache with key: ' . $name);
        $f3->set('cdn_render', !$bypass && !Environment::isTest() ? $logo->render('jpeg', 100) : $logo->dump('png', 0));
    }

    /**
     * @param  \Base  $f3
     * @param         $params
     * @return string
     */
    public function avatar($f3, $params)
    {
        if ($this->getSession()->isLoggedIn()) {
            $size = $params['width'] . 'x' . $params['height'];
            if (!in_array($size, $f3->get('avatar.sizes'), true)) {
                $this->logger->warning('Accessing to not allowed avatar size: ' . $size);
                $f3->error(404);
            }

            $avatar = new \Image();
            if (!$this->cache->exists($name = 'user.avatar.' . $params['id'] . '.' . $size)) {
                $result = \Registry::get('db')->exec('SELECT avatar FROM users WHERE id = ?', [$params['id']]);
                if (count($result) === 1 && !empty($result[0]['avatar']) && is_file($fullPath = $this->uploadDir . $result[0]['avatar'])) {
                    $avatar = new \Image($fullPath);
                } else {
                    $avatar = new \Image('../public/images/default_avatar.png');
                }

                $avatar->resize($params['width'], $params['height'], true);
                $this->cache->set($name, $avatar->dump('png', 0));
                $this->logger->debug('Putting user avatar in cache with key: ' . $name);
            }

            $avatar->load($this->cache->get($name));
            $this->logger->debug('Serving user avatar from cache with key: ' . $name);
            $f3->set('cdn_render', !Environment::isTest() ? $avatar->render('jpeg', 100) : $avatar->dump('png', 0));
        } else {
            $f3->error(404);
        }
    }

    /**
     * @param  \Base  $f3
     * @param         $params
     * @return string
     */
    public function dashboardLogo($f3, $params)
    {
        $size = $params['width'] . 'x' . $params['height'];
        if (!in_array($size, $f3->get('dashboard_logo.sizes'), true)) {
            $this->logger->warning('Accessing to not allowed dashboard logo size: ' . $size);
            $f3->error(404);
        }

        $dashboardLogo = new \Image();

        if (!$this->cache->exists($name = CacheKey::dashboardLogo($size))) {
            $result = \Registry::get('db')->exec('SELECT value AS dashboard_logo FROM settings WHERE name = ?', 'dashboard_logo');
            if (count($result) === 1 && file_exists($fullPath = $this->uploadDir . $result[0]['dashboard_logo'])) {
                $dashboardLogo = new \Image($fullPath);
            } else {
                $dashboardLogo = new \Image('../public/images/default_dashboard_bg.jpeg');
            }

            $dashboardLogo->resize($params['width'], $params['height'], false);
            $this->cache->set($name, $dashboardLogo->dump('png', 0));
            $this->logger->debug('Putting dashboard logo in cache with key: ' . $name);
        }
        $dashboardLogo->load($this->cache->get($name));

        $this->logger->debug('Serving dashboard logo from cache with key: ' . $name);
        $f3->set('cdn_render', !Environment::isTest() ? $dashboardLogo->render('jpeg', 100) : $dashboardLogo->dump('png', 0));
    }

    /**
     * @param  \Base  $f3
     * @param         $params
     * @return string
     */
    public function getChatFile($f3, $params)
    {
        if ($this->getSession()->isLoggedIn()) {
            $userId = $this->getSession()->get('user.id');

            // get file record
            if ($params['chat_type'] !== UploadType::User) {
                $filter = "type = '{$params['chat_type']}' AND owner_id = ? AND display_name = ?";
            } else {
                $filter = 'sender_id = ? AND display_name = ?';
            }

            $file = \Registry::get('db')->exec(
                'SELECT * FROM uploads WHERE ' . $filter . ' AND deleted_on < created_on LIMIT 1',
                [$params['id'], $params['name']]
            );

            if ($file && file_exists($fullPath = $this->uploadDir . $file[0]['path'])) {
                $invited = [];
                if ($params['chat_type'] === UploadType::Group) {
                    $invited = \Registry::get('db')->exec(
                        'SELECT id FROM groups_invitations WHERE user_id = ? AND group_id = ?',
                        [$userId, $params['id']]
                    );
                } elseif ($params['chat_type'] === UploadType::Workroom) {
                    // TODO function for session
                    $invited = \Registry::get('db')->exec(
                        'SELECT id FROM courses_invitations WHERE user_id = ? AND course_id = ?',
                        [$userId, $params['id']]
                    );
                }

                // check rights
                //TODO ask about roles
                if (!in_array($this->getSession()->getRole(), [UserRole::Admin, UserRole::Moderator])
                    && $file[0]['sender_id'] != $userId && $file[0]['recipient_id'] != $userId && !$invited
                ) {
                    $f3->error(404);
                }
                $web = \Web::instance();
                $web->send($fullPath, null, 0, true, $file[0]['file_name']);
            } else {
                $f3->error(404);
            }
        } else {
            $f3->error(404);
        }
    }

    /**
     * @return Session
     */
    private function getSession()
    {
        if (is_null($this->session)) {
            CdnBoot::$self->prepareSession();
        }

        return \Registry::get('session');
    }
}
