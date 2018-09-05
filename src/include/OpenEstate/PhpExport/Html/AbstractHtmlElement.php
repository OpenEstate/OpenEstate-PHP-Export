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
 * An abstract HTML element.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
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
