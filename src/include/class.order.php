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
 * Website-Export, allgemeine Sortierung.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

class ImmoToolOrder {

  var $items = array();

  /**
   * Ein Sortierungs-Array erzeugen.
   */
  function build() {
    $this->items = array();
    $ids = immotool_functions::list_available_objects();
    //echo '<pre>'; print_r($ids); echo '</pre>';
    if (!is_array($ids))
      return false;

    $values = array();
    $unordered = array();
    foreach ($ids as $id) {
      $object = immotool_functions::get_object($id);
      //echo '<pre>'; print_r($object); echo '</pre>';
      if (!is_array($object))
        continue;

      // Werte unabhängig zur Sprache ermitteln
      if (!$this->isLanguageSpecific()) {
        $field = $this->sort_field($object, null);
        if ($field == null) {
          $unordered[] = $object['id'];
          continue;
        }
        $values[$object['id']] = $field;
      }

      // Werte abhängig zur Sprache ermitteln
      else {
        foreach (immotool_functions::get_language_codes() as $lang) {
          $field = $this->sort_field($object, $lang);
          if ($field == null) {
            if (!is_array($unordered[$lang]))
              $unordered[$lang] = array();
            $unordered[$lang][] = $object['id'];
            continue;
          }
          if (!is_array($values[$lang]))
            $values[$lang] = array();
          $values[$lang][$object['id']] = $field;
        }
      }
    }

    // Sortierung unabhängig zur Sprache
    if (!$this->isLanguageSpecific()) {
      asort($values, $this->sort_flag());
      //echo '<pre>'; print_r( $values ); echo '</pre>';
      if (is_array($unordered) && count($unordered) > 0)
        $this->items = array_merge(array_keys($values), $unordered);
      else
        $this->items = array_keys($values);
    }

    // Sortierung abhängig zur Sprache
    else {
      foreach (array_keys($values) as $lang) {
        asort($values[$lang], $this->sort_flag());
        if (is_array($unordered[$lang]) && count($unordered[$lang]) > 0)
          $this->items[$lang] = array_merge(array_keys($values[$lang]), $unordered[$lang]);
        else
          $this->items[$lang] = array_keys($values[$lang]);
      }
    }
    return true;
  }

  /**
   * Pfad zur Cache-Datei der Sortierung.
   */
  function getFile() {
    return IMMOTOOL_BASE_PATH . 'cache/order.' . $this->getName();
  }

  /**
   * Liefert das Sortierungs-Array.
   */
  function getItems($lang) {
    return ($this->isLanguageSpecific()) ? $this->items[$lang] : $this->items;
  }

  /**
   * Name der Sortierung.
   */
  function getName() {
    return null;
  }

  /**
   * Titel der Sortierung, abhängig von der Sprache.
   */
  function getTitle(&$translations, $lang) {
    return null;
  }

  /**
   * Liefert true, wenn für jede Sprache eine separate Sortierung erfolgen soll.
   */
  function isLanguageSpecific() {
    return false;
  }

  /**
   * Sortierungs-Array aus der Cache-Datei erzeugen.
   * @param int $maxLifeTime Maximale Lebenszeit einer Cache-Datei in Sekunden
   * @return array Sortierungs-Array
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
   * Sortierungs-Array aus der Cache-Datei erzeugen.
   * Wenn keine Cache-Datei vorhanden ist, wird diese erzeugt.
   * @param int $maxLifeTime Maximale Lebenszeit einer Cache-Datei in Sekunden
   * @return array Sortierungs-Array
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
   * Liefert das Sortierungsfeld eines Objektes.
   */
  function sort_field(&$object, $lang) {
    return null;
  }

  /**
   * Liefert das Sortierungs-Flag
   * siehe http://www.php.net/manual/en/function.sort.php
   */
  function sort_flag() {
    return SORT_STRING;
  }

  /**
   * Sortierungs-Array serialisieren.
   */
  function write() {
    $data = serialize($this->items);
    $file = $this->getFile();
    $fh = fopen($file, 'w') or die('can\'t write file: ' . $file);
    fwrite($fh, $data);
    fclose($fh);
  }

}
