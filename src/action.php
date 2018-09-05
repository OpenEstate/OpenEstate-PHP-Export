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

namespace OpenEstate\PhpExport;

/**
 * Execute a certain action.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

// initialization
require(__DIR__ . '/include/init.php');
require(__DIR__ . '/config.php');

// start output buffering
if (!\ob_start())
    Utils::logError('Can\'t start output buffering!');

// set headers for JSON response
if (!\headers_sent()) {
    \header('Cache-Control: no-cache, must-revalidate');
    \header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    \header('Content-Type: application/json');
}

// generate output
$env = null;
try {

    // load environment
    $env = new Environment(new MyConfig(__DIR__));

    // process the requested action
    $result = $env->processAction();

    // send action result
    if ($result === null) {
        \http_response_code(501);
        echo Utils::getJson(array('error' => 'No action was executed!'));
    } else {
        echo Utils::getJson($result);
    }

} catch (\Exception $e) {

    // ignore previously buffered output
    \ob_end_clean();
    \ob_start();

    if (!\headers_sent())
        \http_response_code(500);

    //Utils::logError($e);
    Utils::logWarning($e);
    echo Utils::getJson(array('error' => $e->getMessage()));

} finally {

    // shutdown environment
    if ($env !== null)
        $env->shutdown();

    // process buffered output
    $output = \ob_get_clean();
    if (!\is_string($output)) {
        if (!\headers_sent())
            \http_response_code(500);

        echo Utils::getJson(array('error' => 'No result was created!'));
        return;
    }

    // send generated output
    echo $output;

}
