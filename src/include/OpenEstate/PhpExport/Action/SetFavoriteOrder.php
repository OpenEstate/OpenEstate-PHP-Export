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
 * Change ordering of the favorite view.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class SetFavoriteOrder extends AbstractAction
{
    /**
     * Parameter name for the ordering method.
     *
     * @var string
     */
    public $orderParameter = 'order';

    /**
     * Parameter name for the ordering direction.
     *
     * @var string
     */
    public $directionParameter = 'direction';

    /**
     * SetFavoriteOrder constructor.
     *
     * @param string $name
     * internal name
     */
    function __construct($name = 'SetFavoriteOrder')
    {
        parent::__construct($name);
    }

    public function execute(Environment $env)
    {
        $order = (isset($_REQUEST[$this->orderParameter])) ?
            $_REQUEST[$this->orderParameter] : null;
        $direction = (isset($_REQUEST[$this->directionParameter])) ?
            $_REQUEST[$this->directionParameter] : null;

        if (\is_string($order))
            $env->getSession()->setFavoriteOrder($order);
        else
            $env->getSession()->setFavoriteOrder(null);

        if (\is_string($direction))
            $env->getSession()->setFavoriteOrderDirection($direction);
        else
            $env->getSession()->setFavoriteOrderDirection(null);

        return true;
    }

    /**
     * Get parameter values for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param string|null $order
     * name of the ordering method
     *
     * @param string|null $direction
     * direction of ordering ("asc" or "desc")
     *
     * @return array
     * associative array with parameter values
     */
    public function getParameters(Environment $env, $order = null, $direction = null)
    {
        $params = parent::getParameters($env);
        if ($order !== null && \is_string($order))
            $params[$this->orderParameter] = $order;
        if ($direction !== null && \is_string($direction))
            $params[$this->directionParameter] = $direction;
        return $params;
    }

    /**
     * Get url for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param string|null $order
     * name of the ordering method
     *
     * @param string|null $direction
     * direction of ordering ("asc" or "desc")
     *
     * @return string
     * url
     */
    public function getUrl(Environment $env, $order = null, $direction = null)
    {
        return $env->getActionUrl($this->getParameters($env, $order, $direction));
    }
}