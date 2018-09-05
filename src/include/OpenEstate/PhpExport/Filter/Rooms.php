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

use function OpenEstate\PhpExport\gettext as _;
use function OpenEstate\PhpExport\ngettext;

/**
 * Filter by number of rooms.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class Rooms extends AbstractFilter
{
    /**
     * Maximal number of rooms to filter.
     *
     * @var int
     */
    public $roomCount;

    /**
     * Rooms constructor.
     *
     * @param string $name
     * internal name
     *
     * @param int $roomCount
     * maximal number of rooms to filter
     *
     * @param int $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'Rooms', $roomCount = null, $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
        $this->roomCount = (\is_int($roomCount) && $roomCount > 0) ?
            $roomCount : 6;
    }

    protected function filter(&$object, &$items)
    {
        $value = isset($object['attributes']['measures']['count_rooms']['value']) ?
            $object['attributes']['measures']['count_rooms']['value'] : null;

        if (!\is_numeric($value) || $value <= 0)
            return;

        $value = (int)\floor($value);
        $max = $this->getMax();
        $key = ($value >= $max) ?
            $max . '+' :
            (string)$value;

        if (!isset($items[$key]) || !\is_array($items[$key]))
            $items[$key] = array();

        $items[$key][] = $object['id'];
    }

    /**
     * Get maximal number of rooms to filter.
     *
     * @return int
     * maximal number of rooms
     */
    public function getMax()
    {
        return (\is_int($this->roomCount) && $this->roomCount > 0) ?
            $this->roomCount : 5;
    }

    public function getTitle($lang)
    {
        return _('number of rooms');
    }

    public function getWidget(\OpenEstate\PhpExport\Environment $env, $selectedValue = null)
    {
        if (!$this->readOrRebuild($env) || !\is_array($this->items))
            return null;

        $lang = $env->getLanguage();
        //$translations = $env->getTranslations();

        $values = array();
        $values[''] = '[ ' . $this->getTitle($lang) . ' ]';
        $max = $this->getMax();
        for ($i = 1; $i < $max; $i++) {
            $values[(string)$i] = ngettext('%1$s room', '%1$s rooms', $i, $i);
        }
        $values[(string)$max] = ngettext('%1$s room', '%1$s rooms', $max, $max . '+');

        return \OpenEstate\PhpExport\Html\Select::newSingleSelect(
            'filter[' . $this->getName() . ']',
            'openestate-filter-field-' . $this->getName(),
            'openestate-filter-field',
            (string)$selectedValue,
            $values
        );
    }

}
