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
 * Website-Export, Filter nach Ort.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

require_once( IMMOTOOL_BASE_PATH . 'include/class.filter.php' );

class ImmoToolFilter_city extends ImmoToolFilter {

  /**
   * Überprüfung, ob ein Objekt von dem Filter erfasst wird.
   */
  function filter($object, &$items) {
    $value = (isset($object['address']['city'])) ?
        $object['address']['city'] : null;
    if (!is_string($value))
      return;
    $value = trim($value);
    if (strlen($value) == 0)
      return;
    if (!isset($items[$value]) || !is_array($items[$value]))
      $items[$value] = array();
    $items[$value][] = $object['id'];
  }

  /**
   * Name des Filters.
   */
  function getName() {
    return 'city';
  }

  /**
   * Titel des Filters, abhängig von der Sprache.
   */
  function getTitle(&$translations, $lang) {
    $title = (isset($translations['labels']['estate.city'])) ?
        $translations['labels']['estate.city'] : null;
    return is_string($title) ? $title : $this->getName();
  }

  /**
   * HTML-Code zur Auswahl des Filterkriteriums erzeugen.
   */
  function getWidget($selectedValue, $lang, &$translations, &$setup) {
    if (!$this->readOrRebuild($setup->CacheLifeTime))
      return null;
    $widget = '';
    $options = array_keys($this->items);
    asort($options);
    if (is_array($options) && count($options) > 0) {
      $by = $this->getTitle($translations, $lang);
      $widget .= '<select id="filter_' . $this->getName() . '" name="' . IMMOTOOL_PARAM_INDEX_FILTER . '[' . $this->getName() . ']">';
      $widget .= '<option value="">[ ' . $by . ' ]</option>';
      foreach ($options as $city) {
        $selected = ($selectedValue == $city) ? 'selected="selected"' : '';
        $widget .= '<option value="' . $city . '" ' . $selected . '>' . $city . '</option>';
      }
      $widget .= '</select>';
    }
    return $widget;
  }

}
