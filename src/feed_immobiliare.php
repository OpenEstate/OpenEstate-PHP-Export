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
 * Website-Export, Darstellung des Immobiliare-Feeds.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2010, OpenEstate.org
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
if (session_id() == '')
  session_start();
$debugMode = isset($_REQUEST['debug']) && $_REQUEST['debug'] == '1';
if ($debugMode)
  header('Content-Type: text/html; charset=utf-8');
else
  header('Content-Type: text/xml; charset=utf-8');

// Mapping, Vermartungsart
$mapObjectAction = array(
  'RENT' => 'R', // Miete
  'LEASE' => 'R', // Miete
  'EMPHYTEUSIS' => 'R', // Miete
  'RENT_ON_TIME' => 'R', // Miete
  'PURCHASE' => 'S', // Kauf
);

// Mapping, Kategorie
$mapObjectCat = array(
  'COTTAGE' => 'V', // Ferien
  'GENERAL_COMMERCIAL' => 'C', // Gewerbe
  'GENERAL_PIECE_OF_LAND' => 'C', // Gewerbe
  'GENERAL_AGRICULTURE' => 'C', // Gewerbe
  'GENERAL_CAR_SPACE' => 'R', // Wohnen
  'MAIN_WOHNEN' => 'R', // Wohnen
);

// Mapping, Objektart, Wohnung & Ferien
$mapObjectType = array(
  'TERRACE_FLAT' => 'Attico', // Dachgeschosswohnung / Terrassenwohnung
  'PENTHOUSE' => 'Mansarda', // Dachgeschosswohung
  'LOFT' => 'Loft', // Loft
  'RESIDENCE' => 'Appartamento', // Wohnung (ganz allgemein)
  'SPECIAL_HOUSE' => 'Palazzo', // Palast / herrschaftliches Stadthaus
  'FARMHOUSE' => 'Casale', // Bauernhaus
  'COUNTRY_HOUSE' => 'Rustico', // ländliches Wohnhaus / Bauernhaus / Landhaus
  'VILLA' => 'Villa', // Villa
  'HAUS' => 'Stabile', // Gebäude
  'GENERAL_CAR_SPACE' => 'Garage', // Garage
    //'' => 'Multiproprietà',                       // Häuser in gemeinschaftlichem Eigentum (wurde bei Ferienwohnungen oft gemacht, ist aber wieder ein wenig aus der Mode gekommen - mehrere Familien kaufen eine Ferienwohnung und "teilen" sich dann das Haus für definierte Zeiträume zu)
    //'' => 'Open Space',                           // ???
    //'' => 'Villetta a schiera',                   // Reihenhaus-Villa
    //'' => 'Casa Indipendente',                    // freistehendes Haus
    //'' => 'Other',                                // Andere
);

// Mapping, Objektart, Gewerbe, Grundstück
$mapObjectBusinessType_Terreno = array(
  'RESIDENTIAL_GROUND' => 'Residenziale', // Baugrund für Wohnungsbau
  'COMMERCIAL_GROUND' => 'Commerciale', // Baugrund für gewerbliche Immobilien
  'INDUSTRIAL_GROUND' => 'Industriale', // Baugrund für Industriegebäude
  'AGRICULTURAL_FORESTRY_GROUND' => 'Agricolo', // Baugrund für Industriegebäude
);

// Mapping, Objektart, Gewerbe, wirtschaftliche Tätigkeit
$mapObjectBusinessType_Attivita = array(
  'MALL' => 'Centro commerciale', // Einkaufszentrum
  'RESTAURANT' => 'Ristorante', // Restaurant
  'RESTAURANT_BAR' => 'Bar', // Bar
  'DISCOTHEQUE' => 'Discoteca', // Diskothek
  'HOTEL' => 'Hotel', // Hotel
  'HOSTEL' => 'Hotel', // Hotel
  'GUEST_ROOM' => 'Bed and Breakfast', // Zimmer mit Frühstück
  'BOARDINGHOUSE' => 'Pensione', // Pension
  'FITNESS_STUDIO' => 'Palestra', // Fitnesscenter
  'TANNING_SALON' => 'Estetica / Solarium', // Schönheitssalon / Solarium
  'WORKSHOP' => 'Auto officina', // Autowerkstatt
    //'' => 'Negozio',                              // Geschäftslokal
    //'' => 'Azienda agricola',                     // landwirtschaftlicher Betrieb
    //'' => 'Pizzeria',                             // Pizzeria
    //'' => 'Pizza Al Taglio',                      // Fast-Food / Kebap
    //'' => 'Pub',                                  // Pub
    //'' => 'Alimentari',                           // Lebensmittelgeschäft
    //'' => 'Rosticceria',                          // Restaurant mit Grill
    //'' => 'Pasticceria',                          // Konditorei
    //'' => 'Gelateria',                            // Eisdiele
    //'' => 'Panetteria',                           // Bäckerei
    //'' => 'Altro | Alimentare',                   // anderes Lebensmittelgeschäft
    //'' => 'Ferramenta',                           // Eisenwarenhandlung
    //'' => 'Casalinghi',                           // Haushaltswaren
    //'' => 'Abbigliamento',                        // Textilgeschäft
    //'' => 'Parrucchiere uomo/donna',              // Herren-/Damen-Frisör
    //'' => 'Videonoleggio',                        // Videoverleih
    //'' => 'Tabaccheria',                          // Tabak-Trafik
    //'' => 'Tintoria',                             // Färberei
    //'' => 'Lavanderia',                           // Wäscherei
    //'' => 'Cartoleria',                           // Papierhandel
    //'' => 'Libreria',                             // Buchhandlung
    //'' => 'Informatica',                          // Computerwaren
    //'' => 'Telefonia',                            // Telefongeschäft
    //'' => 'Edicola',                              // Zeitungshandel
    //'' => 'Altro | Non alimentare',               // anderes Nicht-Lebensmittel
    //'' => 'Giochi',                               // Spielwaren
    //'' => 'Scommesse',                            // Wettbüro
);

// Mapping, Objektart, Gewerbe, Sonstiges
$mapObjectBusinessType_Immobile = array(
  'INDUSTRIAL_HALL' => 'Capannone Industriale', // Industriehalle
  'HALL_STOCK' => 'Capannone', // Halle
  'GENERAL_AGRICULTURE' => 'Azienda Agricola', // landwirtschaftl. Betrieb
  'GENERAL_CAR_SPACE' => 'Garage', // Garage
  'GESCHAEFTSLOKAL' => 'Negozio', // Geschäftslokal
  'BUERO_GESCHAEFTSLOKAL' => 'Negozio', // Geschäftslokal
  'BUERO_PRAXIS' => 'Ufficio', // Bürolokal
  'HOUSE' => 'Stabile', // Gebäude / Stadthaus
  'EXHIBITION_AREA' => 'Showroom', // Ausstellungsraum
  'HOSPITALITY_INDUSTRY' => 'Albergo', // Gasthof / Unterkünfte
    //'' => 'Casa di cura',                         // Kurhaus
    //'' => 'Magazzino',                            // Magazin
    //'' => 'Scuderia',                             // Ställe und Pferderennställe
    //'' => 'Stabilimento Balneare',                // Badeanlage
    //'' => 'Laboratorio',                          // Labor
    //'' => 'Altro',                                // Andere
);

// Mapping, Art der Gewerbefläche
$mapObjectTerrainType = array(
  'VINICULTURE' => 'vigneto', // Weinberg
  'CULTIVATION' => 'seminativo', // Saatfeld
    //'' => 'seminativo irriguo',                   // Saatfeld mit Bewässerung
    //'' => 'seminativo arborato',                  // Saatfeld mit Bäumen
    //'' => 'seminativo arborato irriguo',          // Saatfeld mit Bewässerung und Bäumen
    //'' => 'prato',                                // Wiese
    //'' => 'prato irriguo',                        // Wiese mit Bewässerung
    //'' => 'prato arborato',                       // Wiese mit Bäumen
    //'' => 'prato a marcita',                      // verrottetes Feld ???
    //'' => 'risaia stabile',                       // Reisfeld
    //'' => 'pascolo',                              // Weide
    //'' => 'pascolo arborato',                     // Weide mit Bäumen
    //'' => 'pascolo cespugliato',                  // Weide mit Sträuchern/Gebüsch
    //'' => 'giardino',                             // Garten
    //'' => 'orto',                                 // Gemüsegarten
    //'' => 'orto irriguo',                         // Gemüsefeld mit BEwässerung
    //'' => 'agrumeto',                             // Feld für Zitrusfrüchte
    //'' => 'uliveto',                              // Olivenhain
    //'' => 'frutteto',                             // Obstgarten
    //'' => 'gelseto',                              // Maulbeerhain
    //'' => 'colture speciali',                     // spezielle Anbaukulturen
    //'' => 'castagneto da frutto',                 // Kastanienhain
    //'' => 'canneto',                              // Schilf
    //'' => 'bosco alto fusto',                     // Wald
    //'' => 'bosco ceduo',                          // Nutzwald
    //'' => 'bosco misto',                          // Mischwald
    //'' => 'incolto produttivo',                   // unbebautes/nutzbares Ödland
    //'' => 'incolto sterile',                      // unfruchtbares Ödland
);

// Konfiguration ermitteln
$setup = new immotool_setup_feeds();
if (is_callable(array('immotool_myconfig', 'load_config_feeds')))
  immotool_myconfig::load_config_feeds($setup);
immotool_functions::init($setup);
if (!$setup->PublishImmobiliareFeed)
  die('Immobiliare-Feed is disabled!');

// Übersetzungen ermitteln
$translations = null;
$lang = (isset($_REQUEST[IMMOTOOL_PARAM_LANG])) ? $_REQUEST[IMMOTOOL_PARAM_LANG] : $setup->DefaultLanguage;
$lang = immotool_functions::init_language($lang, $setup->DefaultLanguage, $translations);
if (!is_array($translations))
  die('Can\'t load translations!');

// Titel ermitteln
$feedTitle = htmlentities($translations['labels']['title']);

// Cache-Datei des Feeds
$feedFile = IMMOTOOL_BASE_PATH . 'cache/feed.immobiliare_' . $lang . '.xml';
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

// URL der Seite ermitteln
$siteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '') ? 'https://' : 'http://';
$siteUrl .= $_SERVER['SERVER_NAME'];

// Zeitpunkt der Erzeugung
$feedStamp = date('Y-m-d\TH:i:sO');
$feedStamp = substr($feedStamp, 0, -2) . ':' . substr($feedStamp, -2);

// Feed erzeugen
$feed = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
$feed .= '<feed xmlns="http://feed.immobiliare.it" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://feed.immobiliare.it/docs/xsd/v15.xsd">' . "\n";
$feed .= '  <version>1.5</version>' . "\n";
$feed .= '  <metadata>' . "\n";
$feed .= '    <publisher>' . "\n";
$feed .= '      <name>' . $feedTitle . '</name>' . "\n";
$feed .= '      <site>' . $siteUrl . '</site>' . "\n";
$feed .= '      <email>' . $setup->MailFrom . '</email>' . "\n";
$feed .= '      <phone></phone>' . "\n";
$feed .= '    </publisher>' . "\n";
$feed .= '    <build-date>' . $feedStamp . '</build-date>' . "\n";
$feed .= '    <multipage>' . "\n";
$feed .= '      <current>1</current>' . "\n";
$feed .= '      <last>1</last>' . "\n";
$feed .= '    </multipage>' . "\n";
$feed .= '  </metadata>' . "\n";
$feed .= '  <properties>' . "\n";

if ($debugMode) {
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
  echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">';
  echo '  <head>';
  echo '    <title>Immobiliare-Feed Debugger</title>';
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
  echo '  <h2>Immobiliare-Feed Debugger</h2>';
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

  // Exposé-URL
  $objectUrl = immotool_functions::get_expose_url($id, $lang, $setup->ExposeUrlTemplate, true);

  // Zeitpunkt der letzten Änderung
  $feedStamp = date('Y-m-d\TH:i:sO', immotool_functions::get_object_stamp($id));
  $feedStamp = substr($feedStamp, 0, -2) . ':' . substr($feedStamp, -2);

  // Mailadresse
  $objectMail = (isset($object['mail'])) ? $object['mail'] : null;
  if (!is_string($objectMail) || strlen(trim($objectMail)) == 0)
    $objectMail = $setup->MailFrom;

  // Vermartungsart
  $action = (isset($object['action'])) ? strtoupper($object['action']) : null;
  if ($action == null) {
    if ($debugMode)
      echo '&gt; UNKNOWN ACTION<br/>';
    continue;
  }
  $objectAction = (isset($mapObjectAction[$action])) ? $mapObjectAction[$action] : null;
  if ($objectAction == null) {
    if ($debugMode)
      echo '&gt; UNSUPPORTED ACTION: ' . $object['action'] . '<br/>';
    continue;
  }

  // Kategorie
  $objectCat = null;
  foreach ($mapObjectCat as $key => $value) {
    $type = trim(strtolower($key));
    if (array_search($type, $object['type_path']) !== false) {
      $objectCat = $value;
      break;
    }
  }
  if ($objectCat == null) {
    if ($debugMode)
      echo '&gt; UNKNOWN CATEGORY<br/>';
    continue;
  }

  // Objektart
  $objectType = null;
  $objectBusinessType = null;
  $objectBusinessTypeCat = null;
  $objectTerrainType = null;

  // Immobilienarten, die nur für Wohn- & Ferien-Objekte gelten
  if ($objectCat == 'R' || $objectCat == 'V') {
    foreach ($mapObjectType as $key => $value) {
      $type = trim(strtolower($key));
      if (array_search($type, $object['type_path']) !== false) {
        $objectType = $value;
        break;
      }
    }
    if ($objectType == null)
      $objectType = 'Other';
  }

  // Immobilienarten, die nur für Gewerbe-Objekte gelten
  else if ($objectCat == 'C') {

    // Grundstücke
    if (array_search('general_piece_of_land', $object['type_path']) !== false) {
      $objectBusinessTypeCat = 'Terreno';
      foreach ($mapObjectBusinessType_Terreno as $key => $value) {
        $type = trim(strtolower($key));
        if (array_search($type, $object['type_path']) !== false) {
          $objectBusinessType = $value;
          break;
        }
      }
      if ($objectBusinessType == null)
        $objectBusinessType = 'Residenziale';
    }

    // weiteres Gewerbe
    else {
      foreach ($mapObjectBusinessType_Attivita as $key => $value) {
        $type = trim(strtolower($key));
        if (array_search($type, $object['type_path']) !== false) {
          $objectBusinessTypeCat = 'Attività';
          $objectBusinessType = $value;
          break;
        }
      }
      if ($objectBusinessType == null || $objectBusinessTypeCat == null) {
        foreach ($mapObjectBusinessType_Immobile as $key => $value) {
          $type = trim(strtolower($key));
          if (array_search($type, $object['type_path']) !== false) {
            $objectBusinessType = $value;
            break;
          }
        }
        if ($objectBusinessType == null)
          $objectBusinessType = 'Altro';
        $objectBusinessTypeCat = 'Immobile';
      }
    }

    // Art der landwirtschaftlichen Gewerbefläche
    foreach ($mapObjectTerrainType as $key => $value) {
      $type = trim(strtolower($key));
      if (array_search($type, $object['type_path']) !== false) {
        $objectTerrainType = $value;
        break;
      }
    }
    if ($objectTerrainType == null) {

      $bebaubar = (isset($object['attributes']['administration']['buildable_with']['value'])) ?
          $object['attributes']['administration']['buildable_with']['value'] : array();
      if (!is_array($bebaubar))
        $bebaubar = array();
      if (array_search('farmland', $bebaubar) !== false)
        $objectTerrainType = 'seminativo'; // Saatfeld
      else if (array_search('fruit_planting', $bebaubar) !== false)
        $objectTerrainType = 'frutteto'; // Obstgarten
      else if (array_search('forest', $bebaubar) !== false)
        $objectTerrainType = 'bosco alto fusto'; // Wald
    }
  }

  else {
    if ($debugMode)
      echo '&gt; INVALID CATEGORY: ' . $objectCat . '<br/>';
    continue;
  }

  // Zustand des Gebäudes
  $objectStatus = null;
  $zustand = (isset($object['attributes']['condition']['condition_type']['value'])) ?
      $object['attributes']['condition']['condition_type']['value'] : array();
  if (!is_array($zustand))
    $zustand = array();
  $alter = (isset($object['attributes']['condition']['age']['value'])) ?
      $object['attributes']['condition']['age']['value'] : '';
  $ausstattung = (isset($object['attributes']['facilities']['equipment']['value'])) ?
      $object['attributes']['facilities']['equipment']['value'] : '';
  if (array_search('renovated_partially', $zustand) !== false)
    $objectStatus = 'ristrutturato'; // renoviert
  else if (array_search('renovated_completely', $zustand) !== false)
    $objectStatus = 'ristrutturato'; // renoviert
  else if (array_search('modernized', $zustand) !== false)
    $objectStatus = 'ristrutturato'; // renoviert
  else if (array_search('restorated_partially', $zustand) !== false)
    $objectStatus = 'da ristrutturare'; // saniert
  else if (array_search('restorated_completely', $zustand) !== false)
    $objectStatus = 'da ristrutturare'; // saniert
  else if (array_search('dilapidated', $zustand) !== false)
    $objectStatus = 'in costruzione'; // Im Bau
  else if (array_search('gutted', $zustand) !== false)
    $objectStatus = 'in costruzione'; // Im Bau
  else if (array_search('needs_renovation_partially', $zustand) !== false)
    $objectStatus = 'in costruzione'; // Im Bau
  else if (array_search('needs_renovation_completely', $zustand) !== false)
    $objectStatus = 'in costruzione'; // Im Bau
  else if (array_search('needs_restoration_partially', $zustand) !== false)
    $objectStatus = 'in costruzione'; // Im Bau
  else if (array_search('needs_restoration_completely', $zustand) !== false)
    $objectStatus = 'in costruzione'; // Im Bau
  else if (array_search('first_occupancy', $zustand) !== false)
    $objectStatus = 'nuovo'; // Neu
  else if (array_search('first_occupancy_after_restoration', $zustand) !== false)
    $objectStatus = 'nuovo'; // Neu
  else if (array_search('as_good_as_new', $zustand) !== false)
    $objectStatus = 'nuovo'; // Neu
  else if (array_search('well_tended', $zustand) !== false)
    $objectStatus = 'buono'; // gut
  else if ($alter == 'new_building')
    $objectStatus = 'nuovo'; // Neu
  else if ($ausstattung == 'basic')
    $objectStatus = 'abitabile'; // bewohnbar
  else if ($ausstattung == 'usual')
    $objectStatus = 'discreto'; // mittel, bewohnbar
  else if ($ausstattung == 'upper')
    $objectStatus = 'buono'; // gut
  else if ($ausstattung == 'luxury')
    $objectStatus = 'ottimo'; // großartig
  else
    $objectStatus = 'nd'; // keine Angaben


// Land (derzeit ausschließlich Italien)
  if (!isset($object['address']['country']) || is_null($object['address']['country'])) {
    if ($debugMode)
      echo '&gt; UNKNOWN COUNTRY<br/>';
    continue;
  }
  $objectLocationCountry = strtoupper($object['address']['country']);
  if ($objectLocationCountry != 'IT') {
    if ($debugMode)
      echo '&gt; UNSUPPORTED COUNTRY: ' . $objectLocationCountry . '<br/>';
    continue;
  }

  // Ortsangaben
  $objectLocationArea1 = (isset($object['other']['immobiliare']['areaName'])) ?
      $object['other']['immobiliare']['areaName'] : null;
  if (is_null($objectLocationArea1) || trim($objectLocationArea1) == '') {
    if ($debugMode)
      echo '&gt; UNKNOWN LOCATION-AREA<br/>';
    continue;
  }
  $objectLocationArea2 = (isset($object['other']['immobiliare']['subAreaName'])) ?
      $object['other']['immobiliare']['subAreaName'] : null;
  if (is_null($objectLocationArea2) || trim($objectLocationArea2) == '') {
    if ($debugMode)
      echo '&gt; UNKNOWN LOCATION-SUBAREA<br/>';
    continue;
  }
  $objectLocationCity = (isset($object['other']['immobiliare']['cityName'])) ?
      $object['other']['immobiliare']['cityName'] : null;
  if (is_null($objectLocationCity) || trim($objectLocationCity) == '') {
    if ($debugMode)
      echo '&gt; UNKNOWN LOCATION-CITY<br/>';
    continue;
  }
  $objectLocationCityCode = (isset($object['other']['immobiliare']['cityCode'])) ?
      $object['other']['immobiliare']['cityCode'] : null;
  if (is_null($objectLocationCityCode))
    $objectLocationCityCode = 0;
  else
    $objectLocationCityCode = (int) $objectLocationCityCode;
  $objectLocationPostal = (isset($object['address']['postal'])) ?
      $object['address']['postal'] : null;
  if (is_null($objectLocationPostal))
    $objectLocationPostal = '';
  $objectLocationLatitude = (isset($object['address']['latitude'])) ?
      $object['address']['latitude'] : null;
  if (!is_numeric($objectLocationLatitude))
    $objectLocationLatitude = null;
  $objectLocationLongitude = (isset($object['address']['longitude'])) ?
      $object['address']['longitude'] : null;
  if (!is_numeric($objectLocationLongitude))
    $objectLocationLongitude = null;
  //$objectLocationStreet = null;
  //if (isset($object['address']['street']) && !is_null($object['address']['street'])) {
  //  $objectLocationStreet = $object['address']['street'];
  //  if (isset($object['address']['street_nr']) && !is_null($object['address']['street_nr']))
  //    $objectLocationStreet .= ' ' . $object['address']['street_nr'];
  //}
  // Zimmerzahl
  $objectRooms = (isset($object['attributes']['measures']['count_rooms']['value'])) ?
      $object['attributes']['measures']['count_rooms']['value'] : null;
  if (!is_numeric($objectRooms))
    $objectRooms = 0;
  else
    $objectRooms = (int) $objectRooms;
  $objectBedrooms = (isset($object['attributes']['measures']['count_bedrooms']['value'])) ?
      $object['attributes']['measures']['count_bedrooms']['value'] : null;
  if (!is_numeric($objectBedrooms))
    $objectBedrooms = 0;
  else
    $objectBedrooms = (int) $objectBedrooms;
  $objectBathrooms = (isset($object['attributes']['measures']['count_bathrooms']['value'])) ?
      $object['attributes']['measures']['count_bathrooms']['value'] : null;
  if (!is_numeric($objectBathrooms))
    $objectBathrooms = 0;
  else
    $objectBathrooms = (int) $objectBathrooms;

  // Fläche
  $objectSize = null;
  $sizes = array('GROSS_AREA', 'TOTAL_AREA', 'PLOT_AREA', 'RESIDENTIAL_AREA', 'BUSINESS_AREA', 'SALES_AREA', 'PARKING_AREA');
  foreach ($sizes as $size) {
    $key = strtolower($size);
    $val = (isset($object['attributes']['measures'][$key]['value'])) ?
        $object['attributes']['measures'][$key]['value'] : null;
    if (!is_numeric($val) || $val <= 0)
      continue;
    $objectSize = $val;
    break;
  }
  if (!is_numeric($objectSize))
    $objectSize = 0;
  else
    $objectSize = (int) $objectSize;

  // Preis
  $objectPrice = null;
  $prices = array();
  if ($object['action'] == 'rent')
    $prices = array('RENT_WITHOUT_HEATING', 'RENT_WITH_HEATING');
  else if ($object['action'] == 'lease')
    $prices = array('LEASE', 'LEASE_PER_AREA');
  else if ($object['action'] == 'emphyteusis')
    $prices = array('LEASE', 'LEASE_PER_AREA');
  else if ($object['action'] == 'rent_on_time')
    $prices = array('RENT_FLAT_RATE');
  else if ($object['action'] == 'purchase')
    $prices = array('BUYING_PRICE');
  else {
    if ($debugMode)
      echo '&gt; UNSUPPORTED ACTION: ' . $object['action'] . '<br/>';
    continue;
  }
  foreach ($prices as $price) {
    $key = strtolower($price);
    $val = (isset($object['attributes']['prices'][$key]['value'])) ?
        $object['attributes']['prices'][$key]['value'] : null;
    if (!is_numeric($val) || $val <= 0)
      continue;
    $objectPrice = $val;
    break;
  }
  if (!is_numeric($objectPrice))
    $objectPrice = 0;
  else
    $objectPrice = (int) $objectPrice;
  $objectPriceCurrency = (isset($object['currency']) && is_string($object['currency'])) ?
      $object['currency'] : 'EUR';
  $objectPriceReserved = (isset($object['hidden_price']) && $object['hidden_price'] === true) ?
      'yes' : 'no';

  // Beschreibungen
  $objectDescription = array();
  foreach ($objectTexts as $key => $text) {
    if ($key == 'id' || $key == 'keywords')
      continue;
    foreach ($text as $textLang => $textValue) {
      if (!isset($objectDescription[$textLang]))
        $objectDescription[$textLang] = '';
      if (strlen($objectDescription[$textLang]) > 0)
        $objectDescription[$textLang] .= '<br/><br/>';
      $objectDescription[$textLang] .= $textValue;
    }
  }
  if (count($objectDescription) == 0) {
    foreach ($object['title'] as $titleLang => $title) {
      $objectDescription[$titleLang] = $title;
    }
  }

  // Küche
  $objectKitchen = null;
  $kueche = (isset($object['attributes']['facilities']['kitchen']['value'])) ?
      $object['attributes']['facilities']['kitchen']['value'] : null;
  if (is_array($kueche)) {
    if (array_search('eat_in_kitchen', $kueche) !== false)
      $objectKitchen = 'Abitabile'; // Wohnküche
    else if (array_search('kitchen_nook', $kueche) !== false)
      $objectKitchen = 'Angolo cottura'; // Kochecke

//else if (array_search('???',$kueche)!==false)
    //  $objectKitchen = 'Cucinotto'; // Kochnische
    else if (array_search('small_kitchen', $kueche) !== false)
      $objectKitchen = 'Angolo cottura'; // 'halbe' Wohnküche
  }

  // Garage / Stellplatz
  $objectGarage = (isset($object['attributes']['measures']['count_parking_spaces']['value'])) ?
      $object['attributes']['measures']['count_parking_spaces']['value'] : null;
  if (!is_numeric($objectGarage))
    $objectGarage = 0;
  else
    $objectGarage = (int) $objectGarage;
  $objectGarageType = 'No';
  if ($objectGarage > 0) {
    $objectGarageType = 'PostoAuto';
    $arten = (isset($object['attributes']['measures']['parking_type']['value'])) ?
        $object['attributes']['measures']['parking_type']['value'] : null;
    if (is_array($arten)) {
      if (array_search('garage', $arten) !== false)
        $objectGarageType = 'Box'; // Box
      else if (array_search('carport', $arten) !== false)
        $objectGarageType = 'Rimessa'; // Schuppen / Carport
      else if (array_search('duplex_garage', $arten) !== false)
        $objectGarageType = 'Rimessa'; // Schuppen / Carport
      else if (array_search('car_space', $arten) !== false)
        $objectGarageType = 'PostoAuto'; // Stellplatz
      else if (array_search('outdoor_car_space', $arten) !== false)
        $objectGarageType = 'PostoAuto'; // Stellplatz
    }
  }

  // Heizung
  $objectHeating = null;
  $heizung = (isset($object['attributes']['facilities']['heating_method']['value'])) ?
      $object['attributes']['facilities']['heating_method']['value'] : null;
  $befeuerung = (isset($object['attributes']['facilities']['lighting_method']['value'])) ?
      $object['attributes']['facilities']['lighting_method']['value'] : null;
  if (is_array($heizung) && count($heizung) > 0) {
    if (array_search('independent', $heizung) !== false)
      $objectHeating = 'Autonomo'; // autonome Heizung
    else if (array_search('central', $heizung) !== false)
      $objectHeating = 'Centralizzato'; // Zentralheizung
  }
  if ($objectHeating == null && is_array($befeuerung) && count($befeuerung) > 0) {
    if (array_search('district_heating', $befeuerung) !== false)
      $objectHeating = 'Teleriscaldamento'; // Fernheizung
  }
  //if ($objectHeating==null) {
  //  $objectHeating = 'Assente'; // fehlend
  //}
  // Gartennutzung
  $objectGarden = null;
  $garten = (isset($object['attributes']['facilities']['garden']['value'])) ?
      $object['attributes']['facilities']['garden']['value'] : null;
  if ($garten === true)
    $objectGarden = 'Privato'; // privat
  else if ($garten === false)
    $objectGarden = 'Nessuno'; // kein Garten

//else if ($garten===false)
  //  $objectGarden = 'Comune'; // gemeinsam
  // Terrasse
  $objectTerrace = null;
  $terrasse = (isset($object['attributes']['facilities']['balcony_terrace']['value'])) ?
      $object['attributes']['facilities']['balcony_terrace']['value'] : null;
  if ($terrasse === true)
    $objectTerrace = 'Y';
  else if ($terrasse === false)
    $objectTerrace = 'N';

  // Balkon
  $objectBalcony = null;
  $balkon = (isset($object['attributes']['facilities']['balcony_terrace']['value'])) ?
      $object['attributes']['facilities']['balcony_terrace']['value'] : null;
  if ($balkon === true)
    $objectBalcony = 'Y';
  else if ($balkon === false)
    $objectBalcony = 'N';

  // Aufzug
  $objectElevator = null;
  $lift = (isset($object['attributes']['facilities']['passenger_elevator']['value'])) ?
      $object['attributes']['facilities']['passenger_elevator']['value'] : null;
  if ($lift == null || $lift === false)
    $lift = (isset($object['attributes']['facilities']['freight_elevator']['value'])) ?
        $object['attributes']['facilities']['freight_elevator']['value'] : $lift;
  if ($lift === true)
    $objectElevator = 'Y';
  else if ($lift === false)
    $objectElevator = 'N';

  // Klimatisiert
  $objectAirCondition = null;
  $klima = (isset($object['attributes']['facilities']['air_conditioned']['value'])) ?
      $object['attributes']['facilities']['air_conditioned']['value'] : null;
  if ($klima === true)
    $objectAirCondition = 'Y';
  else if ($klima === false)
    $objectAirCondition = 'N';

  // Etage
  $objectFloorType = null;
  $objectFloor = (isset($object['attributes']['facilities']['floor']['value'])) ?
      $object['attributes']['facilities']['floor']['value'] : null;
  if (!is_numeric($objectFloor))
    $objectFloor = null;
  else {
    $objectFloor = (int) $objectFloor;

    if (array_search('penthouse', $object['type_path']) !== false)
      $objectFloorType = 'Attico'; // Dachterasse / Penthouse
    else if (array_search('penthouse_apartment', $object['type_path']) !== false)
      $objectFloorType = 'Attico'; // Dachterasse / Penthouse

//else if (array_search('???',$object['type_path'])!==false)
    //  $objectFloorType = 'Controterra'; //
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectFloorType = 'Edificio'; // Bau / Gebäude
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectFloorType = 'Intermedio'; // Zwischenetage
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectFloorType = 'Interrato'; // Kellergeschoss
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectFloorType = 'Multipiano'; // mehrere Stockwerke
    else if (array_search('ground_floor_flat', $object['type_path']) !== false)
      $objectFloorType = 'Pianoterra'; // Ergeschoss

//else if (array_search('???',$object['type_path'])!==false)
    //  $objectFloorType = 'PianoNobile'; // ???
    else if (array_search('mezzanine', $object['type_path']) !== false)
      $objectFloorType = 'Rialzato'; // Hochparterre

//else if (array_search('???',$object['type_path'])!==false)
    //  $objectFloorType = 'Semiinterrato'; // Halb-Kellergeschoss
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectFloorType = 'Ultimo'; // zuletzt
  }

  // Etagenzahl
  $objectNumFloors = (isset($object['attributes']['facilities']['count_floors']['value'])) ?
      $object['attributes']['facilities']['count_floors']['value'] : null;
  if (!is_numeric($objectNumFloors))
    $objectNumFloors = null;
  else
    $objectNumFloors = (int) $objectNumFloors;

  // Mietvertrag
  $objectRentContract = null;
  if ($object['action'] == 'rent_on_time') {
    $objectRentContract = 'Transitorio'; // vorübergehend
  }
  else if ($object['action'] == 'rent') {
    if (array_search('flat_share_students', $object['type_path']) !== false)
      $objectRentContract = 'Studenti'; // Schüler / Studenten
    else
      $objectRentContract = 'Concordato'; // nach Vereinbarung
  }

  // Möblierung
  $objectFurniture = null;
  $moebliert = (isset($object['attributes']['facilities']['furnished']['value'])) ?
      $object['attributes']['facilities']['furnished']['value'] : null;
  if ($moebliert == null)
    $objectFurniture = null;
  else if (strtolower($moebliert) == 'yes')
    $objectFurniture = 'Arredato'; // eingerichtet
  else if (strtolower($moebliert) == 'no')
    $objectFurniture = 'Non Arredato'; // nicht eingerichtet
  else if (strtolower($moebliert) == 'partially')
    $objectFurniture = 'Parzialmente Arredato'; // teilweise eingerichtet


// Sicherheitsalarm
  $objectSecurityAlarm = null;
  $sicherheit = (isset($object['attributes']['facilities']['security']['value'])) ?
      $object['attributes']['facilities']['security']['value'] : null;
  if (is_array($sicherheit)) {
    if (array_search('alarm_system', $sicherheit) !== false)
      $objectSecurityAlarm = 'Y';
    else
      $objectSecurityAlarm = 'N';
  }

  // Internet
  $objectNet = null;
  $technik = (isset($object['attributes']['facilities']['technics']['value'])) ?
      $object['attributes']['facilities']['technics']['value'] : null;
  if (is_array($technik)) {
    if (array_search('dv_cabling', $sicherheit) !== false)
      $objectNet = 'Y';
    else
      $objectNet = 'N';
  }

  // Voraussetzungen für die Immobilie
  $objectFreeConditions = (isset($objectTexts['pricing_description'][$lang])) ?
      $objectTexts['pricing_description'][$lang] : null;

  // Kran
  $objectOverheadCrane = null;
  $kran = (isset($object['attributes']['facilities']['crane']['value'])) ?
      $object['attributes']['facilities']['crane']['value'] : null;
  if ($kran === true)
    $objectOverheadCrane = 'Yes';
  else if ($kran === false)
    $objectOverheadCrane = 'No';

  // Hallenhöhe
  $objectBeamHeight = (isset($object['attributes']['facilities']['hall_height']['value'])) ?
      $object['attributes']['facilities']['hall_height']['value'] : null;
  if (!is_numeric($objectBeamHeight))
    $objectBeamHeight = null;
  else
    $objectBeamHeight = (int) $objectBeamHeight;

  // Bürofläche
  $objectOfficeSize = (isset($object['attributes']['measures']['office_area']['value'])) ?
      $object['attributes']['measures']['office_area']['value'] : null;
  if (!is_numeric($objectOfficeSize))
    $objectOfficeSize = null;
  else
    $objectOfficeSize = (int) $objectOfficeSize;

  // Bilder
  $objectPictures = array();
  if (isset($object['images']) && is_array($object['images'])) {
    foreach ($object['images'] as $img) {
      $imgUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '') ? 'https://' : 'http://';
      $imgUrl .= $_SERVER['SERVER_NAME'];
      $imgUrl .= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
      $imgUrl .= '/data/' . $object['id'] . '/' . $img['name'];
      $objectPictures[] = $imgUrl;
    }
  }

  // ID der Immobilie ermittln
  $uniqueId = ($setup->ExportPublicId && isset($object['nr']) && is_string($object['nr']) && strlen($object['nr']) > 0) ?
      $object['nr'] : $id;

  // Immobilie in den Feed eintragen
  $txtCounter = 0;
  $picCounter = 0;
  $feed .= '    <property operation="write">' . "\n";
  $feed .= '      <unique-id>' . htmlentities($uniqueId) . '</unique-id>' . "\n";
  $feed .= '      <date-updated>' . $feedStamp . '</date-updated>' . "\n";
  //$feed .= '      <date-expiration/>' . "\n";
  $feed .= '      <transaction-type>' . $objectAction . '</transaction-type>' . "\n";
  $feed .= '      <category>' . $objectCat . '</category>' . "\n";
  $feed .= '      <property-type>' . "\n";
  if ($objectType != null) {
    $feed .= '        <type>' . $objectType . '</type>' . "\n";
  }
  else if ($objectBusinessType != null && $objectBusinessTypeCat != null) {
    $feed .= '        <business-type category="' . $objectBusinessTypeCat . '">' . $objectBusinessType . '</business-type>' . "\n";
    if ($objectTerrainType != null) {
      $feed .= '        <terrains>' . "\n";
      $feed .= '          <terrain>' . $objectTerrainType . '</terrain>' . "\n";
      $feed .= '        </terrains>' . "\n";
    }
  }
  $feed .= '      </property-type>' . "\n";
  $feed .= '      <building-status>' . $objectStatus . '</building-status>' . "\n";
  $feed .= '      <agent>' . "\n";
  $feed .= '        <office-name>' . $feedTitle . '</office-name>' . "\n";
  $feed .= '        <email>' . htmlentities($objectMail) . '</email>' . "\n";
  $feed .= '      </agent>' . "\n";
  $feed .= '      <location>' . "\n";
  $feed .= '        <country-code>' . $objectLocationCountry . '</country-code>' . "\n";
  $feed .= '        <administrative-area>' . $objectLocationArea1 . '</administrative-area>' . "\n";
  $feed .= '        <sub-administrative-area>' . $objectLocationArea2 . '</sub-administrative-area>' . "\n";
  if (!is_numeric($objectLocationCityCode) || $objectLocationCityCode <= 0)
    $feed .= '        <city>' . htmlentities($objectLocationCity) . '</city>' . "\n";
  else
    $feed .= '        <city code="' . $objectLocationCityCode . '">' . htmlentities($objectLocationCity) . '</city>' . "\n";
  $feed .= '        <locality>' . "\n";
  $feed .= '          <postal-code>' . htmlentities($objectLocationPostal) . '</postal-code>' . "\n";
  //$feed .= '          <neighbourhood type=""></neighbourhood>' . "\n";
  //if ($objectLocationStreet!=null) {
  //  $feed .= '          <thoroughfare display="no">'.htmlentities($objectLocationStreet).'</thoroughfare>' . "\n";
  //}
  if ($objectLocationLongitude != null && $objectLocationLatitude != null) {
    $feed .= '          <longitude>' . $objectLocationLongitude . '</longitude>' . "\n";
    $feed .= '          <latitude>' . $objectLocationLatitude . '</latitude>' . "\n";
  }
  $feed .= '        </locality>' . "\n";
  $feed .= '      </location>' . "\n";
  $feed .= '      <features>' . "\n";
  $feed .= '        <rooms>' . $objectRooms . '</rooms>' . "\n";
  $feed .= '        <size unit="m2">' . $objectSize . '</size>' . "\n";
  $feed .= '        <price currency="' . $objectPriceCurrency . '" reserved="' . $objectPriceReserved . '">' . $objectPrice . '</price>' . "\n";
  foreach ($objectDescription as $txtLang => $txt) {
    $feed .= '        <description language="' . $txtLang . '"><![CDATA[' . $txt . ']]></description>' . "\n";
    $txtCounter++;
    if ($txtCounter >= 15)
      break;
  }
  $feed .= '      </features>' . "\n";
  $feed .= '      <extra-features>' . "\n";
  $feed .= '        <virtual-tour>' . $objectUrl . '</virtual-tour>' . "\n";
  if (is_numeric($objectBedrooms) && $objectBedrooms > 0) {
    $feed .= '        <bedrooms>' . $objectBedrooms . '</bedrooms>' . "\n";
  }
  if (is_numeric($objectBathrooms) && $objectBathrooms > 0) {
    $feed .= '        <bathrooms>' . $objectBathrooms . '</bathrooms>' . "\n";
  }
  if ($objectKitchen != null) {
    $feed .= '        <kitchen>' . $objectKitchen . '</kitchen>' . "\n";
  }
  if ($objectGarage != null && $objectGarage > 0 && $objectGarageType != null) {
    $feed .= '        <garage type="' . $objectGarageType . '">' . $objectGarage . '</garage>' . "\n";
  }
  if ($objectHeating != null) {
    $feed .= '        <heating>' . $objectHeating . '</heating>' . "\n";
  }
  if ($objectGarden != null) {
    $feed .= '        <garden>' . $objectGarden . '</garden>' . "\n";
  }
  if ($objectTerrace != null) {
    $feed .= '        <terrace>' . $objectTerrace . '</terrace>' . "\n";
  }
  if ($objectBalcony != null) {
    $feed .= '        <balcony>' . $objectBalcony . '</balcony>' . "\n";
  }
  if ($objectElevator != null) {
    $feed .= '        <elevator>' . $objectElevator . '</elevator>' . "\n";
  }
  if ($objectAirCondition != null) {
    $feed .= '        <air-conditioning>' . $objectAirCondition . '</air-conditioning>' . "\n";
  }
  if ($objectFloor != null) {
    if ($objectFloorType == null) {
      $feed .= '        <floor>' . $objectFloor . '</floor>' . "\n";
    }
    else {
      $feed .= '        <floor type="' . $objectFloorType . '">' . $objectFloor . '</floor>' . "\n";
    }
  }
  if ($objectNumFloors != null) {
    $feed .= '        <num-floors>' . $objectNumFloors . '</num-floors>' . "\n";
  }
  if ($objectRentContract != null) {
    $feed .= '        <rent-contract>' . $objectRentContract . '</rent-contract>' . "\n";
  }
  if ($objectFurniture != null) {
    $feed .= '        <furniture>' . $objectFurniture . '</furniture>' . "\n";
  }
  if ($objectSecurityAlarm != null) {
    $feed .= '        <security-alarm>' . $objectSecurityAlarm . '</security-alarm>' . "\n";
  }
  //$feed .= '        <reception></reception>' . "\n";
  if ($objectNet != null) {
    $feed .= '        <net>' . $objectNet . '</net>' . "\n";
  }
  if ($objectFreeConditions != null) {
    $feed .= '        <free-conditions><![CDATA[' . $objectFreeConditions . ']]></free-conditions>' . "\n";
  }
  if ($objectOverheadCrane != null) {
    $feed .= '        <overhead-crane>' . $objectOverheadCrane . '</overhead-crane>' . "\n";
  }
  if ($objectBeamHeight != null) {
    $feed .= '        <beam-height>' . $objectBeamHeight . '</beam-height>' . "\n";
  }
  if ($objectOfficeSize != null) {
    $feed .= '        <office-size>' . $objectOfficeSize . '</office-size>' . "\n";
  }
  $feed .= '      </extra-features>' . "\n";
  if (is_array($objectPictures) && count($objectPictures) > 0) {
    $feed .= '      <pictures>' . "\n";
    foreach ($objectPictures as $pic) {
      $feed .= '        <picture-url>' . $pic . '</picture-url>' . "\n";
      $picCounter++;
      if ($picCounter >= 15)
        break;
    }
    $feed .= '      </pictures>' . "\n";
  }
  $feed .= '    </property>' . "\n";

  if ($debugMode)
    echo '&gt; OK<br/>';
}
$feed .= '  </properties>' . "\n";
$feed .= '</feed>';

// Debug-Ausgabe des Feeds
if ($debugMode) {
  echo '<h2>Generated XML</h2>';
  echo '<pre>' . htmlentities($feed) . '</pre>';
  echo '</body></html>';
}

// normale Ausgabe des Feeds
else {
  // Feed cachen
  $fh = fopen($feedFile, 'w') or die('can\'t write file: ' . $feedFile);
  fwrite($fh, $feed);
  fclose($fh);

  // Feed ausgeben
  echo $feed;
}
