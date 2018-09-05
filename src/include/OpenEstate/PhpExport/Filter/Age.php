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

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Html\Select;
use function OpenEstate\PhpExport\gettext as _;

/**
 * Filter by age (new building or old building).
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class Age extends AbstractFilter
{
    /**
     * Age constructor.
     *
     * @param string $name
     * internal name
     *
     * @param int|null $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'Age', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function filter(array &$object, array &$items)
    {
        $value = (isset($object['attributes']['condition']['age']['value'])) ?
            $object['attributes']['condition']['age']['value'] : null;
        if (!\is_string($value))
            return;

        $value = \strtolower($value);
        if (!isset($items[$value]) || !\is_array($items[$value]))
            $items[$value] = array();

        $items[$value][] = $object['id'];
    }

    public function getTitle($lang)
    {
        return _('age');
    }

    public function getWidget(Environment $env, $selectedValue = null)
    {
        if (!$this->readOrRebuild($env) || !\is_array($this->items))
            return null;

        $lang = $env->getLanguage();
        $translations = $env->getTranslations();

        $options = array('old_building', 'new_building');
        $values = array();
        $values[''] = '[ ' . $this->getTitle($lang) . ' ]';
        foreach ($options as $o) {
            $txt = (isset($translations['labels']['openestate.age.' . $o])) ?
                $translations['labels']['openestate.age.' . $o] : null;
            $values[$o] = \is_string($txt) ?
                $txt : $o;
        }

        return Select::newSingleSelect(
            'filter[' . $this->getName() . ']',
            'openestate-filter-field-' . $this->getName(),
            'openestate-filter-field',
            $selectedValue,
            $values
        );
    }

}
