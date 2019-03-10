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

namespace OpenEstate\PhpExport\Order;

use OpenEstate\PhpExport\Environment;
use function OpenEstate\PhpExport\gettext as _;

/**
 * Order by object ID.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class ObjectId extends AbstractOrder
{
    /**
     * ObjectId constructor.
     *
     * @param string $name
     * internal name
     *
     * @param int|null $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'ObjectId', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function getSortFlag()
    {
        return SORT_NUMERIC;
    }

    protected function getSortValue(Environment $env, array &$object, $lang)
    {
        $id = (isset($object['id'])) ?
            $object['id'] : null;
        if (!\is_string($id))
            return null;

        $val = \explode('-', $id, 2);
        return $val[\count($val) - 1];
    }

    public function getTitle($lang)
    {
        return _('object ID');
    }

}
