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

/**
 * An abstract theme.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
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
     * @var \OpenEstate\PhpExport\Environment
     */
    protected $env;

    /**
     * AbstractTheme constructor.
     *
     * @param string $name
     * internal name of the theme
     *
     * @param $env
     * export environment
     */
    function __construct($name, \OpenEstate\PhpExport\Environment $env)
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
     * Get the export environment.
     *
     * @return \OpenEstate\PhpExport\Environment
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
     * Get absolute path to a file of this theme.
     *
     * @param string $path
     * relative path within the theme directory
     *
     * @return string|null
     * absolute path to the theme file or null, if it is not available
     */
    public function getPath($path = null)
    {
        return $this->env->getThemePath($this->name, $path);
    }

    /**
     * Get the URL to a file of this theme.
     *
     * @param string $path
     * file name within the theme
     *
     * @param $parameters
     * associative array of URL parameters
     *
     * @return string
     * URL of the file in the theme
     */
    public function getUrl($path = null, $parameters = null)
    {
        return $this->env->getThemeUrl($this->name, $path, $parameters);
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
     * @param $plural
     * plural form of the original string
     *
     * @param $value
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
     * Create the HTML view with object details.
     *
     * @return \OpenEstate\PhpExport\View\ExposeHtml
     * created view
     */
    abstract public function newExposeHtml();

    /**
     * Create the HTML view with favorite listing.
     *
     * @return \OpenEstate\PhpExport\View\FavoriteHtml
     * created view
     */
    abstract public function newFavoriteHtml();

    /**
     * Create the HTML view with object listing.
     *
     * @return \OpenEstate\PhpExport\View\ListingHtml
     * created view
     */
    abstract public function newListingHtml();

    /**
     * Set default configuration for the HTML view with object details.
     *
     * @param \OpenEstate\PhpExport\View\ExposeHtml $view
     * view to configure
     */
    abstract public function setupExposeHtml(\OpenEstate\PhpExport\View\ExposeHtml $view);

    /**
     * Set default configuration for the HTML view with favorite listing.
     *
     * @param \OpenEstate\PhpExport\View\FavoriteHtml $view
     * view to configure
     */
    abstract public function setupFavoriteHtml(\OpenEstate\PhpExport\View\FavoriteHtml $view);

    /**
     * Set default configuration for the HTML view with object listing.
     *
     * @param \OpenEstate\PhpExport\View\ListingHtml $view
     * view to configure
     */
    abstract public function setupListingHtml(\OpenEstate\PhpExport\View\ListingHtml $view);
}
