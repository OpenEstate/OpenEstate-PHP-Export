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

namespace OpenEstate\PhpExport;

use function htmlspecialchars as html;

/**
 * Remove previously cached files.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

// initialization
require(__DIR__ . '/include/init.php');
require(__DIR__ . '/config.php');

// start output buffering
if (!\ob_start())
    Utils::logError('Can\'t start output buffering!');

// generate output
$env = null;
try {

    // load environment
    //echo 'loading environment ' . \OpenEstate\PhpExport\VERSION . '<hr>';
    $env = new Environment(new MyConfig(__DIR__), false);

    // get cache directory
    $cacheDir = $env->getConfig()->getCacheFolderPath();
    if (!\is_dir($cacheDir)) {
        throw new \Exception('Can\'t find cache directory!');
    }

    // remove files in cache directory
    $success = 0;
    $error = 0;
    echo '<h2>Remove cache files</h2>';
    echo '<strong>from:</strong> ' . html($cacheDir);
    echo '<ul>';
    foreach (Utils::listDirectory($cacheDir) as $file) {
        if ($file == 'index.html' || $file == '.htaccess') {
            continue;
        }
        $path = $cacheDir . '/' . $file;
        if (!\is_file($path)) {
            continue;
        }
        echo '<li>';
        echo '<b>' . html($file) . '</b>';
        echo ' &rarr; ';
        if (\unlink($path) === true) {
            echo 'OK';
            $success++;
        } else {
            echo 'ERROR';
            $error++;
        }
        echo '</li>';
    }
    echo '</ul>';
    echo '<p>' . $success . ' files removed</p>';
    if ($error>0) {
        echo '<p>' . $error . ' files not removed</p>';
    }

} catch (\Exception $e) {

    // ignore previously buffered output
    \ob_end_clean();
    \ob_start();

    if (!\headers_sent())
        \http_response_code(500);

    //Utils::logError($e);
    Utils::logWarning($e);
    echo '<h1>An internal error occurred!</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
    echo '<pre>' . $e . '</pre>';

} finally {

    // shutdown environment
    if ($env !== null)
        $env->shutdown();

    // send buffered output
    \ob_end_flush();

}
