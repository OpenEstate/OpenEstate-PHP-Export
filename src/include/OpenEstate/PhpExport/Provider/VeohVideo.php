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
 * An embedded view for a video of veoh.com.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class VeohVideo extends AbstractLinkProvider
{
    /**
     * VeohVideo constructor.
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
            $this->width : 410;
        $height = (\is_int($this->height) && $this->height > 0) ?
            $this->height : 341;

        /** @noinspection SpellCheckingInspection */
        return '<div class="openestate-video openestate-video-veoh" style="width:' . $width . 'px;">'
            . "\n"

            // Flash
            . '<div class="openestate-video-container">'
            . '<object id="veohFlashPlayer"'
            . ' class="openestate-video-object"'
            . ' width="' . $width . '"'
            . ' height="' . $height . '">'
            . '<param name="allowFullscreen" value="true" />'
            . '<param name="allowScriptAccess" value="always" />'
            . '<param name="movie" value="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1343&amp;permalinkId=' . \htmlspecialchars($linkId) . '&amp;player=videodetailsembedded&amp;videoAutoPlay=0&amp;id=anonymous" />'
            . '<embed src="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1343&amp;permalinkId=' . \htmlspecialchars($linkId) . '&amp;player=videodetailsembedded&amp;videoAutoPlay=0&amp;id=anonymous"'
            . ' type="application/x-shockwave-flash"'
            . ' allowscriptaccess="always"'
            . ' allowfullscreen="true"'
            . ' width="' . $width . '"'
            . ' height="' . $height . '"'
            . ' id="veohFlashPlayerEmbed"'
            . ' name="veohFlashPlayerEmbed"/>'
            . '</object>'
            . '</div>'
            . "\n"

            // Provider-Link
            . '<div class="openestate-video-subtitle">'
            . '<a href="' . \htmlspecialchars($linkUrl) . '" target="_blank">' . \htmlspecialchars($linkTitle) . '</a>'
            . ' @ <a href="http://www.veoh.com/" target="_blank">veoh.com</a>'
            . '</div>'
            . "\n"
            . '</div>';
    }
}
