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
 * A HTML element for checkboxes.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class Checkbox extends AbstractInputElement
{
    /**
     * Label, that is shown together with the checkbox.
     *
     * @var string
     */
    public $label = null;

    /**
     * Value of the form field.
     *
     * @var string
     */
    public $value;

    /**
     * Enable autofocus for the form field.
     *
     * @var bool
     */
    public $autofocus = false;

    /**
     * Set checkbox checked.
     *
     * @var bool
     */
    public $checked = false;

    /**
     * Set checkbox checked by default.
     *
     * @var bool
     */
    public $defaultChecked = false;

    /**
     * Set form field disabled.
     *
     * @var bool
     */
    public $disabled = false;

    /**
     * Checkbox constructor.
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
     * @param bool|null $checked
     * set checkbox checked
     *
     * @param string|null $label
     * label, that is shown together with the checkbox
     */
    function __construct($name, $id = null, $class = null, $value = '1', $checked = false, $label = null)
    {
        parent::__construct($name, $id, $class);
        $this->value = $value;
        $this->checked = (\is_bool($checked))? $checked: false;
        $this->label = $label;
    }

    public function generate()
    {
        $element = '<input';
        $element .= ' type="checkbox"';

        if (\is_string($this->id))
            $element .= ' id="' . \htmlspecialchars($this->id) . '"';


        if (\is_string($this->class))
            $element .= ' class="' . \htmlspecialchars($this->class) . '"';

        if (\is_string($this->name))
            $element .= ' name="' . \htmlspecialchars($this->name) . '"';

        if (\is_string($this->value))
            $element .= ' value="' . \htmlspecialchars($this->value) . '"';

        if ($this->autofocus === true)
            $element .= ' autofocus';

        if ($this->disabled === true)
            $element .= ' disabled';

        if ($this->checked === true)
            $element .= ' checked';

        if ($this->defaultChecked === true)
            $element .= ' defaultChecked';

        if (\is_string($this->onChange))
            $element .= ' onchange="' . \htmlspecialchars($this->onChange) . '"';

        if (\is_string($this->onFocus))
            $element .= ' onfocus="' . \htmlspecialchars($this->onFocus) . '"';

        if (\is_string($this->onBlur))
            $element .= ' onblur="' . \htmlspecialchars($this->onBlur) . '"';

        $element .= '>';

        if (\is_string($this->label)) {
            $element .= '<label';

            if (\is_string($this->id))
                $element .= ' for="' . \htmlspecialchars($this->id) . '"';

            $element .= '>' . \htmlspecialchars(\trim($this->label));
            $element .= '</label>';
        }

        return $element;
    }

    /**
     * Create a checkbox.
     *
     * @param string $name
     * name of the input field
     *
     * @param string $id
     * id attribute
     *
     * @param string $class
     * class attribute
     *
     * @param string $value
     * value of the form field
     *
     * @param bool|null $checked
     * set checkbox checked
     *
     * @param string|null $label
     * label, that is shown together with the checkbox
     *
     * @return Checkbox
     * created checkbox element
     */
    public static function newBox($name, $id, $class, $value, $checked = false, $label = null)
    {
        return new Checkbox($name, $id, $class, $value, $checked, $label);
    }

}
