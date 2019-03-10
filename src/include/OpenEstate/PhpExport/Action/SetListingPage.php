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
 * Change page of the listing view.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class SetListingPage extends AbstractAction
{
    /**
     * Action name.
     *
     * @var string
     */
    const NAME = 'SetListingPage';

    /**
     * Parameter name for the page number.
     *
     * @var string
     */
    public $pageParameter = 'page';

    /**
     * SetListingPage constructor.
     *
     * @param string $name
     * internal name
     */
    function __construct($name = self::NAME)
    {
        parent::__construct($name);

        // add previously configured prefix to parameter names
        $this->pageParameter = Environment::parameter($this->pageParameter);
    }

    public function execute(Environment $env)
    {
        $page = (isset($_REQUEST[$this->pageParameter])) ?
            $_REQUEST[$this->pageParameter] : null;

        if (!\is_numeric($page))
            $page = null;
        else
            $page = (int)$page;

        if ($page < 1)
            $page = null;

        $env->getSession()->setListingPage($page);
        return true;
    }

    /**
     * Get parameter values for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param int|null $page
     * page number
     *
     * @return array
     * associative array with parameter values
     */
    public function getParameters(Environment $env, $page = null)
    {
        $params = parent::getParameters($env);
        if ($page !== null && \is_numeric($page))
            $params[$this->pageParameter] = $page;
        return $params;
    }

    /**
     * Get url for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param int|null $page
     * page number
     *
     * @return string
     * url
     */
    public function getUrl(Environment $env, $page = null)
    {
        return $env->getActionUrl($this->getParameters($env, $page));
    }
}