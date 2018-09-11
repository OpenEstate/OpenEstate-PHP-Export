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

use OpenEstate\PhpExport\Utils;
use function OpenEstate\PhpExport\gettext as _;

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
     * Initial width of the embedded video in pixels.
     *
     * @var int
     */
    private $width = 0;

    /**
     * Initial height of the embedded video in pixels.
     *
     * @var int
     */
    private $height = 0;

    /**
     * VeohVideo constructor.
     *
     * @param int|null $width
     * initial width of the embedded video in pixels
     *
     * @param int|null $height
     * initial height of the embedded video in pixels
     */
    function __construct($width = null, $height = null)
    {
        parent::__construct();
        $this->width = $width;
        $this->height = $height;
    }

    public function getBody($linkId, $linkUrl = null, $linkTitle = null)
    {
        $width = (\is_int($this->width)) ? $this->width : 410;
        $height = (\is_int($this->height)) ? $this->height : 341;

        /** @noinspection SpellCheckingInspection */
        $html = '<object id="veohFlashPlayer" class="openestate-video-object openestate-video-object-veoh" width="' . $width . '" height="' . $height . '">'
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
            . '</object>';

        if (Utils::isNotBlankString($linkUrl)) {
            if (Utils::isBlankString($linkTitle))
                $linkTitle = _('Show in a separate window.');

            $html .= '<div class="openestate-video-subtitle">'
                . '<a href="' . \htmlspecialchars($linkUrl) . '" target="_blank">' . \htmlspecialchars($linkTitle) . '</a>'
                . ' @ <a href="https://www.veoh.com/" target="_blank">veoh.com</a>'
                . '</div>';
        }

        return $html;
    }
}
