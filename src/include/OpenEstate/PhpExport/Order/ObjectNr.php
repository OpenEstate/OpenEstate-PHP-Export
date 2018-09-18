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

namespace OpenEstate\PhpExport\Order;

use OpenEstate\PhpExport\Environment;
use function OpenEstate\PhpExport\gettext as _;

/**
 * Order by public object nr.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class ObjectNr extends AbstractOrder
{
    /**
     * ObjectNr constructor.
     *
     * @param string $name
     * internal name
     *
     * @param int|null $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'ObjectNr', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function getSortFlag()
    {
        return SORT_STRING;
    }

    protected function getSortValue(Environment $env, array &$object, $lang)
    {
        $nr = (isset($object['nr'])) ?
            $object['nr'] : null;
        $id = (isset($object['id'])) ?
            $object['id'] : null;

        return \is_string($nr) ?
            $nr : \is_string($id) ?
                '#' . $id : null;
    }

    public function getTitle($lang)
    {
        return _('object Nr');
    }

}
