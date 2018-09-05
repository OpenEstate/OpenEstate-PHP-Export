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

/**
 * Initialize export environment.
 *
 * @see https://www.php-fig.org/psr/psr-4/
 * PSR-4: Autoloader
 *
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
 * Example Implementations of PSR-4
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

// Don't execute the file, if it was already loaded.
if (defined('OpenEstate\PhpExport\VERSION')) return;

/**
 * Current version of the PHP export environment.
 *
 * @var string
 */
define('OpenEstate\PhpExport\VERSION', '2.0-dev');

// Require old functions for backwards compatibility.
//require_once(__DIR__ . '/functions.php');

// Load classes automatically.
spl_autoload_register(function ($class) {
    //echo 'lookup ' . $class . '<br>';

    // base directory for the namespace prefix
    $base_dir = __DIR__;

    // project-specific namespace prefix
    //$prefix = 'Foo\\Bar\\';
    $prefix = null;

    // does the class use the namespace prefix?
    if ($prefix !== null) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // no, move to the next registered autoloader
            return;
        }

        // get the relative class name
        $class = substr($class, $len);
    }

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . '/' . str_replace('\\', '/', $class) . '.php';
    //echo 'from ' . $file . '<br>';

    // if the file exists, require it
    if (file_exists($file)) {
        //echo 'require<br>';
        /** @noinspection PhpIncludeInspection */
        require $file;
    }
});
