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
 * Website-Export, Skalierung & Beschneidung der Objekt-Bilder auf eine vorgegebene Größe.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

// Initialisierung der Skript-Umgebung
define('IN_WEBSITE', 1);
if (!defined('IMMOTOOL_BASE_PATH'))
  define('IMMOTOOL_BASE_PATH', '');
if (!extension_loaded('gd')) {
  if (!headers_sent()) {
    // 500-Fehlercode zurückliefern,
    // wenn das GD-PHP-Modul nicht verfügbar ist
    header('HTTP/1.0 500 Internal Server Error');
  }
  echo 'It seems like GD is not installed!';
  return;
}
ob_start();
require_once(IMMOTOOL_BASE_PATH . 'config.php');
require_once(IMMOTOOL_BASE_PATH . 'include/functions.php');
ob_end_clean();

// Initialisierung des Immobilien-Bildes
$setup = new immotool_setup();
if (is_callable(array('immotool_myconfig', 'load_config_default')))
  immotool_myconfig::load_config_default($setup);

// angeforderte Objekt-ID ermitteln
$objectId = (isset($_REQUEST['id']) && is_string($_REQUEST['id'])) ?
    trim(basename($_REQUEST['id'])) : null;
if (is_null($objectId) || strlen($objectId) < 1) {
  if (!headers_sent()) {
    // 400-Fehlercode zurückliefern,
    // wenn keine gültige Objekt-ID übermittelt wurde
    header('HTTP/1.0 400 Bad Request');
  }
  echo 'No id defined!';
  exit;
}

// angefordertes Bild ermitteln
$imgName = (isset($_REQUEST['img']) && is_string($_REQUEST['img'])) ?
    trim(basename($_REQUEST['img'])) : null;
if (is_null($imgName) || strlen($imgName) < 1) {
  if (!headers_sent()) {
    // 400-Fehlercode zurückliefern,
    // wenn keine gültiger Bild-Name übermittelt wurde
    header('HTTP/1.0 400 Bad Request');
  }
  echo 'No img defined!';
  exit;
}

// Pfad des Bildes auf dem Server ermitteln
$imgPath = IMMOTOOL_BASE_PATH . 'data/' . $objectId . '/' . $imgName;
if (!is_file($imgPath)) {
  if (!headers_sent()) {
    // 404-Fehlercode zurückliefern,
    // wenn das angeforderte Bild nicht auf dem Server existiert
    header('HTTP/1.0 404 Not Found');
  }
  echo 'Image file not found!';
  exit;
}

// Maße ermitteln
$x = 100;
$y = 75;
if (isset($_REQUEST['x']) && is_numeric($_REQUEST['x']))
  $x = (int) $_REQUEST['x'];
if (isset($_REQUEST['y']) && is_numeric($_REQUEST['y']))
  $y = (int) $_REQUEST['y'];

// Hintergrund ermitteln
$bg = (isset($_REQUEST['bg']) && is_string($_REQUEST['bg'])) ? $_REQUEST['bg'] : 'ffffff';

// Prüfen, ob das skalierte Bild bereits im Cache-Verzeichnis vorhanden ist
$cacheFile = IMMOTOOL_BASE_PATH . 'cache/img.' . md5($imgPath . '-' . $x . '-' . $y . '-' . $bg) . '.jpg';
if (is_file($cacheFile)) {

  // Cache-Datei nach Ablauf der Vorhaltezeit ggf. löschen
  if (!immotool_functions::check_file_age($cacheFile, $setup->CacheLifeTime)) {
    @unlink($cacheFile);
  }

  // Cache-Datei ausliefern
  else {
    $cacheImg = file_get_contents($cacheFile);
    if ($cacheImg === false) {
      if (!headers_sent()) {
        // 500-Fehlercode zurückliefern,
        // wenn die Cache-Datei nicht geladen werden konnte
        header('HTTP/1.0 500 Internal Server Error');
      }
      echo 'Can\'t load image from cache!';
      exit;
    }
    header('Content-type: image/jpeg');
    echo $cacheImg;
    return;
  }
}

// Skalierung / Verschiebung
$info = getimagesize($imgPath);
$src_x = $info[0];
$src_y = $info[1];
$type = $info[2]; //3 (Typ = PNG) / 2 (Typ = JPG) / 1 (Typ = GIF)
$dest_x = $x;
$dest_y = $y;

$src_ratio = $src_x / $src_y;
$dest_ratio = $dest_x / $dest_y;

// zu hoch
if ($src_ratio <= $dest_ratio) {
  $dest_y = $src_y * $dest_x / $src_x;
  $move_x = 0;
  $move_y = ($y - $dest_y) / 2;
}
// zu breit
else {
  $dest_x = $src_x * $dest_y / $src_y;
  $move_x = ($x - $dest_x) / 2;
  $move_y = 0;
}

//die( "SRC [x=$src_x, y=$src_y], DEST [x=$dest_x, y=$dest_y]" );

$srcImg = null;
if ($type == 1) {
  $srcImg = imagecreatefromgif($imgPath);
}
else if ($type == 2) {
  $srcImg = imagecreatefromjpeg($imgPath);
}
else if ($type == 3) {
  $srcImg = imagecreatefrompng($imgPath);
}
else {
  if (!headers_sent()) {
    // 400-Fehlercode zurückliefern,
    // wenn keine gültiges Bild angefordert wurde
    header('HTTP/1.0 400 Bad Request');
  }
  echo 'Invalid image type!';
  exit;
}

$scaledImg = imagecreatetruecolor($x, $y);
$bgRgb = immotool_functions::get_rgb_from_hex($bg);
$bgColor = imagecolorallocate($scaledImg, $bgRgb['r'], $bgRgb['g'], $bgRgb['b']);
imagefilledrectangle($scaledImg, 0, 0, $x, $y, $bgColor);
imagecopyresampled($scaledImg, $srcImg, $move_x, $move_y, 0, 0, $dest_x, $dest_y, $src_x, $src_y);

// Verkleinertes Bild im Cache-Verzeichnis speichern
imagejpeg($scaledImg, $cacheFile, 90);

// Verkleinertes Bild ausgeben
header('Content-type: image/jpeg');
imagejpeg($scaledImg, null, 90);
imagedestroy($scaledImg);
exit;
