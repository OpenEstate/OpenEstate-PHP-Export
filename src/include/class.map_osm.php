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
 * Website-Export, Umkreiskarte, bereitgestellt von OpenStreetMap.org.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @link http://www.openstreetmap.org/
 */

if (!defined('IN_WEBSITE'))
  exit;

require_once( IMMOTOOL_BASE_PATH . 'include/class.map.php' );

class ImmoToolMap_osm extends ImmoToolMap {

  /** Positions-Markierung auf der Karte darstellen. */
  var $showPositionMarker = true;

  /** Direkt-Link zur Großansicht der Karte darstellen. */
  var $showDirectLink = true;

  /**
   * Body-Daten der Umkreiskarte.
   * @return string Name
   */
  function getBodyContent(&$object, &$translations, $lang) {
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
  function getHeaderContent(&$object, &$translations, $lang) {
    return null;
  }

  /**
   * Name der Umkreiskarte.
   * @return string Name
   */
  function getName() {
    return 'osm';
  }

}
