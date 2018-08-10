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

/**
 * Generate and send a captcha image.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

// initialization
if (!extension_loaded('gd')) {
    if (!headers_sent())
        header('HTTP/1.0 500 Internal Server Error');

    echo 'It seems like GD is not installed!';
    return;
}
//ob_start();
$startupTime = microtime();
require(__DIR__ . '/include/init.php');

// load environment
//echo 'loading environment ' . \OpenEstate\PhpExport\VERSION . '<hr>';
$env = new OpenEstate\PhpExport\Environment(__DIR__);
$env->init();

// setup captcha
define('CAPTCHA_FONT_PATH', $env->getPath('assets/fonts'));
define('CAPTCHA_LENGTH', 5);
//define( 'CAPTCHA_SYMBOLS', 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789');
define('CAPTCHA_SYMBOLS', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789');
define('CAPTCHA_SIZE_X', 125);
define('CAPTCHA_SIZE_Y', 30);
define('CAPTCHA_SIZE_FONT', 25);
define('CAPTCHA_VARIABLE', 'captchaCode');

// get a random true type font
$fonts = array();
$files = \OpenEstate\PhpExport\Utils::listDirectory(CAPTCHA_FONT_PATH);
if (is_array($files)) {
    foreach ($files as $file) {
        if (substr(strtolower($file), -4) === '.ttf') {
            $fonts[] = $file;
        }
    }
}
if (count($fonts) < 1) {
    if (!headers_sent())
        header('HTTP/1.0 500 Internal Server Error');

    echo 'No captcha font was found!';
    return;
}
$font = CAPTCHA_FONT_PATH . '/' . $fonts[array_rand($fonts)];

// render captcha image
$image = imagecreate(CAPTCHA_SIZE_X, CAPTCHA_SIZE_Y);
imagecolorallocate($image, 255, 255, 255);
$left = 0;
$signs = CAPTCHA_SYMBOLS;
$string = '';
for ($i = 1; $i <= CAPTCHA_LENGTH; $i++) {
    $sign = $signs{rand(0, strlen($signs) - 1)};
    $string .= $sign;
    imagettftext($image, 25, rand(-10, 10), $left + (($i == 1 ? 5 : 15) * $i), 25, imagecolorallocate($image, 200, 200, 200), $font, $sign);
    imagettftext($image, 16, rand(-15, 15), $left + (($i == 1 ? 5 : 15) * $i), 25, imagecolorallocate($image, 69, 103, 137), $font, $sign);
}
$env->setSessionValue(CAPTCHA_VARIABLE, $string);
$env->shutdown();

// send captcha image
//ob_clean();
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
exit();
