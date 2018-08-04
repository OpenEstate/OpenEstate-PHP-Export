<?php
/*
 * PHP-Export scripts of OpenEstate-ImmoTool
 * Copyright (C) 2009-2018 OpenEstate.org
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
 * Website-Export, Filter nach möblierten Inseraten.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

require_once( IMMOTOOL_BASE_PATH . 'include/class.filter.php' );

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
