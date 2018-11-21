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

/**
 * Show XML sitemap of exported real estates.
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
$cacheFileResource = null;
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

    if (!\headers_sent())
        \header('Content-Type: text/xml; charset=utf-8');

    // load sitemap from cache
    $cacheFile = $env->getCachePath('sitemap.' . $lang . '.xml');
    if ($env->isProductionMode() && \is_file($cacheFile)) {
        if (Utils::isFileOlderThen($cacheFile, $env->getConfig()->cacheLifeTime)) {
            // remove outdated cache file
            \unlink($cacheFile);
        } else {
            // use sitemap from cache
            echo Utils::readFile($cacheFile);
            return;
        }
    }

    // create sitemap
    $sitemapStamp = \date('Y-m-d');
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    // get object ID's
    $order = new Order\ObjectId();
    if (!$order->readOrRebuild($env))
        throw new \Exception('Can\'t get object ID\'s.');
    $objectIds = $order->getItems($lang);
    //\array_reverse($ids);

    // write objects into sitemap
    $exposeView = $env->newExposeHtml();
    foreach ($objectIds as $objectId) {
        $objectData = $env->getObject($objectId);
        if (!\is_array($objectData))
            continue;

        $objectUrl = $exposeView->getUrl($env, $objectId);
        if (substr($objectUrl, 0, 2) === './') {
            $protocol = ($_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';
            $objectUrl = $protocol . $_SERVER['SERVER_NAME'] . \dirname($_SERVER['PHP_SELF']) . \substr($objectUrl, 1);
        } else if (substr($objectUrl, 0, 1) === '/') {
            $protocol = ($_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';
            $objectUrl = $protocol . $_SERVER['SERVER_NAME'] . $objectUrl;
        }

        $objectStamp = $env->getObjectStamp($objectId);

        $sitemap .= '  <url>' . "\n";
        $sitemap .= '    <loc>' . \htmlspecialchars($objectUrl) . '</loc>' . "\n";
        if (!is_null($objectStamp))
            $sitemap .= '    <lastmod>' . \date('Y-m-d', $objectStamp) . '</lastmod>' . "\n";
        else
            $sitemap .= '    <lastmod>' . $sitemapStamp . '</lastmod>' . "\n";
        $sitemap .= '    <changefreq>weekly</changefreq>' . "\n";
        $sitemap .= '  </url>' . "\n";
    }
    $sitemap .= '</urlset>';

    // write sitemap into cache file
    if ($env->isProductionMode()) {
        $cacheFileResource = \fopen($cacheFile, 'w');
        if (\is_resource($cacheFileResource))
            \fwrite($cacheFileResource, $sitemap);
    }

    // print sitemap
    echo $sitemap;

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
    if (\is_resource($cacheFileResource))
        \fclose($cacheFileResource);

    // process buffered output
    $output = \ob_get_clean();
    if (!\is_string($output)) {
        if (!\headers_sent())
            \http_response_code(500);

        Utils::printErrorMessage('No content was created!');
        return;
    }

    // send generated output
    echo $output;

}
