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

/**
 * Order by area.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
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
     * @param array $lookupFields
     * attributes names, that are used to lookup the area of an object
     *
     * @param int $maxLifeTime
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

    protected function getSortValue(\OpenEstate\PhpExport\Environment &$env, &$object, $lang)
    {
        foreach ($this->lookupFields as $field) {
            $value = (isset($object['attributes']['measures'][$field]['value'])) ?
                $object['attributes']['measures'][$field]['value'] : null;
            if (\is_numeric($value))
                return $value;

        }
        return null;
    }

    public function getTitle(&$translations, $lang)
    {
        $title = (isset($translations['labels']['estate.area'])) ?
            $translations['labels']['estate.area'] : null;
        return \is_string($title) ?
            $title : $this->getName();
    }

}
