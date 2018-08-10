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
 * A HTML element for checkboxes.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class Checkbox extends AbstractBodyElement
{
    /**
     * Label, that is shown together with the checkbox.
     *
     * @var string
     */
    public $label = null;

    /**
     * Name of the form field.
     *
     * @var string
     */
    public $name = null;

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
     * @param string $id
     * id attribute
     *
     * @param string $class
     * class attribute
     *
     * @param string $name
     * name of the form field
     *
     * @param string $value
     * value of the form field
     *
     * @param bool $checked
     * set checkbox checked
     *
     * @param string $label
     * label, that is shown together with the checkbox
     */
    function __construct($id, $class, $name, $value = '1', $checked = false, $label = null)
    {
        parent::__construct($id, $class);
        $this->name = $name;
        $this->value = $value;
        $this->checked = $checked;
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
     * @param string $id
     * id attribute
     *
     * @param string $class
     * class attribute
     *
     * @param string $name
     * name of the form field
     *
     * @param string $value
     * value of the form field
     *
     * @param bool $checked
     * set checkbox checked
     *
     * @param string $label
     * label, that is shown together with the checkbox
     *
     * @return Checkbox
     * created checkbox element
     */
    public static function newBox($id, $class, $name, $value, $checked = false, $label = null)
    {
        return new Checkbox($id, $class, $name, $value, $checked, $label);
    }

}
