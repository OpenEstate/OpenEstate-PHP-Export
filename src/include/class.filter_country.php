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
 * Website-Export, Filter nach Land.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

require_once( IMMOTOOL_BASE_PATH . 'include/class.filter.php' );

class ImmoToolFilter_country extends ImmoToolFilter {

  var $countryNames = null;

  /**
   * Ein Filter-Array erzeugen.
   */
  function build() {
    $this->countryNames = array();
    return parent::build();
  }

  /**
   * Überprüfung, ob ein Objekt von dem Filter erfasst wird.
   */
  function filter($object, &$items) {
    $value = (isset($object['address']['country'])) ?
        $object['address']['country'] : null;
    if (!is_string($value))
      return;
    $value = trim($value);
    if (strlen($value) == 0)
      return;
    if (!isset($items[$value]) || !is_array($items[$value]))
      $items[$value] = array();
    $items[$value][] = $object['id'];

    // Landesname zum Landeskürzel ermitteln und zwischenspeichern
    if (!isset($this->countryNames[$value]))
      $this->countryNames[$value] = array();
    if (is_array($object['address']['country_name'])) {
      foreach ($object['address']['country_name'] as $lang => $countryName) {
        if (isset($this->countryNames[$value][$lang]))
          continue;
        $this->countryNames[$value][$lang] = $countryName;
      }
    }
  }

  /**
   * Name des Filters.
   */
  function getName() {
    return 'country';
  }

  /**
   * Titel des Filters, abhängig von der Sprache.
   */
  function getTitle(&$translations, $lang) {
    $title = (isset($translations['labels']['estate.country'])) ?
        $translations['labels']['estate.country'] : null;
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
      foreach ($options as $country) {
        $selected = ($selectedValue == $country) ? 'selected="selected"' : '';
        $countryName = (is_array($this->countryNames) && isset($this->countryNames[$country][$lang])) ?
            $this->countryNames[$country][$lang] : $country;
        $widget .= '<option value="' . $country . '" ' . $selected . '>' . $countryName . '</option>';
      }
      $widget .= '</select>';
    }
    return $widget;
  }

  /**
   * Filter-Array aus der Cache-Datei erzeugen.
   */
  function read($maxLifeTime = 0) {
    $res = parent::read($maxLifeTime);
    if ($res !== true)
      return false;

    // Landesnamen aus separater Cache-Datei ermitteln
    $file = $this->getFile() . '.names';
    if (!is_file($file))
      return false;

    // abgelaufene Cache-Datei ggf. löschen
    if ($maxLifeTime > 0 && !immotool_functions::check_file_age($file, $maxLifeTime)) {
      unlink($file);
      return false;
    }

    // Array mit Landesnamen aus separater Cache-Datei erzeugen
    $data = immotool_functions::read_file($file);
    if (!is_string($data))
      return false;
    $this->countryNames = unserialize($data);
    //echo '<pre>'; print_r( $this->countryNames ); echo '</pre>';
    //die( 'read ' . $file );
    return true;
  }

  /**
   * Filter-Array serialisieren.
   */
  function write() {
    parent::write();

    // Landesnamen als separate Cache-Datei speichern
    if (is_array($this->countryNames)) {
      $data = serialize($this->countryNames);
      $file = $this->getFile() . '.names';
      $fh = fopen($file, 'w') or die('can\'t write file: ' . $file);
      fwrite($fh, $data);
      fclose($fh);
    }
  }

}
