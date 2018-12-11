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

namespace OpenEstate\PhpExport\Filter;

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Html\Select;
use function OpenEstate\PhpExport\gettext as _;
use function OpenEstate\PhpExport\ngettext;

/**
 * Filter by number of rooms.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
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
     * @param int|null $roomCount
     * maximal number of rooms to filter
     *
     * @param int|null $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'Rooms', $roomCount = null, $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
        $this->roomCount = (\is_int($roomCount) && $roomCount > 0) ?
            $roomCount : 6;
    }

    protected function filter(array &$object, array &$items)
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

    public function getWidget(Environment $env, $selectedValue = null)
    {
        if (!$this->readOrRebuild($env) || !\is_array($this->items))
            return null;

        $lang = $env->getLanguage();
        //$translations = $env->getTranslations();

        $values = array();
        $values[''] = '[ ' . $this->getTitle($lang) . ' ]';
        $max = $this->getMax();
        for ($i = 1; $i < $max; $i++) {
            $values[(string)$i] = ngettext('{0} room', '{0} rooms', $i, $i);
        }
        $values[(string)$max] = ngettext('{0} room', '{0} rooms', $max, $max . '+');

        return Select::newSingleSelect(
            'filter[' . $this->getName() . ']',
            'openestate-filter-field-' . $this->getName(),
            'openestate-filter-field',
            (string)$selectedValue,
            $values
        );
    }

}
