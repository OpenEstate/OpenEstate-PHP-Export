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
 * Website-Export, Hilfsfunktionen
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE')) {
  exit;
}

define('IMMOTOOL_SCRIPT_VERSION', '1.2');

// Parameter, allgemein
if (!defined('IMMOTOOL_PARAM_LANG'))
  define('IMMOTOOL_PARAM_LANG', 'lang');
if (!defined('IMMOTOOL_PARAM_FAV'))
  define('IMMOTOOL_PARAM_FAV', 'fav');

// Parameter, captcha.php
if (!defined('IMMOTOOL_PARAM_CAPTCHA_SESSION'))
  define('IMMOTOOL_PARAM_CAPTCHA_SESSION', 'sess');

// Parameter, index.php
if (!defined('IMMOTOOL_PARAM_INDEX_PAGE'))
  define('IMMOTOOL_PARAM_INDEX_PAGE', 'page');
if (!defined('IMMOTOOL_PARAM_INDEX_RESET'))
  define('IMMOTOOL_PARAM_INDEX_RESET', 'reset');
if (!defined('IMMOTOOL_PARAM_INDEX_ORDER'))
  define('IMMOTOOL_PARAM_INDEX_ORDER', 'order');
if (!defined('IMMOTOOL_PARAM_INDEX_FILTER'))
  define('IMMOTOOL_PARAM_INDEX_FILTER', 'filter');
if (!defined('IMMOTOOL_PARAM_INDEX_FILTER_CLEAR'))
  define('IMMOTOOL_PARAM_INDEX_FILTER_CLEAR', 'clearFilters');
if (!defined('IMMOTOOL_PARAM_INDEX_VIEW'))
  define('IMMOTOOL_PARAM_INDEX_VIEW', 'view');

// Parameter, expose.php
if (!defined('IMMOTOOL_PARAM_EXPOSE_ID'))
  define('IMMOTOOL_PARAM_EXPOSE_ID', 'id');
if (!defined('IMMOTOOL_PARAM_EXPOSE_VIEW'))
  define('IMMOTOOL_PARAM_EXPOSE_VIEW', 'view');
if (!defined('IMMOTOOL_PARAM_EXPOSE_IMG'))
  define('IMMOTOOL_PARAM_EXPOSE_IMG', 'img');
if (!defined('IMMOTOOL_PARAM_EXPOSE_CONTACT'))
  define('IMMOTOOL_PARAM_EXPOSE_CONTACT', 'contact');
if (!defined('IMMOTOOL_PARAM_EXPOSE_CAPTCHA'))
  define('IMMOTOOL_PARAM_EXPOSE_CAPTCHA', 'captchacode');

$GLOBALS['immotool_objects'] = array();
$GLOBALS['immotool_texts'] = array();

if (is_file(IMMOTOOL_BASE_PATH . 'myconfig.php'))
  include( IMMOTOOL_BASE_PATH . 'myconfig.php' );

class immotool_functions {

  function build_page($pageId, $languageCode, $mainTitle, $pageTitle, &$pageContent, $buildTime, $addonStylesheet, $showLanguageSelection = true, $robots = 'index,follow', $linkParam = '') {
    $page = immotool_functions::read_template('global.html');

    // Sprachauswahl
    $languageSelection = null;
    if ($showLanguageSelection === true) {
      $languages = immotool_functions::get_language_codes();
      $languageSelection = '';
      if (count($languages) > 1) {
        $languageSelection .= '<ul>';
        foreach ($languages as $code) {
          $txt = immotool_functions::get_language_name($code);
          $class = ($languageCode == $code) ? 'class="selected"' : '';
          $languageSelection .= '<li ' . $class . '><a href="?' . IMMOTOOL_PARAM_LANG . '=' . $code . $linkParam . '"><img src="img/' . $code . '.png" alt="' . $txt . '" border="0"/>&nbsp;' . $txt . '</a></li>';
        }
        $languageSelection .= '</ul>';
      }
    }
    immotool_functions::replace_var('LANGUAGE_SELECTION', $languageSelection, $page);

    // zusätzlicher Stylesheet
    $pageHeader = '';
    if (is_string($addonStylesheet) && strlen(trim($addonStylesheet)) > 0)
      $pageHeader .= '<link rel="stylesheet" href="' . htmlentities(trim($addonStylesheet)) . '" />' . "\n";

    // Footer
    $pageFooter = 'powered by <a href="http://www.openestate.org" target="_blank">OpenEstate</a>';
    $pageFooter .= '<br/>v' . IMMOTOOL_SCRIPT_VERSION . ', built in ' . $buildTime . 's';

    // Ausgabe erzeugen
    $replacements = array(
      '{PAGE_CONTENT}' => $pageContent,
      '{LANGUAGE_CODE}' => $languageCode,
      '{MAIN_TITLE}' => $mainTitle,
      '{PAGE_ID}' => $pageId,
      '{PAGE_TITLE}' => $pageTitle,
      '{PAGE_FOOTER}' => $pageFooter,
      '{PAGE_HEADER}' => $pageHeader,
      '{ROBOTS}' => $robots,
      '{SESSION_NAME}' => session_name(),
      '{PARAM_LANG}' => IMMOTOOL_PARAM_LANG,
      '{PARAM_FAV}' => IMMOTOOL_PARAM_FAV,
      '{PARAM_CAPTCHA_SESSION}' => IMMOTOOL_PARAM_CAPTCHA_SESSION,
      '{PARAM_INDEX_PAGE}' => IMMOTOOL_PARAM_INDEX_PAGE,
      '{PARAM_INDEX_RESET}' => IMMOTOOL_PARAM_INDEX_RESET,
      '{PARAM_INDEX_ORDER}' => IMMOTOOL_PARAM_INDEX_ORDER,
      '{PARAM_INDEX_FILTER}' => IMMOTOOL_PARAM_INDEX_FILTER,
      '{PARAM_INDEX_FILTER_CLEAR}' => IMMOTOOL_PARAM_INDEX_FILTER_CLEAR,
      '{PARAM_INDEX_VIEW}' => IMMOTOOL_PARAM_INDEX_VIEW,
      '{PARAM_EXPOSE_ID}' => IMMOTOOL_PARAM_EXPOSE_ID,
      '{PARAM_EXPOSE_VIEW}' => IMMOTOOL_PARAM_EXPOSE_VIEW,
      '{PARAM_EXPOSE_IMG}' => IMMOTOOL_PARAM_EXPOSE_IMG,
      '{PARAM_EXPOSE_CONTACT}' => IMMOTOOL_PARAM_EXPOSE_CONTACT,
      '{PARAM_EXPOSE_CAPTCHA}' => IMMOTOOL_PARAM_EXPOSE_CAPTCHA,
    );
    $output = str_replace(array_keys($replacements), array_values($replacements), $page);
    return immotool_functions::fix_encoding($output);
  }

  /**
   * Umwandlung eines Strings zu UTF-8, wenn dies nicht bereits vorliegt.
   */
  function fix_encoding(&$input) {
    $encoding = strtoupper(mb_detect_encoding($input));
    return ($encoding == 'UTF-8' && mb_check_encoding($input, 'UTF-8')) ?
        $input : utf8_encode($input);
  }

  /**
   * Erzeugt eine Filter-Instanz.
   */
  function get_filter($name) {
    $file = IMMOTOOL_BASE_PATH . 'include/class.filter_' . strtolower($name) . '.php';
    if (!is_file($file))
      return null;
    $filterClass = 'ImmoToolFilter_' . strtolower($name);
    if (!class_exists($filterClass))
      require_once( $file );
    eval('$filter = new ' . $filterClass . '();');
    return $filter;
  }

  /**
   * Liefert die ISO-Codes der verfügbaren Sprachen.
   * @return array ISO-Codes der verfügbaren Sprachen
   */
  function get_language_codes() {
    return array_keys($GLOBALS['immotool_languages']);
  }

  /**
   * Liefert die Bezeichnung einer Sprache.
   * @param string $code ISO-Sprach-Code
   * @return string Bezeichnung der Sprache, oder null wenn unbekannt
   */
  function get_language_name($code) {
    if (!isset($GLOBALS['immotool_languages'][$code]) || !is_string($GLOBALS['immotool_languages'][$code]))
      return null;
    else
      return $GLOBALS['immotool_languages'][$code];
  }

  /**
   * Liefert die Übersetzungen einer Sprache.
   * @param string $code ISO-Sprach-Code
   * @return array Übersetzungs-Array, oder null wenn unbekannt
   */
  function get_language_translations($code) {
    if (!is_string($code)) {
      return null;
    }
    $file = IMMOTOOL_BASE_PATH . 'data/i18n_' . $code . '.php';
    if (!is_file($file)) {
      return null;
    }
    if (!isset($GLOBALS['immotool_translations'][$code]) || !is_array($GLOBALS['immotool_translations'][$code])) {
      @include($file);
      if (!isset($GLOBALS['immotool_translations'][$code]) || !is_array($GLOBALS['immotool_translations'][$code])) {
        return null;
      }
    }

    // ggf. individuelle Übersetzungen nachladen
    if (is_callable(array('immotool_myconfig', 'load_translations')))
      immotool_myconfig::load_translations($GLOBALS['immotool_translations'][$code], $code);

    return $GLOBALS['immotool_translations'][$code];
  }

  /**
   * Erzeugt einen Mailer aus der Konfiguration.
   * @param object $setup Konfiguration
   * @return object PHP-Mailer
   */
  function get_mailer(&$setup) {
    // Instanz des PHPMailers erzeugen
    $mailer = null;
    if (is_callable(array('immotool_myconfig', 'load_mailer')))
      $mailer = immotool_myconfig::load_mailer($setup);
    if (!class_exists('PHPMailer'))
      include_once(IMMOTOOL_BASE_PATH . 'include/class.phpmailer.php');
    if (!is_object($mailer))
      $mailer = new PHPMailer();

    // Mailer konfigurieren
    $mailer->IsHTML(false);
    $mailer->From = immotool_functions::get_mail_adress($setup->MailFrom);
    $mailer->FromName = $setup->MailFromName;
    if (is_string($setup->MailToCC) && strlen(trim($setup->MailToCC)) > 0)
      $mailer->AddCC(immotool_functions::get_mail_adress($setup->MailToCC));
    if (is_string($setup->MailToBCC) && strlen(trim($setup->MailToBCC)) > 0)
      $mailer->AddBCC(immotool_functions::get_mail_adress($setup->MailToBCC));
    if ($setup->MailMethod != 'default') {
      $mailer->Mailer = $setup->MailMethod;
      $mailer->Sendmail = $setup->MailSendmailPath;
      $mailer->Host = $setup->MailSmtpHost;
      $mailer->Port = $setup->MailSmtpPort;
      $mailer->SMTPSecure = $setup->MailSmtpSecurity;
      $mailer->SMTPAuth = $setup->MailSmtpAuth;
      $mailer->Username = $setup->MailSmtpAuthLogin;
      $mailer->Password = $setup->MailSmtpAuthPassword;
      $mailer->SMTPDebug = $setup->MailSmtpDebug;
    }
    return $mailer;
  }

  /**
   * Wandelt eine Mailadresse in Punycode um.
   * @param string $email Mailadresse
   * @return umgewandelte Mailadresse
   */
  function get_mail_adress($email) {
    if (!isset($GLOBALS['immotool_idna']) || !is_object($GLOBALS['immotool_idna'])) {
      include_once( IMMOTOOL_BASE_PATH . 'include/Net/IDNA.php' );
      $GLOBALS['immotool_idna'] = Net_IDNA::getInstance();
    }
    return $GLOBALS['immotool_idna']->encode($email, 'utf8');
  }

  /**
   * Liefert die Daten eines Objektes.
   * @param int $id ID des Objektes
   * @return array mit Objektdaten, oder null wenn unbekannt
   */
  function get_object($id = null) {
    if ($id == null || preg_match('/^\w*/i', $id) !== 1) {
      return null;
    }
    $file = IMMOTOOL_BASE_PATH . 'data/' . $id . '/object.php';
    if (!is_file($file)) {
      return null;
    }
    if (!isset($GLOBALS['immotool_objects'][$id]) || !is_array($GLOBALS['immotool_objects'][$id])) {
      @include($file);
      if (!isset($GLOBALS['immotool_objects'][$id]) || !is_array($GLOBALS['immotool_objects'][$id])) {
        return null;
      }
    }
    return $GLOBALS['immotool_objects'][$id];
  }

  /**
   * Erzeugt eine Sortierungs-Instanz.
   */
  function get_order($name) {
    $file = IMMOTOOL_BASE_PATH . 'include/class.order_' . strtolower($name) . '.php';
    if (!is_file($file))
      return null;
    $orderClass = 'ImmoToolOrder_' . strtolower($name);
    if (!class_exists($orderClass))
      require_once( $file );
    eval('$order = new ' . $orderClass . '();');
    return $order;
  }

  /**
   * Liefert die AGB des Anbieters.
   * @return array mit AGB, oder null wenn unbekannt
   */
  function get_terms() {
    $file = IMMOTOOL_BASE_PATH . 'data/terms.php';
    if (!is_file($file)) {
      return null;
    }
    if (!isset($GLOBALS['immotool_terms']) || !is_array($GLOBALS['immotool_terms'])) {
      @include($file);
      if (!isset($GLOBALS['immotool_terms']) || !is_array($GLOBALS['immotool_terms'])) {
        return null;
      }
    }
    return $GLOBALS['immotool_terms'];
  }

  /**
   * Liefert die Texte eines Objektes.
   * @param int $id ID des Objektes
   * @return array mit Objekttexten, oder null wenn unbekannt
   */
  function get_text($id = null) {
    if ($id == null || preg_match('/^\w*/i', $id) !== 1) {
      return null;
    }
    $file = IMMOTOOL_BASE_PATH . 'data/' . $id . '/texts.php';
    if (!is_file($file)) {
      return null;
    }
    if (!isset($GLOBALS['immotool_texts'][$id]) || !is_array($GLOBALS['immotool_texts'][$id])) {
      @include($file);
      if (!isset($GLOBALS['immotool_texts'][$id]) || !is_array($GLOBALS['immotool_texts'][$id])) {
        return null;
      }
    }
    return $GLOBALS['immotool_texts'][$id];
  }

  function init(&$setup) {
    // Session initialisieren
    if (!isset($_SESSION['immotool']) || !is_array($_SESSION['immotool'])) {
      $_SESSION['immotool'] = array();
    }

    // vorgemerkte Inserate ermitteln
    if (!isset($_SESSION['immotool']['favs']) || !is_array($_SESSION['immotool']['favs'])) {
      $_SESSION['immotool']['favs'] = array();

      // Favoriten eventuell aus Cookie ermitteln
      if (isset($_COOKIE['immotool_favs']) && is_string($_COOKIE['immotool_favs'])) {
        foreach (explode(',', $_COOKIE['immotool_favs']) as $fav)
          $_SESSION['immotool']['favs'][] = intval($fav);
      }
    }

    // Inserate ggf. vormerken
    $favId = (isset($_REQUEST[IMMOTOOL_PARAM_FAV])) ? $_REQUEST[IMMOTOOL_PARAM_FAV] : null;
    if (is_string($favId) && preg_match('/^\w*/i', $favId) === 1) {
      $pos = array_search($favId, $_SESSION['immotool']['favs']);
      if ($pos === false)
        $_SESSION['immotool']['favs'][] = $favId;
      else
        unset($_SESSION['immotool']['favs'][$pos]);

      setcookie(
          'immotool_favs', // name
          implode(',', $_SESSION['immotool']['favs']), // value
          time() + 60 * 60 * 24 * 365, // expires after 30 days
          '/', // path
          '', // domain
          false                                            // secure
      );
    }

    // ggf. die Standard-Zeitzone für Datumsformatierungen setzen
    $tz = $_SERVER['TZ'];
    if (is_string($setup->Timezone) && strlen($setup->Timezone) > 0)
      $tz = $setup->Timezone;
    if (function_exists('date_default_timezone_set') && is_string($tz) && strlen($tz) > 0) {
      date_default_timezone_set($tz);
    }
  }

  function init_language($requestedLanguage, $defaultLanguage, &$translations) {
    // Übersetzungen ermitteln
    $lang = $requestedLanguage;
    if (!is_string($lang) || !immotool_functions::is_language_supported($lang)) {
      $lang = (isset($_SESSION['immotool']['lang'])) ? $_SESSION['immotool']['lang'] : null;
      if (!is_string($lang) || !immotool_functions::is_language_supported($lang))
        $lang = $defaultLanguage;
    }
    $_SESSION['immotool']['lang'] = $lang;
    $translations = immotool_functions::get_language_translations($lang);
    if (!is_array($translations))
      die('Can\'t load translation for \'' . $lang . '\'!');
    return $lang;
  }

  /**
   * Überprüfung, ob ein Sprachcode unterstützt wird.
   * @param string $code ISO-Sprachcode
   * @return boolean true, wenn der Sprachcode unterstützt wird
   */
  function is_language_supported($code) {
    return in_array($code, immotool_functions::get_language_codes(), false);
  }

  function has_favourite($favId) {
    $pos = array_search($favId, $_SESSION['immotool']['favs']);
    return $pos !== false;
  }

  function list_available_filters() {
    $dir = IMMOTOOL_BASE_PATH . 'include/';
    $filters = array();
    if (is_dir($dir)) {
      $files = immotool_functions::list_directory($dir);
      if (is_array($files)) {
        foreach ($files as $file) {
          if (is_file($dir . $file) && strpos($file, 'class.filter_') === 0) {
            $filter = substr($file, strlen('class.filter_'));
            $filter = substr($filter, 0, -4);
            $filters[] = $filter;
          }
        }
      }
    }
    return $filters;
  }

  function list_available_objects() {
    $dir = IMMOTOOL_BASE_PATH . 'data/';
    $ids = array();
    if (is_dir($dir)) {
      $files = immotool_functions::list_directory($dir);
      if (is_array($files)) {
        foreach ($files as $file) {
          if (is_dir($dir . $file)) {
            $ids[] = $file;
          }
        }
      }
    }
    return $ids;
  }

  function list_directory($directory) {
    $results = array();
    $handler = opendir($directory);
    while ($file = readdir($handler)) {
      if ($file != '.' && $file != '..')
        $results[] = $file;
    }
    closedir($handler);
    return $results;
  }

  function list_objects($pageNumber, $elementsPerPage, $orderBy, $orderDir, $filters, &$totalCount, $lang, $favIds = null) {
    // ID's der Inserate ermitteln
    $ids = null;
    if (is_string($orderBy)) {
      $orderObj = immotool_functions::get_order($orderBy);
      if ($orderObj == null)
        die('unknown order: ' . $orderBy);
      if ($orderObj != null && $orderObj->readOrRebuild()) {
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
    }

    // ID's aus Verzeichnisnamen ermitteln, wenn etwas schiefgelaufen ist
    if (!is_array($ids)) {
      $ids = immotool_functions::list_available_objects();
      if ($orderDir == 'desc')
        rsort($ids);
      else
        sort($ids);
    }

    // Favoriten ggf. herausfiltern
    if (is_array($favIds)) {
      $ids = array_values(array_intersect($ids, $favIds));
    }

    // Objekt-ID's filtern
    foreach ($filters as $filter => $filterValue) {
      if (!is_string($filterValue) || strlen($filterValue) == 0)
        continue;
      $filterObj = immotool_functions::get_filter($filter);
      if ($filterObj == null || !$filterObj->readOrRebuild())
        continue;
      $items = $filterObj->getItems($filterValue);
      if (is_array($items)) {
        $ids = array_values(array_intersect($ids, $items));
      }
    }

    // Eingrenzung zur Seitenansicht
    $result = array();
    $totalCount = count($ids);
    $maxPageNumber = ceil($totalCount / $elementsPerPage);
    if ($pageNumber > $maxPageNumber)
      $pageNumber = $maxPageNumber;
    $start = ($pageNumber - 1) * $elementsPerPage;
    $end = $start + $elementsPerPage;
    for ($i = $start; $i < $end; $i++) {
      if (!isset($ids[$i]))
        break;
      $result[] = $ids[$i];
    }
    return $result;
  }

  function read_file($file) {
    return file_get_contents($file);
  }

  function read_template($file) {
    return immotool_functions::read_file(IMMOTOOL_BASE_PATH . 'templates/' . $file);
  }

  function replace_links(&$text) {
    $replacements = array(
      '#(https?|ftps?|mailto):\/\/([&;:=\.\/\?\w]+)#i' => '<a href="\1://\2" target="_blank" rel="follow">\1://\2</a>',
    );
    return preg_replace(array_keys($replacements), array_values($replacements), $text);
  }

  function replace_var($varName, $value, &$src) {
    $posBegin = strpos($src, '{' . $varName . '.}');
    $posEnd = strpos($src, '{.' . $varName . '}');
    if (is_null($value) || trim($value) == '') {
      if ($posBegin !== false && $posEnd !== false) {
        $src = substr($src, 0, $posBegin) . substr($src, $posEnd + strlen($varName) + 3);
      }
      else {
        $src = str_replace('{' . $varName . '}', '', $src);
      }
    }
    else {
      if ($posBegin !== false)
        $src = str_replace('{' . $varName . '.}', '', $src);
      if ($posEnd !== false)
        $src = str_replace('{.' . $varName . '}', '', $src);
      $src = str_replace('{' . $varName . '}', $value, $src);
    }
  }

  function wrap_page(&$page, $wrapType, $wrapperScriptUrl, $immotoolBaseUrl, $stylesheets) {
    // Stylesheets importieren
    $styles = '';
    if (is_array($stylesheets) && count($stylesheets) > 0) {
      $styles = "\n<style type=\"text/css\">";
      foreach ($stylesheets as $style)
        $styles .= "\n@import \"$style\";";
      $styles .= "\n</style>";
    }

    // Ersetzungen
    $replacements = array(
      // Inhalt des BODY-Tags ermitteln
      '/(.*)<body([^>]*)>(.*)<\/body>(.*)/is' => '<div\2>' . $styles . '\3</div>',
      // Verlinkungen innerhalb der aktuellen Seite
      '/<a([^>]*)href="\?([^"]*)"/is' => '<a\1href="' . $wrapperScriptUrl . '?wrap=' . $wrapType . '&amp;\2"',
      // index.php => Links
      '/<a([^>]*)href="index\.php"/is' => '<a\1href="' . $wrapperScriptUrl . '?wrap=index"',
      '/<a([^>]*)href="index\.php\?([^"]*)"/is' => '<a\1href="' . $wrapperScriptUrl . '?wrap=index&amp;\2"',
      // index.php => Formulare
      '/<form([^>]*)action="index\.php"/is' => '<form\1action="' . $wrapperScriptUrl . '?wrap=index"',
      // expose.php => Links
      '/<a([^>]*)href="expose\.php"/is' => '<a\1href="' . $wrapperScriptUrl . '?wrap=expose"',
      '/<a([^>]*)href="expose\.php\?([^"]*)"/is' => '<a\1href="' . $wrapperScriptUrl . '?wrap=expose&amp;\2"',
      // expose.php => Formulare
      '/<form([^>]*)action="expose\.php([^"]*)"/is' => '<form\1action="' . $wrapperScriptUrl . '?wrap=expose\2"',
      // captcha.php
      '/<img([^>]*)src="captcha\.php"/is' => '<img\1src="' . $immotoolBaseUrl . 'captcha.php"',
      '/<img([^>]*)src="captcha\.php\?([^"]*)"/is' => '<img\1src="' . $immotoolBaseUrl . 'captcha.php?\2"',
      '/src=\'captcha\.php([^\']*)\'/is' => 'src=\'' . $immotoolBaseUrl . 'captcha.php\1\'',
      // Links auf PDF-Exposés
      '/<a([^>]*)href="data\/([^"]*)\.pdf"/is' => '<a\1href="' . $immotoolBaseUrl . 'data/\2.pdf"',
      // Datenverzeichnis
      '/<img([^>]*)src="img\/([^"]*)"/is' => '<img\1src="' . $immotoolBaseUrl . 'img/\2"',
      '/<img([^>]*)src="data\/([^"]*)"/is' => '<img\1src="' . $immotoolBaseUrl . 'data/\2"',
    );
    return preg_replace(array_keys($replacements), array_values($replacements), $page);
  }

}
