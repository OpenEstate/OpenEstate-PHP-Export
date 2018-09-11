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

use function OpenEstate\PhpExport\gettext as _;

/**
 * A map provided by Google Maps.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class GoogleMap extends AbstractMapProvider
{
    /**
     * Initial zoom level for the map.
     *
     * @var int
     */
    public $zoom;

    /**
     * Enable direct link below the map.
     *
     * @var bool
     */
    public $showDirectLink;

    /**
     * GoogleMap constructor.
     *
     * @param int $zoom
     * initial zoom level for the map
     *
     * @param bool $showDirectLink
     * enable direct link below the map
     */
    function __construct($zoom = 13, $showDirectLink = true)
    {
        parent::__construct();
        $this->zoom = $zoom;
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

        // Create links.
        $frameSrc = 'https://maps.google.com/?ie=UTF8&t=m&ll=' . $lat . ',' . $lon . '&z=' . $this->zoom . '&output=embed';
        $directLink = 'https://maps.google.com/?ie=UTF8&t=m&ll=' . $lat . ',' . $lon . '&z=' . $this->zoom;

        // Write output.
        $output = '<iframe' .
            ' class="openestate-map-object openestate-map-object-google"' .
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
        return 'google';
    }
}
