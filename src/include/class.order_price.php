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
 * Website-Export, Sortierung nach Preis.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

require_once( IMMOTOOL_BASE_PATH . 'include/class.order.php' );

class ImmoToolOrder_price extends ImmoToolOrder {

  /**
   * Name des Filters.
   */
  function getName() {
    return 'price';
  }

  /**
   * Titel der Sortierung, abhängig von der Sprache.
   */
  function getTitle(&$translations, $lang) {
    $title = (isset($translations['labels']['estate.price'])) ?
        $translations['labels']['estate.price'] : null;
    return is_string($title) ? $title : $this->getName();
  }

  /**
   * Liefert das Sortierungsfeld eines Objektes.
   */
  function sort_field(&$object, $lang) {
    // Preisangaben verstecken
    if ($object['hidden_price'] === true)
      return null;

    // Kauf
    if ($object['action'] == 'purchase')
      return (isset($object['attributes']['prices']['purchase_price']['value'])) ?
          $object['attributes']['prices']['purchase_price']['value'] : null;

    // Miete
    if ($object['action'] == 'rent')
      return (isset($object['attributes']['prices']['rent_excluding_service_charges']['value'])) ?
          $object['attributes']['prices']['rent_excluding_service_charges']['value'] : null;

    // Miete auf Zeit
    if ($object['action'] == 'short_term_rent')
      return (isset($object['attributes']['prices']['rent_flat_rate']['value'])) ?
          $object['attributes']['prices']['rent_flat_rate']['value'] : null;

    // Pacht
    if ($object['action'] == 'lease' || $object['action'] == 'emphyteusis')
      return (isset($object['attributes']['prices']['lease']['value'])) ?
          $object['attributes']['prices']['lease']['value'] : null;

    return null;
  }

  /**
   * Liefert das Sortierungs-Flag
   * siehe http://www.php.net/manual/en/function.sort.php
   */
  function sort_flag() {
    return SORT_NUMERIC;
  }

}
