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
 * An abstract action.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
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