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

namespace OpenEstate\PhpExport;

/**
 * The Bootstrap3 example theme.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 *
 * @var Environment $env
 * current export environment
 */
if (!\class_exists(Bootstrap3Theme::class)) {
    class Bootstrap3Theme extends Theme\BasicTheme
    {
        /**
         * ID of the Bootstrap3 framework.
         *
         * @var string
         * @see https://getbootstrap.com/
         */
        const BOOTSTRAP = 'bootstrap3';

        /**
         * ID of the JQuery.js component.
         *
         * @var string
         * @see https://jquery.com/
         */
        const JQUERY = 'jquery';

        /**
         * ID of the Slick.js component.
         *
         * @var string
         * @see http://kenwheeler.github.io/slick/
         */
        const SLICK = 'slick';

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

        public function getComponentIds()
        {
            $components = parent::getComponentIds();
            $components[] =self::BOOTSTRAP;
            $components[] =self::JQUERY;
            $components[] =self::SLICK;
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
return new Bootstrap3Theme($env);
