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

/**
 * Filter by property type.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class Type extends AbstractFilter
{
    /**
     * Type constructor.
     *
     * @param $name
     * internal name
     *
     * @param int $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'Type', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function filter(&$object, &$items)
    {
        $types = (isset($object['type_path'])) ? $object['type_path'] : null;
        if (!\is_array($types) && isset($object['type']))
            $types = array($object['type']);

        if (!\is_array($types))
            return;

        foreach ($types as $type) {
            if (!isset($items[$type]) || !\is_array($items[$type])) {
                $items[$type] = array();
            }
            $items[$type][] = $object['id'];
        }
    }

    public function getTitle($lang)
    {
        return _('object type');
    }

    public function getWidget(\OpenEstate\PhpExport\Environment $env, $selectedValue = null)
    {
        if (!$this->readOrRebuild($env) || !\is_array($this->items))
            return null;

        $lang = $env->getLanguage();
        $translations = $env->getTranslations();

        $options = array();
        foreach (\array_keys($this->items) as $type) {
            $txt = isset($translations['openestate']['types'][$type]) ?
                $translations['openestate']['types'][$type] : null;
            $options[$type] = \is_string($txt) ?
                $txt : $type;
        }
        \asort($options);

        $values = array();
        $values[''] = '[ ' . $this->getTitle($lang) . ' ]';
        foreach ($options as $key => $value) {
            $values[$key] = $value;
        }

        return \OpenEstate\PhpExport\Html\Select::newSingleSelect(
            'filter[' . $this->getName() . ']',
            'openestate-filter-field-' . $this->getName(),
            'openestate-filter-field',
            $selectedValue,
            $values
        );
    }

}
