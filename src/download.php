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
 * Website-Export, Auslieferung eines PDF-Exposés als Download.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2012, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

// Initialisierung
define('IN_WEBSITE', 1);
if (!defined('IMMOTOOL_BASE_PATH'))
  define('IMMOTOOL_BASE_PATH', '');
include(IMMOTOOL_BASE_PATH . 'config.php');
include(IMMOTOOL_BASE_PATH . 'private.php');
include(IMMOTOOL_BASE_PATH . 'include/functions.php');

// Initialisierungen
$setup = new immotool_setup_index();
immotool_functions::init($setup, 'load_config_default');

// angeforderte Sprache ermitteln
$lang = (isset($_REQUEST['lang']) && is_string($_REQUEST['lang'])) ?
    basename(trim($_REQUEST['lang'])) : $setup->DefaultLanguage;
if (is_null($lang) || $lang == '') {
  echo 'No language provided!';
  exit;
}

// angeforderte Objekt-ID ermitteln
$objectId = (isset($_REQUEST['id']) && is_string($_REQUEST['id'])) ?
    basename(trim($_REQUEST['id'])) : null;
if (is_null($objectId) || $objectId == '') {
  echo 'No expose ID provided!';
  exit;
}

// angefordertes Objekt ermitteln
$object = immotool_functions::get_object($objectId);
if (!is_array($object)) {
  echo 'Can\t find an object for ID #' . strip_tags($objectId) . '!';
  exit;
}

// Pfad zur auszuliefernden PDF-Datei ermitteln
$path = 'data/' . $objectId . '/' . $objectId . '_' . $lang . '.pdf';
$fullPath = IMMOTOOL_BASE_PATH . $path;
if (is_dir($fullPath)) {
  echo 'Can\t find pdf for object #' . $objectId . ' in ' . $lang . '!';
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
  echo 'Can\t read pdf for object #' . $objectId . ' in ' . $lang . '!';
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
