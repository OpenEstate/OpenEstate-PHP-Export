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
 * Website-Export, Filter nach möblierten Inseraten.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

require_once( __DIR__ . '/class.filter.php' );

class ImmoToolFilter_furnished extends ImmoToolFilter {

  /**
   * Überprüfung, ob ein Objekt von dem Filter erfasst wird.
   */
  public function filter($object, &$items) {
    $value = (isset($object['attributes']['features']['furnished']['value'])) ?
        $object['attributes']['features']['furnished']['value'] : null;
    if (strtolower($value) == 'yes' || strtolower($value) == 'partial') {
      if (!isset($items['1']) || !is_array($items['1']))
        $items['1'] = array();
      $items['1'][] = $object['id'];
    }
  }

  /**
   * Name des Filters.
   */
  public function getName() {
    return 'furnished';
  }

  /**
   * Titel des Filters, abhängig von der Sprache.
   */
  public function getTitle(&$translations, $lang) {
    $title = (isset($translations['labels']['openestate.furnished'])) ?
        $translations['labels']['openestate.furnished'] : null;
    return is_string($title) ? $title : $this->getName();
  }

  /**
   * HTML-Code zur Auswahl des Filterkriteriums erzeugen.
   */
  public function getWidget($selectedValue, $lang, &$translations, &$setup) {
    $checked = ($selectedValue == '1') ? 'checked="checked"' : '';
    $widget = '<div class="nowrap">';
    $widget .= '<input id="filter_' . $this->getName() . '" name="' . IMMOTOOL_PARAM_INDEX_FILTER . '[' . $this->getName() . ']" value="1" type="checkbox" ' . $checked . '/>';
    $widget .= '<label for="filter_' . $this->getName() . '">' . $this->getTitle($translations, $lang) . '</label>';
    $widget .= '</div>';
    return $widget;
  }

}
