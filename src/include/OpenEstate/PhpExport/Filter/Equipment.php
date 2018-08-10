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

namespace OpenEstate\PhpExport\Filter;

/**
 * Filter by equipment (basic, standard, exclusive, luxury).
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class Equipment extends AbstractFilter
{
    /**
     * Equipment constructor.
     *
     * @param $name
     * internal name
     *
     * @param int $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'Equipment', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function filter(&$object, &$items)
    {
        $value = isset($object['attributes']['features']['equipment']['value']) ?
            $object['attributes']['features']['equipment']['value'] : null;
        if (!\is_string($value))
            return;

        $value = \strtolower($value);
        if (!isset($items[$value]) || !\is_array($items[$value]))
            $items[$value] = array();

        $items[$value][] = $object['id'];
    }

    public function getTitle(&$translations, $lang)
    {
        $title = (isset($translations['labels']['openestate.equipment'])) ?
            $translations['labels']['openestate.equipment'] : null;
        return \is_string($title) ?
            $title : $this->getName();
    }

    public function getWidget($selectedValue, $lang, &$translations, &$setup)
    {
        if (!$this->readOrRebuild($setup->CacheLifeTime) || !\is_array($this->items))
            return null;

        $options = array('basic', 'standard', 'exclusive', 'luxury');
        $values = array();
        $values[''] = '[ ' . $this->getTitle($translations, $lang) . ' ]';
        foreach ($options as $o) {
            $txt = (isset($translations['labels']['openestate.equipment.' . $o])) ?
                $translations['labels']['openestate.equipment.' . $o] : null;
            $values[$o] = \is_string($txt) ?
                $txt : $o;
        }

        return \OpenEstate\PhpExport\Html\Select::newSingleSelect(
            'openestate-filter-field-' . $this->getName(),
            'openestate-filter-field',
            'filter[' . $this->getName() . ']',
            $selectedValue,
            $values
        );
    }

}
