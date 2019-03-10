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

namespace OpenEstate\PhpExport\Filter;

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Html\Select;
use function OpenEstate\PhpExport\gettext as _;

/**
 * Filter by property type.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class Type extends AbstractFilter
{
    /**
     * Type constructor.
     *
     * @param string $name
     * internal name
     *
     * @param int|null $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'Type', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function filter(array &$object, array &$items)
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

    public function getWidget(Environment $env, $selectedValue = null)
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

        return Select::newSingleSelect(
            'filter[' . $this->getName() . ']',
            'openestate-filter-field-' . $this->getName(),
            'openestate-filter-field',
            $selectedValue,
            $values
        );
    }

}
