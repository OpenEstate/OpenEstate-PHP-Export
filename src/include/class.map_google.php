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
 * Website-Export, Umkreiskarte, bereitgestellt von Google Maps.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @link http://maps.google.com/
 */

require_once( __DIR__ . '/class.map.php' );

class ImmoToolMap_google extends ImmoToolMap {

  /** Verwendete Zoom-Stufe */
  public $zoom = 13;

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
    // Links erzeugen
    $iframeSrc = 'https://maps.google.com/?ie=UTF8&amp;t=m&amp;ll=' . $lat . ',' . $lon . '&amp;z=' . $this->zoom . '&amp;output=embed';
    $directLink = 'https://maps.google.com/?ie=UTF8&amp;t=m&amp;ll=' . $lat . ',' . $lon . '&amp;z=' . $this->zoom;

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
      $output .= '<br /><small><a href="' . $directLink . '" target="_blank">' . $translations['labels']['estate.map.directLink'] . '</a></small>';
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
    return 'google';
  }

}
