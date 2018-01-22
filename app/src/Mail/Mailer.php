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

namespace Mail;

use Enum\CacheKey;
use Log\LogWriterTrait;
use Utils\Environment;

/**
 * Mailer Class.
 */
class Mailer extends \Prefab
{
    use LogWriterTrait;

    /**
     * f3 instance.
     *
     * @var \Base f3
     */
    protected $f3;

    /**
     * @var \SMTP
     */
    private $smtp;

    public function __construct()
    {
        $this->f3   = \Base::instance();
        $config     = $this->f3->get('smtp');
        $this->smtp = new \SMTP($config['host'], $config['port'], $config['security'], $config['user'], $config['password']);
        $this->initLogger();
    }

    /**
     * @param \Exception $exception
     *
     */
    public function sendExceptionEmail($exception): void
    {
        $hash         = substr(md5(preg_replace('~(Resource id #)\d+~', '$1', $exception)), 0, 10);
        $mailSentPath = $this->f3->get('ROOT') . '/' . $this->f3->get('LOGS') . 'email-sent-' . $hash;
        $snooze       = strtotime('1 day') - time();
        if (@filemtime($mailSentPath) + $snooze < time() && @file_put_contents($mailSentPath, 'sent')) {
            $from    = '"Otrouha Debugger" <' . $this->f3->get('mailer.frommail') . '>';
            $subject = "PHP: An error occurred on server {$this->f3->get('HOST')} ERROR ID '{$hash}'";
            $message = 'An error occured on <b>' . $this->f3->get('HOST') . '</b><br />' . nl2br($exception->getTraceAsString());
            $this->smptSend($from, $this->f3->get('debug.email'), $subject, $message);
        }
    }

    /**
     * @param $template
     * @param $vars
     * @param $to
     * @param $subject
     * @return bool
     */
    public function send($template, $vars, $to, $subject)
    {
        $from    = $this->f3->get('mailer.frommail');
        $message = \Template::instance()->render($template, null, $vars);

        //replace the db template variables with provided $vars
        if (array_key_exists('first_name', $vars)) {
            $message = str_replace('[F-NAME]', $vars['first_name'], $message);
        }

        /* @todo: put email variable names in an Enum class to make their use easy */
        if (array_key_exists('reset_link', $vars)) {
            $message = str_replace('[ACTIVE-LINK]', $vars['reset_link'], $message);
        }

        if (array_key_exists('course_name', $vars)) {
            $message = str_replace('[COURSE-NAME]', $vars['course_name'], $message);
        }

        if (array_key_exists('start_date', $vars)) {
            $message = str_replace('[START-DATE]', $vars['start_date'], $message);
        }

        if (array_key_exists('session_link', $vars)) {
            $message = str_replace('[C-URL]', $vars['session_link'], $message);
        }

        $message = str_replace('[C-NAME]', \Cache::instance()->get(CacheKey::Organisation), $message);

        $sent = $this->smptSend('"' . \Cache::instance()->get(CacheKey::Organisation) . '" <' . $from . '>', $to, $subject, $message);

        if ($sent !== false && Environment::isNotProduction()) {
            @file_put_contents($this->f3->get('MAIL_STORAGE') . substr($sent, 1, -1) . '.eml',
                explode("354 Go ahead\n", explode("250 OK\nQUIT", $this->smtp->log())[0])[1]
            );
        }

        $this->logger->info('Sending email | Status: ' . ($sent ? 'true' : 'false') . " | Log:\n" . $this->smtp->log());

        return $sent;
    }

    private function smptSend($from, $to, $subject, $message)
    {
        $this->smtp->set('Content-type', 'text/html; charset=UTF-8'); // email will be rendered as html content
        $this->smtp->set('Message-Id', $messageId = $this->generateId());
        $this->smtp->set('From', $from);
        $this->smtp->set('To', $to);
        $this->smtp->set('Subject', $subject);

        $sent = $this->smtp->send($message, true, Environment::isNotProduction());

        return ($sent === true) ? $messageId : $sent;
    }

    /**
     * Generate a unique message id
     * @return string
     */
    private function generateId()
    {
        return sprintf(
            '<%s.%s@%s>',
            base_convert(microtime(), 10, 36),
            base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36),
            $this->f3->get('HOST')
        );
    }
}
