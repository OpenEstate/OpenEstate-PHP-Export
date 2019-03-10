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
 * Order by area.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class Area extends AbstractOrder
{
    /**
     * An array of attributes names,
     * that are used to lookup the area of an object.
     *
     * @var array
     */
    public $lookupFields;

    /**
     * Area constructor.
     *
     * @param string $name
     * internal name
     *
     * @param array|null $lookupFields
     * attributes names, that are used to lookup the area of an object
     *
     * @param int|null $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'Area', $lookupFields = null, $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
        $this->lookupFields = (\is_array($lookupFields)) ? $lookupFields :
            array('total_area', 'residential_area', 'plot_area', 'storage_area', 'retail_area', 'sales_area', 'usable_area');
    }

    protected function getSortFlag()
    {
        return SORT_NUMERIC;
    }

    protected function getSortValue(Environment $env, array &$object, $lang)
    {
        foreach ($this->lookupFields as $field) {
            $value = (isset($object['attributes']['measures'][$field]['value'])) ?
                $object['attributes']['measures'][$field]['value'] : null;
            if (\is_numeric($value))
                return $value;

        }
        return null;
    }

    public function getTitle($lang)
    {
        return _('area');
    }

}
