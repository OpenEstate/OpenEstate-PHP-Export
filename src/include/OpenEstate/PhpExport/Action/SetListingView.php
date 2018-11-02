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
use OpenEstate\PhpExport\Utils;

/**
 * Change view of the listing view.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class SetListingView extends AbstractAction
{
    /**
     * Action name.
     *
     * @var string
     */
    const NAME = 'SetListingView';

    /**
     * Parameter name for the listing view.
     *
     * @var string
     */
    public $viewParameter = 'view';

    /**
     * SetListingView constructor.
     *
     * @param string $name
     * internal name
     */
    function __construct($name = self::NAME)
    {
        parent::__construct($name);
    }

    public function execute(Environment $env)
    {
        $view = (isset($_REQUEST[$this->viewParameter])) ?
            $_REQUEST[$this->viewParameter] : null;

        if (Utils::isBlankString($view))
            $view = null;

        $env->getSession()->setListingView($view);
        return true;
    }

    /**
     * Get parameter values for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param string|null $view
     * name of the requested view
     *
     * @return array
     * associative array with parameter values
     */
    public function getParameters(Environment $env, $view = null)
    {
        $params = parent::getParameters($env);
        if ($view !== null && \is_string($view))
            $params[$this->viewParameter] = $view;
        return $params;
    }

    /**
     * Get url for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param string|null $view
     * name of the requested view
     *
     * @return string
     * url
     */
    public function getUrl(Environment $env, $view = null)
    {
        return $env->getActionUrl($this->getParameters($env, $view));
    }
}