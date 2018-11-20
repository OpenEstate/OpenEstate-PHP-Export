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
 * Website-Export, allgemeiner Filter.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

class ImmoToolFilter {

  public $items = array();

  /**
   * Ein Filter-Array erzeugen.
   */
  public function build() {
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
  public function filter($object, &$items) {

  }

  /**
   * Liefert das Filter-Array.
   */
  public function getItems($value) {
    return (is_array($this->items[$value])) ? $this->items[$value] : array();
  }

  /**
   * Name des Filters.
   */
  public function getName() {
    return null;
  }

  /**
   * Pfad zur Cache-Datei des Filters.
   */
  public function getFile() {
    return immotool_functions::get_path('cache/filter.' . $this->getName());
  }

  /**
   * Titel des Filters, abhängig von der Sprache.
   */
  public function getTitle(&$translations, $lang) {
    return null;
  }

  /**
   * HTML-Code zur Auswahl des Filterkriteriums erzeugen.
   */
  public function getWidget($selectedValue, $lang, &$translations, &$setup) {
    return null;
  }

  /**
   * Filter-Array aus der Cache-Datei erzeugen.
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
   * Filter-Array aus der Cache-Datei erzeugen.
   * Wenn keine Cache-Datei vorhanden ist, wird diese erzeugt.
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
   * Filter-Array serialisieren.
   */
  public function write() {
    $data = serialize($this->items);
    $file = $this->getFile();
    $fh = fopen($file, 'w') or die('can\'t write file: ' . $file);
    fwrite($fh, $data);
    fclose($fh);
  }

}
