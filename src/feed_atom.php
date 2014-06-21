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
 * Website-Export, Darstellung des Atom-Feeds.
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
header('Content-Type: application/atom+xml; charset=utf-8');

// Konfiguration ermitteln
$setup = new immotool_setup_feeds();
if (is_callable(array('immotool_myconfig', 'load_config_feeds')))
  immotool_myconfig::load_config_feeds($setup);
immotool_functions::init($setup);
if (!$setup->PublishAtomFeed)
  die('Atom-Feed is disabled!');

// Übersetzungen ermitteln
$translations = null;
$lang = (isset($_REQUEST[IMMOTOOL_PARAM_LANG])) ? $_REQUEST[IMMOTOOL_PARAM_LANG] : $setup->DefaultLanguage;
$lang = immotool_functions::init_language($lang, $setup->DefaultLanguage, $translations);
if (!is_array($translations))
  die('Can\'t load translations!');

// Cache-Datei des Feeds
$feedFile = IMMOTOOL_BASE_PATH . 'cache/feed.atom_' . $lang . '.xml';
if (is_file($feedFile)) {
  $feed = immotool_functions::read_file($feedFile);
  echo $feed;
  return;
}

// URL des Feed-Skriptes ermitteln
$feedUrl = ($_SERVER['HTTPS'] != '') ? 'https://' : 'http://';
$feedUrl .= $_SERVER['SERVER_NAME'];
$feedUrl .= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
$feedUrl .= '/feed_atom.php';
$feedUrl .= '?' . IMMOTOOL_PARAM_LANG . '=' . $lang;

// Timestamp
$feedStamp = gmdate('Y-m-d\TH:i:s\Z');

// Feed erzeugen
$feed = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$feed .= '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">' . "\n";
$feed .= '  <id>' . $feedUrl . '</id>' . "\n";
$feed .= '  <link rel="self" type="application/atom+xml" href="' . $feedUrl . '" />' . "\n";
$feed .= '  <title>' . $translations['labels']['title'] . '</title>' . "\n";
//$feed .= '  <subtitle>'.$translations['labels']['title'].'</subtitle>' . "\n";
$feed .= '  <generator uri="http://www.openestate.org" version="' . IMMOTOOL_SCRIPT_VERSION . '">OpenEstate-ImmoTool, PHP-Export</generator>' . "\n";
$feed .= '  <rights>' . $translations['labels']['title'] . '</rights>' . "\n";
$feed .= '  <updated>' . $feedStamp . '</updated>' . "\n";
$feed .= '  <dc:creator>' . $translations['labels']['title'] . '</dc:creator>' . "\n";
$feed .= '  <dc:date>' . $feedStamp . '</dc:date>' . "\n";
$feed .= '  <dc:language>' . $lang . '</dc:language>' . "\n";
$feed .= '  <dc:rights>' . $translations['labels']['title'] . '</dc:rights>' . "\n";

// absteigende Sortierung, nach Datum der letzten Änderung
$ids = array();
foreach (immotool_functions::list_available_objects() as $id) {
  $stamp = immotool_functions::get_object_stamp($id);
  if ($stamp == null)
    $stamp = 0;

  if (!isset($ids[$stamp]))
    $ids[$stamp] = array();
  $ids[$stamp][] = $id;
}
$stamps = array_keys($ids);
rsort($stamps, SORT_NUMERIC);

// Immobilien in den Feed schreiben
$counter = 0;
foreach ($stamps as $stamp) {
  foreach ($ids[$stamp] as $id) {
    $object = immotool_functions::get_object($id);
    if (!is_array($object))
      continue;

    // Exposé-URL ermitteln
    $objectUrl = immotool_functions::get_expose_url($id, $lang, $setup->ExposeUrlTemplate);

    // Titel ermitteln
    $objectTitle = $object['title'][$lang];
    if (isset($object['nr']))
      $objectTitle = $object['nr'] . ' » ' . $objectTitle;
    else
      $objectTitle = '#' . $id . ' » ' . $objectTitle;

    // Zusammenfassung ermitteln
    $objectSummary = '';
    $objectTexts = immotool_functions::get_text($id);
    if (isset($objectTexts['kurz_beschr'][$lang]))
      $objectSummary = $objectTexts['kurz_beschr'][$lang];
    else if (isset($objectTexts['objekt_beschr'][$lang]))
      $objectSummary = $objectTexts['objekt_beschr'][$lang];
    else
      $objectSummary = $object['title'][$lang];

    // Immobilie in den Feed eintragen
    $feed .= '  <entry>' . "\n";
    $feed .= '    <id>' . $objectUrl . '</id>' . "\n";
    $feed .= '    <link href="' . $objectUrl . '" />' . "\n";
    $feed .= '    <title>' . $objectTitle . '</title>' . "\n";
    $feed .= '    <author>' . "\n";
    $feed .= '      <name>' . $translations['labels']['title'] . '</name>' . "\n";
    $feed .= '    </author>' . "\n";
    $feed .= '    <summary type="text"><![CDATA[' . $objectSummary . ']]></summary>' . "\n";
    if (!is_null($objectStamp))
      $feed .= '    <updated>' . gmdate('Y-m-d\TH:i:s\Z', $stamp) . '</updated>' . "\n";
    $feed .= '    <dc:creator>' . $translations['labels']['title'] . '</dc:creator>' . "\n";
    $feed .= '  </entry>' . "\n";

    // ggf. abbrechen, wenn das Maximum für Feed-Einträge erreicht ist
    $counter++;
    if (is_numeric($setup->AtomFeedLimit) && $setup->AtomFeedLimit > 0 && $setup->AtomFeedLimit <= $counter)
      break;
  }
}
$feed .= '</feed>';

// Feed cachen
$fh = fopen($feedFile, 'w') or die('can\'t write file: ' . $feedFile);
fwrite($fh, $feed);
fclose($fh);

// Feed ausgeben
echo $feed;
