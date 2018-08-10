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

/**
 * A session store, that uses cookies.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class CookieSession extends AbstractSession
{
    /**
     * Name of the session cookie.
     *
     * @var string
     */
    public $cookieName;

    /**
     * Lifetime of the session cookie in seconds.
     *
     * @var float|int
     */
    public $cookieLifeTime;

    /**
     * Values stored within the session cookie.
     *
     * @var array
     */
    protected $values = null;

    /**
     * CookieSession constructor.
     *
     * @param string $name
     * internal name of the session store
     *
     * @param string $cookieName
     * name of the session cookie
     *
     * @param int $cookieLifeTime
     * lifetime of the session cookie in seconds
     */
    function __construct($name = 'CookieSession', $cookieName = null, $cookieLifeTime = null)
    {
        parent::__construct($name);
        $this->cookieName = (\is_string($cookieName)) ?
            $cookieName : 'OpenEstatePhpExport';
        $this->cookieLifeTime = (\is_int($cookieLifeTime) && $cookieLifeTime >= 0) ?
            $cookieLifeTime : 60 * 60 * 24 * 30;
    }

    public function clear(\OpenEstate\PhpExport\Environment &$env)
    {
        $this->values = array();
    }

    public function get($key)
    {
        if (!\is_string($key))
            return null;
        if (!\is_array($this->values))
            return null;
        if (!isset($this->values[$key]))
            return null;

        return $this->values[$key];
    }

    public function init(\OpenEstate\PhpExport\Environment &$env)
    {
        $this->values = array();
        if (!\is_array($_COOKIE))
            return;
        if (!isset($_COOKIE[$this->cookieName]))
            return;
        $this->values = \unserialize($_COOKIE[$this->cookieName]);
    }

    public function set($key, $value)
    {
        if (!\is_string($key))
            return;
        if (!\is_array($this->values))
            return;

        // Update session value.
        if ($value !== null) {
            $this->values[$key] = $value;
            return;
        }

        // Remove session value.
        if (isset($this->values[$key]))
            unset($this->values[$key]);
    }

    public function write(\OpenEstate\PhpExport\Environment &$env)
    {
        // Remove cookie, if no session values are present.
        if (!\is_array($this->values) || \count($this->values) < 1)
            \setcookie($this->cookieName, '', (\time() - 3600));

        // Update cookie with session values.
        else
            \setcookie($this->cookieName, \serialize($this->values), (\time() + $this->cookieLifeTime));
    }

}
