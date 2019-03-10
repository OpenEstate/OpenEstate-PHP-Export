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
 * Generate a RSS feed with published real estates.
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
    $config = new MyConfig(__DIR__);
    if ($config->trovitFeed !== true) {
        if (!\headers_sent())
            \http_response_code(403);

        echo 'The trovit feed is disabled by configuration!';
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
    $i18n = $env->getTranslations();

    // send content type header
    if (!\headers_sent())
        \header('Content-Type: text/xml; charset=utf-8');

    // lookup for the feed in cache directory
    $cacheFile = Utils::joinPath($config->getCacheFolderPath(), 'feed.trovit_' . $lang . '.xml');
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

    // create feed
    echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
    echo '<trovit>' . "\n";

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

        $objectUrl = Utils::getAbsoluteUrl($objectView->getUrl($env, $objectId));

        // get modification time
        $objectStamp = $env->getObjectStamp($objectId);
        $objectDate = \date('d/m/Y', $objectStamp);
        $objectTime = \date('H:i', $objectStamp);

        // get title
        $objectTitle = (isset($objectData['title'][$lang])) ?
            $objectData['title'][$lang] : '';

        // get content
        $objectContent = $objectTitle;
        foreach ($objectTexts as $key => $text) {
            if ($key == 'id') {
                continue;
            }
            $value = (isset($text[$lang])) ? $text[$lang] : null;
            if (Utils::isNotBlankString($value)) {
                $objectContent .= '<hr/>' . \trim($value);
            }
        }

        // get type of object
        $objectType = (isset($i18n['openestate']['types'][$objectData['type']])) ?
            $i18n['openestate']['types'][$objectData['type']] : $objectData['type'];

        // get action of object and the corresponding price
        $objectAction = '';
        $objectPrice = '0';
        $objectPriceAttributes = '';
        $objectPriceHidden = isset($objectData['hidden_price']) && $objectData['hidden_price'] === true;

        if ($objectData['action'] == 'purchase') {
            $objectAction = 'For Sale';
            $objectPrice = (!$objectPriceHidden && isset($objectData['attributes']['prices']['purchase_price']['value'])) ?
                $objectData['attributes']['prices']['purchase_price']['value'] : null;
        } else if ($objectData['action'] == 'rent') {
            $objectAction = 'For Rent';
            $objectPrice = (!$objectPriceHidden && isset($objectData['attributes']['prices']['rent_excluding_service_charges']['value'])) ?
                $objectData['attributes']['prices']['rent_excluding_service_charges']['value'] : null;
            $rentFlatRatePer = (isset($objectData['attributes']['prices']['rent_flat_rate_per'])) ?
                $objectData['attributes']['prices']['rent_flat_rate_per']['value'] : null;

            if ($objectPriceHidden)
                $objectPriceAttributes = '';
            else if (Utils::isNotBlankString($rentFlatRatePer) && \strtolower(\trim($rentFlatRatePer)) == 'week')
                $objectPriceAttributes = ' period="weekly"';
            else
                $objectPriceAttributes = ' period="monthly"';
        } else if ($objectData['action'] == 'short_term_rent') {
            $objectAction = 'For Rent';
            $objectPrice = (!$objectPriceHidden && isset($objectData['attributes']['prices']['rent_flat_rate']['value'])) ?
                $objectData['attributes']['prices']['rent_flat_rate']['value'] : null;
            $rentFlatRatePer = (isset($objectData['attributes']['prices']['rent_flat_rate_per']['value'])) ?
                $objectData['attributes']['prices']['rent_flat_rate_per']['value'] : null;
            if (Utils::isNotBlankString($rentFlatRatePer) && \strtolower(\trim($rentFlatRatePer)) == 'week')
                $objectPriceAttributes = (!$objectPriceHidden) ? ' period="weekly"' : '';
            else
                $objectPriceAttributes = (!$objectPriceHidden) ? ' period="monthly"' : '';
        } else if ($objectData['action'] == 'lease' || $objectData['action'] == 'emphyteusis') {
            $objectAction = 'For Rent';
            $objectPrice = (!$objectPriceHidden && isset($objectData['attributes']['prices']['lease']['value'])) ?
                $objectData['attributes']['prices']['lease']['value'] : null;
            $objectPriceAttributes = (!$objectPriceHidden) ? ' period="monthly"' : '';
        } else {
            continue;
        }

        // Preis umwandeln
        $objectPrice = ($objectPrice != null) ? intval($objectPrice) : 0;
        if ($objectPrice < 0)
            $objectPrice = 0;

        // Fläche ermitteln
        $objectArea = null;
        foreach (array('TOTAL_AREA', 'RESIDENTIAL_AREA', 'PLOT_AREA', 'STORAGE_AREA', 'RETAIL_AREA', 'SALES_AREA', 'USABLE_AREA') as $area) {
            $area = strtolower($area);
            if (!isset($objectData['attributes']['measures'][$area]['value']))
                continue;
            $value = $objectData['attributes']['measures'][$area]['value'];
            if (is_numeric($value)) {
                $objectArea = intval($objectArea);
                if ($objectArea <= 0)
                    $objectArea = null;
                else
                    break;
            }
        }

        // Grundstücksfläche
        $objectPlotArea = (isset($objectData['attributes']['measures']['plot_area']['value'])) ?
            $objectData['attributes']['measures']['plot_area']['value'] : null;
        if (!is_numeric($objectPlotArea))
            $objectPlotArea = null;
        else
            $objectPlotArea = intval($objectPlotArea);

        // Anzahl Zimmer ermitteln
        $objectRooms = (isset($objectData['attributes']['measures']['count_rooms']['value'])) ?
            $objectData['attributes']['measures']['count_rooms']['value'] : null;
        if (!is_numeric($objectRooms))
            $objectRooms = 0;
        else
            $objectRooms = intval($objectRooms);

        // Anzahl Badezimmer ermitteln
        $objectBathrooms = (isset($objectData['attributes']['measures']['count_bathrooms']['value'])) ?
            $objectData['attributes']['measures']['count_bathrooms']['value'] : null;
        if (!is_numeric($objectBathrooms))
            $objectBathrooms = 0;
        else
            $objectBathrooms = intval($objectBathrooms);

        // Anzahl Etagen ermitteln
        $objectFloorNumber = (isset($objectData['attributes']['features']['count_floors']['value'])) ?
            $objectData['attributes']['features']['count_floors']['value'] : null;
        if (!is_numeric($objectFloorNumber))
            $objectFloorNumber = 0;
        else
            $objectFloorNumber = intval($objectFloorNumber);

        // Stellplatz ermitteln
        $arten = (isset($objectData['attributes']['measures']['parking_type']['value'])) ?
            $objectData['attributes']['measures']['parking_type']['value'] : null;
        $objectParking = (is_array($arten) && count($arten) > 0) ? 1 : 0;

        // Möblierung ermitteln
        $objectIsFurnished = null;
        $moebliert = (isset($objectData['attributes']['features']['furnished']['value'])) ?
            $objectData['attributes']['features']['furnished']['value'] : null;
        if ($moebliert == null)
            $objectIsFurnished = null;
        else if (strtolower($moebliert) == 'yes')
            $objectIsFurnished = 1;
        else if (strtolower($moebliert) == 'partial')
            $objectIsFurnished = 1;
        else if (strtolower($moebliert) == 'no')
            $objectIsFurnished = 0;

        // Zustand ermitteln
        //$objectConditition = (isset($object['attributes']['condition']['condition_type'][$lang]))?
        //        $object['attributes']['condition']['condition_type'][$lang]: null;
        // Baujahr ermitteln
        $objectYear = (isset($objectData['attributes']['condition']['build_year'][$lang])) ?
            $objectData['attributes']['condition']['build_year'][$lang] : null;

        // Anschrift
        $objectAddress = null;
        if (isset($objectData['address']['street']) && \is_string($objectData['address']['street'])) {
            $objectAddress = \trim($objectData['address']['street']);
            if (isset($objectData['address']['street_nr']) && \is_string($objectData['address']['street_nr'])) {
                $objectAddress .= ' ' . \trim($objectData['address']['street_nr']);
            }
        }

        // Ort & Ortsteil
        $objectCity = (isset($objectData['address']['city'])) ?
            $objectData['address']['city'] : null;
        $objectCityPart = (isset($objectData['address']['city_part'])) ?
            $objectData['address']['city_part'] : null;
        $objectPostal = (isset($objectData['address']['postal'])) ?
            $objectData['address']['postal'] : null;
        $objectRegion = (isset($objectData['address']['region'])) ?
            $objectData['address']['region'] : null;
        $objectLatitude = (isset($objectData['address']['latitude'])) ?
            $objectData['address']['latitude'] : null;
        $objectLongitude = (isset($objectData['address']['longitude'])) ?
            $objectData['address']['longitude'] : null;

        // Immobilie in den Feed eintragen
        echo '  <ad>' . "\n";
        echo '    <id><![CDATA[' . $objectId . ']]></id>' . "\n";
        echo '    <url><![CDATA[' . $objectUrl . ']]></url>' . "\n";
        echo '    <title><![CDATA[' . $objectTitle . ']]></title>' . "\n";
        echo '    <type><![CDATA[' . $objectAction . ']]></type>' . "\n";
        echo '    <date><![CDATA[' . $objectDate . ']]></date>' . "\n";
        echo '    <time><![CDATA[' . $objectTime . ']]></time>' . "\n";
        echo '    <agency><![CDATA[' . $env->getConfig()->companyName . ']]></agency>' . "\n";
        echo '    <content><![CDATA[' . $objectContent . ']]></content>' . "\n";
        if (is_numeric($objectPrice) && $objectPrice >= 0) {
            echo '    <price' . $objectPriceAttributes . '><![CDATA[' . $objectPrice . ']]></price>' . "\n";
        }
        echo '    <property_type><![CDATA[' . $objectType . ']]></property_type>' . "\n";
        if (is_numeric($objectArea) && $objectArea > 0)
            echo '    <floor_area unit="meters"><![CDATA[' . $objectArea . ']]></floor_area>' . "\n";
        if (is_numeric($objectRooms) && $objectRooms > 0)
            echo '    <rooms><![CDATA[' . $objectRooms . ']]></rooms>' . "\n";
        if (is_numeric($objectBathrooms) && $objectBathrooms > 0)
            echo '    <bathrooms><![CDATA[' . $objectBathrooms . ']]></bathrooms>' . "\n";
        if (!is_null($objectParking))
            echo '    <parking><![CDATA[' . $objectParking . ']]></parking>' . "\n";
        if (!is_null($objectAddress))
            echo '    <address><![CDATA[' . $objectAddress . ']]></address>' . "\n";
        if (!is_null($objectCity))
            echo '    <city><![CDATA[' . $objectCity . ']]></city>' . "\n";
        if (!is_null($objectCityPart))
            echo '    <city_area><![CDATA[' . $objectCityPart . ']]></city_area>' . "\n";
        if (!is_null($objectPostal))
            echo '    <postcode><![CDATA[' . $objectPostal . ']]></postcode>' . "\n";
        if (!is_null($objectRegion))
            echo '    <region><![CDATA[' . $objectRegion . ']]></region>' . "\n";
        if (!is_null($objectLatitude))
            echo '    <latitude><![CDATA[' . $objectLatitude . ']]></latitude>' . "\n";
        if (!is_null($objectLongitude))
            echo '    <longitude><![CDATA[' . $objectLongitude . ']]></longitude>' . "\n";

        if (isset($objectData['images']) && is_array($objectData['images'])) {
            echo '    <pictures>' . "\n";
            foreach ($objectData['images'] as $img) {
                $imgPath = $env->getDataPath($objectId, $img['name']);
                if (\is_file($imgPath)) {
                    $imgUrl = Utils::getAbsoluteUrl($env->getDataUrl($objectId)) . '/' . $img['name'];
                    $imgTitle = (isset($img['title'][$lang]) && is_string($img['title'][$lang])) ?
                        $img['title'][$lang] : '';

                    echo '      <picture>' . "\n";
                    echo '        <picture_url><![CDATA[' . $imgUrl . ']]></picture_url>' . "\n";
                    echo '        <picture_title><![CDATA[' . $imgTitle . ']]></picture_title>' . "\n";
                    echo '      </picture>' . "\n";
                }
            }
            echo '    </pictures>' . "\n";
        }

        //echo '    <virtual_tour><![CDATA['..']]></virtual_tour>' . "\n";
        //echo '    <expiration_date><![CDATA['..']]></expiration_date>' . "\n";
        if (is_numeric($objectPlotArea) && $objectPlotArea > 0)
            echo '    <plot_area><![CDATA[' . $objectPlotArea . ']]></plot_area>' . "\n";
        if (is_numeric($objectFloorNumber) && $objectFloorNumber > 0)
            echo '    <floor_number><![CDATA[' . $objectFloorNumber . ']]></floor_number>' . "\n";
        //echo '    <orientation><![CDATA['..']]></orientation>' . "\n";
        //echo '    <foreclosure><![CDATA['..']]></foreclosure>' . "\n";
        if (is_numeric($objectIsFurnished))
            echo '    <is_furnished><![CDATA[' . $objectIsFurnished . ']]></is_furnished>' . "\n";
        //echo '    <is_new><![CDATA['..']]></is_new>' . "\n";
        //if (is_string($objectConditition))
        //echo '    <s_condition><![CDATA['.$objectConditition.']]></s_condition>' . "\n";
        if (is_numeric($objectYear))
            echo '    <year><![CDATA[' . $objectYear . ']]></year>' . "\n";
        echo '  </ad>' . "\n";

        $count++;
        if (\is_int($config->trovitFeedLimit) && $config->trovitFeedLimit > 0 && $count >= $config->trovitFeedLimit)
            break;
    }

    echo '</trovit>';

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
