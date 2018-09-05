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

namespace OpenEstate\PhpExport\Provider;

/**
 * An abstract embedded view from an external provider.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class AbstractProvider
{
    /**
     * Width of the embedded element.
     *
     * @var int
     */
    public $width;

    /**
     * Height of the embedded element.
     *
     * @var int
     */
    public $height;

    /**
     * AbstractProvider constructor.
     *
     * @param int $width
     * width of the embedded element
     *
     * @param int $height
     * height of the embedded element
     */
    function __construct($width = null, $height = null)
    {
        $this->width = (\is_int($width) && $width > 0) ?
            $width : 0;
        $this->height = (\is_int($height) && $height > 0) ?
            $height : 0;
    }

    /**
     * AbstractProvider destructor.
     */
    public function __destruct()
    {
    }

    /**
     * Get HTML header elements for the embedded view.
     *
     * @return array
     * header elements
     */
    public function getHeaderElements()
    {
        return array();
    }
}
