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
 * Website-Export, Darstellung des Trovit-Feeds.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2013, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

// Initialisierung der Skript-Umgebung
$startup = microtime();
define('IN_WEBSITE', 1);
if (!defined('IMMOTOOL_BASE_PATH')) {
  define('IMMOTOOL_BASE_PATH', '');
}
require_once(IMMOTOOL_BASE_PATH . 'config.php');
require_once(IMMOTOOL_BASE_PATH . 'private.php');
require_once(IMMOTOOL_BASE_PATH . 'include/functions.php');
require_once(IMMOTOOL_BASE_PATH . 'data/language.php');
$debugMode = isset($_REQUEST['debug']) && $_REQUEST['debug'] == '1';

// Konfiguration ermitteln
$setup = new immotool_setup_feeds();
if (is_callable(array('immotool_myconfig', 'load_config_feeds'))) {
  immotool_myconfig::load_config_feeds($setup);
}
immotool_functions::init($setup);
if (!$setup->PublishTrovitFeed) {
  if (!headers_sent()) {
    // 500-Fehlercode zurückliefern,
    // wenn der Feed in der Konfiguration deaktiviert wurde
    header('HTTP/1.0 500 Internal Server Error');
  }
  echo 'Trovit feed is disabled!';
  exit;
}

// Übersetzungen ermitteln
$translations = null;
$lang = (isset($_REQUEST[IMMOTOOL_PARAM_LANG])) ? $_REQUEST[IMMOTOOL_PARAM_LANG] : $setup->DefaultLanguage;
$lang = immotool_functions::init_language($lang, $setup->DefaultLanguage, $translations);
if (!is_array($translations)) {
  if (!headers_sent()) {
    // 500-Fehlercode zurückliefern,
    // wenn die Übersetzungstexte nicht geladen werden konnten
    header('HTTP/1.0 500 Internal Server Error');
  }
  echo 'Can\'t load translations!';
  exit;
}

// Header senden
if ($debugMode) {
  header('Content-Type: text/html; charset=utf-8');
}
else {
  header('Content-Type: text/xml; charset=utf-8');
}

// Cache-Datei des Feeds
$feedFile = IMMOTOOL_BASE_PATH . 'cache/feed.trovit_' . $lang . '.xml';
if (!$debugMode && is_file($feedFile)) {
  if (!immotool_functions::check_file_age($feedFile, $setup->CacheLifeTime)) {
    // abgelaufene Cache-Datei entfernen
    unlink($feedFile);
  }
  else {
    // Feed aus Cache-Datei erzeugen
    $feed = immotool_functions::read_file($feedFile);
    echo $feed;
    return;
  }
}

// Feed erzeugen
$feed = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
$feed .= '<trovit>' . "\n";

if ($debugMode) {
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
  echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">';
  echo '  <head>';
  echo '    <title>Trovit-Feed Debugger</title>';
  echo '    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />';
  echo '    <meta http-equiv="Content-Language" content="de" />';
  echo '    <meta http-equiv="pragma" content="no-cache" />';
  echo '    <meta http-equiv="cache-control" content="no-cache" />';
  echo '    <meta http-equiv="expires" content="0" />';
  echo '    <meta http-equiv="imagetoolbar" content="no" />';
  echo '    <meta name="MSSmartTagsPreventParsing" content="true" />';
  echo '    <meta name="generator" content="OpenEstate-ImmoTool" />';
  echo '    <link rel="stylesheet" href="style.php" />';
  echo '    <meta name="robots" content="noindex,follow" />';
  echo '  </head>';
  echo '  <body>';
  echo '  <h2>Trovit-Feed Debugger</h2>';
}

foreach (immotool_functions::list_available_objects() as $id) {
  $object = immotool_functions::get_object($id);
  if ($debugMode)
    echo '<h3 style="margin-top:1em;margin-bottom:0;"><a href="expose.php?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $id . '">property #' . $id . '</a></h3>';
  if (!is_array($object)) {
    if ($debugMode)
      echo '&gt; NOT FOUND<br/>';
    continue;
  }

  $objectTexts = immotool_functions::get_text($id);
  if (!is_array($objectTexts))
    $objectTexts = array();

  // nur Wohnimmobilien exportieren
  //if (array_search('main_wohnen',$object['type_path'])===false) {
  //  if ($debugMode) echo '&gt; UNSUPPORTED TYPE: '.$object['type'].'<br/>';
  //  continue;
  //}
  // Exposé-URL ermitteln
  $objectUrl = immotool_functions::get_expose_url($id, $lang, $setup->ExposeUrlTemplate, true);

  // Immobilienart ermitteln
  $objectType = (is_string($translations['openestate']['types'][$object['type']])) ?
      $translations['openestate']['types'][$object['type']] : $object['type'];

  // Datum ermitteln
  $objectStamp = immotool_functions::get_object_stamp($id);
  if ($objectStamp == null)
    $objectStamp = 0;
  $objectDate = ($objectStamp > 0) ? date('d/m/Y', $objectStamp) : date('d/m/Y');
  $objectTime = ($objectStamp > 0) ? date('H:i', $objectStamp) : date('H:i');

  // Inhalt ermitteln
  $objectTitle = (isset($object['title'][$lang])) ?
      $object['title'][$lang] : '';
  $objectContent = $objectTitle;
  foreach ($objectTexts as $key => $text) {
    if ($key == 'id') {
      continue;
    }
    $value = immotool_functions::write_attribute_value('freitexte', $key, $text, $translations, $lang);
    if (!is_null($value) && trim($value) != '') {
      $objectContent .= '<hr/>' . trim($value);
    }
  }

  // Vermarktungsart & Preis ermitteln
  $objectAction = '';
  $objectPrice = '0';
  $objectPriceAttribs = '';
  $objectPriceHidden = isset($object['hidden_price']) && $object['hidden_price'] === true;
  if ($object['action'] == 'kauf') {
    $objectAction = 'For Sale';
    $objectPrice = (!$objectPriceHidden && isset($object['attributes']['preise']['kaufpreis']['value'])) ?
        $object['attributes']['preise']['kaufpreis']['value'] : null;
  }
  else if ($object['action'] == 'miete') {
    $objectAction = 'For Rent';
    $objectPrice = (!$objectPriceHidden && isset($object['attributes']['preise']['kaltmiete']['value'])) ?
        $object['attributes']['preise']['kaltmiete']['value'] : null;
    $mietePro = (isset($object['attributes']['preise']['miete_pro'])) ?
        $object['attributes']['preise']['miete_pro']['value'] : null;
    if ($mietePro == 'WOCHE')
      $objectPriceAttribs = (!$objectPriceHidden) ? ' period="weekly"' : '';
    else
      $objectPriceAttribs = (!$objectPriceHidden) ? ' period="monthly"' : '';
  }
  else if ($object['action'] == 'waz') {
    $objectAction = 'For Rent';
    $objectPrice = (!$objectPriceHidden && isset($object['attributes']['preise']['pauschalmiete']['value'])) ?
        $object['attributes']['preise']['pauschalmiete']['value'] : null;
    $mietePro = (isset($object['attributes']['preise']['miete_pro']['value'])) ?
        $object['attributes']['preise']['miete_pro']['value'] : null;
    if ($mietePro == 'WOCHE')
      $objectPriceAttribs = (!$objectPriceHidden) ? ' period="weekly"' : '';
    else
      $objectPriceAttribs = (!$objectPriceHidden) ? ' period="monthly"' : '';
  }
  else if ($object['action'] == 'pacht') {
    $objectAction = 'For Rent';
    $objectPrice = (!$objectPriceHidden && isset($object['attributes']['preise']['pacht']['value'])) ?
        $object['attributes']['preise']['pacht']['value'] : null;
    $objectPriceAttribs = (!$objectPriceHidden) ? ' period="monthly"' : '';
  }
  else if ($object['action'] == 'erbpacht') {
    $objectAction = 'For Rent';
    $objectPrice = (!$objectPriceHidden && isset($object['attributes']['preise']['pacht']['value'])) ?
        $object['attributes']['preise']['pacht']['value'] : null;
    $objectPriceAttribs = (!$objectPriceHidden) ? ' period="monthly"' : '';
  }
  else {
    if ($debugMode)
      echo '&gt; UNSUPPORTED ACTION: ' . $object['action'] . '<br/>';
    continue;
  }

  // Preis umwandeln
  $objectPrice = ($objectPrice != null) ? intval($objectPrice) : 0;
  if ($objectPrice < 0)
    $objectPrice = 0;

  // Fläche ermitteln
  $objectArea = null;
  foreach (array('gesamtflaeche', 'wohnflaeche', 'grundstuecksflaeche', 'lagerflaeche', 'nutzflaeche') as $area) {
    if (!isset($object['attributes']['flaechen'][$area]['value']))
      continue;
    $value = $object['attributes']['flaechen'][$area]['value'];
    if (is_numeric($value)) {
      $objectArea = intval($objectArea);
      if ($objectArea <= 0)
        $objectArea = null;
      else
        break;
    }
  }

  // Grundstücksfläche
  $objectPlotArea = (isset($object['attributes']['flaechen']['grundstuecksflaeche']['value'])) ?
      $object['attributes']['flaechen']['grundstuecksflaeche']['value'] : null;
  if (!is_numeric($objectPlotArea))
    $objectPlotArea = null;
  else
    $objectPlotArea = intval($objectPlotArea);

  // Anzahl Zimmer ermitteln
  $objectRooms = (isset($object['attributes']['flaechen']['anz_zimmer']['value'])) ?
      $object['attributes']['flaechen']['anz_zimmer']['value'] : null;
  if (!is_numeric($objectRooms))
    $objectRooms = 0;
  else
    $objectRooms = intval($objectRooms);

  // Anzahl Badezimmer ermitteln
  $objectBathrooms = (isset($object['attributes']['flaechen']['anz_badezimmer']['value'])) ?
      $object['attributes']['flaechen']['anz_badezimmer']['value'] : null;
  if (!is_numeric($objectBathrooms))
    $objectBathrooms = 0;
  else
    $objectBathrooms = intval($objectBathrooms);

  // Anzahl Zimmer ermitteln
  $objectFloorNumber = (isset($object['attributes']['ausstattung']['etage_gesamt']['value'])) ?
      $object['attributes']['ausstattung']['etage_gesamt']['value'] : null;
  if (!is_numeric($objectFloorNumber))
    $objectFloorNumber = 0;
  else
    $objectFloorNumber = intval($objectFloorNumber);

  // Stellplatz ermitteln
  $arten = (isset($object['attributes']['flaechen']['stellplatzart']['value'])) ?
      $object['attributes']['flaechen']['stellplatzart']['value'] : null;
  $objectParking = (is_array($arten) && count($arten) > 0) ? 1 : 0;

  // Möblierung ermitteln
  $objectIsFurnished = null;
  $moebliert = (isset($object['attributes']['ausstattung']['moebliert']['value'])) ?
      $object['attributes']['ausstattung']['moebliert']['value'] : null;
  if ($moebliert == 'JA' || $moebliert == 'TEIL')
    $objectIsFurnished = 1;
  else if ($moebliert == 'NEIN')
    $objectIsFurnished = 0;

  // Zustand ermitteln
  //$objectConditition = (isset($object['attributes']['zustand']['zustand'][$lang]))?
  //        $object['attributes']['zustand']['zustand'][$lang]: null;
  // Baujahr ermitteln
  $objectYear = (isset($object['attributes']['zustand']['baujahr'][$lang])) ?
      $object['attributes']['zustand']['baujahr'][$lang] : null;

  // Anschrift
  $objectAdress = null;
  if (isset($object['adress']['street']) && is_string($object['adress']['street'])) {
    $objectAdress = trim($object['adress']['street']);
    if (isset($object['adress']['street_nr']) && is_string($object['adress']['street_nr'])) {
      $objectAdress .= ' ' . trim($object['adress']['street_nr']);
    }
  }

  // Ort & Ortsteil
  $objectCity = (isset($object['adress']['city'])) ?
      $object['adress']['city'] : null;
  $objectCityPart = (isset($object['adress']['city_part'])) ?
      $object['adress']['city_part'] : null;
  $objectPostal = (isset($object['adress']['postal'])) ?
      $object['adress']['postal'] : null;
  $objectRegion = (isset($object['adress']['region'])) ?
      $object['adress']['region'] : null;
  $objectLatitude = (isset($object['adress']['latitude'])) ?
      $object['adress']['latitude'] : null;
  $objectLongitude = (isset($object['adress']['longitude'])) ?
      $object['adress']['longitude'] : null;

  // Immobilie in den Feed eintragen
  $feed .= '  <ad>' . "\n";
  $feed .= '    <id><![CDATA[' . $object['id'] . ']]></id>' . "\n";
  $feed .= '    <url><![CDATA[' . $objectUrl . ']]></url>' . "\n";
  $feed .= '    <title><![CDATA[' . $objectTitle . ']]></title>' . "\n";
  $feed .= '    <type><![CDATA[' . $objectAction . ']]></type>' . "\n";
  $feed .= '    <date><![CDATA[' . $objectDate . ']]></date>' . "\n";
  $feed .= '    <time><![CDATA[' . $objectTime . ']]></time>' . "\n";
  $feed .= '    <agency><![CDATA[' . $translations['labels']['title'] . ']]></agency>' . "\n";
  $feed .= '    <content><![CDATA[' . $objectContent . ']]></content>' . "\n";
  if (is_numeric($objectPrice) && $objectPrice >= 0) {
    $feed .= '    <price' . $objectPriceAttribs . '><![CDATA[' . $objectPrice . ']]></price>' . "\n";
  }
  $feed .= '    <property_type><![CDATA[' . $objectType . ']]></property_type>' . "\n";
  if (is_numeric($objectArea) && $objectArea > 0)
    $feed .= '    <floor_area unit="meters"><![CDATA[' . $objectArea . ']]></floor_area>' . "\n";
  if (is_numeric($objectRooms) && $objectRooms > 0)
    $feed .= '    <rooms><![CDATA[' . $objectRooms . ']]></rooms>' . "\n";
  if (is_numeric($objectBathrooms) && $objectBathrooms > 0)
    $feed .= '    <bathrooms><![CDATA[' . $objectBathrooms . ']]></bathrooms>' . "\n";
  if (!is_null($objectParking))
    $feed .= '    <parking><![CDATA[' . $objectParking . ']]></parking>' . "\n";
  if (!is_null($objectAdress))
    $feed .= '    <address><![CDATA[' . $objectAdress . ']]></address>' . "\n";
  if (!is_null($objectCity))
    $feed .= '    <city><![CDATA[' . $objectCity . ']]></city>' . "\n";
  if (!is_null($objectCityPart))
    $feed .= '    <city_area><![CDATA[' . $objectCityPart . ']]></city_area>' . "\n";
  if (!is_null($objectPostal))
    $feed .= '    <postcode><![CDATA[' . $objectPostal . ']]></postcode>' . "\n";
  if (!is_null($objectRegion))
    $feed .= '    <region><![CDATA[' . $objectRegion . ']]></region>' . "\n";
  if (!is_null($objectLatitude))
    $feed .= '    <latitude><![CDATA[' . $objectLatitude . ']]></latitude>' . "\n";
  if (!is_null($objectLongitude))
    $feed .= '    <longitude><![CDATA[' . $objectLongitude . ']]></longitude>' . "\n";

  if (isset($object['images']) && is_array($object['images'])) {
    $feed .= '    <pictures>' . "\n";
    foreach ($object['images'] as $img) {
      $imgUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '') ? 'https://' : 'http://';
      $imgUrl .= $_SERVER['SERVER_NAME'];
      $imgUrl .= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
      $imgUrl .= '/data/' . $object['id'] . '/' . $img['name'];
      $imgTitle = (isset($img['title'][$lang]) && is_string($img['title'][$lang])) ?
          $img['title'][$lang] : '';
      $feed .= '      <picture>' . "\n";
      $feed .= '        <picture_url><![CDATA[' . $imgUrl . ']]></picture_url>' . "\n";
      $feed .= '        <picture_title><![CDATA[' . $imgTitle . ']]></picture_title>' . "\n";
      $feed .= '      </picture>' . "\n";
    }
    $feed .= '    </pictures>' . "\n";
  }

  //$feed .= '    <virtual_tour><![CDATA['..']]></virtual_tour>' . "\n";
  //$feed .= '    <expiration_date><![CDATA['..']]></expiration_date>' . "\n";
  if (is_numeric($objectPlotArea) && $objectPlotArea > 0)
    $feed .= '    <plot_area><![CDATA[' . $objectPlotArea . ']]></plot_area>' . "\n";
  if (is_numeric($objectFloorNumber) && $objectFloorNumber > 0)
    $feed .= '    <floor_number><![CDATA[' . $objectFloorNumber . ']]></floor_number>' . "\n";
  //$feed .= '    <orientation><![CDATA['..']]></orientation>' . "\n";
  //$feed .= '    <foreclosure><![CDATA['..']]></foreclosure>' . "\n";
  if (is_numeric($objectIsFurnished))
    $feed .= '    <is_furnished><![CDATA[' . $objectIsFurnished . ']]></is_furnished>' . "\n";
  //$feed .= '    <is_new><![CDATA['..']]></is_new>' . "\n";
  //if (is_string($objectConditition))
  //$feed .= '    <s_condition><![CDATA['.$objectConditition.']]></s_condition>' . "\n";
  if (is_numeric($objectYear))
    $feed .= '    <year><![CDATA[' . $objectYear . ']]></year>' . "\n";
  $feed .= '  </ad>' . "\n";

  if ($debugMode)
    echo '&gt; OK<br/>';
}
$feed .= '</trovit>';

// Debug-Ausgabe des Feeds
if ($debugMode) {
  echo '<h2>Generated XML</h2>';
  echo '<textarea style="width:95%; height:30em; margin-bottom:1em;" readonly="readonly">' . htmlspecialchars($feed) . '</textarea>';
  echo '</body></html>';
}

// normale Ausgabe des Feeds
else {

  // Feed cachen
  $fh = @fopen($feedFile, 'w');
  if (!$fh) {
    if (!headers_sent()) {
      // 500-Fehlercode zurückliefern,
      // wenn die Feed-Datei nicht geschrieben werden kann
      header('HTTP/1.0 500 Internal Server Error');
    }
    echo 'Can\'t write feed to: ' . $feedFile;
    return;
  }
  fwrite($fh, $feed);
  fclose($fh);

  // Feed ausgeben
  echo $feed;
}
immotool_functions::shutdown($setup);
