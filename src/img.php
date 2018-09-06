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
 * Return an possibly scale an object image.
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
$srcImg = null;
$scaledImg = null;
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
    if (is_file($imgCachePath)) {
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

        $dest_x = $x;
        $dest_y = $y;
        $src_ratio = $src_x / $src_y;
        $dest_ratio = $dest_x / $dest_y;

        // too tall
        if ($src_ratio <= $dest_ratio) {
            $dest_y = $src_y * $dest_x / $src_x;
            $move_x = 0;
            $move_y = ($y - $dest_y) / 2;
        } // too wide
        else {
            $dest_x = $src_x * $dest_y / $src_y;
            $move_x = ($x - $dest_x) / 2;
            $move_y = 0;
        }

        $srcImg = null;
        if ($type == 1) // GIF
            $srcImg = \imagecreatefromgif($imgObjectPath);

        else if ($type == 2) // JPG
            $srcImg = \imagecreatefromjpeg($imgObjectPath);

        else if ($type == 3) // PNG
            $srcImg = \imagecreatefrompng($imgObjectPath);

        else
            throw new \Exception('The image type is not supported!');

        $scaledImg = \imagecreatetruecolor($x, $y);
        $bgRgb = Utils::getRgbFromHex($bg);
        $bgColor = (\is_array($bgRgb)) ?
            \imagecolorallocate($scaledImg, $bgRgb['r'], $bgRgb['g'], $bgRgb['b']) :
            \imagecolorallocate($scaledImg, 255, 255, 255);

        \imagefilledrectangle($scaledImg, 0, 0, $x, $y, $bgColor);
        \imagecopyresampled($scaledImg, $srcImg, $move_x, $move_y, 0, 0, $dest_x, $dest_y, $src_x, $src_y);

        // save scaled image into cache directory
        \imagejpeg($scaledImg, $imgCachePath, 85);

        // return scaled image
        if (!\headers_sent()) {
            \header('Cache-Control: max-age=3600, public');
            \header('Content-type: image/jpeg');
        }

        \imagejpeg($scaledImg, null, 85);
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
    echo '<h1>An internal error occurred!</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
    echo '<pre>' . $e . '</pre>';

} finally {

    // shutdown environment
    if ($env !== null)
        $env->shutdown();

    if ($srcImg !== null)
        \imagedestroy($srcImg);

    if ($scaledImg !== null)
        \imagedestroy($scaledImg);

    // send buffered output
    \ob_end_flush();

}
