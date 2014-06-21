<?php
/*
 * PHP-Export scripts of OpenEstate-ImmoTool
 * Copyright (C) 2009-2014 OpenEstate.org
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
 * @copyright 2009-2010, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

require_once( IMMOTOOL_BASE_PATH . 'include/class.filter.php' );

class ImmoToolFilter_rooms extends ImmoToolFilter {

  /**
   * Überprüfung, ob ein Objekt von dem Filter erfasst wird.
   */
  function filter($object, &$items) {
    $value = isset($object['attributes']['flaechen']['anz_zimmer']['value']) ?
        $object['attributes']['flaechen']['anz_zimmer']['value'] : null;
    if (!is_numeric($value))
      return;

    $key = '';
    if ($value <= 1)
      $key = '1';
    else if ($value <= 2)
      $key = '2';
    else if ($value <= 3)
      $key = '3';
    else if ($value <= 4)
      $key = '4';
    else
      $key = '5+';

    if (!isset($items[$key]) || !is_array($items[$key]))
      $items[$key] = array();
    $items[$key][] = $object['id'];
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
    $title = (isset($translations['labels']['openestate.zimmer'])) ?
        $translations['labels']['openestate.zimmer'] : null;
    return is_string($title) ? $title : $this->getName();
  }

  /**
   * HTML-Code zur Auswahl des Filterkriteriums erzeugen.
   */
  function getWidget($selectedValue, $lang, &$translations, &$setup) {
    $widget = '';
    if (!$this->readOrRebuild() || !is_array($this->items))
      return $widget;

    // HTML-Code zur Auswahlbox erzeugen
    $options = array('1', '2', '3', '4', '5+');
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
