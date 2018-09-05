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
 * An abstract HTML body element.
 */
abstract class AbstractBodyElement extends AbstractHtmlElement
{
    /**
     * AbstractBodyElement constructor.
     *
     * @param string $id
     * id attribute
     *
     * @param string $class
     * class attribute
     */
    function __construct($id = null, $class = null)
    {
        parent::__construct($id, $class);
    }

    /**
     * AbstractBodyElement destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    final public function isBody()
    {
        return true;
    }

    final public function isHeader()
    {
        return false;
    }
}
