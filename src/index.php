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
 * Website-Export, Darstellung der Inseratsübersicht.
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

// Favoriten ggf. entfernen
if (isset($_REQUEST[IMMOTOOL_PARAM_INDEX_FAVS_CLEAR]) && is_string($_REQUEST[IMMOTOOL_PARAM_INDEX_FAVS_CLEAR])) {
  if (isset($_SESSION['immotool']['favs']))
    $_SESSION['immotool']['favs'] = array();
  if (isset($_COOKIE['immotool_favs']))
    unset($_COOKIE['immotool_favs']);
  setcookie(
      'immotool_favs', // name
      '', // value
      time() + 60 * 60 * 24 * 365, // expires after 30 days
      '/', // path
      '', // domain
      false // secure
  );
}

// Initialisierungen
$setup = new immotool_setup_index();
immotool_functions::init($setup, 'load_config_index');

// Übersetzungen ermitteln
$translations = null;
$lang = (isset($_REQUEST[IMMOTOOL_PARAM_LANG])) ? $_REQUEST[IMMOTOOL_PARAM_LANG] : null;
$lang = immotool_functions::init_language($lang, $setup->DefaultLanguage, $translations);
if (!is_array($translations))
  die('Can\'t load translations!');

// Ansicht ermitteln
$view = (isset($_REQUEST[IMMOTOOL_PARAM_INDEX_VIEW])) ? $_REQUEST[IMMOTOOL_PARAM_INDEX_VIEW] : null;
if ($view != 'fav')
  $view = 'index';

// Modus ermitteln
$mode = (isset($_REQUEST[IMMOTOOL_PARAM_INDEX_MODE])) ? $_REQUEST[IMMOTOOL_PARAM_INDEX_MODE] : null;
if (!is_string($mode) || strlen($mode) <= 0)
  $mode = (isset($_SESSION['immotool']['mode'])) ? $_SESSION['immotool']['mode'] : null;
if ($mode != 'gallery' && $mode != 'entry')
  $mode = $setup->DefaultMode;
$_SESSION['immotool']['mode'] = $mode;

// Sortierung & Filterkriterien ignorieren und aus der Session entfernen
if (isset($_REQUEST[IMMOTOOL_PARAM_INDEX_RESET]) && is_string($_REQUEST[IMMOTOOL_PARAM_INDEX_RESET])) {
  unset($_SESSION['immotool']['orderBy']);
  unset($_SESSION['immotool']['orderDir']);
  unset($_SESSION['immotool']['filter']);
  unset($_SESSION['immotool']['page']);
  if (isset($_REQUEST[IMMOTOOL_PARAM_INDEX_ORDER]))
    unset($_REQUEST[IMMOTOOL_PARAM_INDEX_ORDER]);
  if (isset($_REQUEST[IMMOTOOL_PARAM_INDEX_FILTER]))
    unset($_REQUEST[IMMOTOOL_PARAM_INDEX_FILTER]);
  if (isset($_REQUEST[IMMOTOOL_PARAM_INDEX_PAGE]))
    unset($_REQUEST[IMMOTOOL_PARAM_INDEX_PAGE]);
}

// Seitenzahl ermitteln
$elementsPerPage = $setup->ElementsPerPage;
$page = (isset($_REQUEST[IMMOTOOL_PARAM_INDEX_PAGE])) ? $_REQUEST[IMMOTOOL_PARAM_INDEX_PAGE] : null;
if (!is_numeric($page) || $page <= 0) {
  $page = (isset($_SESSION['immotool']['page'])) ? $_SESSION['immotool']['page'] : null;
  if (!is_numeric($page) || $page <= 0)
    $page = 1;
}
$_SESSION['immotool']['page'] = $page;

// Sortierung ermitteln
$order = (isset($_REQUEST[IMMOTOOL_PARAM_INDEX_ORDER])) ? $_REQUEST[IMMOTOOL_PARAM_INDEX_ORDER] : '';
$order = explode('-', $order);
$orderBy = (isset($order[0]) && strlen($order[0]) > 0) ? $order[0] : '';
if (!is_string($orderBy) || trim($orderBy) == '') {
  $orderBy = (isset($_SESSION['immotool']['orderBy'])) ? $_SESSION['immotool']['orderBy'] : null;
  if (!is_string($orderBy) || trim($orderBy) == '')
    $orderBy = $setup->DefaultOrderBy;
}
$orderDir = (isset($order[1]) && strlen($order[1]) > 0) ? $order[1] : '';
if (!is_string($orderDir) || trim($orderDir) == '') {
  $orderDir = (isset($_SESSION['immotool']['orderDir'])) ? $_SESSION['immotool']['orderDir'] : null;
  if (!is_string($orderDir) || trim($orderDir) == '')
    $orderDir = $setup->DefaultOrderDir;
}
$_SESSION['immotool']['orderBy'] = $orderBy;
$_SESSION['immotool']['orderDir'] = $orderDir;

// Filterkriterien ermitteln
$filters = array();
if ($view != 'fav') {
  $filters = (isset($_REQUEST[IMMOTOOL_PARAM_INDEX_FILTER])) ? $_REQUEST[IMMOTOOL_PARAM_INDEX_FILTER] : null;
  $filterClear = (isset($_REQUEST[IMMOTOOL_PARAM_INDEX_FILTER_CLEAR])) ? $_REQUEST[IMMOTOOL_PARAM_INDEX_FILTER_CLEAR] : null;
  if ($filterClear == '1') {
    $_SESSION['immotool']['filter'] = array();
    if (!is_array($filters))
      $filters = array();
  }
  else if (!is_array($filters)) {
    $filters = (isset($_SESSION['immotool']['filter'])) ? $_SESSION['immotool']['filter'] : null;
    if (!is_array($filters))
      $filters = array();
  }
  $_SESSION['immotool']['filter'] = $filters;
}

// Parameter der Seite
$mainTitle = $translations['labels']['title'];
$pageTitle = ($view == 'fav') ? $translations['labels']['title.fav'] : $translations['labels']['title.index'];

// Inhalt der Seite erzeugen
$totalCount = 0;
$listing = immotool_functions::read_template('listing.html');
$favIds = ($view == 'fav') ? $_SESSION['immotool']['favs'] : null;
$result = immotool_functions::list_objects($page, $elementsPerPage, $orderBy, $orderDir, $filters, $totalCount, $lang, $setup->CacheLifeTime, $favIds);
$counter = 0;
foreach ($result as $resultId) {
  $counter++;
  $bg = (($counter % 2) == 0) ? 'openestate_light' : 'openestate_dark';
  $object = immotool_functions::get_object($resultId);
  $listingEntry = immotool_functions::read_template('listing_' . $mode . '.html');
  if (!is_string($listingEntry))
    $listingEntry = immotool_functions::read_template('listing_entry.html');
  immotool_functions::replace_var('ID', $object['id'], $listingEntry);
  immotool_functions::replace_var('BG', $bg, $listingEntry);
  immotool_functions::replace_var('ACTION', $translations['openestate']['actions'][$object['action']], $listingEntry);
  immotool_functions::replace_var('TYPE', $translations['openestate']['types'][$object['type']], $listingEntry);
  immotool_functions::replace_var('POSTAL', $object['address']['postal'], $listingEntry);
  immotool_functions::replace_var('CITY', $object['address']['city'], $listingEntry);
  immotool_functions::replace_var('COUNTRY', $object['address']['country_name'][$lang], $listingEntry);

  // Titel ermitteln
  $title = $object['title'][$lang];
  if (!is_null($object['nr']))
    $title = $object['nr'] . ' &raquo; ' . $title;
  else
    $title = '#' . $object['id'] . ' &raquo; ' . $title;
  immotool_functions::replace_var('TITLE', $title, $listingEntry);

  // Dynamisch verkleinertes Titelbild ausliefern
  if ($setup->DynamicImageScaling === true && extension_loaded('gd')) {
    $img = 'data/' . $object['id'] . '/img_0.jpg';
    if (!is_file(IMMOTOOL_BASE_PATH . $img)) {
      immotool_functions::replace_var('IMAGE', null, $listingEntry);
    }
    else {
      $imgScaleScript = 'img.php?id=' . $object['id'] . '&amp;img=img_0.jpg';
      if ($mode == 'gallery')
        $imgScaleScript .= '&amp;x=' . $setup->GalleryImageSize[0] . '&amp;y=' . $setup->GalleryImageSize[1];
      else
        $imgScaleScript .= '&amp;x=' . $setup->ListingImageSize[0] . '&amp;y=' . $setup->ListingImageSize[1];
      immotool_functions::replace_var('IMAGE', $imgScaleScript, $listingEntry);
    }
  }

  // Titelbild direkt ausliefern
  else {
    $img = 'data/' . $object['id'] . '/';
    if ($mode == 'gallery')
      $img .= 'title.jpg';
    else
      $img .= 'img_0.thumb.jpg';

    if (is_file(IMMOTOOL_BASE_PATH . $img))
      immotool_functions::replace_var('IMAGE', $img, $listingEntry);
    else
      immotool_functions::replace_var('IMAGE', null, $listingEntry);
  }

  // Die ersten drei Attribute jeder Gruppe darstellen
  foreach (array_keys($object['attributes']) as $group) {
    // Namen der darstellbaren Attribute ermitteln
    $attribs = array_keys($object['attributes'][$group]);

    // HACK: Angaben zur Courtage nicht darstellen
    if ($group == 'prices') {
      $pos = array_search('brokerage', $attribs);
      if ($pos !== false)
        unset($attribs[$pos]);
      $pos = array_search('brokerage_with_vat', $attribs);
      if ($pos !== false)
        unset($attribs[$pos]);
    }

    // HACK: Warmmiete & Kaltmiete nicht gemeinsam darstellen
    if ($group == 'prices' && array_search('rent_without_heating', $attribs) !== false) {
      $pos = array_search('rent_with_heating', $attribs);
      if ($pos !== false)
        unset($attribs[$pos]);
    }

    // HACK: Bruttofläche & Wohnfläche nicht gemeinsam darstellen
    if ($group == 'measures' && array_search('gross_area', $attribs) !== false) {
      $pos = array_search('residential_area', $attribs);
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
      if (isset($attribs[$i - 1]) && $attribs[$i - 1] != false) {
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
    $pagination .= '<li><a href="?' . IMMOTOOL_PARAM_INDEX_PAGE . '=1&amp;' . IMMOTOOL_PARAM_INDEX_VIEW . '=' . $view . '&amp;' . IMMOTOOL_PARAM_LANG . '=' . $lang . '">1</a></li>';
    if ($start > 2)
      $pagination .= '<li>...</li>';
  }
  for ($i = $start; $i <= $end; $i++) {
    $class = ($page == $i) ? 'class="selected"' : '';
    $pagination .= '<li ' . $class . '><a href="?' . IMMOTOOL_PARAM_INDEX_PAGE . '=' . $i . '&amp;' . IMMOTOOL_PARAM_INDEX_VIEW . '=' . $view . '&amp;' . IMMOTOOL_PARAM_LANG . '=' . $lang . '">' . $i . '</a></li>';
  }
  if ($end < $maxPageNumber) {
    if (($end + 1) < $maxPageNumber)
      $pagination .= '<li>...</li>';
    $pagination .= '<li><a href="?' . IMMOTOOL_PARAM_INDEX_PAGE . '=' . $maxPageNumber . '&amp;' . IMMOTOOL_PARAM_INDEX_VIEW . '=' . $view . '&amp;' . IMMOTOOL_PARAM_LANG . '=' . $lang . '">' . $maxPageNumber . '</a></li>';
  }
}
else {
  $pagination .= '<li>&nbsp;</li>';
}
$pagination .= '<li ' . (($view == 'fav') ? 'class="selected"' : '') . ' style="float:right;"><a href="?' . IMMOTOOL_PARAM_INDEX_VIEW . '=fav&amp;' . IMMOTOOL_PARAM_LANG . '=' . $lang . '" rel="nofollow">' . $translations['labels']['tab.fav'] . '</a></li>';
$pagination .= '<li ' . (($view == 'index') ? 'class="selected"' : '') . ' style="float:right;"><a href="index.php?' . IMMOTOOL_PARAM_LANG . '=' . $lang . '" rel="nofollow">' . $translations['labels']['tab.index'] . '</a></li>';
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
  if ($view != 'fav') {
    if (is_array($setup->FilterOptions) && count($setup->FilterOptions) > 0) {
      foreach ($setup->FilterOptions as $key) {
        $filterObj = immotool_functions::get_filter($key);
        if (!is_object($filterObj))
          continue;
        $filterVal = (isset($filters[$key])) ? $filters[$key] : null;
        $filterWidget = $filterObj->getWidget($filterVal, $lang, $translations, $setup);
        if (!is_string($filterWidget) || strlen($filterWidget) == 0)
          continue;
        $showMenu .= '<div class="listing_filter">';
        $showMenu .= $filterWidget;
        $showMenu .= '</div>';
      }
    }
  }
}

immotool_functions::replace_var('MENU', $showMenu, $listing);
if ($showMenu != null) {
  immotool_functions::replace_var('ALT_ACTION_SEARCH', $translations['labels']['action.search'], $listing);

  // Buttons in der Vormerkliste
  if ($view == 'fav') {
    immotool_functions::replace_var('ALT_ACTION_RESET', null, $listing);
    immotool_functions::replace_var('ALT_ACTION_CLEAR_FAVS', $translations['labels']['action.clearFavs'], $listing);
    immotool_functions::replace_var('QUESTION_CLEAR_FAVS', $translations['labels']['action.clearFavs.question'], $listing);
  }

  // Buttons in der Immobilienliste
  else {
    immotool_functions::replace_var('ALT_ACTION_RESET', $translations['labels']['action.reset'], $listing);
    immotool_functions::replace_var('ALT_ACTION_CLEAR_FAVS', null, $listing);
  }
}

$replacement = array(
  '{ALT_VIEW_GALLERY}' => $translations['labels']['view.gallery'],
  '{ALT_VIEW_TABLE}' => $translations['labels']['view.table'],
  '{PAGINATION}' => $pagination,
  '{VIEW}' => $view,
  '{ENTRIES}' => '',
);
$pageContent = str_replace(array_keys($replacement), array_values($replacement), $listing);
$pageHeader = '';

// Ausgabe erzeugen
$metaRobots = 'noindex,follow';
$metaKeywords = null;
$metaDescription = null;
$linkParam = '';
$buildTime = microtime() - $startup;
$output = immotool_functions::build_page('index', $lang, $mainTitle, $pageTitle, $pageHeader, $pageContent, $buildTime, $setup->AdditionalStylesheet, $setup->ShowLanguageSelection, $metaRobots, $linkParam, $metaKeywords, $metaDescription);
if (is_string($setup->Charset) && strlen(trim($setup->Charset)) > 0) {
  $output = immotool_functions::encode($output, $setup->Charset);
}
if (is_string($setup->ContentType) && strlen(trim($setup->ContentType)) > 0) {
  header('Content-Type: ' . $setup->ContentType);
}
echo $output;
