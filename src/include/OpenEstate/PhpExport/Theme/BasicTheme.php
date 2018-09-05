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

namespace OpenEstate\PhpExport\Theme;

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\View\ExposeHtml;
use OpenEstate\PhpExport\View\FavoriteHtml;
use OpenEstate\PhpExport\View\ListingHtml;

/**
 * A theme with basic functionality.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class BasicTheme extends AbstractTheme
{
    /**
     * BasicTheme constructor.
     *
     * @param string $name
     * internal name of the theme
     *
     * @param Environment $env
     * export environment
     */
    function __construct($name, Environment $env)
    {
        parent::__construct($name, $env);
    }

    public function newExposeHtml()
    {
        return new ExposeHtml($this->getEnvironment());
    }

    public function newFavoriteHtml()
    {
        return new FavoriteHtml($this->getEnvironment());
    }

    public function newListingHtml()
    {
        return new ListingHtml($this->getEnvironment());
    }

    public function setupExposeHtml(ExposeHtml $view)
    {
    }

    public function setupFavoriteHtml(FavoriteHtml $view)
    {
    }

    public function setupListingHtml(ListingHtml $view)
    {
    }
}
