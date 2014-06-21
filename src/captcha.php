<?php
/*
 * PHP-Export scripts of OpenEstate-ImmoTool
 * Copyright (C) 2009-2014 OpenEstate.org
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
 * Website-Export, Darstellung einer Captcha-Grafik.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

// Initialisierung
define('IN_WEBSITE', 1);
if (!defined('IMMOTOOL_BASE_PATH'))
  define('IMMOTOOL_BASE_PATH', '');
if (!extension_loaded('gd'))
  die('It seems like GD is not installed!');
include(IMMOTOOL_BASE_PATH . 'include/functions.php');
define('CAPTCHA_FONT_PATH', IMMOTOOL_BASE_PATH . 'include/fonts');
define('CAPTCHA_LENGTH', 5);
//define( 'CAPTCHA_SYMBOLS', 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789');
define('CAPTCHA_SYMBOLS', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789');
define('CAPTCHA_SIZE_X', 125);
define('CAPTCHA_SIZE_Y', 30);
define('CAPTCHA_SIZE_FONT', 25);
define('CAPTCHA_VARIABLE', 'captchacode');
if (is_string($_REQUEST['sess']) && strlen($_REQUEST['sess']) > 0)
  session_name($_REQUEST['sess']);
session_start();

// zufällige TTF Schriftart ermitteln
$fonts = immotool_functions::list_directory(CAPTCHA_FONT_PATH);
if (!is_array($fonts) || count($fonts) <= 0)
  die('No font was found in path \'' . CAPTCHA_FONT_PATH . '\'!');
$font = CAPTCHA_FONT_PATH . '/' . $fonts[array_rand($fonts)];

// Captcha rendern
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
$_SESSION[CAPTCHA_VARIABLE] = $string;

// Captcha ausgeben
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
session_write_close();
exit();
