<?php /** @noinspection HtmlUnknownTarget */
/** @noinspection HtmlUnknownTarget */

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

namespace OpenEstate\PhpExport\Provider;

/**
 * A map provided by OpenStreetMaps.org.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
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
     *
     * @param int $width
     * width of the embedded element
     *
     * @param int $height
     * height of the embedded element
     */
    function __construct($showPositionMarker = true, $showDirectLink = true, $width = 0, $height = 0)
    {
        parent::__construct($width, $height);
        $this->showPositionMarker = $showPositionMarker;
        $this->showDirectLink = $showDirectLink;
    }

    public function getBody(&$object, &$translations, $lang)
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

        $width = (\is_int($this->width) && $this->width > 0) ?
            $this->width : 560;
        $height = (\is_int($this->height) && $this->height > 0) ?
            $this->height : 315;

        // Create links.
        /** @noinspection SpellCheckingInspection */
        $frameSrc = 'https://www.openstreetmap.org/export/embed.html?bbox=' . $bbox . '&amp;layer=mapnik';
        $directLink = 'https://www.openstreetmap.org/?lat=' . $lat . '&amp;lon=' . $lon . '&amp;zoom=12&amp;layers=M';
        if ($this->showPositionMarker === true) {
            $frameSrc .= '&amp;marker=' . $lat . ',' . $lon;
            /** @noinspection SpellCheckingInspection */
            $directLink .= '&amp;mlat=' . $lat . '&amp;mlon=' . $lon;
        }

        // Write output.
        $output = '<iframe' .
            ' class="openestate-map-frame"' .
            ' width="' . $width . '"' .
            ' height="' . $height . '"' .
            ' src="' . $frameSrc . '">' .
            '</iframe>';

        if ($this->showDirectLink === true) {
            $output .= '<div class="openestate-map-subtitle">'
                . '<a href="' . $directLink . '" target="_blank">'
                . \htmlspecialchars($translations['labels']['estate.map.directLink'])
                . '</a></div>';
        }

        return '<div class="openestate-map openestate-map-osm">' . $output . '</div>';
    }

}
