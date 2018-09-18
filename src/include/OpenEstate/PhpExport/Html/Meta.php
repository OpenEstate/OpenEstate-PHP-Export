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
 * A HTML element for meta tags.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
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
     * @param string|null $content
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
     * Create meta element for the generator.
     *
     * @param string $generator
     * generator
     *
     * @return Meta
     * created meta element
     */
    public static function newGenerator($generator)
    {
        return Meta::newName('meta-generator', 'generator', $generator);
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
     * @param string|null $content
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
     * @param int|null $delay
     * duration to wait for refresh in seconds
     *
     * @return Meta
     * created meta element
     */
    public static function newRefresh($url, $delay = 0)
    {
        return Meta::newHttpEquiv('meta-refresh', 'refresh', (\is_int($delay))? $delay: 0 . ',url=' . $url);
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
        return Meta::newName('meta-robots', 'robots', $robots);
    }

    /**
     * Create meta element for viewport.
     *
     * @param string $viewport
     * viewport declaration (e.g. "width=device-width, initial-scale=1")
     *
     * @return Meta
     * created meta element
     */
    public static function newViewport($viewport)
    {
        return Meta::newName('meta-viewport', 'viewport', $viewport);
    }
}
