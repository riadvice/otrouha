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

namespace Test;

use Core\Exam;
use SebastianBergmann\CodeCoverage\CodeCoverage;

class Scenario
{
    protected $group = 'Test Scenario';

    /**
     * @param $f3 \Base
     * @return array
     */
    public function run($f3)
    {
        /**
         * @var $coverage CodeCoverage
         */
        $class   = new \ReflectionClass($this);
        $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        $results = [];
        foreach ($methods as $method) {
            /* Select methods starting by test and run them */
            if (preg_match('/^test/i', $method->name)) {
                $humanisedMethodName = preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', str_replace('test', '', $method->name));
                Exam::startCoverage($this->group . ' :: ' . $humanisedMethodName);
                $results = array_merge($results, call_user_func_array([$this, $method->name], [$f3]));
                Exam::stopCoverage();
            }
        }

        return [$this->group => $results];
    }

    /**
     * @return \Test
     */
    protected function newTest()
    {
        // We logout any existing user if there is anyone to force flushing session data
        \Base::instance()->clear('utest.rerouted');
        \Base::instance()->clear('form_errors');
        \Base::instance()->clear('data');
        \Base::instance()->clear('cdn_render');
        \Base::instance()->clear('utest.headers');
        // @fixme: to be activated \Base::instance()->mock('GET /logout');
        \Base::instance()->set('utest.number', $this->currentTestNumber() + 1);
        $this->resetErrorHandler();

        $test = new UnitTest();
        $test->setGroup($this->group);

        return $test;
    }

    protected function resetErrorHandler(): void
    {
        $f3 = \Base::instance();

        // Remove error handler in unit test mode
        $f3->set('ONERROR', function () use ($f3): void {
            // Never use $f3->clear('ERROR'); here as it needs to be done by the developer after checking the error
            $f3->set('utest.errors.' . $f3->hash(serialize($f3->get('ERROR'))), $f3->get('ERROR'));
        });
    }

    /**
     * @return int
     */
    protected function currentTestNumber()
    {
        return \Base::instance()->get('utest.number');
    }

    /**
     * @param $array
     * @return array
     */
    protected function postData($array)
    {
        $array['csrf_token'] = \Registry::get('session')->generateToken();

        return $array;
    }

    /**
     * @return string
     */
    protected function rerouted()
    {
        return \Base::instance()->get('utest.rerouted');
    }

    /**
     * @param        $alias
     * @param  array $params
     * @return bool
     */
    protected function reroutedTo($alias, $params = [])
    {
        return $this->rerouted() === \Base::instance()->alias($alias, $params);
    }

    /**
     * @param $code
     * @return bool
     */
    protected function returnedError($code)
    {
        $f3        = \Base::instance();
        $lastError = $f3->get('utest.errors.' . $this->hashError($f3->get('ERROR')));
        $f3->clear('utest.errors.' . $this->hashError($f3->get('ERROR')));

        return $code === $lastError['code'];
    }

    /**
     * @param  array  $error
     * @return string
     */
    protected function hashError($error)
    {
        return \Base::instance()->hash(serialize($error));
    }

    /**
     * @param $response
     * @param $text
     * @param $type
     * @return bool
     */
    public function responseHasFlash($response, $text, $type)
    {
        return preg_match('/{text:"' . $text . '", type: "' . $type . '"}/', $response, $matches) == 1;
    }

    /**
     * @param $name
     * @param $file
     * @return string
     */
    public function uploadImage($name, $file)
    {
        // Put the file in the magic variable
        $_FILES = [
            $name => [
                'name'     => $fileName = uniqid($name, false) . '.jpg',
                'type'     => 'image/jpg',
                'size'     => filesize($file),
                'tmp_name' => $file,
                'error'    => 0
            ]
        ];

        return $fileName;
    }
}
