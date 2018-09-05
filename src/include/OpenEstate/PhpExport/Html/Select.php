<?php /** @noinspection HtmlUnknownAttribute */

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
 * A HTML element for select boxes.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
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
