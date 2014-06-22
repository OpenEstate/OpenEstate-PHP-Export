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
 * @copyright 2009-2011, OpenEstate.org
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

// Titel ermitteln
$feedTitle = htmlspecialchars($translations['labels']['title']);

// Cache-Datei des Feeds
$feedFile = IMMOTOOL_BASE_PATH . 'cache/feed.atom_' . $lang . '.xml';
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

// URL des Feed-Skriptes ermitteln
$feedUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '') ? 'https://' : 'http://';
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
$feed .= '  <title>' . $feedTitle . '</title>' . "\n";
//$feed .= '  <subtitle>'.$feedTitle.'</subtitle>' . "\n";
$feed .= '  <generator uri="http://www.openestate.org" version="' . IMMOTOOL_SCRIPT_VERSION . '">OpenEstate-ImmoTool, PHP-Export</generator>' . "\n";
$feed .= '  <rights>' . $feedTitle . '</rights>' . "\n";
$feed .= '  <updated>' . $feedStamp . '</updated>' . "\n";
$feed .= '  <dc:creator>' . $feedTitle . '</dc:creator>' . "\n";
$feed .= '  <dc:date>' . $feedStamp . '</dc:date>' . "\n";
$feed .= '  <dc:language>' . $lang . '</dc:language>' . "\n";
$feed .= '  <dc:rights>' . $feedTitle . '</dc:rights>' . "\n";

if ($debugMode) {
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
  echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">';
  echo '  <head>';
  echo '    <title>Atom-Feed Debugger</title>';
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
  echo '  <h2>Atom-Feed Debugger</h2>';
}

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

    // Exposé-URL ermitteln
    $objectUrl = immotool_functions::get_expose_url($id, $lang, $setup->ExposeUrlTemplate, true);

    // Titel ermitteln
    $objectTitle = $object['title'][$lang];
    if (isset($object['nr']))
      $objectTitle = $object['nr'] . ' » ' . $objectTitle;
    else
      $objectTitle = '#' . $id . ' » ' . $objectTitle;

    // Zusammenfassung ermitteln
    $objectSummary = '';
    if (isset($objectTexts['short_description'][$lang]))
      $objectSummary = $objectTexts['short_description'][$lang];
    else if (isset($objectTexts['detailled_description'][$lang]))
      $objectSummary = $objectTexts['detailled_description'][$lang];
    else
      $objectSummary = $object['title'][$lang];

    // Immobilie in den Feed eintragen
    $feed .= '  <entry>' . "\n";
    $feed .= '    <id>' . $objectUrl . '</id>' . "\n";
    $feed .= '    <link href="' . $objectUrl . '" />' . "\n";
    $feed .= '    <title>' . htmlspecialchars($objectTitle) . '</title>' . "\n";
    $feed .= '    <author>' . "\n";
    $feed .= '      <name>' . $feedTitle . '</name>' . "\n";
    $feed .= '    </author>' . "\n";
    $feed .= '    <summary type="text"><![CDATA[' . $objectSummary . ']]></summary>' . "\n";
    if (!is_null($stamp))
      $feed .= '    <updated>' . gmdate('Y-m-d\TH:i:s\Z', $stamp) . '</updated>' . "\n";
    else
      $feed .= '    <updated>' . $feedStamp . '</updated>' . "\n";
    $feed .= '    <dc:creator>' . $feedTitle . '</dc:creator>' . "\n";
    $feed .= '  </entry>' . "\n";

    if ($debugMode)
      echo '&gt; OK<br/>';

    // ggf. abbrechen, wenn das Maximum für Feed-Einträge erreicht ist
    $counter++;
    if (is_numeric($setup->AtomFeedLimit) && $setup->AtomFeedLimit > 0 && $setup->AtomFeedLimit <= $counter) {
      if ($debugMode)
        echo '&gt; STOP, reached limit of ' . $setup->AtomFeedLimit . ' entries<br/>';
      break;
    }
  }
}
$feed .= '</feed>';

// Debug-Ausgabe des Feeds
if ($debugMode) {
  echo '<h2>Generated XML</h2>';
  echo '<textarea style="width:95%; height:30em; margin-bottom:1em;" readonly="readonly">' . htmlspecialchars($feed) . '</textarea>';
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
