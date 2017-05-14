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
 * Website-Export, Darstellung des RSS-Feeds.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
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
if (!$setup->PublishRssFeed) {
  if (!headers_sent()) {
    // 500-Fehlercode zurückliefern,
    // wenn der Feed in der Konfiguration deaktiviert wurde
    header('HTTP/1.0 500 Internal Server Error');
  }
  echo 'RSS feed is disabled!';
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
$feedFile = IMMOTOOL_BASE_PATH . 'cache/feed.rss_' . $lang . '.xml';
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
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '') ? 'https://' : 'http://';
$baseUrl .= $_SERVER['SERVER_NAME'];
$baseUrl .= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')) . '/';
$feedUrl = $baseUrl . 'feed_rss.php';
$feedUrl .= '?' . IMMOTOOL_PARAM_LANG . '=' . $lang;

// Timestamp
$feedStamp = date('r');

// Titel ermitteln
$feedTitle = htmlspecialchars($translations['labels']['title']);

// Feed erzeugen
$feed = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$feed .= '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
$feed .= '  <channel>' . "\n";
$feed .= '    <title>' . $feedTitle . '</title>' . "\n";
$feed .= '    <link>' . $feedUrl . '</link>' . "\n";
$feed .= '    <description>' . $feedTitle . '</description>' . "\n";
$feed .= '    <language>' . $lang . '</language>' . "\n";
$feed .= '    <copyright>' . $feedTitle . '</copyright>' . "\n";
$feed .= '    <pubDate>' . $feedStamp . '</pubDate>' . "\n";
$feed .= '    <lastBuildDate>' . $feedStamp . '</lastBuildDate>' . "\n";
$feed .= '    <generator>OpenEstate-ImmoTool, PHP-Export v' . IMMOTOOL_SCRIPT_VERSION . '</generator>' . "\n";
$feed .= '    <atom:link href="' . $feedUrl . '" rel="self" type="application/rss+xml" />' . "\n";
$feed .= '    <dc:creator>' . $feedTitle . '</dc:creator>' . "\n";

if ($debugMode) {
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
  echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">';
  echo '  <head>';
  echo '    <title>RSS-Feed Debugger</title>';
  echo '    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />';
  echo '    <meta http-equiv="pragma" content="no-cache" />';
  echo '    <meta http-equiv="cache-control" content="no-cache" />';
  echo '    <meta http-equiv="expires" content="0" />';
  echo '    <meta http-equiv="imagetoolbar" content="no" />';
  echo '    <meta name="MSSmartTagsPreventParsing" content="true" />';
  echo '    <meta name="generator" content="OpenEstate-ImmoTool" />';
  echo '    <meta name="robots" content="noindex,follow" />';
  echo '    <link rel="stylesheet" href="style.php" />';
  echo '  </head>';
  echo '  <body>';
  echo '  <h2>RSS-Feed Debugger</h2>';
}

// ID's der darzustellenden Immobilien gemäß Sortierung ermitteln
$ids = array();
$orderBy = @$setup->OrderBy;
$orderDir = @$setup->OrderDir;
$orderObj = (is_string($orderBy)) ?
    immotool_functions::get_order($orderBy) : null;

// gewählte Sortierung durchführen
if ($orderObj != null && $orderObj->readOrRebuild($setup->CacheLifeTime)) {
  $items = $orderObj->getItems($lang);
  if (!is_array($items)) {
    if (!headers_sent()) {
      // 500-Fehlercode zurückliefern,
      // wenn die Sortierung nicht durchgeführt werden konnte
      header('HTTP/1.0 500 Internal Server Error');
    }
    echo 'Ordering by \'' . $orderBy . '\' failed!';
    exit;
  }
  if ($orderDir == 'desc') {
    $ids = array_reverse($items);
  }
  else {
    $ids = $items;
  }
}

// absteigende Sortierung, nach Datum der letzten Änderung
else {
  $items = array();
  foreach (immotool_functions::list_available_objects() as $id) {
    $stamp = immotool_functions::get_object_stamp($id);
    if ($stamp == null)
      $stamp = 0;

    if (!isset($items[$stamp]))
      $items[$stamp] = array();
    $items[$stamp][] = $id;
  }
  $stamps = array_keys($items);
  rsort($stamps, SORT_NUMERIC);
  foreach ($stamps as $stamp) {
    foreach ($items[$stamp] as $id) {
      $ids[] = $id;
    }
  }
}

// Immobilien in den Feed schreiben
$counter = 0;
foreach ($ids as $id) {
  $object = immotool_functions::get_object($id);
  if ($debugMode)
    echo '<h3 style="margin-top:1em;margin-bottom:0;"><a href="expose.php?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $id . '">property #' . $id . '</a></h3>';
  if (!is_array($object)) {
    if ($debugMode)
      echo '&gt; NOT FOUND<br/>';
    continue;
  }
  $objectStamp = immotool_functions::get_object_stamp($id);
  $objectTexts = immotool_functions::get_text($id);
  if (!is_array($objectTexts))
    $objectTexts = array();

  // Exposé-URL ermitteln
  $objectUrl = immotool_functions::get_expose_url($id, $lang, $setup->ExposeUrlTemplate, true);

  // Titel ermitteln
  $objectTitle = $object['title'][$lang];
  if (isset($object['nr']))
    $objectTitle = trim($object['nr'] . ' » ' . $objectTitle);
  else
    $objectTitle = trim('#' . $id . ' » ' . $objectTitle);

  // Zusammenfassung ermitteln
  $objectSummary = null;
  if (is_null($objectSummary) && isset($objectTexts['short_description']))
    $objectSummary = immotool_functions::write_attribute_value('descriptions', 'short_description', $objectTexts['short_description'], $translations, $lang);
  if (is_null($objectSummary) && isset($objectTexts['detailled_description']))
    $objectSummary = immotool_functions::write_attribute_value('descriptions', 'detailled_description', $objectTexts['detailled_description'], $translations, $lang);
  if (is_null($objectSummary) && isset($object['title'][$lang]))
    $objectSummary = $object['title'][$lang];
  if (is_null($objectSummary))
    $objectSummary = '';

  // ggf. Bild in Zusammenfassung einfügen
  if ($setup->RssFeedWithImage === true && isset($object['images'][0]['thumb']) && is_string($object['images'][0]['thumb'])) {
    $titleImg = 'data/' . $object['id'] . '/' . $object['images'][0]['thumb'];
    if (is_file(IMMOTOOL_BASE_PATH . $titleImg)) {
      $objectSummary = '<img src="' . $baseUrl . $titleImg . '" alt="" align="left" /> ' . $objectSummary;
    }
  }

  // Immobilie in den Feed eintragen
  $feed .= '    <item>' . "\n";
  $feed .= '      <title>' . htmlspecialchars($objectTitle) . '</title>' . "\n";
  $feed .= '      <link>' . $objectUrl . '</link>' . "\n";
  //$feed .= '      <description>' . htmlspecialchars($objectSummary) . '</description>' . "\n";
  $feed .= '      <description><![CDATA[' . $objectSummary . ']]></description>' . "\n";
  if (!is_null($objectStamp))
    $feed .= '      <pubDate>' . date('r', $objectStamp) . '</pubDate>' . "\n";
  else
    $feed .= '      <pubDate>' . $feedStamp . '</pubDate>' . "\n";
  $feed .= '      <guid isPermaLink="false">' . $objectUrl . '</guid>' . "\n";
  $feed .= '      <dc:creator>' . $feedTitle . '</dc:creator>' . "\n";
  $feed .= '    </item>' . "\n";

  if ($debugMode)
    echo '&gt; OK<br/>';

  // ggf. abbrechen, wenn das Maximum für Feed-Einträge erreicht ist
  $counter++;
  if (is_numeric($setup->RssFeedLimit) && $setup->RssFeedLimit > 0 && $setup->RssFeedLimit <= $counter) {
    if ($debugMode)
      echo '&gt; STOP, reached limit of ' . $setup->RssFeedLimit . ' entries<br/>';
    break;
  }
}
$feed .= '  </channel>';
$feed .= '</rss>';

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
