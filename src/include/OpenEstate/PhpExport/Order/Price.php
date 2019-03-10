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
 * Order by price.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class Price extends AbstractOrder
{
    /**
     * Price constructor.
     *
     * @param string $name
     * internal name
     *
     * @param int|null $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'Price', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function getSortFlag()
    {
        return SORT_NUMERIC;
    }

    protected function getSortValue(Environment $env, array &$object, $lang)
    {
        // Don't sort the object by its price,
        // if prices are hidden for the object.
        if ($object['hidden_price'] === true)
            return null;

        // sort for purchase
        if ($object['action'] == 'purchase')
            return (isset($object['attributes']['prices']['purchase_price']['value'])) ?
                $object['attributes']['prices']['purchase_price']['value'] : null;

        // sort for rent
        if ($object['action'] == 'rent')
            return (isset($object['attributes']['prices']['rent_excluding_service_charges']['value'])) ?
                $object['attributes']['prices']['rent_excluding_service_charges']['value'] : null;

        // sort for rent on time
        if ($object['action'] == 'short_term_rent')
            return (isset($object['attributes']['prices']['rent_flat_rate']['value'])) ?
                $object['attributes']['prices']['rent_flat_rate']['value'] : null;

        // sort for leasing
        if ($object['action'] == 'lease' || $object['action'] == 'emphyteusis')
            return (isset($object['attributes']['prices']['lease']['value'])) ?
                $object['attributes']['prices']['lease']['value'] : null;

        return null;
    }

    public function getTitle($lang)
    {
        return _('price');
    }

}
