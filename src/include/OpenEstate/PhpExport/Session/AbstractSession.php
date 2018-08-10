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
 * An abstract session store.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class AbstractSession
{
    /**
     * Internal name of the session store.
     *
     * @var string
     */
    private $name;

    /**
     * AbstractSession constructor.
     *
     * @param string $name
     * internal name of the session store
     */
    function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Remove session from the backend store.
     *
     * @param \OpenEstate\PhpExport\Environment $env
     * export environment
     *
     * @return void
     */
    abstract public function clear(\OpenEstate\PhpExport\Environment &$env);

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
     * Get the internal name of the session store.
     *
     * @return string
     * session store name
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * Initialize the session.
     *
     * @param \OpenEstate\PhpExport\Environment $env
     * export environment
     *
     * @return void
     */
    abstract public function init(\OpenEstate\PhpExport\Environment &$env);

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
     * Write session to the backend store.
     *
     * @param \OpenEstate\PhpExport\Environment
     * $env export environment
     *
     * @return void
     */
    abstract public function write(\OpenEstate\PhpExport\Environment &$env);

}
