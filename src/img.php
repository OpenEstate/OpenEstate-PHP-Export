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
 * Return a scaled object image.
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
$srcImage = null;
$scaledImage = null;
try {

    // get requested object id
    $objectId = (isset($_REQUEST['id']) && \is_string($_REQUEST['id'])) ?
        \trim(\basename($_REQUEST['id'])) : null;
    if (Utils::isBlankString($objectId))
        throw new \Exception('No object id was provided!');

    // get requested image name
    $imgName = (isset($_REQUEST['img']) && \is_string($_REQUEST['img'])) ?
        \trim(\basename($_REQUEST['img'])) : null;
    if (Utils::isBlankString($imgName))
        throw new \Exception('No image name was provided!');

    // get requested image size
    $x = (isset($_REQUEST['x']) && \is_numeric($_REQUEST['x'])) ?
        (int)$_REQUEST['x'] : 0;
    $y = (isset($_REQUEST['y']) && \is_numeric($_REQUEST['y'])) ?
        (int)$_REQUEST['y'] : 0;

    // get requested background color
    /** @noinspection SpellCheckingInspection */
    $bg = (isset($_REQUEST['bg']) && \is_string($_REQUEST['bg']) && \strlen($_REQUEST['bg']) >= 6) ?
        $_REQUEST['bg'] : 'ffffff';

    // load environment
    $env = new Environment(new MyConfig(__DIR__), false);

    // get path to the image in object data
    $imgObjectPath = $env->getDataPath($objectId, $imgName);
    if (!\is_file($imgObjectPath))
        throw new \Exception('Your requested image was not found!');

    // get path to the image in cache folder
    $imgCachePath = $env->getCachePath('img.' . \md5($objectId . '-' . $imgName . '-' . $x . '-' . $y . '-' . $bg) . '.jpg');
    if (\is_file($imgCachePath) && Utils::isFileOlderThen($imgCachePath, $env->getConfig()->cacheLifeTime))
        @unlink($imgCachePath);

    // get further image information from object data
    $imgData = null;
    $objectData = $env->getObject($objectId);
    if (isset($objectData['images']) && \is_array($objectData['images'])) {
        foreach ($objectData['images'] as $img) {
            if (isset($img['name']) && $img['name'] == $imgName) {
                $imgData = $img;
                break;
            }
        }
    }
    if (!\is_array($imgData))
        throw new \Exception('Your requested image is not assigned to the object!');

    // send previously cached file
    if ($env->isProductionMode() && is_file($imgCachePath)) {
        $image = Utils::readFile($imgCachePath);

        if ($image === null)
            throw new \Exception('Can\'t read cached image file!');

        if (!\headers_sent()) {
            \header('Cache-Control: max-age=3600, public');
            \header('Content-type: image/jpeg');
        }

        echo $image;
        return;
    }

    // created scaled image
    if ($env->getConfig()->dynamicImageScaling && Utils::isGdExtensionAvailable() && ($x > 0 || $y > 0)) {
        $imgInfo = \getimagesize($imgObjectPath);
        $src_x = $imgInfo[0];
        $src_y = $imgInfo[1];
        $type = $imgInfo[2];

        //if ($x < 1) $x = $src_x;
        //if ($y < 1) $y = $src_y;
        if ($x < 1) $x = \ceil(($src_x / $src_y) * $y);
        if ($y < 1) $y = \ceil(($src_y / $src_x) * $x);

        $target_x = $x;
        $target_y = $y;
        $src_ratio = $src_x / $src_y;
        $target_ratio = $target_x / $target_y;

        // too tall
        if ($src_ratio <= $target_ratio) {
            $target_y = $src_y * $target_x / $src_x;
            $move_x = 0;
            $move_y = ($y - $target_y) / 2;
        } // too wide
        else {
            $target_x = $src_x * $target_y / $src_y;
            $move_x = ($x - $target_x) / 2;
            $move_y = 0;
        }

        $srcImage = null;
        if ($type == 1) // GIF
            $srcImage = \imagecreatefromgif($imgObjectPath);

        else if ($type == 2) // JPG
            $srcImage = \imagecreatefromjpeg($imgObjectPath);

        else if ($type == 3) // PNG
            $srcImage = \imagecreatefrompng($imgObjectPath);

        else
            throw new \Exception('The image type is not supported!');

        $scaledImage = \imagecreatetruecolor($x, $y);
        $bgRgb = Utils::getRgbFromHex($bg);
        $bgColor = (\is_array($bgRgb)) ?
            \imagecolorallocate($scaledImage, $bgRgb['r'], $bgRgb['g'], $bgRgb['b']) :
            \imagecolorallocate($scaledImage, 255, 255, 255);

        \imagefilledrectangle($scaledImage, 0, 0, $x, $y, $bgColor);
        \imagecopyresampled($scaledImage, $srcImage, $move_x, $move_y, 0, 0, $target_x, $target_y, $src_x, $src_y);

        // save scaled image into cache directory
        if ($env->isProductionMode())
            \imagejpeg($scaledImage, $imgCachePath, 85);

        // return scaled image
        if (!\headers_sent()) {
            \header('Cache-Control: max-age=3600, public');
            \header('Content-type: image/jpeg');
        }

        \imagejpeg($scaledImage, null, 85);
        return;
    }

    // pass the requested image directly, if neither scaling nor caching were processed
    $mimeType = (isset($imgData['mimetype']) && \is_string($imgData['mimetype'])) ?
        $imgData['mimetype'] : 'application/octet-stream';
    $image = Utils::readFile($imgObjectPath);

    if ($image === null)
        throw new \Exception('Can\'t read object image file!');

    if (!\headers_sent()) {
        \header('Cache-Control: max-age=3600, public');
        \header('Content-type: ' . $mimeType);
    }

    echo $image;

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

    // close image resources
    if (\is_resource($srcImage))
        \imagedestroy($srcImage);
    if (\is_resource($scaledImage))
        \imagedestroy($scaledImage);

    // send buffered output
    \ob_end_flush();

}
