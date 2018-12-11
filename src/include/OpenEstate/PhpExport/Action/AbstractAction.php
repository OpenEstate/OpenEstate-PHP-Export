<?php
/*
 * Copyright 2009-2018 OpenEstate.org.
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
 * An abstract action.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
abstract class AbstractAction
{
    /**
     * Internal name of this action.
     *
     * @var string
     */
    private $name;

    /**
     * AbstractAction constructor.
     *
     * @param string $name
     * internal name
     */
    function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * AbstractAction destructor.
     */
    public function __destruct()
    {
    }

    /**
     * Execute this action.
     *
     * @param Environment $env
     * export environment
     *
     * @return mixed
     * action result
     */
    abstract public function execute(Environment $env);

    /**
     * Get the internal action name.
     *
     * @return string
     * action name
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * Get parameter values for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @return array
     * associative array with parameter values
     */
    public function getParameters(Environment $env)
    {
        return array(
            $env->actionParameter => $this->name
        );
    }

    /**
     * Get url for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @return string
     * url
     */
    public function getUrl(Environment $env)
    {
        return $env->getActionUrl($this->getParameters($env));
    }
}