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
 * Website-Export, Sortierung nach Zeitpunkt der letzten Änderung.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2013, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

require_once( IMMOTOOL_BASE_PATH . 'include/class.order.php' );

class ImmoToolOrder_lastmod extends ImmoToolOrder {

  /**
   * Name des Filters.
   */
  function getName() {
    return 'lastmod';
  }

  /**
   * Titel der Sortierung, abhängig von der Sprache.
   */
  function getTitle(&$translations, $lang) {
    $title = (isset($translations['labels']['lastModification'])) ?
        $translations['labels']['lastModification'] : null;
    return is_string($title) ? $title : $this->getName();
  }

  /**
   * Liefert das Sortierungsfeld eines Objektes.
   */
  function sort_field(&$object, $lang) {
    $id = (isset($object['id'])) ? $object['id'] : null;
    if (!is_string($id))
      return null;
    return immotool_functions::get_object_stamp($id);
  }

  /**
   * Liefert das Sortierungs-Flag
   * siehe http://www.php.net/manual/en/function.sort.php
   */
  function sort_flag() {
    return SORT_NUMERIC;
  }

}