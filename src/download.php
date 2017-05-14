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
 * Website-Export, Auslieferung eines PDF-Exposés als Download.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

// Initialisierung der Skript-Umgebung
define('IN_WEBSITE', 1);
if (!defined('IMMOTOOL_BASE_PATH'))
  define('IMMOTOOL_BASE_PATH', '');
ob_start();
require_once(IMMOTOOL_BASE_PATH . 'config.php');
require_once(IMMOTOOL_BASE_PATH . 'include/functions.php');
ob_end_clean();

// Initialisierung des Exposé-Downloads
$setup = new immotool_setup_index();
if (is_callable(array('immotool_myconfig', 'load_config_default')))
  immotool_myconfig::load_config_default($setup);

// angeforderte Sprache ermitteln
$lang = (isset($_REQUEST['lang']) && is_string($_REQUEST['lang'])) ?
    basename(trim($_REQUEST['lang'])) : $setup->DefaultLanguage;
if (is_null($lang) || $lang == '') {
  if (!headers_sent()) {
    // 400-Fehlercode zurückliefern,
    // wenn die übermittelte Sprache ungültig ist
    header('HTTP/1.0 400 Bad Request');
  }
  echo 'No language provided!';
  exit;
}

// angeforderte Objekt-ID ermitteln
$objectId = (isset($_REQUEST['id']) && is_string($_REQUEST['id'])) ?
    basename(trim($_REQUEST['id'])) : null;
if (is_null($objectId) || $objectId == '') {
  if (!headers_sent()) {
    // 400-Fehlercode zurückliefern,
    // wenn die übermittelte Objekt-ID ungültig ist
    header('HTTP/1.0 400 Bad Request');
  }
  echo 'No expose ID provided!';
  exit;
}

// angefordertes Objekt ermitteln
$object = immotool_functions::get_object($objectId);
if (!is_array($object)) {
  if (!headers_sent()) {
    // 404-Fehlercode zurückliefern,
    // wenn keine Immobilie zur übermittelten Objekt-ID gefunden wurde
    header('HTTP/1.0 404 Not Found');
  }
  echo 'Can\'t find the requested object!';
  exit;
}

// Pfad zur auszuliefernden PDF-Datei ermitteln
$path = 'data/' . $objectId . '/' . $objectId . '_' . $lang . '.pdf';
$fullPath = IMMOTOOL_BASE_PATH . $path;
if (!is_file($fullPath)) {
  if (!headers_sent()) {
    // 404-Fehlercode zurückliefern,
    // wenn keine PDF-Exposé zur Immobilie gefunden wurde
    header('HTTP/1.0 404 Not Found');
  }
  echo 'Can\'t find pdf for the requested object!';
  exit;
}

// Dateiname der zu sendenden PDF-Datei ermitteln
$downloadFileName = (isset($object['nr']) && is_string($object['nr'])) ?
    trim($object['nr']) : null;
if (is_null($downloadFileName) || strlen($downloadFileName) < 1)
  $downloadFileName = $objectId;
$downloadFileName = preg_replace('/[^a-zA-Z0-9_\\-\\.]/', '', $downloadFileName) . '-' . $lang . '.pdf';
//$downloadFileName = str_replace( array('"', '\''), array('', ''), $downloadFileName ) . '-' . $lang . '.pdf';
// Datei ausliefern
$fd = fopen($fullPath, 'r');
if ($fd == null || $fd == false) {
  if (!headers_sent()) {
    // 500-Fehlercode zurückliefern,
    // wenn der Lese-Zugriff auf die Datei nicht möglich ist
    header('HTTP/1.0 500 Internal Server Error');
  }
  echo 'Can\'t open the requested file!';
  exit;
}
header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="' . $downloadFileName . '"');
header('Content-length: ' . filesize($fullPath));
header('Cache-control: private');
while (!feof($fd)) {
  $buffer = fread($fd, 2048);
  echo $buffer;
}
fclose($fd);
exit;
