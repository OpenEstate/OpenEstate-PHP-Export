<?php
/*
 * Copyright 2009-2019 OpenEstate.org.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenEstate\PhpExport\Action;

use OpenEstate\PhpExport\Environment;

/**
 * Add an object ID to the list of favored objects.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class AddFavorite extends AbstractAction
{
    /**
     * Action name.
     *
     * @var string
     */
    const NAME = 'AddFavorite';

    /**
     * Parameter name for the object ID.
     *
     * @var string
     */
    public $objectIdParameter = 'id';

    /**
     * AddFavorite constructor.
     *
     * @param string $name
     * internal name
     */
    function __construct($name = self::NAME)
    {
        parent::__construct($name);

        // add previously configured prefix to parameter names
        $this->objectIdParameter = Environment::parameter($this->objectIdParameter);
    }

    public function execute(Environment $env)
    {
        $objectId = (isset($_REQUEST[$this->objectIdParameter])) ?
            $_REQUEST[$this->objectIdParameter] : null;
        if (!\is_int($objectId) && !\is_string($objectId))
            return false;

        $env->getSession()->addFavorite($objectId);
        return true;
    }

    /**
     * Get parameter values for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param int|string|null $objectId
     * object ID, that is added to the list of favorites
     *
     * @return array
     * associative array with parameter values
     */
    public function getParameters(Environment $env, $objectId = null)
    {
        $params = parent::getParameters($env);
        if ($objectId !== null && (\is_string($objectId) || \is_int($objectId)))
            $params[$this->objectIdParameter] = (string)$objectId;
        return $params;
    }

    /**
     * Get url for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param int|string|null $objectId
     * object ID, that is added to the list of favorites
     *
     * @return string
     * url
     */
    public function getUrl(Environment $env, $objectId = null)
    {
        return $env->getActionUrl($this->getParameters($env, $objectId));
    }
}