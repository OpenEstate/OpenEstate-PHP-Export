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
 * Website-Export, allgemeiner Filter.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

class ImmoToolFilter {

  var $items = array();

  /**
   * Ein Filter-Array erzeugen.
   */
  function build() {
    $this->items = array();
    $ids = immotool_functions::list_available_objects();
    if (!is_array($ids))
      return false;
    foreach ($ids as $id) {
      $object = immotool_functions::get_object($id);
      if (!is_array($object))
        continue;
      $this->filter($object, $this->items);
    }
    return true;
  }

  /**
   * Überprüfung, ob ein Objekt von dem Filter erfasst wird.
   */
  function filter($object, &$items) {

  }

  /**
   * Liefert das Filter-Array.
   */
  function getItems($value) {
    return (is_array($this->items[$value])) ? $this->items[$value] : array();
  }

  /**
   * Name des Filters.
   */
  function getName() {
    return null;
  }

  /**
   * Pfad zur Cache-Datei des Filters.
   */
  function getFile() {
    return IMMOTOOL_BASE_PATH . 'cache/filter.' . $this->getName();
  }

  /**
   * Titel des Filters, abhängig von der Sprache.
   */
  function getTitle(&$translations, $lang) {
    return null;
  }

  /**
   * HTML-Code zur Auswahl des Filterkriteriums erzeugen.
   */
  function getWidget($selectedValue, $lang, &$translations, &$setup) {
    return null;
  }

  /**
   * Filter-Array aus der Cache-Datei erzeugen.
   */
  function read($maxLifeTime = 0) {
    $file = $this->getFile();
    if (!is_file($file))
      return false;

    // abgelaufene Cache-Datei ggf. löschen
    if ($maxLifeTime > 0 && !immotool_functions::check_file_age($file, $maxLifeTime)) {
      unlink($file);
      return false;
    }

    // Array aus Cache-Datei erzeugen
    $data = immotool_functions::read_file($file);
    if (!is_string($data))
      return false;
    $this->items = unserialize($data);
    //echo '<pre>'; print_r( $this->items ); echo '</pre>';
    //die( 'read ' . $file );
    return true;
  }

  /**
   * Filter-Array aus der Cache-Datei erzeugen.
   * Wenn keine Cache-Datei vorhanden ist, wird diese erzeugt.
   */
  function readOrRebuild($maxLifeTime = 0) {
    if ($this->read($maxLifeTime))
      return true;
    if (!$this->build())
      return false;
    $this->write();
    return true;
  }

  /**
   * Filter-Array serialisieren.
   */
  function write() {
    $data = serialize($this->items);
    $file = $this->getFile();
    $fh = fopen($file, 'w') or die('can\'t write file: ' . $file);
    fwrite($fh, $data);
    fclose($fh);
  }

}
