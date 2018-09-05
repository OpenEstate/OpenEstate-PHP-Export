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

namespace OpenEstate\PhpExport\View;

use OpenEstate\PhpExport\Utils;

/**
 * A detailed view for a real estate object.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class ExposeHtml extends AbstractHtmlView
{
    /**
     * @var string
     * parameter name for the object ID
     */
    public $objectIdParameter = 'id';

    /**
     * ExposeHtml constructor.
     *
     * @param \OpenEstate\PhpExport\Environment $env
     * export environment
     */
    function __construct(\OpenEstate\PhpExport\Environment $env)
    {
        parent::__construct($env);
    }

    protected function generate()
    {
        try {
            return $this->loadThemeFile('expose.php');
        } catch (\Exception $e) {
            Utils::logError($e);
            return null;
        }
    }

    /**
     * Get data for the requested real estate object.
     *
     * @return array|null
     * object data or null, if no valid object was requested
     */
    public function &getObjectData()
    {
        return $this->env->getObject($this->getObjectId());
    }

    /**
     * Get ID of the requested real estate object.
     *
     * @return string|null
     * object ID or null, if no object was requested
     */
    public function getObjectId()
    {
        return (isset($_REQUEST[$this->objectIdParameter]) && \is_string($_REQUEST[$this->objectIdParameter])) ?
            $_REQUEST[$this->objectIdParameter] : null;
    }

    /**
     * Get texts for the requested real estate object.
     *
     * @return array|null
     * object texts or null, if no valid object was requested
     */
    public function getObjectTexts()
    {
        return $this->env->getObjectText($this->getObjectId());
    }

    /**
     * Get parameter values for this view.
     *
     * @param string|null $objectId
     * ID of the object to show
     *
     * @return array
     * associative array with parameter values
     */
    public function getParameters($objectId = null)
    {
        $params = parent::getParameters();

        if ($objectId !== null)
            $params[$this->objectIdParameter] = $objectId;

        return $params;
    }
}
