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

namespace OpenEstate\PhpExport\Html;

/**
 * A HTML element Stylesheets.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
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
     * @param string|null $id
     * id attribute
     *
     * @param string|null $class
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
     * @param string|null $media
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
     * @param string|null $media
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
