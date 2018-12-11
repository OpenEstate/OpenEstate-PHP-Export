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

/**
 * Filter by equipment (basic, standard, exclusive, luxury).
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class Equipment extends AbstractFilter
{
    /**
     * Equipment constructor.
     *
     * @param string $name
     * internal name
     *
     * @param int|null $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'Equipment', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function filter(array &$object, array &$items)
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

    public function getTitle($lang)
    {
        return _('equipment');
    }

    public function getWidget(Environment $env, $selectedValue = null)
    {
        if (!$this->readOrRebuild($env) || !\is_array($this->items))
            return null;

        $lang = $env->getLanguage();
        $translations = $env->getTranslations();

        $options = array('basic', 'standard', 'exclusive', 'luxury');
        $values = array();
        $values[''] = '[ ' . $this->getTitle($lang) . ' ]';
        foreach ($options as $o) {
            $txt = (isset($translations['labels']['openestate.equipment.' . $o])) ?
                $translations['labels']['openestate.equipment.' . $o] : null;
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
