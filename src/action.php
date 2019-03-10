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

namespace OpenEstate\PhpExport;

/**
 * Execute a certain action.
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
