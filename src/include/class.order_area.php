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
 * Website-Export, Sortierung nach Fläche.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

require_once(__DIR__ . '/class.order.php');

class ImmoToolOrder_area extends ImmoToolOrder
{
    // Diese Flächenattribute werden zur Ermittlung des Sortierungswertes herangezogen.
    public $lookupFields = array('total_area', 'residential_area', 'plot_area', 'storage_area', 'retail_area', 'sales_area', 'usable_area');

    /**
     * Name des Filters.
     */
    public function getName()
    {
        return 'area';
    }

    /**
     * Titel der Sortierung, abhängig von der Sprache.
     */
    public function getTitle(&$translations, $lang)
    {
        $title = (isset($translations['labels']['estate.area'])) ?
            $translations['labels']['estate.area'] : null;
        return is_string($title) ? $title : $this->getName();
    }

    /**
     * Liefert das Sortierungsfeld eines Objektes.
     */
    public function sort_field(&$object, $lang)
    {
        foreach ($this->lookupFields as $field) {
            $value = (isset($object['attributes']['measures'][$field]['value'])) ?
                $object['attributes']['measures'][$field]['value'] : null;
            if (is_numeric($value))
                return $value;
        }
        return null;
    }

    /**
     * Liefert das Sortierungs-Flag
     * siehe http://www.php.net/manual/en/function.sort.php
     */
    public function sort_flag()
    {
        return SORT_NUMERIC;
    }
}
