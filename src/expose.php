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
 * Show details about a real estate object.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

// initialization
$startupTime = \microtime(true);
require(__DIR__ . '/include/init.php');
require(__DIR__ . '/config.php');

// start output buffering
if (!\ob_start())
    Utils::logError('Can\'t start output buffering!');

// generate output
$env = null;
try {

    // load environment
    $env = new Environment(new MyConfig(__DIR__));

    // process the requested action, if necessary
    $env->processAction();

    // generate and print HTML
    echo $env->newExposeHtml()->process();

} catch (\Exception $e) {

    // ignore previously buffered output
    \ob_end_clean();
    \ob_start();

    if (!\headers_sent())
        \http_response_code(500);

    //Utils::logError($e);
    Utils::logWarning($e);
    Utils::printErrorException($e);

} finally {

    // shutdown environment
    if ($env !== null)
        $env->shutdown();

    // process buffered output
    $output = \ob_get_clean();
    if (!\is_string($output)) {
        if (!\headers_sent())
            \http_response_code(500);

        Utils::printErrorMessage('No content was created!');
        return;
    }

    // add statistics
    if ($env !== null && $env->isStatistics()) {
        $buildTime = \microtime(true) - $startupTime;
        $output = \str_replace(
            '</body>',
            '<pre class="openestate-statistics">' . Utils::writeStatistics($buildTime) . '</pre></body>',
            $output
        );
    }

    // send generated output
    echo $output;

}
