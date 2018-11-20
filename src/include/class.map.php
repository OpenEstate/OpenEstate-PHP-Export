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
 * Website-Export, allgemeine Umkreiskarte.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

class ImmoToolMap {

  /**
   * Hilfsfunktion zur Überprüfung, ob Geo-Koordinaten bei einer Immobilie hinterlegt wurden.
   * @return boolean
   */
  public function canShowForObject(&$object) {
    return isset($object['address']['latitude']) && isset($object['address']['longitude']) && is_numeric($object['address']['latitude']) && is_numeric($object['address']['longitude']);
  }

  /**
   * Body-Daten der Umkreiskarte.
   * @return string Name
   */
  public function getBodyContent(&$object, &$translations, $lang) {
    return null;
  }

  /**
   * Header-Daten der Umkreiskarte.
   * @return string Name
   */
  public function getHeaderContent(&$object, &$translations, $lang) {
    return null;
  }

  /**
   * Hilfsfunktion zur Ermittlung des Breitengrades einer Immobilie.
   * @param array $object Immobilie
   * @return float Wert des Breitengrades oder null, wenn nicht angegeben
   */
  public function getLatitude(&$object) {
    return (isset($object['address']['latitude'])) ? $object['address']['latitude'] : null;
  }

  /**
   * Hilfsfunktion zur Ermittlung des Längengrades einer Immobilie.
   * @param array $object Immobilie
   * @return float Wert des Längengrades oder null, wenn nicht angegeben
   */
  public function getLongitude(&$object) {
    return (isset($object['address']['longitude'])) ? $object['address']['longitude'] : null;
  }

  /**
   * Name der Umkreiskarte.
   * @return string Name
   */
  public function getName() {
    return null;
  }

}
