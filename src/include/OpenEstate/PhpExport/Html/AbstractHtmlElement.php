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
 * An abstract HTML element.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
abstract class AbstractHtmlElement
{
    /**
     * Specifies a unique id for an element.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_global_id.asp Details about the "id" attribute.
     */
    public $id;

    /**
     * Specifies one or more class names for an element (refers to a class in a style sheet).
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_global_class.asp Details about the "class" attribute.
     */
    public $class;

    /**
     * Specifies extra information about an element.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_global_title.asp Details about the "title" attribute.
     */
    public $title;

    /**
     * AbstractHtmlElement constructor.
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
        $this->id = $id;
        $this->class = $class;
        $this->title = $title;
    }

    /**
     * AbstractHtmlElement destructor.
     */
    public function __destruct()
    {
    }

    /**
     * Generate the HTML element.
     *
     * @return string
     * generated HTML code
     */
    abstract public function generate();

    /**
     * Generate attributes of the HTML element.
     *
     * @return string
     * generated attributes in HTML syntax
     */
    protected function generateAttributes()
    {
        return \implode(' ', $this->getAttributes());
    }

    /**
     * Get attributes of the HTML element.
     *
     * @return array
     * array of attributes in HTML syntax
     */
    protected function getAttributes()
    {
        $attributes = array();

        if (\is_string($this->id))
            $attributes[] = 'id="' . html($this->id) . '"';

        if (\is_string($this->class))
            $attributes[] = 'class="' . html($this->class) . '"';

        if (\is_string($this->title))
            $attributes[] = 'title="' . html($this->title) . '"';

        return $attributes;
    }

    /**
     * Determine, if the element is placed within the HTML body.
     *
     * @return bool
     * true, if it is a HTML body element
     */
    abstract public function isBody();

    /**
     * Determine, if the element is placed within the HTML header.
     *
     * @return bool
     * true, if it is a HTML header element
     */
    abstract public function isHeader();

}
