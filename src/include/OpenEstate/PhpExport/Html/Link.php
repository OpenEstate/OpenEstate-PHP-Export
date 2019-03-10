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

namespace OpenEstate\PhpExport\Html;

use function htmlspecialchars as html;

/**
 * A HTML element for link tags.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @see http://www.w3schools.com/tags/tag_link.asp Details about the "link" element.
 */
class Link extends AbstractHeadElement
{
    /**
     * Specifies the location of the linked document.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_link_href.asp Details about the "href" attribute.
     */
    public $href = null;

    /**
     * Specifies the language of the text in the linked document.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_link_hreflang.asp Details about the "hreflang" attribute.
     */
    public $hrefLang = null;

    /**
     * Specifies on what device the linked document will be displayed.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_link_media.asp Details about the "media" attribute.
     */
    public $media = null;

    /**
     * Specifies the relationship between the current document and the linked document.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_link_rel.asp Details about the "rel" attribute.
     */
    public $rel = null;

    /**
     * Specifies the relationship between the current document and the linked document.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_link_sizes.asp Details about the "sizes" attribute.
     */
    public $sizes = null;

    /**
     * Specifies the media type of the linked document.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_link_type.asp Details about the "type" attribute.
     */
    public $type = null;

    /**
     * Link constructor.
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
        return '<link ' . $this->generateAttributes() . '/>';
    }

    protected function getAttributes()
    {
        $attributes = parent::getAttributes();

        if (\is_string($this->rel))
            $attributes[] = 'rel="' . html($this->rel) . '"';

        if (\is_string($this->type))
            $attributes[] = 'type="' . html($this->type) . '"';

        if (\is_string($this->href))
            $attributes[] = 'href="' . html($this->href) . '"';

        if (\is_string($this->hrefLang))
            $attributes[] = 'hreflang="' . html($this->hrefLang) . '"';

        if (\is_string($this->media))
            $attributes[] = 'media="' . html($this->media) . '"';

        if (\is_string($this->sizes))
            $attributes[] = 'sizes="' . html($this->sizes) . '"';

        return $attributes;
    }

    /**
     * Create a link for an atom feed.
     *
     * @param string $id
     * id attribute
     *
     * @param string $href
     * URL of the atom feed
     *
     * @param string|null $title
     * title of the atom feed
     *
     * @return Link
     * created Link element
     */
    public static function newAtomFeed($id, $href, $title = null)
    {
        $link = new Link($id, null, $title);
        $link->rel = 'alternate';
        $link->type = 'application/atom+xml';
        $link->href = $href;
        return $link;
    }

    /**
     * Create a link for a rss feed.
     *
     * @param string $id
     * id attribute
     *
     * @param string $href
     * URL of the atom feed
     *
     * @param string|null $title
     * title of the atom feed
     *
     * @return Link
     * created Link element
     */
    public static function newRssFeed($id, $href, $title = null)
    {
        $link = new Link($id, null, $title);
        $link->rel = 'alternate';
        $link->type = 'application/rss+xml';
        $link->href = $href;
        return $link;
    }
}
