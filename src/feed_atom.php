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

use function htmlspecialchars as html;
use function OpenEstate\PhpExport\gettext as _;

/**
 * Generate an Atom feed with published real estates.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

// feed settings
define('FEED_DATE_FORMAT', 'Y-m-d\TH:i:s\Z');

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
    $config = new MyConfig(__DIR__);
    if ($config->atomFeed !== true) {
        if (!\headers_sent())
            \http_response_code(403);

        echo 'The atom feed is disabled by configuration!';
        return;
    }
    $env = new Environment($config, false);

    // get requested language
    if (isset($_REQUEST['lang'])) {
        $lang = $_REQUEST['lang'];
        if (!$env->isSupportedLanguage($lang)) {
            throw new \Exception('The requested language is not supported!');
        }
        if ($lang !== $env->getLanguage()) {
            $env->setLanguage($lang);
        }

    } else {
        $lang = $env->getLanguage();
    }

    // send content type header
    if (!\headers_sent())
        \header('Content-Type: text/xml; charset=utf-8');

    // lookup for the feed in cache directory
    $cacheFile = Utils::joinPath($config->getCacheFolderPath(), 'feed.atom_' . $lang . '.xml');
    if ($env->isProductionMode() && \is_file($cacheFile)) {
        if (Utils::isFileOlderThen($cacheFile, $config->cacheLifeTime)) {
            // remove outdated cache file
            \unlink($cacheFile);
        } else {
            // send feed from the previously cached file
            $feed = Utils::readFile($cacheFile);
            if ($feed === null) {
                throw new \Exception('Can\t read feed from cache file!');
            }
            echo $feed;

            return;
        }
    }

    // get feed url
    $feedUrl = Utils::getAbsoluteUrl(Utils::joinPath($config->baseUrl, \basename(__FILE__)) . '?lang=' . $lang);

    // get current timestamp
    $feedStamp = \gmdate(FEED_DATE_FORMAT);

    // create feed
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    /** @noinspection XmlUnusedNamespaceDeclaration */
    echo '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">' . "\n";
    echo '  <id>' . html($feedUrl) . '</id>' . "\n";
    echo '  <link rel="self" type="application/atom+xml" href="' . html($feedUrl) . '" />' . "\n";
    echo '  <title>' . html(\ucfirst(_('current offers'))) . '</title>' . "\n";
    echo '  <subtitle>' . html(\ucfirst(_('current offers by {0}', $env->getConfig()->companyName))) . '</subtitle>' . "\n";
    echo '  <generator uri="https://openestate.org" version="' . VERSION . '">OpenEstate-PHP-Export</generator>' . "\n";
    echo '  <rights>' . html($env->getConfig()->companyName) . '</rights>' . "\n";
    echo '  <updated>' . html($feedStamp) . '</updated>' . "\n";
    echo '  <dc:creator>' . html($env->getConfig()->companyName) . '</dc:creator>' . "\n";
    echo '  <dc:date>' . html($feedStamp) . '</dc:date>' . "\n";
    echo '  <dc:language>' . html($lang) . '</dc:language>' . "\n";
    echo '  <dc:rights>' . html($env->getConfig()->companyName) . '</dc:rights>' . "\n";

    // load objects ordered by last modification (descending)
    $count = 0;
    $order = new Order\LastMod();
    $order->readOrRebuild($env);
    $objectIds = \array_reverse($order->getItems($lang));
    $objectView = $env->newExposeHtml();
    foreach ($objectIds as $objectId) {
        // load object data
        $objectData = $env->getObject($objectId);
        if (!\is_array($objectData)) {
            continue;
        }
        $objectTexts = $env->getObjectText($objectId);
        if (!\is_array($objectTexts)) {
            continue;
        }

        $objectStamp = \gmdate(FEED_DATE_FORMAT, $env->getObjectStamp($objectId));
        $objectUrl = Utils::getAbsoluteUrl($objectView->getUrl($env, $objectId));

        $objectTitle = $objectData['title'][$lang];
        if (isset($objectData['nr']) && Utils::isNotBlankString($objectData['nr']))
            $objectTitle = \trim($objectData['nr'] . ' » ' . $objectTitle);
        else
            $objectTitle = \trim('#' . $objectId . ' » ' . $objectTitle);

        $objectSummary = '';
        if (isset($objectTexts['short_description'][$lang]) && Utils::isNotBlankString($objectTexts['short_description'][$lang]))
            $objectSummary = $objectTexts['short_description'][$lang];
        else if (isset($objectTexts['detailled_description']) && Utils::isNotBlankString($objectTexts['detailled_description'][$lang]))
            $objectSummary = $objectTexts['detailled_description'][$lang];
        else if (isset($objectData['title'][$lang]) && Utils::isNotBlankString($objectData['title'][$lang]))
            $objectSummary = $objectData['title'][$lang];

        if ($config->atomFeedWithImage === true && isset($objectData['images'][0]['thumb']) && Utils::isNotBlankString($objectData['images'][0]['thumb'])) {
            $objectImgPath = $env->getDataPath($objectId, $objectData['images'][0]['thumb']);
            if (\is_file($objectImgPath)) {
                $objectImgUrl = Utils::getAbsoluteUrl($env->getDataUrl($objectId)) . '/' . $objectData['images'][0]['thumb'];
                $objectSummary = '<img src="' . html($objectImgUrl) . '" alt="" align="left" /> ' . $objectSummary;
            }
        }

        echo '  <entry>' . "\n";
        echo '    <id>' . html($objectUrl) . '</id>' . "\n";
        echo '    <link href="' . html($objectUrl) . '" />' . "\n";
        echo '    <title>' . html($objectTitle) . '</title>' . "\n";
        echo '    <author>' . "\n";
        echo '      <name>' . html($env->getConfig()->companyName) . '</name>' . "\n";
        echo '    </author>' . "\n";
        //echo '    <summary type="text">' . html(\trim(\strip_tags($objectSummary))) . '</summary>' . "\n";
        //echo '    <content type="html">' . html($objectSummary) . '</content>' . "\n";
        echo '    <content type="html"><![CDATA[' . $objectSummary . ']]></content>' . "\n";
        echo '    <updated>' . $objectStamp . '</updated>' . "\n";
        echo '    <dc:creator>' . html($objectStamp) . '</dc:creator>' . "\n";
        echo '  </entry>' . "\n";

        $count++;
        if (\is_int($config->atomFeedLimit) && $config->atomFeedLimit > 0 && $count >= $config->atomFeedLimit)
            break;
    }

    echo '</feed>';

    // write feed into cache file
    if ($env->isProductionMode()) {
        // process buffered output
        $feed = \ob_get_clean();

        // write feed into local cache
        $fh = \fopen($cacheFile, 'w');
        if ($fh !== false) {
            \fwrite($fh, $feed);
            \fclose($fh);
        }

        // write generated feed
        \ob_start();
        echo $feed;
    }

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

    // send buffered output
    \ob_end_flush();

}
