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
 * Generate and send a captcha image.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

// captcha settings
define('OPENESTATE_CAPTCHA_LENGTH', 5);
define('OPENESTATE_CAPTCHA_SYMBOLS', 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789');
define('OPENESTATE_CAPTCHA_SIZE_X', 125);
define('OPENESTATE_CAPTCHA_SIZE_Y', 30);
define('OPENESTATE_CAPTCHA_SIZE_FONT', 25);

// initialization
require(__DIR__ . '/include/init.php');
require(__DIR__ . '/config.php');

// start output buffering
if (!\ob_start())
    Utils::logError('Can\'t start output buffering!');

// generate output
$env = null;
$image = null;
try {

    // check for gd extension
    if (!Utils::isGdExtensionAvailable())
        throw new \Exception('It seems like GD is not installed!');

    // load environment
    //echo 'loading environment ' . \OpenEstate\PhpExport\VERSION . '<hr>';
    $env = new Environment(new MyConfig(__DIR__));

    // get a random true type font
    $fonts = array();
    $fontPath = $env->getAssetsPath('fonts');
    $files = Utils::listDirectory($fontPath);
    if (\is_array($files)) {
        foreach ($files as $file) {
            if (substr(strtolower($file), -4) === '.ttf')
                $fonts[] = $file;
        }
    }
    if (\count($fonts) < 1)
        throw new \Exception('No captcha font was found!');
    $font = $fontPath . '/' . $fonts[array_rand($fonts)];

    // render captcha image
    $image = \imagecreate(OPENESTATE_CAPTCHA_SIZE_X, OPENESTATE_CAPTCHA_SIZE_Y);

    \imagealphablending($image, false);
    $bg = \imagecolorallocatealpha($image, 255, 255, 255, 127);
    imagefilledrectangle($image, 0, 0, OPENESTATE_CAPTCHA_SIZE_X, OPENESTATE_CAPTCHA_SIZE_Y, $bg);
    \imagealphablending($image, true);

    $left = 0;
    $signs = OPENESTATE_CAPTCHA_SYMBOLS;
    $string = '';
    for ($i = 1; $i <= OPENESTATE_CAPTCHA_LENGTH; $i++) {
        $sign = $signs{\rand(0, \strlen($signs) - 1)};
        $string .= $sign;
        \imagettftext(
            $image,
            25,
            rand(-10, 10),
            $left + (($i == 1 ? 5 : 15) * $i),
            25,
            \imagecolorallocate($image, 200, 200, 200),
            $font,
            $sign
        );
        \imagettftext(
            $image,
            16,
            rand(-15, 15),
            $left + (($i == 1 ? 5 : 15) * $i),
            25,
            \imagecolorallocate($image, 69, 103, 137),
            $font,
            $sign
        );
    }
    $env->getSession()->setCaptcha(Utils::getCaptchaHash($string));

    // send generated output
    if (!\is_resource($image))
        throw new \Exception('No image was created!');

    if (!\headers_sent()) {
        \header('Cache-Control: no-cache, must-revalidate');
        \header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        \header('Content-type: image/png');
    }

    // send captcha image
    \imagepng($image);

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

    // close image resource
    if (\is_resource($image))
        \imagedestroy($image);

    // send buffered output
    \ob_end_flush();

}
