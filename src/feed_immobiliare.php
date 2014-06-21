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
header('Content-Type: text/xml; charset=utf-8');

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

// Cache-Datei des Feeds
$feedFile = IMMOTOOL_BASE_PATH . 'cache/feed.immobiliare_' . $lang . '.xml';
if (is_file($feedFile)) {
  $feed = immotool_functions::read_file($feedFile);
  echo $feed;
  return;
}

// URL der Seite ermitteln
$siteUrl = ($_SERVER['HTTPS'] != '') ? 'https://' : 'http://';
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
$feed .= '      <name>' . $translations['labels']['title'] . '</name>' . "\n";
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

foreach (immotool_functions::list_available_objects() as $id) {
  $object = immotool_functions::get_object($id);
  if (!is_array($object))
    continue;

  $objectTexts = immotool_functions::get_text($id);
  if (!is_array($objectTexts))
    continue;

  // Exposé-URL
  $objectUrl = immotool_functions::get_expose_url($id, $lang, $setup->ExposeUrlTemplate);

  // Zeitpunkt der letzten Änderung
  $feedStamp = date('Y-m-d\TH:i:sO', immotool_functions::get_object_stamp($id));
  $feedStamp = substr($feedStamp, 0, -2) . ':' . substr($feedStamp, -2);

  // Mailadresse
  $objectMail = (isset($object['mail'])) ? $object['mail'] : null;
  if (!is_string($objectMail) || strlen(trim($objectMail)) == 0)
    $objectMail = $setup->MailFrom;

  // Vermartungsart
  $objectAction = null;
  if ($object['action'] == 'miete')
    $objectAction = 'R';
  else if ($object['action'] == 'pacht')
    $objectAction = 'R';
  else if ($object['action'] == 'erbpacht')
    $objectAction = 'R';
  else if ($object['action'] == 'waz')
    $objectAction = 'R';
  else if ($object['action'] == 'kauf')
    $objectAction = 'S';
  else
    continue;

  // Kategorie
  $objectCat = null;
  if (array_search('ferienhaus', $object['type_path']) !== false)
    $objectCat = 'V';
  else if (array_search('main_gewerbe', $object['type_path']) !== false)
    $objectCat = 'C';
  else if (array_search('main_landwirtschaft', $object['type_path']) !== false)
    $objectCat = 'C';
  else if (array_search('main_grund', $object['type_path']) !== false)
    $objectCat = 'C';
  else if (array_search('main_wohnen', $object['type_path']) !== false)
    $objectCat = 'R';
  else if (array_search('main_stellplatz', $object['type_path']) !== false)
    $objectCat = 'R';
  else
    continue;

  // Objektart
  $objectType = null;
  $objectBusinessType = null;
  $objectBusinessTypeCat = null;
  $objectTerrainType = null;

  // Immobilienarten, die nur für Wohn- & Ferien-Objekte gelten
  if ($objectCat == 'R' || $objectCat == 'V') {

    // Wohnungen
    if (array_search('appartmentwohnung', $object['type_path']) !== false)
      $objectType = 'Appartamento';
    else if (array_search('penthousewohnung', $object['type_path']) !== false)
      $objectType = 'Attico';
    else if (array_search('loftwohnung', $object['type_path']) !== false)
      $objectType = 'Loft';
    else if (array_search('dachwohnung', $object['type_path']) !== false)
      $objectType = 'Mansarda';

    // Häuser
    else if (array_search('mfh', $object['type_path']) !== false)
      $objectType = 'Multiproprietà';
    else if (array_search('mfh_gewerbe', $object['type_path']) !== false)
      $objectType = 'Multiproprietà';
    else if (array_search('schloss', $object['type_path']) !== false)
      $objectType = 'Palazzo';
    else if (array_search('burg', $object['type_path']) !== false)
      $objectType = 'Palazzo';
    else if (array_search('chalet', $object['type_path']) !== false)
      $objectType = 'Palazzo';
    else if (array_search('besonderes_haus', $object['type_path']) !== false)
      $objectType = 'Rustico';
    else if (array_search('villa', $object['type_path']) !== false)
      $objectType = 'Villa';
    else if (array_search('efh', $object['type_path']) !== false)
      $objectType = 'Villa';
    else if (array_search('landhaus', $object['type_path']) !== false)
      $objectType = 'Villa';
    else if (array_search('reihenhaus', $object['type_path']) !== false)
      $objectType = 'Villetta a schiera';
    else if (array_search('haus', $object['type_path']) !== false)
      $objectType = 'Casa Indipendente';

    // Stellplätze
    else if (array_search('main_stellplatz', $object['type_path']) !== false)
      $objectType = 'Garage';

    // keine Ahnung???
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectType = 'Casale';
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectType = 'Stabile';
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectType = 'Open Space';
    // Sonstiges
    else
      $objectType = 'Other';
  }

  // Immobilienarten, die nur für Gewerbe-Objekte gelten
  else if ($objectCat == 'C') {

    // Grundstücke
    if (array_search('main_grund', $object['type_path']) !== false) {
      $objectBusinessTypeCat = 'Terreno';

      if (array_search('wohngrund', $object['type_path']) !== false)
        $objectBusinessType = 'Residenziale'; // Wohn-Bauland
      else if (array_search('gewerbegrund', $object['type_path']) !== false)
        $objectBusinessType = 'Commerciale'; // Gewerbe Grundstücke
      else if (array_search('industriegrund', $object['type_path']) !== false)
        $objectBusinessType = 'Industriale'; // Industrial Land
      else if (array_search('land_forstgrund', $object['type_path']) !== false)
        $objectBusinessType = 'Agricolo'; // landwirtschaftliche Grundstück oder Gebäude
      else
        $objectBusinessType = 'Residenziale';
    }

    // weiteres Gewerbe
    else {
      //$objectBusinessTypeCat = 'Attività';
      $objectBusinessTypeCat = 'Immobile';

      if (array_search('einkaufszentrum', $object['type_path']) !== false)
        $objectBusinessType = 'Centro commerciale'; // Shopping-Center
      else if (array_search('bauernhof', $object['type_path']) !== false)
        $objectBusinessType = 'Azienda agricola'; // Bauernhof
      else if (array_search('restaurant', $object['type_path']) !== false)
        $objectBusinessType = 'Ristorante'; // Restaurant
      else if (array_search('bar', $object['type_path']) !== false)
        $objectBusinessType = 'Bar'; // Bar

//else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Pizzeria'; // Pizzeria
      else if (array_search('bistro', $object['type_path']) !== false)
        $objectBusinessType = 'Pizza Al Taglio'; // Pizza, Fast Food, Kebab

//else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Pub'; // Pub
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Alimentari'; // Nahrung
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Rosticceria'; // Feinkostgeschäft / Delikatessengeschäft
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Pasticceria'; // Süßwaren
      else if (array_search('disko', $object['type_path']) !== false)
        $objectBusinessType = 'Discoteca'; // Disko
      else if (array_search('hotel', $object['type_path']) !== false)
        $objectBusinessType = 'Hotel'; // Hotels

//else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Bed and Breakfast'; // Bed & Breakfest
      else if (array_search('pension', $object['type_path']) !== false)
        $objectBusinessType = 'Pensione'; // Pension

//else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Gelateria'; // Eisdiele
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Panetteria'; // Bäckerei
      else if (array_search('gastronomie', $object['type_path']) !== false)
        $objectBusinessType = 'Altro | Alimentare'; // Sonstiges Essen
      else if (array_search('ladenlokal', $object['type_path']) !== false)
        $objectBusinessType = 'Altro | Alimentare'; // Sonstiges Essen
      else if (array_search('geschaeftslokal', $object['type_path']) !== false)
        $objectBusinessType = 'Altro | Alimentare'; // Sonstiges Essen
      else if (array_search('kaufhaus', $object['type_path']) !== false)
        $objectBusinessType = 'Negozio'; // Geschäft / Kaufhaus
      else if (array_search('geschaeftshaus', $object['type_path']) !== false)
        $objectBusinessType = 'Negozio'; // Geschäft / Kaufhaus
      else if (array_search('wohn_geschaeftshaus', $object['type_path']) !== false)
        $objectBusinessType = 'Negozio'; // Geschäft / Kaufhaus

//else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Ferramenta'; // Eisenwaren
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Casalinghi'; // Haushalt
      else if (array_search('sportanlage', $object['type_path']) !== false)
        $objectBusinessType = 'Palestra'; // Sporthalle / Turnhalle

//else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Abbigliamento'; // Kleidung
      else if (array_search('sonnenstudio', $object['type_path']) !== false)
        $objectBusinessType = 'Estetica / Solarium'; // Ästhetik / Solarium

//else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Parrucchiere uomo/donna'; // Friseur Mann / Frau
      else if (array_search('werkstatt', $object['type_path']) !== false)
        $objectBusinessType = 'Auto officina'; // KFZ-Betriebe

//else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Videonoleggio'; // Videothek
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Tabaccheria'; // Tabakladen
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Tintoria'; // Subunternehmer
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Lavanderia'; // Wäscheservice
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Cartoleria'; // Schreibwarenladen
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Libreria'; // Bibliothek
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Informatica'; // Computer ???
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Telefonia'; // Telefonie ???
      else if (array_search('kiosk', $object['type_path']) !== false)
        $objectBusinessType = 'Edicola'; // Zeitschriften
      else if (array_search('laden', $object['type_path']) !== false)
        $objectBusinessType = 'Altro | Non alimentare'; // Sonstiges, Non-Food
      else if (array_search('freizeit_sport', $object['type_path']) !== false)
        $objectBusinessType = 'Giochi'; // Sport & Spiel

//else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Scommesse'; // Wetten
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Capannone'; // ???
      else if (array_search('parkhaus', $object['type_path']) !== false)
        $objectBusinessType = 'Garage'; // Garage / Stellplatz
      else if (array_search('buero', $object['type_path']) !== false)
        $objectBusinessType = 'Ufficio'; // Büro

//else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Stabile'; // ???
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Capannone Industriale'; // ???
      else if (array_search('krankenhaus', $object['type_path']) !== false)
        $objectBusinessType = 'Casa di cura'; // Krankenhaus
      else if (array_search('sanatorium', $object['type_path']) !== false)
        $objectBusinessType = 'Casa di cura'; // Krankenhaus
      else if (array_search('lagerhalle', $object['type_path']) !== false)
        $objectBusinessType = 'Magazzino'; // Lagerhaus / Abstellraum / Depot
      else if (array_search('lagerflaeche', $object['type_path']) !== false)
        $objectBusinessType = 'Magazzino'; // Lagerhaus / Abstellraum / Depot
      else if (array_search('halle_lager', $object['type_path']) !== false)
        $objectBusinessType = 'Magazzino'; // Lagerhaus / Abstellraum / Depot
      else if (array_search('ausstellungsflaeche', $object['type_path']) !== false)
        $objectBusinessType = 'Showroom'; // Ausstellungsraum

//else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Scuderia'; // Stall
      //else if (array_search('???',$object['type_path'])!==false)
      //  $objectBusinessType = 'Stabilimento Balneare'; // Badeanstalt
      else if (array_search('atelierwohnung', $object['type_path']) !== false)
        $objectBusinessType = 'Laboratorio'; // Labor
      else if (array_search('hotel', $object['type_path']) !== false)
        $objectBusinessType = 'Albergo'; // Hotel / Unterkunft
      else if (array_search('hostel', $object['type_path']) !== false)
        $objectBusinessType = 'Albergo'; // Hotel / Unterkunft
      else if (array_search('gast', $object['type_path']) !== false)
        $objectBusinessType = 'Albergo'; // Hotel / Unterkunft
      else
        $objectBusinessType = 'Altro'; // Andere
    }

    // Art der landwirtschaftlichen Fläche
    $bebaubar = (isset($object['attributes']['verwaltung']['bebaubar_mit']['value'])) ?
        $object['attributes']['verwaltung']['bebaubar_mit']['value'] : array();
    if (!is_array($bebaubar))
      $bebaubar = array();
    if (array_search('ackerbau', $object['type_path']) !== false) {
      $baumbestand = (isset($object['attributes']['ausstattung']['baumbestand']['value'])) ?
          $object['attributes']['ausstattung']['baumbestand']['value'] : null;
      if ($baumbestand === true)
        $objectTerrainType = 'seminativo arborato'; // Ackerland mit Baumbestand
      else
        $objectTerrainType = 'seminativo'; // Ackerland
    }
    else if (array_search('weinbau', $object['type_path']) !== false)
      $objectTerrainType = 'vigneto'; // Weinberge
    else if (array_search('acker', $bebaubar) !== false)
      $objectTerrainType = 'seminativo'; // Ackerland
    else if (array_search('obstpflanzung', $bebaubar) !== false)
      $objectTerrainType = 'frutteto'; // Obst
    else if (array_search('wald', $bebaubar) !== false)
      $objectTerrainType = 'pascolo arborato'; // Waldweiden

//else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'agrumeto'; // Zitrusfrüchte
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'bosco alto fusto'; // Hochwald
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'bosco ceduo'; // Niederwald
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'bosco misto'; // Mischwald
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'canneto'; // Schilf
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'castagneto da frutto'; // Kastanien Obst
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'colture speciali'; // Sonderkulturen
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'gelseto'; // ???
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'giardino'; // Garten
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'incolto produttivo'; // ???
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'incolto sterile'; // ???
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'orto'; // ???
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'orto irriguo'; // Gartenbewässerung
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'pascolo'; // Weide
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'pascolo cespugliato'; // Weide, Busch
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'prato'; // Rasen
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'prato arborato'; // Wiese mit Baumbestand
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'prato a marcita'; // Rasen Gang
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'prato irriguo'; // Rasen-Bewässerung
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'risaia stabile'; // ???
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'seminativo arborato irriguo'; // bewässerten Anbauflächen mit Baumbestand
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'seminativo irriguo'; // bewässerte Kulturen
    //else if (array_search('???',$object['type_path'])!==false)
    //  $objectTerrainType = 'uliveto'; // Olivenöl
  }

  else {
    continue;
  }

  // Zustand des Gebäudes
  $objectStatus = null;
  $zustand = (isset($object['attributes']['zustand']['zustand']['value'])) ?
      $object['attributes']['zustand']['zustand']['value'] : array();
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


// Ortsangaben
  if (!isset($object['adress']['country']) || is_null($object['adress']['country']))
    continue;
  $objectLocationCountry = strtoupper($object['adress']['country']);
  if ($objectLocationCountry != 'IT')
    continue;
  $objectLocationArea1 = (isset($object['other']['immobiliare']['areaName'])) ?
      $object['other']['immobiliare']['areaName'] : null;
  if (is_null($objectLocationArea1))
    $objectLocationArea1 = '';
  $objectLocationArea2 = (isset($object['other']['immobiliare']['subAreaName'])) ?
      $object['other']['immobiliare']['subAreaName'] : null;
  if (is_null($objectLocationArea2))
    $objectLocationArea2 = '';
  $objectLocationCity = (isset($object['other']['immobiliare']['cityName'])) ?
      $object['other']['immobiliare']['cityName'] : null;
  if (is_null($objectLocationCity))
    $objectLocationCity = '';
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
  $objectLocationStreet = null;
  if (isset($object['adress']['street']) && !is_null($object['adress']['street'])) {
    $objectLocationStreet = $object['adress']['street'];
    if (isset($object['adress']['street_nr']) && !is_null($object['adress']['street_nr']))
      $objectLocationStreet .= ' ' . $object['adress']['street_nr'];
  }

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
  else
    continue;
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
  $heizung = (isset($object['attributes']['ausstattung']['kueche']['value'])) ?
      $object['attributes']['ausstattung']['kueche']['value'] : null;
  if (is_array($heizung) && count($heizung) > 0) {
    if (array_search('autonom', $heizung) !== false)
      $objectHeating = 'Autonomo'; // autonome Heizung
    else if (array_search('fernwaerme', $heizung) !== false)
      $objectHeating = 'Teleriscaldamento'; // Fernheizung
    else if (array_search('zentral', $heizung) !== false)
      $objectHeating = 'Centralizzato'; // Zentralheizung
  }
  else {
    $objectHeating = 'Assente'; // Keine
  }

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
      $imgUrl = ($_SERVER['HTTPS'] != '') ? 'https://' : 'http://';
      $imgUrl .= $_SERVER['SERVER_NAME'];
      $imgUrl .= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
      $imgUrl .= '/data/' . $object['id'] . '/' . $img['name'];
      $objectPictures[] = $imgUrl;
    }
  }

  // Immobilie in den Feed eintragen
  $txtCounter = 0;
  $picCounter = 0;
  $feed .= '    <property operation="force">' . "\n";
  $feed .= '      <unique-id>' . $id . '</unique-id>' . "\n";
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
  $feed .= '        <office-name>' . $translations['labels']['title'] . '</office-name>' . "\n";
  $feed .= '        <email>' . $objectMail . '</email>' . "\n";
  $feed .= '      </agent>' . "\n";
  $feed .= '      <location>' . "\n";
  $feed .= '        <country-code>' . $objectLocationCountry . '</country-code>' . "\n";
  $feed .= '        <administrative-area>' . $objectLocationArea1 . '</administrative-area>' . "\n";
  $feed .= '        <sub-administrative-area>' . $objectLocationArea2 . '</sub-administrative-area>' . "\n";
  if (!is_numeric($objectLocationCityCode) || $objectLocationCityCode <= 0)
    $feed .= '        <city>' . $objectLocationCity . '</city>' . "\n";
  else
    $feed .= '        <city code="' . $objectLocationCityCode . '">' . $objectLocationCity . '</city>' . "\n";
  $feed .= '        <locality>' . "\n";
  $feed .= '          <postal-code>' . $objectLocationPostal . '</postal-code>' . "\n";
  //$feed .= '          <neighbourhood type=""></neighbourhood>' . "\n";
  if ($objectLocationStreet != null) {
    $feed .= '          <thoroughfare display="no">' . $objectLocationStreet . '</thoroughfare>' . "\n";
  }
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
    $feed .= '        <free-conditions><![CDATA[' . $objectFreeConditions . ']]</free-conditions>' . "\n";
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
}
$feed .= '  </properties>' . "\n";
$feed .= '</feed>';

// Feed cachen
$fh = fopen($feedFile, 'w') or die('can\'t write file: ' . $feedFile);
fwrite($fh, $feed);
fclose($fh);

// Feed ausgeben
echo $feed;
