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
 * Website-Export, Umkreiskarte, bereitgestellt von OpenStreetMap.org.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @link http://www.openstreetmap.org/
 */

require_once( __DIR__ . '/class.map.php' );

class ImmoToolMap_osm extends ImmoToolMap {

  /** Positions-Markierung auf der Karte darstellen. */
  public $showPositionMarker = true;

  /** Direkt-Link zur Großansicht der Karte darstellen. */
  public $showDirectLink = true;

  /**
   * Body-Daten der Umkreiskarte.
   * @return string Name
   */
  public function getBodyContent(&$object, &$translations, $lang) {
    $lat = $this->getLatitude($object);
    $lon = $this->getLongitude($object);
    if (!is_numeric($lat) || !is_numeric($lon))
      return null;
    //$lat += (rand(1,999)/rand(1,999));
    //$lon -= (rand(1,999)/rand(1,999));
    // darstellbaren Bereich ermitteln
    $diff = 0.05;
    $lat2 = round($lat, 2);
    $lon2 = round($lon, 2);
    $bbox = ($lon2 - $diff) . ',' . ($lat2 - $diff) . ',' . ($lon2 + $diff) . ',' . ($lat2 + $diff);

    // Links erzeugen
    $iframeSrc = 'https://www.openstreetmap.org/export/embed.html?bbox=' . $bbox . '&amp;layer=mapnik';
    $directLink = 'https://www.openstreetmap.org/?lat=' . $lat . '&amp;lon=' . $lon . '&amp;zoom=12&amp;layers=M';
    if ($this->showPositionMarker === true) {
      $iframeSrc .= '&amp;marker=' . $lat . ',' . $lon;
      $directLink .= '&amp;mlat=' . $lat . '&amp;mlon=' . $lon;
    }

    // Ausgabe erzeugen
    $output = '<iframe' .
        ' class="openstreetmap"' .
        ' width="640"' .
        ' height="480"' .
        ' frameborder="0"' .
        ' scrolling="no"' .
        ' marginheight="0"' .
        ' marginwidth="0"' .
        ' src="' . $iframeSrc . '">' .
        '</iframe>';

    if ($this->showDirectLink === true) {
      $output .= '<br/><small><a href="' . $directLink . '" target="_blank">' .
          $translations['labels']['estate.map.directLink'] .
          '</a></small>';
    }

    return '<div id="openestate_map">' . $output . '</div>';
  }

  /**
   * Header-Daten der Umkreiskarte.
   * @return string Name
   */
  public function getHeaderContent(&$object, &$translations, $lang) {
    return null;
  }

  /**
   * Name der Umkreiskarte.
   * @return string Name
   */
  public function getName() {
    return 'osm';
  }

}
