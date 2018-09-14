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

namespace OpenEstate\PhpExport;

/**
 * The Bootstrap3 example theme.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @var Environment $env
 * current export environment
 */
if (!\class_exists(Bootstrap3Theme::class)) {
    class Bootstrap3Theme extends Theme\BasicTheme
    {
        /**
         * Bootstrap3Theme constructor.
         *
         * @param Environment $env
         * export environment
         */
        function __construct($env)
        {
            parent::__construct(\basename(__DIR__), $env);
        }

        public function setupExposeHtml(View\ExposeHtml $view)
        {
            parent::setupExposeHtml($view);
        }

        public function setupFavoriteHtml(View\FavoriteHtml $view)
        {
            parent::setupFavoriteHtml($view);
        }

        public function setupListingHtml(View\ListingHtml $view)
        {
            parent::setupListingHtml($view);
        }
    }

}

// Return an instance of the theme to the including script.
return new Bootstrap3Theme($env);
