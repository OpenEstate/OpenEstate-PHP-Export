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
 * An abstract HTML input element.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
abstract class AbstractInputElement extends AbstractBodyElement
{
    /**
     * Name of the input field.
     *
     * @var string
     */
    public $name = null;

    /**
     * AbstractInputElement constructor.
     *
     * @param string|null $name
     * name of the input field
     *
     * @param string|null $id
     * id attribute
     *
     * @param string|null $class
     * class attribute
     */
    function __construct($name, $id = null, $class = null)
    {
        parent::__construct($id, $class);
        $this->name = $name;
    }

    /**
     * AbstractBodyElement destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }
}
