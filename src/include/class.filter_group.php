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
 * Website-Export, Filter nach Gruppen-Nummer.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

require_once( IMMOTOOL_BASE_PATH . 'include/class.filter.php' );

class ImmoToolFilter_group extends ImmoToolFilter {

  /**
   * Überprüfung, ob ein Objekt von dem Filter erfasst wird.
   */
  function filter($object, &$items) {
    $value = (isset($object['group_nr'])) ?
        $object['group_nr'] : null;
    if (!is_numeric($value))
      $value = 0;
    if (!isset($items[$value]) || !is_array($items[$value]))
      $items[$value] = array();
    $items[$value][] = $object['id'];
  }

  /**
   * Name des Filters.
   */
  function getName() {
    return 'group';
  }

  /**
   * Titel des Filters, abhängig von der Sprache.
   */
  function getTitle(&$translations, $lang) {
    $title = (isset($translations['labels']['estate.group'])) ?
        $translations['labels']['estate.group'] : null;
    return is_string($title) ? $title : $this->getName();
  }

  /**
   * HTML-Code zur Auswahl des Filterkriteriums erzeugen.
   */
  function getWidget($selectedValue, $lang, &$translations, &$setup) {
    $widget = '';
    if (!$this->readOrRebuild($setup->CacheLifeTime) || !is_array($this->items))
      return $widget;
    $selectedValue = (string) $selectedValue;
    $sortedGroups = array();
    foreach (array_keys($this->items) as $group) {
      $sortedGroups[] = $group;
    }
    sort($sortedGroups);
    if (is_array($sortedGroups) && count($sortedGroups) > 0) {
      $by = $this->getTitle($translations, $lang);
      $widget .= '<select id="filter_' . $this->getName() . '" name="' . IMMOTOOL_PARAM_INDEX_FILTER . '[' . $this->getName() . ']">';
      $widget .= '<option value="">[ ' . $by . ' ]</option>';
      foreach ($sortedGroups as $group) {
        $group = (string) $group;
        $selected = ($selectedValue == $group) ? 'selected="selected"' : '';
        $widget .= '<option value="' . $group . '" ' . $selected . '>' . $group . '</option>';
      }
      $widget .= '</select>';
    }
    return $widget;
  }

}
