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
 * Website-Export, Darstellung der Inseratsübersicht
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

// Konfiguration ermitteln
$setup = new immotool_setup_index();
if (is_callable(array('immotool_myconfig', 'load_config_index')))
  immotool_myconfig::load_config_index($setup);
immotool_functions::init($setup);

// Übersetzungen ermitteln
$translations = null;
$lang = immotool_functions::init_language($_REQUEST[IMMOTOOL_PARAM_LANG], $setup->DefaultLanguage, $translations);
if (!is_array($translations))
  die('Can\'t load translations!');

// Seitenzahl ermitteln
$elementsPerPage = $setup->ElementsPerPage;
$page = $_REQUEST[IMMOTOOL_PARAM_INDEX_PAGE];
if (!is_numeric($page) || $page <= 0) {
  $page = $_SESSION['immotool']['page'];
  if (!is_numeric($page) || $page <= 0)
    $page = 1;
}
$_SESSION['immotool']['page'] = $page;

// Sortierung & Filterkriterien ignorieren und aus der Session entfernen
$reset = false;
if (is_string($_REQUEST[IMMOTOOL_PARAM_INDEX_RESET])) {
  $reset = true;
  unset($_SESSION['immotool']['orderBy']);
  unset($_SESSION['immotool']['orderDir']);
  unset($_SESSION['immotool']['filter']);
  unset($_REQUEST[IMMOTOOL_PARAM_INDEX_ORDER]);
  unset($_REQUEST[IMMOTOOL_PARAM_INDEX_FILTER]);
}

// Sortierung ermitteln
$order = explode('-', $_REQUEST[IMMOTOOL_PARAM_INDEX_ORDER]);
$orderBy = $order[0];
$orderDir = $order[1];
if (!is_string($orderBy) || trim($orderBy) == '') {
  $orderBy = $_SESSION['immotool']['orderBy'];
  if (!is_string($orderBy) || trim($orderBy) == '')
    $orderBy = $setup->DefaultOrderBy;
}
if (!is_string($orderDir) || trim($orderDir) == '') {
  $orderDir = $_SESSION['immotool']['orderDir'];
  if (!is_string($orderDir) || trim($orderDir) == '')
    $orderDir = $setup->DefaultOrderDir;
}
$_SESSION['immotool']['orderBy'] = $orderBy;
$_SESSION['immotool']['orderDir'] = $orderDir;

// Filterkriterien ermitteln
$filters = $_REQUEST[IMMOTOOL_PARAM_INDEX_FILTER];
if ($_REQUEST[IMMOTOOL_PARAM_INDEX_FILTER_CLEAR] == '1') {
  $_SESSION['immotool']['filter'] = array();
  if (!is_array($filters))
    $filters = array();
}
else if (!is_array($filters)) {
  $filters = $_SESSION['immotool']['filter'];
  if (!is_array($filters))
    $filters = array();
}
$_SESSION['immotool']['filter'] = $filters;

// Parameter der Seite
$view = $_REQUEST[IMMOTOOL_PARAM_INDEX_VIEW];
if ($view != 'fav')
  $view = 'index';
$mainTitle = $translations['labels']['title'];
$pageTitle = ($view == 'fav') ? $translations['labels']['title.fav'] : $translations['labels']['title.index'];
$robots = 'noindex,follow';

// Inhalt der Seite erzeugen
$totalCount = 0;
$listing = immotool_functions::read_template('listing.html');
$favIds = ($view == 'fav') ? $_SESSION['immotool']['favs'] : null;
$result = immotool_functions::list_objects($page, $elementsPerPage, $orderBy, $orderDir, $filters, $totalCount, $lang, $favIds);
$counter = 0;
foreach ($result as $resultId) {
  $counter++;
  $bg = (($counter % 2) == 0) ? 'openestate_light' : 'openestate_dark';
  $object = immotool_functions::get_object($resultId);
  $listingEntry = immotool_functions::read_template('listing_entry.html');
  immotool_functions::replace_var('ID', $object['id'], $listingEntry);
  immotool_functions::replace_var('BG', $bg, $listingEntry);
  immotool_functions::replace_var('ACTION', $translations['openestate']['actions'][$object['action']], $listingEntry);
  immotool_functions::replace_var('TYPE', $translations['openestate']['types'][$object['type']], $listingEntry);
  immotool_functions::replace_var('POSTAL', $object['adress']['postal'], $listingEntry);
  immotool_functions::replace_var('CITY', $object['adress']['city'], $listingEntry);
  immotool_functions::replace_var('COUNTRY', $object['adress']['country_name'][$lang], $listingEntry);

  // Titel ermitteln
  $title = $object['title'][$lang];
  if (!is_null($object['nr']))
    $title = $object['nr'] . ' &raquo; ' . $title;
  else
    $title = '#' . $object['id'] . ' &raquo; ' . $title;
  immotool_functions::replace_var('TITLE', $title, $listingEntry);

  // Titelbild ermitteln
  $img = 'data/' . $object['id'] . '/img_0.thumb.jpg';
  if (is_file(IMMOTOOL_BASE_PATH . $img))
    immotool_functions::replace_var('IMAGE', $img, $listingEntry);
  else
    immotool_functions::replace_var('IMAGE', null, $listingEntry);

  // Die ersten drei Attribute jeder Gruppe darstellen
  foreach (array_keys($object['attributes']) as $group) {
    // Namen der darstellbaren Attribute ermitteln
    $attribs = array_keys($object['attributes'][$group]);

    // HACK: Angaben zur Courtage nicht darstellen
    if ($group == 'preise') {
      $pos = array_search('courtage_aussen', $attribs);
      if ($pos !== false)
        unset($attribs[$pos]);
      $pos = array_search('courtage_aussen_tax', $attribs);
      if ($pos !== false)
        unset($attribs[$pos]);
    }

    // HACK: Warmmiete & Kaltmiete nicht gemeinsam darstellen
    if ($group == 'preise' && array_search('kaltmiete', $attribs) !== false) {
      $pos = array_search('warmmiete', $attribs);
      if ($pos !== false)
        unset($attribs[$pos]);
    }

    // HACK: Bruttofläche & Wohnfläche nicht gemeinsam darstellen
    if ($group == 'flaechen' && array_search('bruttoflaeche', $attribs) !== false) {
      $pos = array_search('wohnflaeche', $attribs);
      if ($pos !== false)
        unset($attribs[$pos]);
    }

    // Darstellung der ersten drei Attribute pro Gruppe
    // Wenn keine Attribute hinterlegt sind, werden die Platzhalter geleert.
    $attribs = array_values($attribs);
    for ($i = 1; $i <= 3; $i++) {
      $pos = strpos($listingEntry, '{' . strtoupper($group) . '_' . $i . '}');
      if ($pos === false)
        break;
      $attribTitle = null;
      $attribValue = null;
      if ($attribs[$i - 1] != false) {
        $attrib = $attribs[$i - 1];
        $attribTitle = $translations['openestate']['attributes'][$group][$attrib];
        $attribValue = $object['attributes'][$group][$attrib][$lang];
      }
      immotool_functions::replace_var(strtoupper($group) . '_' . $i, $attribValue, $listingEntry);
      immotool_functions::replace_var(strtoupper($group) . '_' . $i . '_TITLE', $attribTitle, $listingEntry);
    }
  }

  // Darstellung der Links
  $favTitle = immotool_functions::has_favourite($object['id']) ?
      $translations['labels']['link.expose.unfav'] : $translations['labels']['link.expose.fav'];

  immotool_functions::replace_var('LINK_EXPOSE', 'expose.php?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'], $listingEntry);
  immotool_functions::replace_var('LINK_EXPOSE_TEXT', $translations['labels']['link.expose.view'], $listingEntry);
  immotool_functions::replace_var('LINK_FAV', '?' . IMMOTOOL_PARAM_FAV . '=' . $object['id'] . '&amp;' . IMMOTOOL_PARAM_INDEX_VIEW . '=' . $view, $listingEntry);
  immotool_functions::replace_var('LINK_FAV_TEXT', $favTitle, $listingEntry);
  immotool_functions::replace_var('LINK_CONTACT', 'expose.php?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'] . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=contact', $listingEntry);
  immotool_functions::replace_var('LINK_CONTACT_TEXT', $translations['labels']['link.expose.contact'], $listingEntry);
  $pdf = 'data/' . $object['id'] . '/' . $object['id'] . '_' . $lang . '.pdf';
  if (is_file(IMMOTOOL_BASE_PATH . $pdf)) {
    immotool_functions::replace_var('LINK_PDF', $pdf, $listingEntry);
    immotool_functions::replace_var('LINK_PDF_TEXT', $translations['labels']['link.expose.pdf'], $listingEntry);
  }
  else {
    immotool_functions::replace_var('LINK_PDF', null, $listingEntry);
    immotool_functions::replace_var('LINK_PDF_TEXT', null, $listingEntry);
  }

  // Eintrag einfügen
  $listing = str_replace('{ENTRIES}', "$listingEntry\n{ENTRIES}", $listing);
}

if ($totalCount == 0) {
  $msg = $translations['errors']['noEstatesFound'];
  $listing = str_replace('{ENTRIES}', '<div id="openestate_empty_list">' . $msg . '</div>', $listing);
}

// Seitennavigation
$pagination = '<ul>';
$maxPageNumber = ceil($totalCount / $elementsPerPage);
if ($maxPageNumber > 1) {
  $start = $page - 4;
  if ($start < 1)
    $start = 1;
  $end = $page + 4;
  if ($end > $maxPageNumber)
    $end = $maxPageNumber;
  if ($start > 1) {
    $pagination .= '<li><a href="?' . IMMOTOOL_PARAM_INDEX_PAGE . '=1&amp;' . IMMOTOOL_PARAM_INDEX_VIEW . '=' . $view . '">1</a></li>';
    if ($start > 2)
      $pagination .= '<li>...</li>';
  }
  for ($i = $start; $i <= $end; $i++) {
    $class = ($page == $i) ? 'class="selected"' : '';
    $pagination .= '<li ' . $class . '><a href="?' . IMMOTOOL_PARAM_INDEX_PAGE . '=' . $i . '&amp;' . IMMOTOOL_PARAM_INDEX_VIEW . '=' . $view . '">' . $i . '</a></li>';
  }
  if ($end < $maxPageNumber) {
    if (($end + 1) < $maxPageNumber)
      $pagination .= '<li>...</li>';
    $pagination .= '<li><a href="?' . IMMOTOOL_PARAM_INDEX_PAGE . '=' . $maxPageNumber . '&amp;' . IMMOTOOL_PARAM_INDEX_VIEW . '=' . $view . '">' . $maxPageNumber . '</a></li>';
  }
}
else {
  $pagination .= '<li>&nbsp;</li>';
}
$pagination .= '<li ' . (($view == 'fav') ? 'class="selected"' : '') . ' style="float:right;"><a href="?' . IMMOTOOL_PARAM_INDEX_VIEW . '=fav" rel="nofollow">' . $translations['labels']['title.fav'] . '</a></li>';
$pagination .= '<li ' . (($view == 'index') ? 'class="selected"' : '') . ' style="float:right;"><a href="index.php" rel="nofollow">' . $translations['labels']['title.index'] . '</a></li>';
$pagination .= '</ul>';

// Menü erzeugen
$showMenu = null;
if ((is_array($setup->OrderOptions) && count($setup->OrderOptions) > 0) ||
    (is_array($setup->FilterOptions) && count($setup->FilterOptions) > 0)) {
  $showMenu = '';

  // Sortierung
  if (is_array($setup->OrderOptions) && count($setup->OrderOptions) > 0) {
    $sortedOrders = array();
    $availableOrders = array();
    foreach ($setup->OrderOptions as $key) {
      $orderObj = immotool_functions::get_order($key);
      $by = $orderObj->getTitle($translations, $lang);
      $sortedOrders[$key] = $by;
      $availableOrders[$key] = $orderObj;
    }
    asort($sortedOrders);
    $showMenu .= '<div class="listing_ordering">';
    $showMenu .= '<select id="order" name="' . IMMOTOOL_PARAM_INDEX_ORDER . '">';
    $showMenu .= '<optgroup label="' . $translations['labels']['order.asc'] . '">';
    foreach ($sortedOrders as $key => $by) {
      $orderObj = $availableOrders[$key];
      $selected = ($orderDir == 'asc' && $orderBy == $key) ? 'selected="selected"' : '';
      $showMenu .= '<option value="' . $key . '-asc" ' . $selected . '>&uarr; ' . $by . ' &uarr;</option>';
    }
    $showMenu .= '</optgroup>';
    $showMenu .= '<optgroup label="' . $translations['labels']['order.desc'] . '">';
    foreach ($sortedOrders as $key => $by) {
      $orderObj = $availableOrders[$key];
      $selected = ($orderDir == 'desc' && $orderBy == $key) ? 'selected="selected"' : '';
      $showMenu .= '<option value="' . $key . '-desc" ' . $selected . '>&darr; ' . $by . ' &darr;</option>';
    }
    $showMenu .= '</optgroup>';
    $showMenu .= '</select>';
    $showMenu .= '</div>';
  }

  // Filterkriterien
  if (is_array($setup->FilterOptions) && count($setup->FilterOptions) > 0) {
    foreach ($setup->FilterOptions as $key) {
      $filterObj = immotool_functions::get_filter($key);
      if (!is_object($filterObj))
        continue;
      $filterWidget = $filterObj->getWidget($filters[$key], $lang, $translations, $setup);
      if (!is_string($filterWidget) || strlen($filterWidget) == 0)
        continue;
      $showMenu .= '<div class="listing_filter">';
      $showMenu .= $filterWidget;
      $showMenu .= '</div>';
    }
  }
}

immotool_functions::replace_var('MENU', $showMenu, $listing);

$replacement = array(
  '{PAGINATION}' => $pagination,
  '{VIEW}' => $view,
  '{ENTRIES}' => '',
);
$pageContent = str_replace(array_keys($replacement), array_values($replacement), $listing);

// Ausgabe erzeugen
$buildTime = microtime() - $startup;
header("Content-Type: text/html; charset=utf-8");
echo immotool_functions::build_page('index', $lang, $mainTitle, $pageTitle, $pageContent, $buildTime, $setup->AdditionalStylesheet, $setup->ShowLanguageSelection, $robots);
