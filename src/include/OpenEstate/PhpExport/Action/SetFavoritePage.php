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

/**
 * Change page of the favorite view.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class SetFavoritePage extends AbstractAction
{
    /**
     * Parameter name for the page number.
     *
     * @var string
     */
    public $pageParameter = 'page';

    /**
     * SetFavoritePage constructor.
     *
     * @param string $name
     * internal name
     */
    function __construct($name = 'SetFavoritePage')
    {
        parent::__construct($name);
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

        $env->getSession()->setFavoritePage($page);
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
}