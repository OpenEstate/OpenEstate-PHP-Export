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
 * Show XML sitemap of exported real estates.
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
        if (substr($objectUrl, 0, 1) === '/') {
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
    echo '<h1>An internal error occurred!</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
    echo '<pre>' . $e . '</pre>';

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

        echo '<h1>An internal error occurred!</h1>';
        echo '<p>No content was created!</p>';
        return;
    }

    // send generated output
    echo $output;

}
