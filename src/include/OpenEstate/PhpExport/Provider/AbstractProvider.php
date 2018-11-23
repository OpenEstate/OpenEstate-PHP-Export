<?php
/*
 * Copyright 2009-2018 OpenEstate.org.
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
 * An abstract embedded view from an external provider.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
abstract class AbstractProvider
{
    /**
     * AbstractProvider constructor.
     */
    function __construct()
    {
    }

    /**
     * AbstractProvider destructor.
     */
    public function __destruct()
    {
    }

    /**
     * Get HTML header elements for the embedded view.
     *
     * @return array
     * header elements
     */
    public function getHeaderElements()
    {
        return array();
    }
}