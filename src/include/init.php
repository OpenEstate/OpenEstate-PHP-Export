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
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

// Don't execute the file, if it was already loaded.
if (defined('OpenEstate\PhpExport\VERSION')) return;

/**
 * Current version of the PHP export environment.
 *
 * @var string
 */
define('OpenEstate\PhpExport\VERSION', '2.0-beta2');

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

    // if the file exists, require it
    if (file_exists($file)) {
        /** @noinspection PhpIncludeInspection */
        require $file;
    }
});
