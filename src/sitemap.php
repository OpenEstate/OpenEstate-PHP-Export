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
 * Website-Export, Darstellung der XML-Sitemap.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

// Initialisierung
$startup = microtime();
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/private.php');
require_once(__DIR__ . '/include/functions.php');
require_once(__DIR__ . '/data/language.php');
$debugMode = isset($_REQUEST['debug']) && $_REQUEST['debug'] == '1';

// Konfiguration ermitteln
$setup = new immotool_setup();
if (is_callable(array('immotool_myconfig', 'load_config_default'))) {
  immotool_myconfig::load_config_default($setup);
}

// Übersetzungen ermitteln
$translations = null;
$lang = (isset($_REQUEST[IMMOTOOL_PARAM_LANG])) ? $_REQUEST[IMMOTOOL_PARAM_LANG] : $setup->DefaultLanguage;
$lang = immotool_functions::init_language($lang, $setup->DefaultLanguage, $translations);
if (!is_array($translations)) {
  die('Can\'t load translations!');
}

// Header senden
if ($debugMode) {
  header('Content-Type: text/html; charset=utf-8');
}
else {
  header('Content-Type: text/xml; charset=utf-8');
}

// Cache-Datei der Sitemap
$cacheFile = immotool_functions::get_path('cache/sitemap.' . $lang . '.xml');
if (!$debugMode && is_file($cacheFile)) {
  if (!immotool_functions::check_file_age($cacheFile, $setup->CacheLifeTime)) {
    // abgelaufene Cache-Datei entfernen
    unlink($cacheFile);
  }
  else {
    // Sitemap aus Cache-Datei erzeugen
    $sitemap = immotool_functions::read_file($cacheFile);
    echo $sitemap;
    return;
  }
}

// Timestamp
$sitemapStamp = date('Y-m-d');

// Sitemap erzeugen
$sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$sitemap .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

if ($debugMode) {
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
  echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">';
  echo '  <head>';
  echo '    <title>Sitemap Debugger</title>';
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
  echo '  <h2>Sitemap Debugger</h2>';
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
  if (!is_array($items))
    die('empty order: ' . $orderBy);
  if (is_array($items)) {
    if ($orderDir == 'desc')
      $ids = array_reverse($items);
    else
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

// Immobilien in die Sitemap schreiben
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
  $objectUrl = immotool_functions::get_expose_url($id, $lang, $setup->ExposeUrlTemplate, true);

  // Immobilie in die Sitemap eintragen
  $sitemap .= '  <url>' . "\n";
  $sitemap .= '    <loc>' . $objectUrl . '</loc>' . "\n";
  if (!is_null($objectStamp))
    $sitemap .= '    <lastmod>' . date('Y-m-d', $objectStamp) . '</lastmod>' . "\n";
  else
    $sitemap .= '    <lastmod>' . $sitemapStamp . '</lastmod>' . "\n";
  $sitemap .= '    <changefreq>daily</changefreq>' . "\n";
  $sitemap .= '  </url>' . "\n";

  if ($debugMode)
    echo '&gt; OK<br/>';
}
$sitemap .= '</urlset> ';

// Debug-Ausgabe der Sitemap
if ($debugMode) {
  echo '<h2>Generated XML</h2>';
  echo '<textarea style="width:95%; height:30em; margin-bottom:1em;" readonly="readonly">' . htmlspecialchars($sitemap) . '</textarea>';
  echo '</body></html>';
}

// normale Ausgabe der Sitemap
else {
  // Sitemap cachen
  $fh = fopen($cacheFile, 'w') or die('can\'t write file: ' . $cacheFile);
  fwrite($fh, $sitemap);
  fclose($fh);

  // Sitemap ausgeben
  echo $sitemap;
}
