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

namespace Actions;

use Actions\Base as BaseAction;
use Helpers\Flash;
use Nette\Utils\Strings;
use Models\Base as Model;

/**
 * Class Delete
 * @package actions
 */
abstract class Delete extends BaseAction
{
    protected $recordId;

    /**
     * @var \ReflectionClass
     */
    protected $class;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var Model
     */
    protected $modelInstance;

    /**
     * @var string
     */
    protected $deleteMethodName = 'erase';

    /**
     * @var string
     */
    protected $messageArg;

    /**
     * @param \Base $f3
     * @param array $params
     */
    public function execute($f3, $params): void
    {
        $this->recordId = $params['id'];
        if ($this->model === null) {
            $this->model = $f3->camelcase(Strings::capitalize(str_replace('-', '_', Strings::before($f3->get('ALIAS'), '_delete'))));
        }

        $this->class         = new \ReflectionClass("models\\{$this->model}");
        $this->modelInstance = $this->class->newInstance();
        $this->modelInstance->load($this->getFilter());

        $this->logger->info("Built delete action for entity {$this->model} with id {$this->recordId}");

        if ($this->modelInstance->valid()) {
            $deleteResult = call_user_func_array([$this->modelInstance, $this->deleteMethodName], []);
            if ($deleteResult === false) {
                $result = ['code' => 500, 'deleted' => false];
                $this->logger->critical("Error occurred while deleting entity {$this->model} with id {$this->recordId}");
            } else {
                $result = ['code' => 200, 'deleted' => true];
                if ($this->messageArg !== null) {
                    $message  = $this->i18n->msg(strtolower($this->model) . '.delete_success');
                    $argument = Strings::startsWith($message, '{0}') ? Strings::capitalize($this->modelInstance[$this->messageArg]) : $this->modelInstance[$this->messageArg];
                    Flash::instance()->addMessage($this->f3->format($message, $argument), Flash::SUCCESS);
                } else {
                    Flash::instance()->addMessage($this->f3->format($this->i18n->msg(strtolower($this->model) . '.delete_success')), Flash::SUCCESS);
                }
            }
        } else {
            $result = ['code' => 404, 'deleted' => false];
            $this->logger->error("Entity {$this->model} with id {$this->recordId} could not be deleted");
        }
        $this->renderJson($result);
    }

    protected function getFilter()
    {
        return ['id = ?', [$this->recordId]];
    }
}
