<?php
/*
 * Copyright 2009-2019 OpenEstate.org.
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

use OpenEstate\PhpExport\Utils;
use function OpenEstate\PhpExport\gettext as _;

/**
 * An embedded view for a video of veoh.com.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class VeohVideo extends AbstractLinkProvider
{
    /**
     * Provider name.
     *
     * @var string
     */
    const NAME = 'video@veoh.com';

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
