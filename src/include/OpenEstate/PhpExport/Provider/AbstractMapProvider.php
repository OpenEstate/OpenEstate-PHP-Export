<?php
/*
 * Copyright 2009-2019 OpenEstate.org.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenEstate\PhpExport\Provider;

/**
 * An abstract provider for a map view.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
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
