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

namespace OpenEstate\PhpExport\Session;

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Utils;

/**
 * A session store, that uses PHP sessions.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @see http://php.net/manual/en/book.session.php PHP documentation about sessions.
 */
class PhpSession extends AbstractSession
{
    /**
     * Name of the root variable within $_SESSION,
     * that holds the session data.
     *
     * @var string
     */
    protected $root;

    /**
     * PhpSession constructor.
     *
     * @param Environment $env
     * export environment
     *
     * @param string $root
     * name of the root variable within $_SESSION
     */
    function __construct(Environment $env, $root = null)
    {
        parent::__construct($env);
        $this->root = (Utils::isNotBlankString($root)) ?
            $root : 'OpenEstatePhpExport';

        if (\session_status() === PHP_SESSION_NONE) {
            Utils::logWarning('Session handling is disabled in PHP settings!');
            return;
        }
    }

    public function clear()
    {
        if (!isset($_SESSION))
            return;
        if (!isset($_SESSION[$this->root]))
            return;

        unset($_SESSION[$this->root]);
    }

    public function get($key)
    {
        if (!\is_string($key))
            return null;
        if (!isset($_SESSION))
            return null;
        if (!isset($_SESSION[$this->root]))
            return null;
        if (!isset($_SESSION[$this->root][$key]))
            return null;

        return $_SESSION[$this->root][$key];
    }

    public function init()
    {
        if (\session_status() !== PHP_SESSION_NONE)
            return;

        \session_start();

        if (!isset($_SESSION[$this->root]))
            $_SESSION[$this->root] = array();
    }

    public function set($key, $value)
    {
        if (!\is_string($key))
            return;
        if (!isset($_SESSION))
            return;

        // Update session value.
        if ($value !== null) {
            if (!isset($_SESSION[$this->root]))
                $_SESSION[$this->root] = array();

            $_SESSION[$this->root][$key] = $value;
            return;
        }

        // Remove session value.
        if (!isset($_SESSION[$this->root])) return;
        if (!isset($_SESSION[$this->root][$key])) return;
        unset($_SESSION[$this->root][$key]);
    }

    public function write()
    {
        //\session_write_close();
    }

}
