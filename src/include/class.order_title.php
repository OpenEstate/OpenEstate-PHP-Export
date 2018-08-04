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
 * Website-Export, Sortierung nach Titel
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

require_once( __DIR__ . '/class.order.php' );

class ImmoToolOrder_title extends ImmoToolOrder {

  /**
   * Name des Filters.
   */
  public function getName() {
    return 'title';
  }

  /**
   * Titel der Sortierung, abhängig von der Sprache.
   */
  public function getTitle(&$translations, $lang) {
    $title = (isset($translations['labels']['estate.title'])) ?
        $translations['labels']['estate.title'] : null;
    return is_string($title) ? $title : $this->getName();
  }

  /**
   * Liefert true, wenn für jede Sprache eine separate Sortierung erfolgen soll.
   */
  public function isLanguageSpecific() {
    return true;
  }

  /**
   * Liefert das Sortierungsfeld eines Objektes.
   */
  public function sort_field(&$object, $lang) {
    $val = (isset($object['title'][$lang])) ?
        $object['title'][$lang] : null;
    return is_string($val) ? $val : '';
  }

  /**
   * Liefert das Sortierungs-Flag
   * siehe http://www.php.net/manual/en/function.sort.php
   */
  public function sort_flag() {
    return SORT_STRING;
  }

}
