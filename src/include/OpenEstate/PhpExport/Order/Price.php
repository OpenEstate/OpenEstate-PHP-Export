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

namespace OpenEstate\PhpExport\Order;

use function OpenEstate\PhpExport\gettext as _;

/**
 * Order by price.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class Price extends AbstractOrder
{
    /**
     * Price constructor.
     *
     * @param $name
     * internal name
     *
     * @param int $maxLifeTime
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

    protected function getSortValue(\OpenEstate\PhpExport\Environment $env, &$object, $lang)
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
