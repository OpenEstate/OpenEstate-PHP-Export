<?php
/*
 * Copyright 2009-2019 OpenEstate.org.
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

namespace OpenEstate\PhpExport;

/**
 * The default example theme.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 *
 * @var Environment $env
 * current export environment
 */
if (!\class_exists(DefaultTheme::class)) {
    class DefaultTheme extends Theme\BasicTheme
    {
        /**
         * ID of the Pure CSS framework.
         *
         * @var string
         * @see https://purecss.io/
         */
        const PURE = 'pure';

        /**
         * ID of the JQuery.js component.
         *
         * @var string
         * @see https://jquery.com/
         */
        const JQUERY = 'jquery';

        /**
         * ID of the Popper.js component.
         *
         * @var string
         * @see https://popper.js.org/
         */
        const POPPER = 'popper';

        /**
         * ID of the Slick.js component.
         *
         * @var string
         * @see http://kenwheeler.github.io/slick/
         */
        const SLICK = 'slick';

        /**
         * ID of the Colorbox component.
         *
         * @var string
         * @see http://www.jacklmoore.com/colorbox/
         */
        const COLORBOX = 'colorbox';

        /**
         * DefaultTheme constructor.
         *
         * @param Environment $env
         * export environment
         */
        function __construct($env)
        {
            parent::__construct(\basename(__DIR__), $env);
        }

        public function getComponentIds()
        {
            $components = parent::getComponentIds();
            $components[] =self::PURE;
            $components[] =self::JQUERY;
            $components[] =self::POPPER;
            $components[] =self::SLICK;
            $components[] =self::COLORBOX;
            return $components;
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
return new DefaultTheme($env);
