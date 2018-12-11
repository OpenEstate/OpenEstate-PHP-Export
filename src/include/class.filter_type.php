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

/**
 * Website-Export, Filter nach Immobilienart.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

require_once(__DIR__ . '/class.filter.php');

class ImmoToolFilter_type extends ImmoToolFilter
{
    /**
     * Überprüfung, ob ein Objekt von dem Filter erfasst wird.
     */
    public function filter($object, &$items)
    {
        $types = (isset($object['type_path'])) ? $object['type_path'] : null;
        if (!is_array($types))
            $types = array($object['type']);
        foreach ($types as $type) {
            if (!isset($items[$type]) || !is_array($items[$type]))
                $items[$type] = array();
            $items[$type][] = $object['id'];
        }
    }

    /**
     * Name des Filters.
     */
    public function getName()
    {
        return 'type';
    }

    /**
     * Titel des Filters, abhängig von der Sprache.
     */
    public function getTitle(&$translations, $lang)
    {
        $title = (isset($translations['labels']['estate.type'])) ?
            $translations['labels']['estate.type'] : null;
        return is_string($title) ? $title : $this->getName();
    }

    /**
     * HTML-Code zur Auswahl des Filterkriteriums erzeugen.
     */
    public function getWidget($selectedValue, $lang, &$translations, &$setup)
    {
        $widget = '';
        if (!$this->readOrRebuild($setup->CacheLifeTime) || !is_array($this->items))
            return $widget;
        $sortedTypes = array();
        foreach (array_keys($this->items) as $type) {
            $txt = isset($translations['openestate']['types'][$type]) ?
                $translations['openestate']['types'][$type] : null;
            $sortedTypes[$type] = is_string($txt) ? $txt : $type;
        }
        asort($sortedTypes);
        if (is_array($sortedTypes) && count($sortedTypes) > 0) {
            $by = $this->getTitle($translations, $lang);
            $widget .= '<select id="filter_' . $this->getName() . '" name="' . IMMOTOOL_PARAM_INDEX_FILTER . '[' . $this->getName() . ']">';
            $widget .= '<option value="">[ ' . $by . ' ]</option>';
            foreach ($sortedTypes as $type => $txt) {
                if ($setup->FilterAllEstateTypes === false && strpos($type, 'general_') !== 0)
                    continue;
                $selected = ($selectedValue == $type) ? 'selected="selected"' : '';
                $widget .= '<option value="' . $type . '" ' . $selected . '>' . $txt . '</option>';
            }
            $widget .= '</select>';
        }
        return $widget;
    }
}
