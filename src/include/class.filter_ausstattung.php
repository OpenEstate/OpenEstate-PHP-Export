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
 * Website-Export, Filter nach Ausstattungsart.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2010, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

require_once( IMMOTOOL_BASE_PATH . 'include/class.filter.php' );

class ImmoToolFilter_ausstattung extends ImmoToolFilter {

  /**
   * Überprüfung, ob ein Objekt von dem Filter erfasst wird.
   */
  function filter($object, &$items) {
    $value = isset($object['attributes']['ausstattung']['ausstattung_art']['value']) ?
        $object['attributes']['ausstattung']['ausstattung_art']['value'] : null;
    if (!is_string($value))
      return;
    $value = strtolower($value);
    if (!isset($items[$value]) || !is_array($items[$value]))
      $items[$value] = array();
    $items[$value][] = $object['id'];
  }

  /**
   * Name des Filters.
   */
  function getName() {
    return 'ausstattung';
  }

  /**
   * Titel des Filters, abhängig von der Sprache.
   */
  function getTitle(&$translations, $lang) {
    $title = (isset($translations['labels']['openestate.ausstattung'])) ?
        $translations['labels']['openestate.ausstattung'] : null;
    return is_string($title) ? $title : $this->getName();
  }

  /**
   * HTML-Code zur Auswahl des Filterkriteriums erzeugen.
   */
  function getWidget($selectedValue, $lang, &$translations, &$setup) {
    $widget = '';
    if (!$this->readOrRebuild($setup->CacheLifeTime) || !is_array($this->items))
      return $widget;

    // Optionen in der Auswahlbox ermitteln
    $options = array('einfach', 'normal', 'gehoben', 'luxus');
    $values = array();
    foreach ($options as $o) {
      $txt = (isset($translations['labels']['openestate.ausstattung.' . $o])) ?
          $translations['labels']['openestate.ausstattung.' . $o] : null;
      $values[$o] = is_string($txt) ? $txt : $o;
    }

    // HTML-Code zur Auswahlbox erzeugen
    if (is_array($values) && count($values) > 0) {
      $by = $this->getTitle($translations, $lang);
      $widget .= '<select id="filter_' . $this->getName() . '" name="' . IMMOTOOL_PARAM_INDEX_FILTER . '[' . $this->getName() . ']">';
      $widget .= '<option value="">[ ' . $by . ' ]</option>';
      foreach ($values as $value => $txt) {
        $selected = ($selectedValue == $value) ? 'selected="selected"' : '';
        $widget .= '<option value="' . $value . '" ' . $selected . '>' . $txt . '</option>';
      }
      $widget .= '</select>';
    }
    return $widget;
  }

}
