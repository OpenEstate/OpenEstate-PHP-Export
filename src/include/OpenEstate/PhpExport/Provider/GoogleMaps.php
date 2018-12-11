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
 * A map provided by Google Maps.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class GoogleMaps extends AbstractMapProvider
{
    /**
     * Provider name.
     *
     * @var string
     */
    const NAME = 'GoogleMaps';

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
     * GoogleMaps constructor.
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
