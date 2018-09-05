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
 * A session store, that uses PHP sessions.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
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
     * @param $env
     * export environment
     *
     * @param string $root
     * name of the root variable within $_SESSION
     */
    function __construct(\OpenEstate\PhpExport\Environment $env, $root = null)
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
