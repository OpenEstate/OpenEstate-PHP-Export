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
 * An embedded view for a video of vimeo.com.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class VimeoVideo extends AbstractLinkProvider
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
        $width = (\is_int($this->width)) ? $this->width : 533;
        $height = (\is_int($this->height)) ? $this->height : 300;

        $html = '<iframe class="openestate-video-object openestate-video-object-vimeo" '
            . 'src="' . \htmlspecialchars($frameUrl) . '" '
            . 'width="' . $width . '" height="' . $height . '"></iframe>';

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
