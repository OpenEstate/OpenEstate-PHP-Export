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
 * Website-Export, Filter nach Zimmerzahl.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

require_once( __DIR__ . '/class.filter.php' );

class ImmoToolFilter_rooms extends ImmoToolFilter {

  /**
   * Anzahl der maximal zu filternden Zimmer
   * @var int Anzahl
   */
  public $roomCount = 6;

  /**
   * Überprüfung, ob ein Objekt von dem Filter erfasst wird.
   */
  public function filter($object, &$items) {
    $value = isset($object['attributes']['measures']['count_rooms']['value']) ?
        $object['attributes']['measures']['count_rooms']['value'] : null;
    if (!is_numeric($value) || $value <= 0)
      return;
    $value = (int) floor($value);

    $key = '';
    $max = $this->getMax();
    if ($value >= $max)
      $key = $max . '+';
    else
      $key = strval($value);

    if (!isset($items[$key]) || !is_array($items[$key]))
      $items[$key] = array();
    $items[$key][] = $object['id'];
  }

  public function getMax() {
    return (is_int($this->roomCount) && $this->roomCount > 0) ?
        $this->roomCount : 5;
  }

  /**
   * Name des Filters.
   */
  public function getName() {
    return 'rooms';
  }

  /**
   * Titel des Filters, abhängig von der Sprache.
   */
  public function getTitle(&$translations, $lang) {
    $title = (isset($translations['labels']['openestate.count_rooms'])) ?
        $translations['labels']['openestate.count_rooms'] : null;
    return is_string($title) ? $title : $this->getName();
  }

  /**
   * HTML-Code zur Auswahl des Filterkriteriums erzeugen.
   */
  public function getWidget($selectedValue, $lang, &$translations, &$setup) {
    $widget = '';
    if (!$this->readOrRebuild($setup->CacheLifeTime) || !is_array($this->items))
      return $widget;

    // HTML-Code zur Auswahlbox erzeugen
    $options = array();
    $max = $this->getMax();
    for ($i = 1; $i < $max; $i++) {
      $options[] = strval($i);
    }
    $options[] = $max . '+';
    if (is_array($options) && count($options) > 0) {
      $by = $this->getTitle($translations, $lang);
      $widget .= '<select id="filter_' . $this->getName() . '" name="' . IMMOTOOL_PARAM_INDEX_FILTER . '[' . $this->getName() . ']">';
      $widget .= '<option value="">[ ' . $by . ' ]</option>';
      foreach ($options as $option) {
        $selected = ($selectedValue == $option) ? 'selected="selected"' : '';
        $widget .= '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
      }
      $widget .= '</select>';
    }
    return $widget;
  }

}
