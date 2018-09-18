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
 * An abstract HTML element.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
abstract class AbstractHtmlElement
{
    /**
     * ID attribute of the HTML element.
     *
     * @var string
     */
    public $id;

    /**
     * Class attribute of the HTML element.
     *
     * @var string
     */
    public $class;

    /**
     * AbstractHtmlElement constructor.
     *
     * @param string|null $id
     * id attribute
     *
     * @param string|null $class
     * class attribute
     */
    function __construct($id = null, $class = null)
    {
        $this->id = $id;
        $this->class = $class;
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
