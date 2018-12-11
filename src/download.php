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

/**
 * Website-Export, Auslieferung eines PDF-Exposés als Download.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

// Initialisierung
ob_start();
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/include/functions.php');
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
$fullPath = immotool_functions::get_path($path);
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
