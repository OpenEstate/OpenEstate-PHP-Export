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
use OpenEstate\PhpExport\Utils;
use OpenEstate\PhpExport\View\ExposeHtml;
use OpenEstate\PhpExport\View\FavoriteHtml;
use OpenEstate\PhpExport\View\ListingHtml;

/**
 * An abstract theme.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
abstract class AbstractTheme
{
    /**
     * Internal name of the theme.
     *
     * @var string
     */
    private $name;

    /**
     * Export environment.
     *
     * @var Environment
     */
    protected $env;

    /**
     * Header components, that should not be integrated by the theme.
     *
     * @var array
     */
    private $disabledComponents = array();

    /**
     * AbstractTheme constructor.
     *
     * @param string $name
     * internal name of the theme
     *
     * @param Environment $env
     * export environment
     */
    function __construct($name, Environment $env)
    {
        $this->name = $name;
        $this->env = $env;
    }

    /**
     * AbstractTheme destructor.
     */
    public function __destruct()
    {
    }

    /**
     * Get a list of components, that are provided by this theme.
     *
     * @return array
     * list of components ID's
     */
    public function getComponentIds()
    {
        return array();
    }

    /**
     * Get the export environment.
     *
     * @return Environment
     * export environment
     */
    public function getEnvironment()
    {
        return $this->env;
    }

    /**
     * Get the internal name of the theme.
     *
     * @return string
     * theme name
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * Get a translation using the original string.
     *
     * @param string $lang
     * language code
     *
     * @param string $original
     * original string to translate
     *
     * @return string
     * translation
     *
     * @see \Gettext\TranslatorInterface::gettext()
     */
    public function i18nGettext($lang, $original)
    {
        return null;
    }

    /**
     * Get a translation checking the plural form.
     *
     * @param string $lang
     * language code
     *
     * @param string $original
     * original string to translate
     *
     * @param string $plural
     * plural form of the original string
     *
     * @param string $value
     * value to determine plural forms
     *
     * @return string
     * translation
     *
     * @see \Gettext\TranslatorInterface::ngettext()
     */
    public function i18nGettextPlural($lang, $original, $plural, $value)
    {
        return null;
    }

    /**
     * Test, if a certain component is enabled for this theme.
     *
     * @param string $componentId
     * ID of the component
     *
     * @return bool
     * true, if the component is enabled
     */
    public function isComponentEnabled($componentId)
    {
        return Utils::isNotBlankString($componentId) &&
            !\in_array($componentId, $this->disabledComponents);
    }

    /**
     * Create the HTML view with object details.
     *
     * @return ExposeHtml
     * created view
     */
    abstract public function newExposeHtml();

    /**
     * Create the HTML view with favorite listing.
     *
     * @return FavoriteHtml
     * created view
     */
    abstract public function newFavoriteHtml();

    /**
     * Create the HTML view with object listing.
     *
     * @return ListingHtml
     * created view
     */
    abstract public function newListingHtml();

    /**
     * Enable or disable a certain component in the theme.
     *
     * @param string $componentId
     * ID of the component
     *
     * @param bool $enabled
     * true, if the component is enabled
     */
    public function setComponentEnabled($componentId, $enabled)
    {
        if (Utils::isBlankString($componentId)) return;

        $index = \array_search($componentId, $this->disabledComponents);

        if ($enabled === false) {
            if ($index === false)
                $this->disabledComponents[] = $componentId;
        } else {
            if ($index !== false)
                unset($this->disabledComponents[$index]);
        }
    }

    /**
     * Set default configuration for the HTML view with object details.
     *
     * @param ExposeHtml $view
     * view to configure
     */
    abstract public function setupExposeHtml(ExposeHtml $view);

    /**
     * Set default configuration for the HTML view with favorite listing.
     *
     * @param FavoriteHtml $view
     * view to configure
     */
    abstract public function setupFavoriteHtml(FavoriteHtml $view);

    /**
     * Set default configuration for the HTML view with object listing.
     *
     * @param ListingHtml $view
     * view to configure
     */
    abstract public function setupListingHtml(ListingHtml $view);
}
