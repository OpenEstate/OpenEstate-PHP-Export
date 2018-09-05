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
     *
     * @param int $width
     * width of the embedded element
     *
     * @param int $height
     * height of the embedded element
     */
    function __construct($width = 0, $height = 0)
    {
        parent::__construct($width, $height);
    }

    /**
     * AbstractMapProvider destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Test, if an object provides geo coordinates in order to create a map.
     *
     * @param array $object
     * object data
     *
     * @return boolean
     * true, if the object provides geo coordinates
     */
    public function canShowForObject(&$object)
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
     * @param array $translations
     * translation data
     *
     * @param string $lang
     * current language
     *
     * @return string
     * generated HTML code
     */
    abstract public function getBody(&$object, &$translations, $lang);

    /**
     * Get latitude value from a real estate object.
     *
     * @param array $object
     * object data
     *
     * @return float|null
     * latitude value or null, if not found
     */
    public function getLatitude(&$object)
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
    public function getLongitude(&$object)
    {
        return (isset($object['address']['longitude'])) ?
            $object['address']['longitude'] :
            null;
    }

}
