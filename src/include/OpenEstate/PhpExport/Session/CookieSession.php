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

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Utils;

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
     * @param Environment $env
     * export environment
     *
     * @param string $cookieName
     * name of the session cookie
     *
     * @param int $cookieLifeTime
     * lifetime of the session cookie in seconds
     */
    function __construct(Environment $env, $cookieName = null, $cookieLifeTime = null)
    {
        parent::__construct($env);
        $this->cookieName = (Utils::isNotBlankString($cookieName)) ?
            $cookieName : 'OpenEstatePhpExport';
        $this->cookieLifeTime = (\is_int($cookieLifeTime) && $cookieLifeTime >= 0) ?
            $cookieLifeTime : 60 * 60 * 24 * 30;
    }

    public function clear()
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

    public function init()
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

    public function write()
    {
        // Remove cookie, if no session values are present.
        if (!\is_array($this->values) || \count($this->values) < 1)
            \setcookie($this->cookieName, '', (\time() - 3600));

        // Update cookie with session values.
        else
            \setcookie($this->cookieName, \serialize($this->values), (\time() + $this->cookieLifeTime));
    }

}