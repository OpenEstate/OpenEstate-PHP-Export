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
 * An embedded view for a video of vimeo.com.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class VimeoVideo extends AbstractLinkProvider
{
    /**
     * Provider name.
     *
     * @var string
     */
    const NAME = 'video@vimeo.com';

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
     * VimeoVideo constructor.
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
        $frameUrl = 'https://player.vimeo.com/video/' . $linkId . '?title=0&byline=0&portrait=0';
        $width = (\is_int($this->width)) ? $this->width : 560;
        $height = (\is_int($this->height)) ? $this->height : 315;

        $html = '<iframe class="openestate-video-object openestate-video-object-vimeo" '
            . 'src="' . \htmlspecialchars($frameUrl) . '" '
            . 'width="' . $width . '" height="' . $height . '" '
            . 'frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

        if (Utils::isNotBlankString($linkUrl)) {
            if (Utils::isBlankString($linkTitle))
                $linkTitle = _('Show in a separate window.');

            $html .= '<div class="openestate-video-subtitle">'
                . '<a href="' . \htmlspecialchars($linkUrl) . '" target="_blank">' . \htmlspecialchars($linkTitle) . '</a>'
                . ' @ <a href="https://vimeo.com/" target="_blank">vimeo.com</a>'
                . '</div>';
        }

        return $html;
    }
}
