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
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
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
