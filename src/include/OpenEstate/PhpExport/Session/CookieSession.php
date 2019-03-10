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

namespace OpenEstate\PhpExport\Session;

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Utils;

/**
 * A session store, that uses cookies.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
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
        $this->values = \unserialize(\base64_decode($_COOKIE[$this->cookieName]));
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
        $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '';
        $domain = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != 'localhost') ?
            $_SERVER['HTTP_HOST'] : false;

        // Remove cookie, if no session values are present.
        if (!\is_array($this->values) || \count($this->values) < 1) {
            \setcookie(
                $this->cookieName,
                '',
                (\time() - 3600),
                '/',
                $domain,
                $secure,
                true
            );
        }


        // Update cookie with session values.
        else {
            \setcookie(
                $this->cookieName,
                \base64_encode(\serialize($this->values)),
                (\time() + $this->cookieLifeTime),
                '/',
                $domain,
                $secure,
                true
            );
        }
    }

}
