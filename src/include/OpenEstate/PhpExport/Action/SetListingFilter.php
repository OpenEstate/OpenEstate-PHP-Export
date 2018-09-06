<?php
/*
 * PHP-Export scripts of OpenEstate-ImmoTool
 * Copyright (C) 2009-2018 OpenEstate.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace OpenEstate\PhpExport\Action;

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Utils;

/**
 * Change filter settings for the listing view.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class SetListingFilter extends AbstractAction
{
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
    function __construct($name = 'SetListingFilter')
    {
        parent::__construct($name);
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