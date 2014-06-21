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
  'MIETE' => 'R', // Miete
  'PACHT' => 'R', // Miete
  'ERBPACHT' => 'R', // Miete
  'WAZ' => 'R', // Miete
  'KAUF' => 'S', // Kauf
);

// Mapping, Kategorie
$mapObjectCat = array(
  'FERIENHAUS' => 'V', // Ferien
  'MAIN_GEWERBE' => 'C', // Gewerbe
  'MAIN_GRUND' => 'C', // Gewerbe
  'MAIN_LANDWIRTSCHAFT' => 'C', // Gewerbe
  'MAIN_STELLPLATZ' => 'R', // Wohnen
  'MAIN_WOHNEN' => 'R', // Wohnen
);

// Mapping, Objektart, Wohnung & Ferien
$mapObjectType = array(
  'TERRASSENWOHNUNG' => 'Attico', // Dachgeschosswohnung / Terrassenwohnung
  'DACHWOHNUNG' => 'Mansarda', // Dachgeschosswohung
  'LOFTWOHNUNG' => 'Loft', // Loft
  'WOHNUNG' => 'Appartamento', // Wohnung (ganz allgemein)
  'BESONDERES_HAUS' => 'Palazzo', // Palast / herrschaftliches Stadthaus
  'BAUERNHAUS' => 'Casale', // Bauernhaus
  'LANDHAUS' => 'Rustico', // ländliches Wohnhaus / Bauernhaus / Landhaus
  'VILLA' => 'Villa', // Villa
  'HAUS' => 'Stabile', // Gebäude
  'MAIN_STELLPLATZ' => 'Garage', // Garage
    //'' => 'Multiproprietà',                       // Häuser in gemeinschaftlichem Eigentum (wurde bei Ferienwohnungen oft gemacht, ist aber wieder ein wenig aus der Mode gekommen - mehrere Familien kaufen eine Ferienwohnung und "teilen" sich dann das Haus für definierte Zeiträume zu)
    //'' => 'Open Space',                           // ???
    //'' => 'Villetta a schiera',                   // Reihenhaus-Villa
    //'' => 'Casa Indipendente',                    // freistehendes Haus
    //'' => 'Other',                                // Andere
);

// Mapping, Objektart, Gewerbe, Grundstück
$mapObjectBusinessType_Terreno = array(
  'WOHNGRUND' => 'Residenziale', // Baugrund für Wohnungsbau
  'GEWERBEGRUND' => 'Commerciale', // Baugrund für gewerbliche Immobilien
  'INDUSTRIEGRUND' => 'Industriale', // Baugrund für Industriegebäude
  'LAND_FORSTGRUND' => 'Agricolo', // Baugrund für Industriegebäude
);

// Mapping, Objektart, Gewerbe, wirtschaftliche Tätigkeit
$mapObjectBusinessType_Attivita = array(
  'EINKAUFSZENTRUM' => 'Centro commerciale', // Einkaufszentrum
  'RESTAURANT' => 'Ristorante', // Restaurant
  'BAR' => 'Bar', // Bar
  'DISKO' => 'Discoteca', // Diskothek
  'HOTEL' => 'Hotel', // Hotel
  'HOSTEL' => 'Hotel', // Hotel
  'FREMDENZIMMER' => 'Bed and Breakfast', // Zimmer mit Frühstück
  'PENSION' => 'Pensione', // Pension
  'FITNESSTUDIO' => 'Palestra', // Fitnesscenter
  'SONNENSTUDIO' => 'Estetica / Solarium', // Schönheitssalon / Solarium
  'WERKSTATT' => 'Auto officina', // Autowerkstatt
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
  'INDUSTRIEHALLE' => 'Capannone Industriale', // Industriehalle
  'HALLE_LAGER' => 'Capannone', // Halle
  'MAIN_LANDWIRTSCHAFT' => 'Azienda Agricola', // landwirtschaftl. Betrieb
  'MAIN_STELLPLATZ' => 'Garage', // Garage
  'GESCHAEFTSLOKAL' => 'Negozio', // Geschäftslokal
  'BUERO_GESCHAEFTSLOKAL' => 'Negozio', // Geschäftslokal
  'BUERO_PRAXIS' => 'Ufficio', // Bürolokal
  'HAUS' => 'Stabile', // Gebäude / Stadthaus
  'AUSSTELLUNGSFLAECHE' => 'Showroom', // Ausstellungsraum
  'GAST' => 'Albergo', // Gasthof / Unterkünfte
    //'' => 'Casa di cura',                         // Kurhaus
    //'' => 'Magazzino',                            // Magazin
    //'' => 'Scuderia',                             // Ställe und Pferderennställe
    //'' => 'Stabilimento Balneare',                // Badeanlage
    //'' => 'Laboratorio',                          // Labor
    //'' => 'Altro',                                // Andere
);

// Mapping, Art der Gewerbefläche
$mapObjectTerrainType = array(
  'WEINBAU' => 'vigneto', // Weinberg
  'ACKERBAU' => 'seminativo', // Saatfeld
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
    if (array_search('main_grund', $object['type_path']) !== false) {
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

      $bebaubar = (isset($object['attributes']['verwaltung']['bebaubar_mit']['value'])) ?
          $object['attributes']['verwaltung']['bebaubar_mit']['value'] : array();
      if (!is_array($bebaubar))
        $bebaubar = array();
      if (array_search('acker', $bebaubar) !== false)
        $objectTerrainType = 'seminativo'; // Saatfeld
      else if (array_search('obstpflanzung', $bebaubar) !== false)
        $objectTerrainType = 'frutteto'; // Obstgarten
      else if (array_search('wald', $bebaubar) !== false)
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
  $zustand = (isset($object['attributes']['zustand']['zustand']['value'])) ?
      $object['attributes']['zustand']['zustand']['value'] : array();
  if (!is_array($zustand))
    $zustand = array();
  $alter = (isset($object['attributes']['zustand']['alter']['value'])) ?
      $object['attributes']['zustand']['alter']['value'] : '';
  $ausstattung = (isset($object['attributes']['ausstattung']['ausstattung_art']['value'])) ?
      $object['attributes']['ausstattung']['ausstattung_art']['value'] : '';
  if (array_search('renoviert_teil', $zustand) !== false)
    $objectStatus = 'ristrutturato'; // renoviert
  else if (array_search('renoviert_voll', $zustand) !== false)
    $objectStatus = 'ristrutturato'; // renoviert
  else if (array_search('modernisiert', $zustand) !== false)
    $objectStatus = 'ristrutturato'; // renoviert
  else if (array_search('saniert_teil', $zustand) !== false)
    $objectStatus = 'da ristrutturare'; // saniert
  else if (array_search('saniert_voll', $zustand) !== false)
    $objectStatus = 'da ristrutturare'; // saniert
  else if (array_search('baufaellig', $zustand) !== false)
    $objectStatus = 'in costruzione'; // Im Bau
  else if (array_search('entkernt', $zustand) !== false)
    $objectStatus = 'in costruzione'; // Im Bau
  else if (array_search('renovierungsbedarf_teil', $zustand) !== false)
    $objectStatus = 'in costruzione'; // Im Bau
  else if (array_search('renovierungsbedarf_voll', $zustand) !== false)
    $objectStatus = 'in costruzione'; // Im Bau
  else if (array_search('sanierungsbedarf_teil', $zustand) !== false)
    $objectStatus = 'in costruzione'; // Im Bau
  else if (array_search('sanierungsbedarf_voll', $zustand) !== false)
    $objectStatus = 'in costruzione'; // Im Bau
  else if (array_search('erstbezug', $zustand) !== false)
    $objectStatus = 'nuovo'; // Neu
  else if (array_search('erstbezug_nach_sanierung', $zustand) !== false)
    $objectStatus = 'nuovo'; // Neu
  else if (array_search('neuwertig', $zustand) !== false)
    $objectStatus = 'nuovo'; // Neu
  else if (array_search('gepflegt', $zustand) !== false)
    $objectStatus = 'buono'; // gut
  else if ($alter == 'neubau')
    $objectStatus = 'nuovo'; // Neu
  else if ($ausstattung == 'einfach')
    $objectStatus = 'abitabile'; // bewohnbar
  else if ($ausstattung == 'normal')
    $objectStatus = 'discreto'; // mittel, bewohnbar
  else if ($ausstattung == 'gehoben')
    $objectStatus = 'buono'; // gut
  else if ($ausstattung == 'luxus')
    $objectStatus = 'ottimo'; // großartig
  else
    $objectStatus = 'nd'; // keine Angaben


// Land (derzeit ausschließlich Italien)
  if (!isset($object['adress']['country']) || is_null($object['adress']['country'])) {
    if ($debugMode)
      echo '&gt; UNKNOWN COUNTRY<br/>';
    continue;
  }
  $objectLocationCountry = strtoupper($object['adress']['country']);
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
  $objectLocationPostal = (isset($object['adress']['postal'])) ?
      $object['adress']['postal'] : null;
  if (is_null($objectLocationPostal))
    $objectLocationPostal = '';
  $objectLocationLatitude = (isset($object['adress']['latitude'])) ?
      $object['adress']['latitude'] : null;
  if (!is_numeric($objectLocationLatitude))
    $objectLocationLatitude = null;
  $objectLocationLongitude = (isset($object['adress']['longitude'])) ?
      $object['adress']['longitude'] : null;
  if (!is_numeric($objectLocationLongitude))
    $objectLocationLongitude = null;
  //$objectLocationStreet = null;
  //if (isset($object['adress']['street']) && !is_null($object['adress']['street'])) {
  //  $objectLocationStreet = $object['adress']['street'];
  //  if (isset($object['adress']['street_nr']) && !is_null($object['adress']['street_nr']))
  //    $objectLocationStreet .= ' ' . $object['adress']['street_nr'];
  //}
  // Zimmerzahl
  $objectRooms = (isset($object['attributes']['flaechen']['anz_zimmer']['value'])) ?
      $object['attributes']['flaechen']['anz_zimmer']['value'] : null;
  if (!is_numeric($objectRooms))
    $objectRooms = 0;
  else
    $objectRooms = (int) $objectRooms;
  $objectBedrooms = (isset($object['attributes']['flaechen']['anz_schlafzimmer']['value'])) ?
      $object['attributes']['flaechen']['anz_schlafzimmer']['value'] : null;
  if (!is_numeric($objectBedrooms))
    $objectBedrooms = 0;
  else
    $objectBedrooms = (int) $objectBedrooms;
  $objectBathrooms = (isset($object['attributes']['flaechen']['anz_badezimmer']['value'])) ?
      $object['attributes']['flaechen']['anz_badezimmer']['value'] : null;
  if (!is_numeric($objectBathrooms))
    $objectBathrooms = 0;
  else
    $objectBathrooms = (int) $objectBathrooms;

  // Fläche
  $objectSize = null;
  $sizes = array('BRUTTOFLAECHE', 'GESAMTFLAECHE', 'GRUNDSTUECKSFLAECHE', 'WOHNFLAECHE', 'GEWERBEFLAECHE', 'VERKAUFSFLAECHE', 'STELLPLATZFLAECHE');
  foreach ($sizes as $size) {
    $key = strtolower($size);
    $val = (isset($object['attributes']['flaechen'][$key]['value'])) ?
        $object['attributes']['flaechen'][$key]['value'] : null;
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
  if ($object['action'] == 'miete')
    $prices = array('KALTMIETE', 'WARMMIETE');
  else if ($object['action'] == 'pacht')
    $prices = array('PACHT', 'PACHT_QM');
  else if ($object['action'] == 'erbpacht')
    $prices = array('PACHT', 'PACHT_QM');
  else if ($object['action'] == 'waz')
    $prices = array('PAUSCHALMIETE');
  else if ($object['action'] == 'kauf')
    $prices = array('KAUFPREIS');
  else {
    if ($debugMode)
      echo '&gt; UNSUPPORTED ACTION: ' . $object['action'] . '<br/>';
    continue;
  }
  foreach ($prices as $price) {
    $key = strtolower($price);
    $val = (isset($object['attributes']['preise'][$key]['value'])) ?
        $object['attributes']['preise'][$key]['value'] : null;
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
  $kueche = (isset($object['attributes']['ausstattung']['kueche']['value'])) ?
      $object['attributes']['ausstattung']['kueche']['value'] : null;
  if (is_array($kueche)) {
    if (array_search('wohnkueche', $kueche) !== false)
      $objectKitchen = 'Abitabile'; // Wohnküche
    else if (array_search('kochecke', $kueche) !== false)
      $objectKitchen = 'Angolo cottura'; // Kochecke

//else if (array_search('???',$kueche)!==false)
    //  $objectKitchen = 'Cucinotto'; // Kochnische
    else if (array_search('kleine_kueche', $kueche) !== false)
      $objectKitchen = 'Angolo cottura'; // 'halbe' Wohnküche
  }

  // Garage / Stellplatz
  $objectGarage = (isset($object['attributes']['flaechen']['anz_stellplaetze']['value'])) ?
      $object['attributes']['flaechen']['anz_stellplaetze']['value'] : null;
  if (!is_numeric($objectGarage))
    $objectGarage = 0;
  else
    $objectGarage = (int) $objectGarage;
  $objectGarageType = 'No';
  if ($objectGarage > 0) {
    $objectGarageType = 'PostoAuto';
    $arten = (isset($object['attributes']['flaechen']['stellplatzart']['value'])) ?
        $object['attributes']['flaechen']['stellplatzart']['value'] : null;
    if (is_array($arten)) {
      if (array_search('garage', $arten) !== false)
        $objectGarageType = 'Box'; // Box
      else if (array_search('carport', $arten) !== false)
        $objectGarageType = 'Rimessa'; // Schuppen / Carport
      else if (array_search('duplex', $arten) !== false)
        $objectGarageType = 'Rimessa'; // Schuppen / Carport
      else if (array_search('stellplatz', $arten) !== false)
        $objectGarageType = 'PostoAuto'; // Stellplatz
      else if (array_search('aussen', $arten) !== false)
        $objectGarageType = 'PostoAuto'; // Stellplatz
    }
  }

  // Heizung
  $objectHeating = null;
  $heizung = (isset($object['attributes']['ausstattung']['heizungsart']['value'])) ?
      $object['attributes']['ausstattung']['heizungsart']['value'] : null;
  $befeuerung = (isset($object['attributes']['ausstattung']['befeuerung']['value'])) ?
      $object['attributes']['ausstattung']['befeuerung']['value'] : null;
  if (is_array($heizung) && count($heizung) > 0) {
    if (array_search('autonom', $heizung) !== false)
      $objectHeating = 'Autonomo'; // autonome Heizung
    else if (array_search('zentral', $heizung) !== false)
      $objectHeating = 'Centralizzato'; // Zentralheizung
  }
  if ($objectHeating == null && is_array($befeuerung) && count($befeuerung) > 0) {
    if (array_search('fernwaerme', $befeuerung) !== false)
      $objectHeating = 'Teleriscaldamento'; // Fernheizung
  }
  //if ($objectHeating==null) {
  //  $objectHeating = 'Assente'; // fehlend
  //}
  // Gartennutzung
  $objectGarden = null;
  $garten = (isset($object['attributes']['ausstattung']['gartennutzung']['value'])) ?
      $object['attributes']['ausstattung']['gartennutzung']['value'] : null;
  if ($garten === true)
    $objectGarden = 'Privato'; // privat
  else if ($garten === false)
    $objectGarden = 'Nessuno'; // kein Garten

//else if ($garten===false)
  //  $objectGarden = 'Comune'; // gemeinsam
  // Terrasse
  $objectTerrace = null;
  $terrasse = (isset($object['attributes']['ausstattung']['balkon_terrasse']['value'])) ?
      $object['attributes']['ausstattung']['balkon_terrasse']['value'] : null;
  if ($terrasse === true)
    $objectTerrace = 'Y';
  else if ($terrasse === false)
    $objectTerrace = 'N';

  // Balkon
  $objectBalcony = null;
  $balkon = (isset($object['attributes']['ausstattung']['balkon_terrasse']['value'])) ?
      $object['attributes']['ausstattung']['balkon_terrasse']['value'] : null;
  if ($balkon === true)
    $objectBalcony = 'Y';
  else if ($balkon === false)
    $objectBalcony = 'N';

  // Aufzug
  $objectElevator = null;
  $lift = (isset($object['attributes']['ausstattung']['pers_lift']['value'])) ?
      $object['attributes']['ausstattung']['pers_lift']['value'] : null;
  if ($lift == null || $lift === false)
    $lift = (isset($object['attributes']['ausstattung']['last_lift']['value'])) ?
        $object['attributes']['ausstattung']['last_lift']['value'] : $lift;
  if ($lift === true)
    $objectElevator = 'Y';
  else if ($lift === false)
    $objectElevator = 'N';

  // Klimatisiert
  $objectAirCondition = null;
  $klima = (isset($object['attributes']['ausstattung']['klimatisiert']['value'])) ?
      $object['attributes']['ausstattung']['klimatisiert']['value'] : null;
  if ($klima === true)
    $objectAirCondition = 'Y';
  else if ($klima === false)
    $objectAirCondition = 'N';

  // Etage
  $objectFloorType = null;
  $objectFloor = (isset($object['attributes']['ausstattung']['etage']['value'])) ?
      $object['attributes']['ausstattung']['etage']['value'] : null;
  if (!is_numeric($objectFloor))
    $objectFloor = null;
  else {
    $objectFloor = (int) $objectFloor;

    if (array_search('dachwohnung', $object['type_path']) !== false)
      $objectFloorType = 'Attico'; // Dachterasse / Penthouse
    else if (array_search('penthousewohnung', $object['type_path']) !== false)
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
    else if (array_search('erdgeschosswohnung', $object['type_path']) !== false)
      $objectFloorType = 'Pianoterra'; // Ergeschoss

//else if (array_search('???',$object['type_path'])!==false)
    //  $objectFloorType = 'PianoNobile'; // ???
    else if (array_search('hochparterre', $object['type_path']) !== false)
      $objectFloorType = 'Rialzato'; // Hochparterre

//else if (array_search('???',$object['type_path'])!==false)
    //  $objectFloorType = 'Semiinterrato'; // Halb-Kellergeschoss
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectFloorType = 'Ultimo'; // zuletzt
  }

  // Etagenzahl
  $objectNumFloors = (isset($object['attributes']['ausstattung']['etage_gesamt']['value'])) ?
      $object['attributes']['ausstattung']['etage_gesamt']['value'] : null;
  if (!is_numeric($objectNumFloors))
    $objectNumFloors = null;
  else
    $objectNumFloors = (int) $objectNumFloors;

  // Mietvertrag
  $objectRentContract = null;
  if ($object['action'] == 'waz') {
    $objectRentContract = 'Transitorio'; // vorübergehend
  }
  else if ($object['action'] == 'miete') {
    if (array_search('studentenwg', $object['type_path']) !== false)
      $objectRentContract = 'Studenti'; // Schüler / Studenten
    else
      $objectRentContract = 'Concordato'; // nach Vereinbarung
  }

  // Möblierung
  $objectFurniture = null;
  $moebliert = (isset($object['attributes']['ausstattung']['moebliert']['value'])) ?
      $object['attributes']['ausstattung']['moebliert']['value'] : null;
  if ($moebliert == 'ja')
    $objectFurniture = 'Arredato'; // eingerichtet
  else if ($moebliert == 'nein')
    $objectFurniture = 'Non Arredato'; // nicht eingerichtet
  else if ($moebliert == 'teil')
    $objectFurniture = 'Parzialmente Arredato'; // teilweise eingerichtet


// Sicherheitsalarm
  $objectSecurityAlarm = null;
  $sicherheit = (isset($object['attributes']['ausstattung']['sicherheitstechnik']['value'])) ?
      $object['attributes']['ausstattung']['sicherheitstechnik']['value'] : null;
  if (is_array($sicherheit)) {
    if (array_search('alarmanlage', $sicherheit) !== false)
      $objectSecurityAlarm = 'Y';
    else
      $objectSecurityAlarm = 'N';
  }

  // Internet
  $objectNet = null;
  $technik = (isset($object['attributes']['ausstattung']['technik']['value'])) ?
      $object['attributes']['ausstattung']['technik']['value'] : null;
  if (is_array($technik)) {
    if (array_search('dv_verkabelung', $sicherheit) !== false)
      $objectNet = 'Y';
    else
      $objectNet = 'N';
  }

  // Voraussetzungen für die Immobilie
  $objectFreeConditions = (isset($objectTexts['preis_beschr'][$lang])) ?
      $objectTexts['preis_beschr'][$lang] : null;

  // Kran
  $objectOverheadCrane = null;
  $kran = (isset($object['attributes']['ausstattung']['kran']['value'])) ?
      $object['attributes']['ausstattung']['kran']['value'] : null;
  if ($kran === true)
    $objectOverheadCrane = 'Yes';
  else if ($kran === false)
    $objectOverheadCrane = 'No';

  // Hallenhöhe
  $objectBeamHeight = (isset($object['attributes']['ausstattung']['hallenhoehe']['value'])) ?
      $object['attributes']['ausstattung']['hallenhoehe']['value'] : null;
  if (!is_numeric($objectBeamHeight))
    $objectBeamHeight = null;
  else
    $objectBeamHeight = (int) $objectBeamHeight;

  // Bürofläche
  $objectOfficeSize = (isset($object['attributes']['flaechen']['bueroflaeche']['value'])) ?
      $object['attributes']['flaechen']['bueroflaeche']['value'] : null;
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
