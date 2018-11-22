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

use function htmlspecialchars as html;

/**
 * A HTML element for stylesheets.
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
     * URL of the stylesheet file to include.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_link_href.asp Details about the "href" attribute.
     */
    public $href = null;

    /**
     * Media type of the stylesheet.
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
     *
     * @param string|null $title
     * title attribute
     */
    function __construct($id = null, $class = null, $title = null)
    {
        parent::__construct($id, $class, $title);
    }

    public function generate()
    {
        if (\is_string($this->href)) {
            return '<link ' . $this->generateAttributes() . '/>';
        }

        $element = '<style ' . $this->generateAttributes() . '>';

        if (\is_string($this->content)) {
            $element .= "\n"
                //. "//<![CDATA[\n"
                . \trim($this->content) . "\n";
            //. "//]]>\n";
        }

        return $element . '</style>';
    }

    protected function getAttributes()
    {
        $attributes = parent::getAttributes();
        $attributes[] = 'type="text/css"';

        if (\is_string($this->href)) {
            $attributes[] = 'rel="stylesheet"';
            $attributes[] = 'href="' . html($this->href) . '"';
        }

        if (\is_string($this->media))
            $attributes[] = 'media="' . html($this->media) . '"';

        return $attributes;
    }

    /**
     * Create an embedded stylesheet.
     *
     * @param string $id
     * id attribute
     *
     * @param string $content
     * stylesheet code
     *
     * @param string|null $media
     * media types
     *
     * @return Stylesheet
     * created stylesheet element
     */
    public static function newContent($id, $content, $media = null)
    {
        $style = new Stylesheet($id);
        $style->content = $content;
        $style->media = $media;
        return $style;
    }

    /**
     * Create an external stylesheet.
     *
     * @param string $id
     * id attribute
     *
     * @param string $href
     * URL of the external stylesheet
     *
     * @param string|null $media
     * media types
     *
     * @return Stylesheet
     * created stylesheet element
     */
    public static function newLink($id, $href, $media = null)
    {
        $style = new Stylesheet($id);
        $style->href = $href;
        $style->media = $media;
        return $style;
    }

}
