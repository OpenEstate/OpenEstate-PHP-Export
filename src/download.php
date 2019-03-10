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
 * Return a pdf file with details about an object.
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
$downloadResource = null;
try {

    // load environment
    $env = new Environment(new MyConfig(__DIR__), false);

    // get requested language
    $lang = (isset($_REQUEST['lang']) && Utils::isNotBlankString($_REQUEST['lang'])) ?
        \trim($_REQUEST['lang']) : null;
    if ($lang !== $env->getLanguage()) {
        if ($env->isSupportedLanguage($lang))
            $env->setLanguage($lang);
        else
            $lang = $env->getLanguage();
    }

    // get requested object id
    $objectId = (isset($_REQUEST['id']) && Utils::isNotBlankString($_REQUEST['id'])) ?
        \basename(\trim($_REQUEST['id'])) : null;
    if (Utils::isBlankString($objectId))
        throw new \Exception('No object id was provided!');

    // get requested object data
    $object = $env->getObject($objectId);
    if (!\is_array($object))
        throw new \Exception('The requested object was not found!');

    // get path to the requested pdf file
    $pdfPath = $env->getObjectPdf($objectId, $lang);
    if (!\is_file($pdfPath))
        throw new \Exception('The requested document was not found!');

    // get file name, that is used in the response
    $pdfName = (isset($object['nr']) && \is_string($object['nr'])) ?
        \trim($object['nr']) : null;
    if (Utils::isBlankString($pdfName))
        $pdfName = $objectId;
    $pdfName = \preg_replace('/[^a-zA-Z0-9_\\-\\.]/', '', $pdfName) . '-' . $lang . '.pdf';

    // send the file
    $downloadResource = \fopen($pdfPath, 'r');
    if (!\is_resource($downloadResource))
        throw new \Exception('Can\t open the requested document!');

    \header('Content-type: application/pdf');
    \header('Content-Disposition: inline; filename="' . $pdfName . '"');
    \header('Content-length: ' . \filesize($pdfPath));
    \header('Cache-Control: no-cache, must-revalidate');
    \header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

    while (!\feof($downloadResource))
        echo \fread($downloadResource, 2048);

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

    // close file resource
    if (\is_resource($downloadResource))
        \fclose($downloadResource);

    // send buffered output
    \ob_end_flush();

}
