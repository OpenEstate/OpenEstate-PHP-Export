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

namespace OpenEstate\PhpExport\Session;

use OpenEstate\PhpExport\Utils;

/**
 * An abstract session store.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class AbstractSession
{
    /**
     * Export environment.
     *
     * @var \OpenEstate\PhpExport\Environment
     */
    protected $env;

    /**
     * AbstractSession constructor.
     *
     * @param $env
     * export environment
     */
    function __construct(\OpenEstate\PhpExport\Environment $env)
    {
        $this->env = $env;
    }

    /**
     * AbstractSession destructor.
     */
    public function __destruct()
    {
    }

    /**
     * Add an object to the favorites.
     *
     * @param string $objectId
     * ID of the object to add
     */
    public function addFavorite($objectId)
    {
        $favorites = $this->getFavorites();
        if (!\in_array($objectId, $favorites))
            $favorites[] = $objectId;

        $this->set('favorites', $favorites);
    }

    /**
     * Remove session from the backend store.
     *
     * @return void
     */
    abstract public function clear();

    /**
     * Get a value from session store.
     *
     * @param string $key
     * variable name in session store
     *
     * @return mixed|null
     * requested value or null, if not available in session store
     */
    abstract public function get($key);

    /**
     * Get captcha hash value.
     *
     * @return string|null
     * captcha hash value or null, if not specified
     */
    public function getCaptcha()
    {
        $captcha = $this->get('captcha');
        return (Utils::isNotBlankString($captcha)) ?
            $captcha : null;
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
     * Get list of favorite object ID's.
     *
     * @return array
     * array of object ID's
     */
    public function getFavorites()
    {
        $favorites = $this->get('favorites');
        return (\is_array($favorites)) ?
            $favorites : array();
    }

    /**
     * Get name of ordering for the favorite view.
     *
     * @return string|null
     * internal name of current ordering or null, if not specified
     */
    public function getFavoriteOrder()
    {
        $ordering = $this->get('favOrder');
        return (Utils::isNotBlankString($ordering)) ?
            $ordering : null;
    }

    /**
     * Get direction of ordering for the favorite view.
     *
     * @return string|null
     * current ordering direction or null, if not specified
     */
    public function getFavoriteOrderDirection()
    {
        $dir = $this->get('favOrderDir');
        return (Utils::isNotBlankString($dir)) ?
            $dir : null;
    }

    /**
     * Get page number for the favorite view.
     *
     * @return int|null
     * page number or null, if not specified
     */
    public function getFavoritePage()
    {
        $page = $this->get('favPage');
        return (\is_int($page)) ?
            (int)$page : null;
    }

    /**
     * Get view for the favorite view.
     *
     * @return string|null
     * name of the view or null, if not specified
     */
    public function getFavoriteView()
    {
        $view = $this->get('favView');
        return (Utils::isNotBlankString($view)) ?
            $view : null;
    }

    /**
     * Get current language from session.
     *
     * @return string|null
     * current language or null, if not specified
     */
    public function getLanguage()
    {
        $lang = $this->get('language');
        return (Utils::isNotBlankString($lang)) ?
            $lang : null;
    }

    /**
     * Get filter values for the listing view.
     *
     * @return array
     * array of filter values
     */
    public function getListingFilters()
    {
        $filters = $this->get('listingFilters');
        return (\is_array($filters)) ?
            $filters : array();
    }

    /**
     * Get name of ordering for the listing view.
     *
     * @return string|null
     * internal name of current ordering or null, if not specified
     */
    public function getListingOrder()
    {
        $ordering = $this->get('listingOrder');
        return (Utils::isNotBlankString($ordering)) ?
            $ordering : null;
    }

    /**
     * Get direction of ordering for the listing view.
     *
     * @return string|null
     * current ordering direction or null, if not specified
     */
    public function getListingOrderDirection()
    {
        $dir = $this->get('listingOrderDir');
        return (Utils::isNotBlankString($dir)) ?
            $dir : null;
    }

    /**
     * Get page number for the listing view.
     *
     * @return int|null
     * page number or null, if not specified
     */
    public function getListingPage()
    {
        $page = $this->get('listingPage');
        return (\is_int($page)) ?
            (int)$page : null;
    }

    /**
     * Get view for the listing view.
     *
     * @return string|null
     * name of the view or null, if not specified
     */
    public function getListingView()
    {
        $view = $this->get('listingView');
        return (Utils::isNotBlankString($view)) ?
            $view : null;
    }

    /**
     * Initialize the session.
     *
     * @return void
     */
    abstract public function init();

    /**
     * Test, if an object was added to favorites.
     *
     * @param string $objectId
     * ID of the object to lookup
     *
     * @return bool
     * true, if the object was added to favorites
     */
    public function isFavorite($objectId)
    {
        if (Utils::isBlankString($objectId))
            return false;

        $favorites = $this->getFavorites();
        return \array_search($objectId, $favorites) !== false;
    }

    /**
     * Remove an object from the favorites.
     *
     * @param string $objectId
     * ID of the object to remove
     */
    public function removeFavorite($objectId)
    {
        if (Utils::isBlankString($objectId))
            return;

        $favorites = $this->getFavorites();
        $index = \array_search($objectId, $favorites);
        if ($index !== false)
            unset($favorites[$index]);

        if (\count($favorites) < 1)
            $favorites = null;

        $this->set('favorites', $favorites);
    }

    /**
     * Set a value in session store.
     *
     * @param string $key
     * variable name in session store
     *
     * @param mixed|null $value
     * value to save or null, to remove the value from session store
     */
    abstract public function set($key, $value);

    /**
     * Set captcha hash value.
     *
     * @var $captcha string|null
     * captcha hash value or null, if not specified
     */
    public function setCaptcha($captcha)
    {
        $this->set('captcha', $captcha);
    }

    /**
     * Set name of ordering for the favorite view.
     *
     * @var $order string|null
     * current ordering or null, if not specified
     */
    public function setFavoriteOrder($order)
    {
        $this->set('favOrder', $order);
    }

    /**
     * Set direction of ordering for the favorite view.
     *
     * @var $direction string|null
     * ordering direction ("asc" or "desc") or null, if not specified
     */
    public function setFavoriteOrderDirection($direction)
    {
        $this->set('favOrderDir', $direction);
    }

    /**
     * Set page number for the favorite view.
     *
     * @var $page int|null
     * page number or null, if not specified
     */
    public function setFavoritePage($page)
    {
        $this->set('favPage', $page);
    }

    /**
     * Set view for the favorite view.
     *
     * @var $view string|null
     * view name or null, if not specified
     */
    public function setFavoriteView($view)
    {
        $this->set('favView', $view);
    }

    /**
     * Set current language.
     *
     * @var $language string|null
     * current language or null, if not specified
     */
    public function setLanguage($language)
    {
        $this->set('language', $language);
    }

    /**
     * Set filter values for the listing view.
     *
     * @var $filters array|null
     * current filter values or null, if not specified
     */
    public function setListingFilters($filters)
    {
        $this->set('listingFilters', $filters);
    }

    /**
     * Set name of ordering for the listing view.
     *
     * @var $order string|null
     * current ordering or null, if not specified
     */
    public function setListingOrder($order)
    {
        $this->set('listingOrder', $order);
    }

    /**
     * Set direction of ordering for the listing view.
     *
     * @var $direction string|null
     * ordering direction ("asc" or "desc") or null, if not specified
     */
    public function setListingOrderDirection($direction)
    {
        $this->set('listingOrderDir', $direction);
    }

    /**
     * Set page number for the listing view.
     *
     * @var $page int|null
     * page number or null, if not specified
     */
    public function setListingPage($page)
    {
        $this->set('listingPage', $page);
    }

    /**
     * Set view for the listing view.
     *
     * @var $view string|null
     * view name or null, if not specified
     */
    public function setListingView($view)
    {
        $this->set('listingView', $view);
    }

    /**
     * Write session to the backend store.
     *
     * @return void
     */
    abstract public function write();

}
