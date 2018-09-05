<?php
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
 * An embedded view for a video of youtube.com.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class YouTubeVideo extends AbstractLinkProvider
{
    /**
     * YouTubeVideo constructor.
     *
     * @param int $width
     * width of the embedded element
     *
     * @param int $height
     * height of the embedded element
     */
    function __construct($width = 0, $height = 0)
    {
        parent::__construct($width, $height);
    }

    public function getBody($linkId, $linkTitle, $linkUrl)
    {
        $width = (\is_int($this->width) && $this->width > 0) ?
            $this->width : 560;
        $height = (\is_int($this->height) && $this->height > 0) ?
            $this->height : 315;

        return '<div class="openestate-video openestate-video-youtube" style="width:' . $width . 'px;">'
            . "\n"

            // IFrame
            . '<div class="openestate-video-container">'
            . '<iframe src="https://www.youtube-nocookie.com/embed/' . \htmlspecialchars($linkId) . '?rel=0"'
            . ' class="openestate-video-frame"'
            . ' width="' . $width . '"'
            . ' height="' . $height . '">'
            . '</iframe>'
            . '</div>'
            . "\n"

            // Provider-Link
            . '<div class="openestate-video-subtitle">'
            . '<a href="' . \htmlspecialchars($linkUrl) . '" target="_blank">' . \htmlspecialchars($linkTitle) . '</a>'
            . ' @ <a href="https://www.youtube.com/" target="_blank">youtube.com</a>'
            . '</div>'
            . "\n"
            . '</div>';
    }
}
