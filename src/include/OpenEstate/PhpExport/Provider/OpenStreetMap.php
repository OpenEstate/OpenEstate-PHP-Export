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

namespace OpenEstate\PhpExport\Provider;

use function OpenEstate\PhpExport\gettext as _;

/**
 * A map provided by OpenStreetMaps.org.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class OpenStreetMap extends AbstractMapProvider
{
    /**
     * Enable position marker on the map.
     *
     * @var bool
     */
    public $showPositionMarker;

    /**
     * Enable direct link below the map.
     *
     * @var bool
     */
    public $showDirectLink;

    /**
     * OpenStreetMap constructor.
     *
     * @param bool $showPositionMarker
     * enable position marker on the map
     *
     * @param bool $showDirectLink
     * enable direct link below the map
     */
    function __construct($showPositionMarker = true, $showDirectLink = true)
    {
        parent::__construct();
        $this->showPositionMarker = $showPositionMarker;
        $this->showDirectLink = $showDirectLink;
    }

    public function getBody(array &$object)
    {
        $lat = $this->getLatitude($object);
        $lon = $this->getLongitude($object);
        if (!\is_numeric($lat) || !\is_numeric($lon))
            return null;

        //$lat += (\rand(1,999) / \rand(1,999));
        //$lon -= (\rand(1,999) / \rand(1,999));

        // Calculate bounding box.
        $diff = 0.05;
        $lat2 = \round($lat, 2);
        $lon2 = \round($lon, 2);
        $bbox = ($lon2 - $diff) . ',' . ($lat2 - $diff) . ',' . ($lon2 + $diff) . ',' . ($lat2 + $diff);

        // Create links.
        /** @noinspection SpellCheckingInspection */
        $frameSrc = 'https://www.openstreetmap.org/export/embed.html?bbox=' . $bbox . '&layer=mapnik';
        $directLink = 'https://www.openstreetmap.org/?lat=' . $lat . '&lon=' . $lon . '&zoom=12&layers=M';
        if ($this->showPositionMarker === true) {
            $frameSrc .= '&marker=' . $lat . ',' . $lon;
            /** @noinspection SpellCheckingInspection */
            $directLink .= '&mlat=' . $lat . '&mlon=' . $lon;
        }

        // Write output.
        $output = '<iframe' .
            ' class="openestate-map-object openestate-map-object-osm"' .
            ' src="' . \htmlspecialchars($frameSrc) . '">' .
            '</iframe>';

        if ($this->showDirectLink === true) {
            $output .= '<div class="openestate-map-subtitle">'
                . '<a href="' . \htmlspecialchars($directLink) . '" target="_blank">'
                . _('Show in a separate window.')
                . '</a></div>';
        }

        return $output;
    }

    public function getName()
    {
        return 'osm';
    }
}
