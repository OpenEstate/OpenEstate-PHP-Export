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
 * Change view of the listing view.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class SetListingView extends AbstractAction
{
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
    function __construct($name = 'SetListingView')
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
}