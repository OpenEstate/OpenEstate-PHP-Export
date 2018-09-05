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
 * Remove an object ID from the list of favored objects.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class RemoveFavorite extends AbstractAction
{
    /**
     * Parameter name for the object ID.
     *
     * @var string
     */
    public $objectIdParameter = 'id';

    /**
     * RemoveFavorite constructor.
     *
     * @param string $name
     * internal name
     */
    function __construct($name = 'RemoveFavorite')
    {
        parent::__construct($name);
    }

    public function execute(Environment $env)
    {
        $objectId = (isset($_REQUEST[$this->objectIdParameter]))?
            $_REQUEST[$this->objectIdParameter]: null;
        if (!\is_int($objectId) && !\is_string($objectId))
            return false;

        $env->getSession()->removeFavorite($objectId);
        return true;
    }

    /**
     * Get parameter values for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param int|string|null $objectId
     * object ID, that is removed from the list of favorites
     *
     * @return array
     * associative array with parameter values
     */
    public function getParameters(Environment $env, $objectId = null)
    {
        $params = parent::getParameters($env);
        if ($objectId !== null && (\is_string($objectId) || \is_int($objectId)))
            $params[$this->objectIdParameter] = (string) $objectId;
        return $params;
    }
}