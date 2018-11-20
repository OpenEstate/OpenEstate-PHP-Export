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
 * Website-Export, allgemeine Sortierung.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

class ImmoToolOrder {

  public $items = array();

  /**
   * Ein Sortierungs-Array erzeugen.
   */
  public function build() {
    $this->items = array();
    $ids = immotool_functions::list_available_objects();
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
  public function getFile() {
    return immotool_functions::get_path('cache/order.' . $this->getName());
  }

  /**
   * Liefert das Sortierungs-Array.
   */
  public function getItems($lang) {
    return ($this->isLanguageSpecific()) ? $this->items[$lang] : $this->items;
  }

  /**
   * Name der Sortierung.
   */
  public function getName() {
    return null;
  }

  /**
   * Titel der Sortierung, abhängig von der Sprache.
   */
  public function getTitle(&$translations, $lang) {
    return null;
  }

  /**
   * Liefert true, wenn für jede Sprache eine separate Sortierung erfolgen soll.
   */
  public function isLanguageSpecific() {
    return false;
  }

  /**
   * Sortierungs-Array aus der Cache-Datei erzeugen.
   * @param int $maxLifeTime Maximale Lebenszeit einer Cache-Datei in Sekunden
   * @return array Sortierungs-Array
   */
  public function read($maxLifeTime = 0) {
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
  public function readOrRebuild($maxLifeTime = 0) {
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
  public function sort_field(&$object, $lang) {
    return null;
  }

  /**
   * Liefert das Sortierungs-Flag
   * siehe http://www.php.net/manual/en/function.sort.php
   */
  public function sort_flag() {
    return SORT_STRING;
  }

  /**
   * Sortierungs-Array serialisieren.
   */
  public function write() {
    $data = serialize($this->items);
    $file = $this->getFile();
    $fh = fopen($file, 'w') or die('can\'t write file: ' . $file);
    fwrite($fh, $data);
    fclose($fh);
  }

}
