<?php
/*
 * PHP-Export scripts of OpenEstate-ImmoTool
 * Copyright (C) 2009-2017 OpenEstate.org
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
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

// Initialisierung
define('IN_WEBSITE', 1);
if (!defined('IMMOTOOL_BASE_PATH')) {
  define('IMMOTOOL_BASE_PATH', '');
}
if (!extension_loaded('gd')) {
  if (!headers_sent()) {
    // 500-Fehlercode zurückliefern,
    // wenn das GD-PHP-Modul nicht verfügbar ist
    header('HTTP/1.0 500 Internal Server Error');
  }
  echo 'It seems like GD is not installed!';
  return;
}
require_once(IMMOTOOL_BASE_PATH . 'config.php');
require_once(IMMOTOOL_BASE_PATH . 'private.php');
require_once(IMMOTOOL_BASE_PATH . 'include/functions.php');
define('CAPTCHA_FONT_PATH', IMMOTOOL_BASE_PATH . 'include/fonts');
define('CAPTCHA_LENGTH', 5);
//define( 'CAPTCHA_SYMBOLS', 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789');
define('CAPTCHA_SYMBOLS', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789');
define('CAPTCHA_SIZE_X', 125);
define('CAPTCHA_SIZE_Y', 30);
define('CAPTCHA_SIZE_FONT', 25);
define('CAPTCHA_VARIABLE', 'captchaCode');

$setup = new immotool_setup();
immotool_functions::init_config($setup, 'load_config_default');
immotool_functions::init_session();

// zufällige TTF Schriftart ermitteln
$fonts = array();
$files = immotool_functions::list_directory(CAPTCHA_FONT_PATH);
if (is_array($files)) {
  foreach ($files as $file) {
    if (substr(strtolower($file), -4) === '.ttf') {
      $fonts[] = $file;
    }
  }
}
if (count($fonts) < 1) {
  die('No font was found in path \'' . CAPTCHA_FONT_PATH . '\'!');
}
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
immotool_functions::put_session_value(CAPTCHA_VARIABLE, $string);
immotool_functions::shutdown($setup);

// Captcha ausgeben
ob_clean();
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
exit();
