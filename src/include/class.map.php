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
 * Website-Export, allgemeine Umkreiskarte.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

class ImmoToolMap {

  /**
   * Hilfsfunktion zur Überprüfung, ob Geo-Koordinaten bei einer Immobilie hinterlegt wurden.
   * @return boolean
   */
  function canShowForObject(&$object) {
    return isset($object['address']['latitude']) && isset($object['address']['longitude']) && is_numeric($object['address']['latitude']) && is_numeric($object['address']['longitude']);
  }

  /**
   * Body-Daten der Umkreiskarte.
   * @return string Name
   */
  function getBodyContent(&$object, &$translations, $lang) {
    return null;
  }

  /**
   * Header-Daten der Umkreiskarte.
   * @return string Name
   */
  function getHeaderContent(&$object, &$translations, $lang) {
    return null;
  }

  /**
   * Hilfsfunktion zur Ermittlung des Breitengrades einer Immobilie.
   * @param array $object Immobilie
   * @return float Wert des Breitengrades oder null, wenn nicht angegeben
   */
  function getLatitude(&$object) {
    return (isset($object['address']['latitude'])) ? $object['address']['latitude'] : null;
  }

  /**
   * Hilfsfunktion zur Ermittlung des Längengrades einer Immobilie.
   * @param array $object Immobilie
   * @return float Wert des Längengrades oder null, wenn nicht angegeben
   */
  function getLongitude(&$object) {
    return (isset($object['address']['longitude'])) ? $object['address']['longitude'] : null;
  }

  /**
   * Name der Umkreiskarte.
   * @return string Name
   */
  function getName() {
    return null;
  }

}
