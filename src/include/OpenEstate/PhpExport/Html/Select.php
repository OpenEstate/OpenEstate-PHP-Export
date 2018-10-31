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
 * A HTML element for select boxes.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class Select extends AbstractInputElement
{
    /**
     * Value of the form field.
     *
     * @var string|array
     */
    public $value = null;

    /**
     * Associative array with selection options.
     *
     * @var array
     */
    public $options = array();

    /**
     * Size of the select field.
     *
     * @var string
     */
    public $size = null;

    /**
     * Enable autofocus for the form field.
     *
     * @var bool
     */
    public $autofocus = false;

    /**
     * Set form field disabled.
     *
     * @var bool
     */
    public $disabled = false;

    /**
     * Set multiple selection enabled.
     *
     * @var bool
     */
    public $multiple = false;

    /**
     * Select constructor.
     *
     * @param string $name
     * name of the input field
     *
     * @param string|null $id
     * id attribute
     *
     * @param string|null $class
     * class attribute
     *
     * @param string|null $value
     * value of the input field
     *
     * @param array|null $options
     * selection options
     */
    function __construct($name, $id = null, $class = null, $value = null, $options = null)
    {
        parent::__construct($name, $id, $class);
        $this->value = $value;
        $this->options = $options;
    }

    public function generate()
    {
        $element = '<select';

        if (\is_string($this->id))
            $element .= ' id="' . \htmlspecialchars($this->id) . '"';

        if (\is_string($this->class))
            $element .= ' class="' . \htmlspecialchars($this->class) . '"';

        if (\is_string($this->name))
            $element .= ' name="' . \htmlspecialchars($this->name) . '"';

        if (\is_string($this->size))
            $element .= ' size="' . \htmlspecialchars($this->size) . '"';

        if ($this->autofocus === true)
            $element .= ' autofocus';

        if ($this->disabled === true)
            $element .= ' disabled';

        if ($this->multiple === true)
            $element .= ' multiple';

        if (\is_string($this->onChange))
            $element .= ' onchange="' . \htmlspecialchars($this->onChange) . '"';

        if (\is_string($this->onFocus))
            $element .= ' onfocus="' . \htmlspecialchars($this->onFocus) . '"';

        if (\is_string($this->onBlur))
            $element .= ' onblur="' . \htmlspecialchars($this->onBlur) . '"';

        $element .= '>';

        if (\is_array($this->options)) {
            foreach ($this->options as $key => $value) {
                $key = (string)$key;
                if ($this->multiple === true)
                    $selected = (\in_array($key, $this->value)) ? ' selected' : '';
                else
                    $selected = ($this->value === $key) ? ' selected' : '';

                $element .= '<option value="' . \htmlspecialchars($key) . '"' . $selected . '>' . \htmlspecialchars($value) . '</option>';
            }
        }

        return $element . '</select>';
    }

    /**
     * Create a multiple selection.
     *
     * @param string $name
     * name of the form field
     *
     * @param string|null $id
     * id attribute
     *
     * @param string|null $class
     * class attribute
     *
     * @param string|null $value
     * value of the form field
     *
     * @param array|null $options
     * selection options
     *
     * @param int|null $size
     * size of the select field
     *
     * @return Select
     * created select element
     */
    public static function newMultiSelect($name, $id = null, $class = null, $value = null, $options = null, $size = 4)
    {
        $select = new Select($name, $id, $class, $value, $options);
        $select->size = (\is_int($size)) ? $size : 4;
        $select->multiple = true;
        return $select;
    }

    /**
     * Create a single selection.
     *
     * @param string $name
     * name of the form field
     *
     * @param string|null $id
     * id attribute
     *
     * @param string|null $class
     * class attribute
     *
     * @param string|null $value
     * value of the form field
     *
     * @param array|null $options
     * selection options
     *
     * @return Select
     * created select element
     */
    public static function newSingleSelect($name, $id = null, $class = null, $value = null, $options = null)
    {
        return new Select($name, $id, $class, $value, $options);
    }
}
