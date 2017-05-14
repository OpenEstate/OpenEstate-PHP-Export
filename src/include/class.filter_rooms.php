<?php
/*
 * PHP-Export scripts of OpenEstate-ImmoTool
 * Copyright (C) 2009-2017 OpenEstate.org
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

/**
 * Website-Export, Filter nach Zimmerzahl.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

require_once( IMMOTOOL_BASE_PATH . 'include/class.filter.php' );

class ImmoToolFilter_rooms extends ImmoToolFilter {

  /**
   * Anzahl der maximal zu filternden Zimmer
   * @var int Anzahl
   */
  var $roomCount = 6;

  /**
   * Überprüfung, ob ein Objekt von dem Filter erfasst wird.
   */
  function filter($object, &$items) {
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

  function getMax() {
    return (is_int($this->roomCount) && $this->roomCount > 0) ?
        $this->roomCount : 5;
  }

  /**
   * Name des Filters.
   */
  function getName() {
    return 'rooms';
  }

  /**
   * Titel des Filters, abhängig von der Sprache.
   */
  function getTitle(&$translations, $lang) {
    $title = (isset($translations['labels']['openestate.count_rooms'])) ?
        $translations['labels']['openestate.count_rooms'] : null;
    return is_string($title) ? $title : $this->getName();
  }

  /**
   * HTML-Code zur Auswahl des Filterkriteriums erzeugen.
   */
  function getWidget($selectedValue, $lang, &$translations, &$setup) {
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
