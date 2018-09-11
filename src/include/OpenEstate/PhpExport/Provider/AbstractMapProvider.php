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

namespace OpenEstate\PhpExport\Provider;

/**
 * An abstract provider for a map view.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class AbstractMapProvider extends AbstractProvider
{
    /**
     * AbstractMapProvider constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * AbstractMapProvider destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Initialize the map provider for an object.
     *
     * @param array $object
     * object data
     *
     * @return boolean
     * true, if a map is shown for the object
     */
    public function init(array &$object)
    {
        return isset($object['address']['latitude'])
            && isset($object['address']['longitude'])
            && \is_numeric($object['address']['latitude'])
            && \is_numeric($object['address']['longitude']);
    }

    /**
     * Get HTML code for the map.
     *
     * @param array $object
     * object data
     *
     * @return string
     * generated HTML code
     */
    abstract public function getBody(array &$object);

    /**
     * Get latitude value from a real estate object.
     *
     * @param array $object
     * object data
     *
     * @return float|null
     * latitude value or null, if not found
     */
    public function getLatitude(array &$object)
    {
        return (isset($object['address']['latitude'])) ?
            $object['address']['latitude'] : null;
    }

    /**
     * Get longitude value from a real estate object.
     *
     * @param array $object
     * object data
     *
     * @return float|null
     * longitude value or null, if not found
     */
    public function getLongitude(array &$object)
    {
        return (isset($object['address']['longitude'])) ?
            $object['address']['longitude'] :
            null;
    }

    /**
     * Get internal name for the map.
     *
     * @return string
     * internal name
     */
    abstract public function getName();
}
