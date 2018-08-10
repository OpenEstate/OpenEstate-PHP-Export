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
 * A HTML element for meta tags.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.w3schools.com/tags/tag_meta.asp Details about the "meta" element.
 */
class Meta extends AbstractHeadElement
{
    /**
     * Name attribute.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_meta_name.asp Details about the "name" attribute.
     */
    public $name = null;

    /**
     * Content attribute.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_meta_content.asp Details about the "content" attribute.
     */
    public $content = null;

    /**
     * HTTP-Equiv attribute.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_meta_http_equiv.asp Details about the "http-equiv" attribute.
     */
    public $httpEquiv = null;

    /**
     * Charset attribute.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_meta_charset.asp Details about the "charset" attribute.
     */
    public $charset = null;

    /**
     * Meta constructor.
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
        $element = '<meta';

        if (\is_string($this->id))
            $element .= ' id="' . \htmlspecialchars($this->id) . '"';

        if (\is_string($this->name))
            $element .= ' name="' . \htmlspecialchars($this->name) . '"';

        if (\is_string($this->content))
            $element .= ' content="' . \htmlspecialchars($this->content) . '"';

        if (\is_string($this->httpEquiv))
            $element .= ' http-equiv="' . \htmlspecialchars($this->httpEquiv) . '"';

        if (\is_string($this->charset))
            $element .= ' charset="' . \htmlspecialchars($this->charset) . '"';

        return $element . '>';
    }

    /**
     * Create meta element for the page author.
     *
     * @param string $author
     * author name
     *
     * @return Meta
     * created meta element
     */
    public static function newAuthor($author)
    {
        return Meta::newName(
            'meta-author',
            'author',
            $author
        );
    }

    /**
     * Create meta element for the document charset.
     *
     * @param string $charset
     * charset
     *
     * @return Meta
     * created meta element
     */
    public static function newCharset($charset)
    {
        $meta = new Meta('meta-charset');
        $meta->charset = $charset;
        return $meta;
    }

    /**
     * Create meta element for the page copyright.
     *
     * @param string $copyright
     * copyright information
     *
     * @return Meta
     * created meta element
     */
    public static function newCopyright($copyright)
    {
        return Meta::newName('meta-copyright', 'copyright', $copyright);
    }

    /**
     * Create meta element for the page description.
     *
     * @param string $description
     * description
     *
     * @return Meta
     * created meta element
     */
    public static function newDescription($description)
    {
        return Meta::newName('meta-description', 'description', $description);
    }

    /**
     * Create meta element with http-equiv.
     *
     * @param string $id
     * id attribute
     *
     * @param string $httpEquiv
     * http-equiv attribute
     *
     * @param string $content
     * content attribute
     *
     * @return Meta
     * created meta element
     */
    public static function newHttpEquiv($id, $httpEquiv, $content = null)
    {
        $meta = new Meta($id);
        $meta->httpEquiv = $httpEquiv;
        $meta->content = $content;
        return $meta;
    }

    /**
     * Create meta element for page keywords.
     *
     * @param string $keywords
     * keywords
     *
     * @return Meta
     * created meta element
     */
    public static function newKeywords($keywords)
    {
        return Meta::newName('meta-keywords', 'keywords', $keywords);
    }

    /**
     * Create meta element with name.
     *
     * @param string $id
     * id attribute
     *
     * @param string $name
     * name attribute
     *
     * @param string $content
     * content attribute
     *
     * @return Meta
     * created meta element
     */
    public static function newName($id, $name, $content = null)
    {
        $meta = new Meta($id);
        $meta->name = $name;
        $meta->content = $content;
        return $meta;
    }

    /**
     * Create meta element for page refresh.
     *
     * @param string $url
     * URL, that is called for refresh
     *
     * @param int $delay
     * duration to wait for refresh in seconds
     *
     * @return Meta
     * created meta element
     */
    public static function newRefresh($url, $delay = 0)
    {
        return Meta::newHttpEquiv('meta-refresh', 'refresh', $delay . ',url=' . $url);
    }

    /**
     * Create meta element for robots.
     *
     * @param string $robots
     * robots declaration (e.g. "noindex,follow")
     *
     * @return Meta
     * created meta element
     */
    public static function newRobots($robots)
    {
        return Meta::newHttpEquiv('meta-robots', 'robots', $robots);
    }
}
