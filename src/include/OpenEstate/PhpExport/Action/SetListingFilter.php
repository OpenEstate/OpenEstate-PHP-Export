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
 * Change filter settings for the listing view.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class SetListingFilter extends AbstractAction
{
    /**
     * Action name.
     *
     * @var string
     */
    const NAME = 'SetListingFilter';

    /**
     * Parameter name for the filter values.
     *
     * @var string
     */
    public $filterParameter = 'filter';

    /**
     * Parameter name for clearing filter values.
     *
     * @var string
     */
    public $clearParameter = 'clear';

    /**
     * SetListingFilter constructor.
     *
     * @param string $name
     * internal name
     */
    function __construct($name = self::NAME)
    {
        parent::__construct($name);

        // add previously configured prefix to parameter names
        $this->filterParameter = Environment::parameter($this->filterParameter);
        $this->clearParameter = Environment::parameter($this->clearParameter);
    }

    public function execute(Environment $env)
    {
        $filter = (isset($_REQUEST[$this->filterParameter])) ?
            $_REQUEST[$this->filterParameter] : null;
        $clear = (isset($_REQUEST[$this->clearParameter])) ?
            $_REQUEST[$this->clearParameter] : null;

        if ($clear !== null)
            $env->getSession()->setListingFilters(null);

        else if (\is_array($filter)) {
            foreach (array_keys($filter) as $key) {
                if (Utils::isBlankString($filter[$key]))
                    unset($filter[$key]);
            }

            $env->getSession()->setListingFilters($filter);
        }

        // always fall back to the first page, if filters are changed
        $env->getSession()->set('listingPage', null);
        return true;
    }

    /**
     * Get parameter values for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param array|null $filter
     * filter values
     *
     * @param bool|null $clear
     * clear filter values
     *
     * @return array
     * associative array with parameter values
     */
    public function getParameters(Environment $env, $filter = null, $clear = null)
    {
        $params = parent::getParameters($env);
        if ($filter !== null && \is_array($filter))
            $params[$this->filterParameter] = $filter;
        if ($clear !== null)
            $params[$this->clearParameter] = 1;
        return $params;
    }

    /**
     * Get url for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param array|null $filter
     * filter values
     *
     * @param bool|null $clear
     * clear filter values
     *
     * @return string
     * url
     */
    public function getUrl(Environment $env, $filter = null, $clear = null)
    {
        return $env->getActionUrl($this->getParameters($env, $filter, $clear));
    }
}