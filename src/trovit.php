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
 * Website-Export, Darstellung des Trovit-XML-Feeds.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

// Initialisierung
$startup = microtime();
define('IN_WEBSITE', 1);
if (!defined('IMMOTOOL_BASE_PATH'))
  define('IMMOTOOL_BASE_PATH', '');
include(IMMOTOOL_BASE_PATH . 'config.php');
include(IMMOTOOL_BASE_PATH . 'include/functions.php');
include(IMMOTOOL_BASE_PATH . 'data/language.php');
session_start();
header("Content-Type: text/xml; charset=utf-8");

// Konfiguration ermitteln
$setup = new immotool_setup_trovit();
if (is_callable(array('immotool_myconfig', 'load_config_trovit')))
  immotool_myconfig::load_config_trovit($setup);
immotool_functions::init($setup);
if (!$setup->PublishFeed)
  die('Trovit-XML-Feed is disabled!');

// Übersetzungen ermitteln
$translations = null;
$lang = immotool_functions::init_language($_REQUEST[IMMOTOOL_PARAM_LANG], $setup->DefaultLanguage, $translations);
if (!is_array($translations))
  die('Can\'t load translations!');

// Cache-Datei des Trovit-Feeds
$feedFile = IMMOTOOL_BASE_PATH . 'cache/trovit.' . $lang . '.xml';
if (is_file($feedFile)) {
  $feed = immotool_functions::read_file($feedFile);
  echo $feed;
  return;
}

// Trovit-Feed erzeugen
$feed = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
$feed .= '<trovit>' . "\n";

foreach (immotool_functions::list_available_objects() as $id) {
  $object = immotool_functions::get_object($id);
  if (!is_array($object))
    continue;

  // nur Wohnimmobilien exportieren
  if (array_search('main_wohnen', $object['type_path']) === false)
    continue;

  $objectUrl = '';

  // Exposé-URL aus Vorlage ermitteln
  if (is_string($setup->ExposeUrlTemplate) && strlen($setup->ExposeUrlTemplate) > 0) {
    $replacement = array(
      '{ID}' => $object['id'],
      '{LANG}' => $lang,
    );
    $objectUrl = str_replace(array_keys($replacement), array_values($replacement), $setup->ExposeUrlTemplate);
  }

  // Exposé-URL automatisch ermitteln
  else {
    $objectUrl = ($_SERVER['HTTPS'] != '') ? 'https://' : 'http://';
    $objectUrl .= $_SERVER['SERVER_NAME'];
    $objectUrl .= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
    $objectUrl .= '/expose.php';
    $objectUrl .= '?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'];
    $objectUrl .= '&' . IMMOTOOL_PARAM_LANG . '=' . $lang;
  }

  // Immobilienart ermitteln
  $objectType = (is_string($translations['openestate']['types'][$object['type']])) ?
      $translations['openestate']['types'][$object['type']] : $object['type'];

  // Inhalt ermitteln
  $objectContent = $object['title'][$lang];
  foreach (immotool_functions::get_text($id) as $key => $text) {
    if ($key == 'id')
      continue;
    if (!is_string($text[$lang]) || trim($text[$lang]) == '')
      continue;
    $objectContent .= '<hr/>' . trim($text[$lang]);
  }

  // Vermarktungsart & Preis ermitteln
  $objectAction = '';
  $objectPrice = '0';
  $objectPriceAttribs = '';
  if ($object['action'] == 'kauf') {
    $objectPrice = $object['attributes']['preise']['kaufpreis']['value'];
    if ($lang == 'de')
      $objectAction = 'Zum Verkauf';
    else
      $objectAction = 'For Sale';
  }
  else if ($object['action'] == 'miete') {
    $objectPrice = $object['attributes']['preise']['kaltmiete']['value'];
    $mietePro = $object['attributes']['preise']['miete_pro']['value'];
    if ($lang == 'de') {
      $objectAction = 'Zur Miete';
      if ($mietePro == 'WOCHE')
        $objectPriceAttribs = ' period="wöchentlich"';
      else
        $objectPriceAttribs = ' period="monatlich"';
    }
    else {
      $objectAction = 'For Rent';
      if ($mietePro == 'WOCHE')
        $objectPriceAttribs = ' period="weekly"';
      else
        $objectPriceAttribs = ' period="monthly"';
    }
  }
  else if ($object['action'] == 'waz') {
    $objectPrice = $object['attributes']['preise']['pauschalmiete']['value'];
    $mietePro = $object['attributes']['preise']['miete_pro']['value'];
    if ($lang == 'de') {
      $objectAction = 'Zur Miete';
      if ($mietePro == 'WOCHE')
        $objectPriceAttribs = ' period="wöchentlich"';
      else
        $objectPriceAttribs = ' period="monatlich"';
    }
    else {
      $objectAction = 'For Rent';
      if ($mietePro == 'WOCHE')
        $objectPriceAttribs = ' period="weekly"';
      else
        $objectPriceAttribs = ' period="monthly"';
    }
  }
  else if ($object['action'] == 'pacht') {
    $objectPrice = $object['attributes']['preise']['pacht']['value'];
    if ($lang == 'de') {
      $objectAction = 'Zur Miete';
      $objectPriceAttribs = ' period="monatlich"';
    }
    else {
      $objectAction = 'For Rent';
      $objectPriceAttribs = ' period="monthly"';
    }
  }
  else if ($object['action'] == 'erbpacht') {
    $objectPrice = $object['attributes']['preise']['pacht']['value'];
    if ($lang == 'de') {
      $objectAction = 'Zur Miete';
      $objectPriceAttribs = ' period="monatlich"';
    }
    else {
      $objectAction = 'For Rent';
      $objectPriceAttribs = ' period="monthly"';
    }
  }
  else {
    continue;
  }

  // Preis umwandeln
  $objectPrice = intval($objectPrice);
  if ($objectPrice <= 0)
    continue;

  // Fläche ermitteln
  $objectArea = null;
  foreach (array('gesamtflaeche', 'wohnflaeche', 'grundstuecksflaeche', 'lagerflaeche', 'nutzflaeche') as $area) {
    $value = $object['attributes']['flaechen'][$field]['value'];
    if (is_numeric($value)) {
      $objectArea = intval($objectArea);
      if ($objectArea <= 0)
        $objectArea = null;
      else
        break;
    }
  }

  // Grundstücksfläche
  $objectPlotArea = $object['attributes']['flaechen']['grundstuecksflaeche']['value'];
  if (!is_numeric($objectPlotArea))
    $objectPlotArea = null;
  else
    $objectPlotArea = intval($objectPlotArea);

  // Anzahl Zimmer ermitteln
  $objectRooms = $object['attributes']['flaechen']['anz_zimmer']['value'];
  if (!is_numeric($objectRooms))
    $objectRooms = 0;
  else
    $objectRooms = intval($objectRooms);

  // Anzahl Badezimmer ermitteln
  $objectBathrooms = $object['attributes']['flaechen']['anz_badezimmer']['value'];
  if (!is_numeric($objectBathrooms))
    $objectBathrooms = 0;
  else
    $objectBathrooms = intval($objectBathrooms);

  // Anzahl Zimmer ermitteln
  $objectFloorNumber = $object['attributes']['ausstattung']['etage_gesant']['value'];
  if (!is_numeric($objectFloorNumber))
    $objectFloorNumber = 0;
  else
    $objectFloorNumber = intval($objectFloorNumber);

  // Stellplatz ermitteln
  $arten = $object['attributes']['flaechen']['stellplatzart']['value'];
  $objectParking = (is_array($arten) && count($arten) > 0) ? 1 : 0;

  // Möblierung ermitteln
  $objectIsFurnished = null;
  $moebliert = $object['attributes']['ausstattung']['moebliert']['value'];
  if ($moebliert == 'JA' || $moebliert == 'TEIL')
    $objectIsFurnished = 1;
  else if ($moebliert == 'NEIN')
    $objectIsFurnished = 0;

  // Zustand ermitteln
  $objectConditition = $object['attributes']['zustand']['zustand'][$lang];

  // Baujahr ermitteln
  $objectYear = $object['attributes']['zustand']['baujahr'][$lang];

  // Anschrift
  $objectAdress = null;
  if (is_string($object['adress']['street'])) {
    $objectAdress = trim($object['adress']['street']);
    if (is_string($object['adress']['street_nr'])) {
      $objectAdress .= ' ' . trim($object['adress']['street_nr']);
    }
  }

  // Ort & Ortsteil
  $objectCity = $object['adress']['city'];
  $objectCityPart = $object['adress']['city_part'];
  $objectPostal = $object['adress']['postal'];
  $objectRegion = $object['adress']['region'];
  $objectLatitude = $object['adress']['latitude'];
  $objectLongitude = $object['adress']['longitude'];


  // Immobilie in den Feed eintragen
  $feed .= '  <ad>' . "\n";
  $feed .= '    <id><![CDATA[' . $object['id'] . ']]></id>' . "\n";
  $feed .= '    <url><![CDATA[' . $objectUrl . ']]></url>' . "\n";
  $feed .= '    <title><![CDATA[' . $object['title'][$lang] . ']]></title>' . "\n";
  $feed .= '    <type><![CDATA[' . $objectAction . ']]></type>' . "\n";
  $feed .= '    <agency><![CDATA[' . $translations['labels']['title'] . ']]></agency>' . "\n";
  $feed .= '    <content><![CDATA[' . $objectContent . ']]></content>' . "\n";
  $feed .= '    <price' . $objectPriceAttribs . '><![CDATA[' . $objectPrice . ']]></price>' . "\n";
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

  if (is_array($object['images'])) {
    $feed .= '    <pictures>' . "\n";
    foreach ($object['images'] as $img) {
      $imgUrl = ($_SERVER['HTTPS'] != '') ? 'https://' : 'http://';
      $imgUrl .= $_SERVER['SERVER_NAME'];
      $imgUrl .= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
      $imgUrl .= '/data/' . $object['id'] . '/' . $img['name'];
      $imgTitle = (is_string($img['title'][$lang])) ? $img['title'][$lang] : '';
      $feed .= '      <picture>' . "\n";
      $feed .= '        <picture_url><![CDATA[' . $imgUrl . ']]></picture_url>' . "\n";
      $feed .= '        <picture_title><![CDATA[' . $imgTitle . ']]></picture_title>' . "\n";
      $feed .= '      </picture>' . "\n";
    }
    $feed .= '    </pictures>' . "\n";
  }

  //$feed .= '    <virtual_tour><![CDATA['..']]></virtual_tour>' . "\n";
  //$feed .= '    <date><![CDATA['..']]></date>' . "\n";
  //$feed .= '    <time><![CDATA['..']]></time>' . "\n";
  //$feed .= '    <expiration_date><![CDATA['..']]></expiration_date>' . "\n";
  if (is_numeric($objectPlotArea) && $objectPlotArea > 0)
    $feed .= '    <plot_area><![CDATA[' . $objectPlotArea . ']]></plot_area>' . "\n";
  if (is_numeric($objectFloorNumber) && $objectFloorNumber > 0)
    $feed .= '    <floor_numbers><![CDATA[' . $objectFloorNumber . ']]></floor_numbers>' . "\n";
  //$feed .= '    <orientation><![CDATA['..']]></orientation>' . "\n";
  //$feed .= '    <foreclosure><![CDATA['..']]></foreclosure>' . "\n";
  if (is_numeric($objectIsFurnished))
    $feed .= '    <is_furnished><![CDATA[' . $objectIsFurnished . ']]></is_furnished>' . "\n";
  //$feed .= '    <is_new><![CDATA['..']]></is_new>' . "\n";
  if (is_string($objectConditition))
    $feed .= '    <s_condition><![CDATA[' . $objectConditition . ']]></s_condition>' . "\n";
  if (is_string($objectYear))
    $feed .= '    <year><![CDATA[' . $objectYear . ']]></year>' . "\n";
  $feed .= '  </ad>' . "\n";
}
$feed .= '</trovit>';

// Trovit-Feed cachen
$fh = fopen($feedFile, 'w') or die('can\'t write file: ' . $feedFile);
fwrite($fh, $feed);
fclose($fh);

// Trovit-Feed ausgeben
echo $feed;
