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

namespace OpenEstate\PhpExport\Html;

/**
 * A HTML element Stylesheets.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.w3schools.com/tags/tag_style.asp Details about the "style" element.
 * @see http://www.w3schools.com/tags/tag_link.asp Details about the "link" element.
 */
class Stylesheet extends AbstractHeadElement
{
    /**
     * Stylesheet contents to include.
     *
     * @var string
     */
    public $content = null;

    /**
     * URL of the Stylesheet file to include.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_link_href.asp Details about the "href" attribute.
     */
    public $href = null;

    /**
     * Media type of the Stylesheet.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_style_media.asp Details about the "media" attribute in "style" elements.
     * @see http://www.w3schools.com/tags/att_link_media.asp Details about the "media" attribute in "link" elements.
     */
    public $media = null;

    /**
     * Stylesheet constructor.
     *
     * @param string $id
     * id attribute
     *
     * @param string $class
     * class attribute
     */
    function __construct($id = null, $class = null)
    {
        parent::__construct($id, $class);
    }

    public function generate()
    {
        if (\is_string($this->href)) {
            $element = '<link';

            if (\is_string($this->id))
                $element .= ' id="' . \htmlspecialchars($this->id) . '"';

            $element .= ' rel="stylesheet"';
            $element .= ' type="text/css"';
            $element .= ' href="' . \htmlspecialchars($this->href) . '"';

            if (\is_string($this->media))
                $element .= ' media="' . \htmlspecialchars($this->media) . '"';

            return $element . " />";
        }

        $element = '<style';

        if (\is_string($this->id))
            $element .= ' id="' . \htmlspecialchars($this->id) . '"';

        $element .= ' type="text/css"';

        if (\is_string($this->media))
            $element .= ' media="' . \htmlspecialchars($this->media) . '"';

        if (\is_string($this->content)) {
            return $element . ">\n"
                //. "//<![CDATA[\n"
                . \trim($this->content) . "\n"
                //. "//]]>\n";
                . "</style>";
        }

        return $element . '></style>';
    }

    /**
     * Create an embedded Stylesheet.
     *
     * @param string $id
     * id attribute
     *
     * @param string $content
     * Stylesheet code
     *
     * @param string $media
     * media types
     *
     * @return Stylesheet
     * created Stylesheet element
     */
    public static function newContent($id, $content, $media = null)
    {
        $style = new Stylesheet($id);
        $style->content = $content;
        $style->media = $media;
        return $style;
    }

    /**
     * Create an external Stylesheet.
     *
     * @param string $id
     * id attribute
     *
     * @param string $href
     * URL of the external Stylesheet
     *
     * @param string $media
     * media types
     *
     * @return Stylesheet
     * created Stylesheet element
     */
    public static function newLink($id, $href, $media = null)
    {
        $style = new Stylesheet($id);
        $style->href = $href;
        $style->media = $media;
        return $style;
    }

}
