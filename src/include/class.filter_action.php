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
 * Website-Export, Filter nach Vermarktungsart.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

require_once( __DIR__ . '/class.filter.php' );

class ImmoToolFilter_action extends ImmoToolFilter {

  /**
   * Überprüfung, ob ein Objekt von dem Filter erfasst wird.
   */
  public function filter($object, &$items) {
    $value = (isset($object['action'])) ?
        $object['action'] : null;
    if (!is_string($value))
      return;
    if (!isset($items[$value]) || !is_array($items[$value]))
      $items[$value] = array();
    $items[$value][] = $object['id'];
  }

  /**
   * Name des Filters.
   */
  public function getName() {
    return 'action';
  }

  /**
   * Titel des Filters, abhängig von der Sprache.
   */
  public function getTitle(&$translations, $lang) {
    $title = (isset($translations['labels']['estate.action'])) ?
        $translations['labels']['estate.action'] : null;
    return is_string($title) ? $title : $this->getName();
  }

  /**
   * HTML-Code zur Auswahl des Filterkriteriums erzeugen.
   */
  public function getWidget($selectedValue, $lang, &$translations, &$setup) {
    $widget = '';
    if (!$this->readOrRebuild($setup->CacheLifeTime) || !is_array($this->items))
      return $widget;
    $sortedActions = array();
    foreach (array_keys($this->items) as $action) {
      $sortedActions[$action] = isset($translations['openestate']['actions'][$action]) ?
          $translations['openestate']['actions'][$action] : $action;
    }
    asort($sortedActions);
    if (is_array($sortedActions) && count($sortedActions) > 0) {
      $by = $this->getTitle($translations, $lang);
      $widget .= '<select id="filter_' . $this->getName() . '" name="' . IMMOTOOL_PARAM_INDEX_FILTER . '[' . $this->getName() . ']">';
      $widget .= '<option value="">[ ' . $by . ' ]</option>';
      foreach ($sortedActions as $action => $txt) {
        $selected = ($selectedValue == $action) ? 'selected="selected"' : '';
        $widget .= '<option value="' . $action . '" ' . $selected . '>' . $txt . '</option>';
      }
      $widget .= '</select>';
    }
    return $widget;
  }

}
