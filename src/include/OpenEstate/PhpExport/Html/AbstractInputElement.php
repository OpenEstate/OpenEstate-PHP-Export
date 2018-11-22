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
     * Executed Javascript, if the value was changed.
     *
     * @var string
     */
    public $onChange = null;

    /**
     * Executed Javascript, if the input element gains the focus.
     *
     * @var string
     */
    public $onFocus = null;

    /**
     * Executed Javascript, if the input element loses the focus.
     *
     * @var string
     */
    public $onBlur = null;

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
     *
     * @param string|null $title
     * title attribute
     */
    function __construct($name, $id = null, $class = null, $title = null)
    {
        parent::__construct($id, $class, $title);
        $this->name = $name;
    }

    /**
     * AbstractBodyElement destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    protected function getAttributes()
    {
        $attributes = parent::getAttributes();

        if (\is_string($this->name))
            $attributes[] = 'name="' . html($this->name) . '"';

        if (\is_string($this->onChange))
            $attributes[] = 'onchange="' . html($this->onChange) . '"';

        if (\is_string($this->onFocus))
            $attributes[] = 'onfocus="' . html($this->onFocus) . '"';

        if (\is_string($this->onBlur))
            $attributes[] = 'onblur="' . html($this->onBlur) . '"';

        return $attributes;
    }
}
