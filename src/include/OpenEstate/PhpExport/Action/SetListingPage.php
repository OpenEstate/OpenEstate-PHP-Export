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

/**
 * Change page of the listing view.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class SetListingPage extends AbstractAction
{
    /**
     * Parameter name for the page number.
     *
     * @var string
     */
    public $pageParameter = 'page';

    /**
     * SetListingPage constructor.
     *
     * @param $name
     * internal name
     */
    function __construct($name = 'SetListingPage')
    {
        parent::__construct($name);
    }

    public function execute(\OpenEstate\PhpExport\Environment $env)
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
     * @param \OpenEstate\PhpExport\Environment $env
     * export environment
     *
     * @param int|null $page
     * page number
     *
     * @return array
     * associative array with parameter values
     */
    public function getParameters(\OpenEstate\PhpExport\Environment $env, $page = null)
    {
        $params = parent::getParameters($env);
        if ($page !== null && \is_numeric($page))
            $params[$this->pageParameter] = $page;
        return $params;
    }
}