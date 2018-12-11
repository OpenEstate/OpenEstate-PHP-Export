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
 * Website-Export, Sortierung nach Preis.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

require_once(__DIR__ . '/class.order.php');

class ImmoToolOrder_price extends ImmoToolOrder
{
    /**
     * Name des Filters.
     */
    public function getName()
    {
        return 'price';
    }

    /**
     * Titel der Sortierung, abhängig von der Sprache.
     */
    public function getTitle(&$translations, $lang)
    {
        $title = (isset($translations['labels']['estate.price'])) ?
            $translations['labels']['estate.price'] : null;
        return is_string($title) ? $title : $this->getName();
    }

    /**
     * Liefert das Sortierungsfeld eines Objektes.
     */
    public function sort_field(&$object, $lang)
    {
        // Preisangaben verstecken
        if ($object['hidden_price'] === true)
            return null;

        // Kauf
        if ($object['action'] == 'purchase')
            return (isset($object['attributes']['prices']['purchase_price']['value'])) ?
                $object['attributes']['prices']['purchase_price']['value'] : null;

        // Miete
        if ($object['action'] == 'rent')
            return (isset($object['attributes']['prices']['rent_excluding_service_charges']['value'])) ?
                $object['attributes']['prices']['rent_excluding_service_charges']['value'] : null;

        // Miete auf Zeit
        if ($object['action'] == 'short_term_rent')
            return (isset($object['attributes']['prices']['rent_flat_rate']['value'])) ?
                $object['attributes']['prices']['rent_flat_rate']['value'] : null;

        // Pacht
        if ($object['action'] == 'lease' || $object['action'] == 'emphyteusis')
            return (isset($object['attributes']['prices']['lease']['value'])) ?
                $object['attributes']['prices']['lease']['value'] : null;

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
