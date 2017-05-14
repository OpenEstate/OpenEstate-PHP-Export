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
 * Website-Export, Hilfsfunktionen.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE')) {
  exit;
}

define('IMMOTOOL_SCRIPT_VERSION', '1.6.36');
//error_reporting( E_ALL );
//ini_set('display_errors','1');

if (!defined('IMMOTOOL_CRYPT_KEY'))
  define('IMMOTOOL_CRYPT_KEY', 'ZtKMCpjIamND3MN3cx8I1pFGZ1Pul4h4pnujtUlnCUDkTMfXPO');

// Session-Cookie
if (!defined('IMMOTOOL_SESSION_COOKIE_NAME'))
  define('IMMOTOOL_SESSION_COOKIE_NAME', 'openestate_sid');
if (!defined('IMMOTOOL_SESSION_COOKIE_PATH'))
  define('IMMOTOOL_SESSION_COOKIE_PATH', '/');
if (!defined('IMMOTOOL_SESSION_COOKIE_DOMAIN'))
  define('IMMOTOOL_SESSION_COOKIE_DOMAIN', '');
if (!defined('IMMOTOOL_SESSION_COOKIE_SECURE'))
  define('IMMOTOOL_SESSION_COOKIE_SECURE', false);
if (!defined('IMMOTOOL_SESSION_COOKIE_AGE'))
  define('IMMOTOOL_SESSION_COOKIE_AGE', (60 * 60 * 24 * 30)); // 30 Tage


// Caching
if (!defined('IMMOTOOL_OBJECTS_CACHE_SIZE'))
  define('IMMOTOOL_OBJECTS_CACHE_SIZE', '10');
if (!defined('IMMOTOOL_TEXTS_CACHE_SIZE'))
  define('IMMOTOOL_TEXTS_CACHE_SIZE', '10');

// Parameter, allgemein
if (!defined('IMMOTOOL_PARAM_LANG'))
  define('IMMOTOOL_PARAM_LANG', 'lang');
if (!defined('IMMOTOOL_PARAM_FAV'))
  define('IMMOTOOL_PARAM_FAV', 'fav');
if (!defined('IMMOTOOL_PARAM_CAT'))
  define('IMMOTOOL_PARAM_CAT', 'cat');

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
if (!defined('IMMOTOOL_PARAM_INDEX_FAVS_CLEAR'))
  define('IMMOTOOL_PARAM_INDEX_FAVS_CLEAR', 'clearFavs');
if (!defined('IMMOTOOL_PARAM_INDEX_VIEW'))
  define('IMMOTOOL_PARAM_INDEX_VIEW', 'view');
if (!defined('IMMOTOOL_PARAM_INDEX_MODE'))
  define('IMMOTOOL_PARAM_INDEX_MODE', 'mode');

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
$GLOBALS['immotool_session'] = array();
$GLOBALS['immotool_session_id'] = null;

if (is_file(IMMOTOOL_BASE_PATH . 'myconfig.php')) {
  require_once( IMMOTOOL_BASE_PATH . 'myconfig.php' );
}

/**
 * Hilfsfunktionen des ImmoTool PHP-Exports.
 */
class immotool_functions {

  /**
   * Erzeugung einer Seite.
   * @param object $setup Konfiguration
   * @param string $pageId ID der Seite
   * @param string $languageCode Gewählte Sprache
   * @param string $mainTitle Haupttitel
   * @param string $pageTitle Untertitel
   * @param string $pageHeader HTML-Code, der im head-Bereich der Seite eingefügt wird.
   * @param string $pageContent HTML-Code, der im body-Bereich der Seite eingefügt wird.
   * @param int $startupTime Start-Zeitpunkt der Skript-Ausführung
   * @param string $metaRobots Als Meta-Tag dargestellte Robots-Einstellungen
   * @param string $metaKeywords Als Meta-Tag dargestellte Keywords
   * @param string $metaDescription Als Meta-Tag dargestellte Beschreibung
   * @param string $linkParam zusätzliche Parameter für Links, z.B. in der Sprachauswahl
   * @return string HTML-Code der erzeugten Seite
   */
  function build_page(&$setup, $pageId, $languageCode, $mainTitle, $pageTitle, $pageHeader, &$pageContent, $startupTime, $metaRobots = 'index,follow', $metaKeywords = null, $metaDescription = null, $linkParam = '') {

    $page = null;
    if (defined('IMMOTOOL_CAT'))
      $page = immotool_functions::read_template('global_' . IMMOTOOL_CAT . '.html', $setup->TemplateFolder);
    if (!is_string($page))
      $page = immotool_functions::read_template('global.html', $setup->TemplateFolder);
    if (!is_string($pageHeader))
      $pageHeader = '';
    if (strlen($pageHeader) > 0)
      $pageHeader .= "\n";

    // Sprachauswahl
    $languages = immotool_functions::get_language_codes();
    $languageSelection = null;
    if ($setup->ShowLanguageSelection === true) {
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

    // META-Description
    if (is_string($metaDescription) && strlen(trim($metaDescription)) > 0) {
      $txt = trim(htmlspecialchars(strip_tags($metaDescription)));
      $pageHeader .= '<meta name="description" content="' . $txt . '" />' . "\n";
    }

    // META-Keywords
    if (is_string($metaKeywords) && strlen(trim($metaKeywords)) > 0) {
      $txt = trim(htmlspecialchars(strip_tags($metaKeywords)));
      $pageHeader .= '<meta name="keywords" content="' . $txt . '" />' . "\n";
    }

    // META-Robots
    if (is_string($metaRobots) && strlen(trim($metaRobots)) > 0) {
      $txt = trim(htmlspecialchars(strip_tags($metaRobots)));
      $pageHeader .= '<meta name="robots" content="' . $txt . '" />' . "\n";
    }
    else {
      $pageHeader .= '<meta name="robots" content="index,follow" />' . "\n";
    }

    // zusätzlicher Stylesheet
    if (is_string($setup->AdditionalStylesheet) && strlen(trim($setup->AdditionalStylesheet)) > 0) {
      if (strlen($pageHeader) > 0) {
        $pageHeader .= "\n";
      }
      $txt = trim(htmlspecialchars(strip_tags($setup->AdditionalStylesheet)));
      $pageHeader .= '<link rel="stylesheet" href="' . $txt . '" />' . "\n";
    }

    // RSS- & Atom-Feeds ggf. einbinden
    $feedSetup = new immotool_setup_feeds();
    if (is_callable(array('immotool_myconfig', 'load_config_feeds')))
      immotool_myconfig::load_config_feeds($feedSetup);
    if ($feedSetup->PublishRssFeed)
      $pageHeader .= '<link rel="alternate" type="application/rss+xml" title="RSS-Feed" href="feed_rss.php?' . IMMOTOOL_PARAM_LANG . '=' . $languageCode . '" />' . "\n";
    if ($feedSetup->PublishAtomFeed)
      $pageHeader .= '<link rel="alternate" type="application/atom+xml" title="Atom-Feed" href="feed_atom.php?' . IMMOTOOL_PARAM_LANG . '=' . $languageCode . '" />' . "\n";

    // Footer
    $buildTime = (is_numeric($startupTime)) ? microtime() - $startupTime : 0;
    $pageFooter = 'powered by <a href="http://openestate.org" target="_blank">OpenEstate</a>';
    $pageFooter .= "\n<!--";
    $pageFooter .= "\nversion      : " . IMMOTOOL_SCRIPT_VERSION;
    if ($buildTime > 0)
      $pageFooter .= "\nbuild time   : " . number_format($buildTime, '3');
    if (function_exists('memory_get_usage'))
      $pageFooter .= "\nmemory usage : " . immotool_functions::write_bytes(memory_get_usage());
    if (function_exists('memory_get_peak_usage'))
      $pageFooter .= "\nmemory peak  : " . immotool_functions::write_bytes(memory_get_peak_usage());
    $pageFooter .= "\n-->\n";

    // Weitere Link-Parameter
    $linkParams = '';
    if (count($languages) > 1) {
      $linkParams .= '&amp;' . IMMOTOOL_PARAM_LANG . '=' . $languageCode;
    }
    if (defined('IMMOTOOL_CAT')) {
      $linkParams .= '&amp;' . IMMOTOOL_PARAM_CAT . '=' . IMMOTOOL_CAT;
    }

    // Ausgabe erzeugen
    $replacements = array(
      '{PAGE_CONTENT}' => $pageContent,
      '{LANGUAGE_CODE}' => $languageCode,
      '{MAIN_TITLE}' => $mainTitle,
      '{PAGE_ID}' => $pageId,
      '{PAGE_TITLE}' => $pageTitle,
      '{PAGE_FOOTER}' => $pageFooter,
      '{PAGE_HEADER}' => $pageHeader,
      '{CATEGORY}' => (defined('IMMOTOOL_CAT')) ? IMMOTOOL_CAT : '',
      '{ROBOTS}' => $metaRobots, // HACK: Abwärtskompatiblität, kann demnächst entfernt werden
      '{DEFAULT_LINK_PARAMS}' => $linkParams,
      '{PARAM_LANG}' => IMMOTOOL_PARAM_LANG,
      '{PARAM_FAV}' => IMMOTOOL_PARAM_FAV,
      '{PARAM_CAT}' => IMMOTOOL_PARAM_CAT,
      '{PARAM_INDEX_PAGE}' => IMMOTOOL_PARAM_INDEX_PAGE,
      '{PARAM_INDEX_RESET}' => IMMOTOOL_PARAM_INDEX_RESET,
      '{PARAM_INDEX_ORDER}' => IMMOTOOL_PARAM_INDEX_ORDER,
      '{PARAM_INDEX_FILTER}' => IMMOTOOL_PARAM_INDEX_FILTER,
      '{PARAM_INDEX_FILTER_CLEAR}' => IMMOTOOL_PARAM_INDEX_FILTER_CLEAR,
      '{PARAM_INDEX_FAVS_CLEAR}' => IMMOTOOL_PARAM_INDEX_FAVS_CLEAR,
      '{PARAM_INDEX_VIEW}' => IMMOTOOL_PARAM_INDEX_VIEW,
      '{PARAM_INDEX_MODE}' => IMMOTOOL_PARAM_INDEX_MODE,
      '{PARAM_EXPOSE_ID}' => IMMOTOOL_PARAM_EXPOSE_ID,
      '{PARAM_EXPOSE_VIEW}' => IMMOTOOL_PARAM_EXPOSE_VIEW,
      '{PARAM_EXPOSE_IMG}' => IMMOTOOL_PARAM_EXPOSE_IMG,
      '{PARAM_EXPOSE_CONTACT}' => IMMOTOOL_PARAM_EXPOSE_CONTACT,
      '{PARAM_EXPOSE_CAPTCHA}' => IMMOTOOL_PARAM_EXPOSE_CAPTCHA,
    );
    return str_replace(array_keys($replacements), array_values($replacements), $page);
  }

  /**
   * Darstellung einer Fehlerseite.
   * @param string $errorMessage Fehlermeldung
   * @param string $languageCode Gewählte Sprache
   * @param int $startupTime Start-Zeitpunkt der Skript-Ausführung
   * @param object $setup Konfiguration
   * @param array $translations Übersetzungen
   */
  function print_error($errorMessage, $languageCode, $startupTime, &$setup, &$translations) {
    $pageTitle = (isset($translations['errors']['warning'])) ? $translations['errors']['warning'] : 'Warning!';
    $mainTitle = (isset($translations['errors']['anErrorOccured'])) ? $translations['errors']['anErrorOccured'] : 'An error occured!';
    $pageHeader = '';
    $pageContent = '<div id="openestate_error"><h1>' . $mainTitle . '</h1>' . $errorMessage . '</div>';
    $metaRobots = 'noindex,nofollow';
    $setup->ShowLanguageSelection = false;

    $output = immotool_functions::build_page($setup, 'error', $languageCode, $mainTitle, $pageTitle, $pageHeader, $pageContent, $startupTime, $metaRobots);
    if (is_string($setup->Charset) && strlen(trim($setup->Charset)) > 0) {
      $output = immotool_functions::encode($output, $setup->Charset);
    }
    if (is_string($setup->ContentType) && strlen(trim($setup->ContentType)) > 0) {
      header('Content-Type: ' . $setup->ContentType);
    }
    echo $output;
    immotool_functions::shutdown($setup);
  }

  /**
   * Alter einer Datei prüfen
   * @param string $file Pfad der zu prüfenden Datei
   * @param int $maxLifetime maximale Lebenszeit der Datei in Sekunden
   * @return bool Liefert true, wenn die Datei noch nicht die maximale Lebenszeit überschritten hat
   */
  function check_file_age($file, $maxLifetime) {
    if (!is_file($file))
      return false;
    $fileTime = filemtime($file) + $maxLifetime;
    return $fileTime > time();
  }

  /**
   * Umwandlung einer Ausgabe in einen anderen Zeichensatz.
   * @param string $input Eingabe
   * @param string $targetEncoding Zeichensatz
   * @return string Ausgabe
   */
  function encode(&$input, $targetEncoding) {

    if (!function_exists('mb_detect_encoding') || !function_exists('iconv'))
      return $input;

    // Zeichensatz der Ausgabe ermitteln
    $sourceEncoding = strtoupper(mb_detect_encoding($input));
    if ($sourceEncoding === false)
      return $input;

    // Kodierung der Ausgabe ggf. umwandeln
    if (strtoupper(trim($sourceEncoding)) == strtoupper(trim($targetEncoding))) {
      return $input;
    }
    return iconv(
        strtoupper(trim($sourceEncoding)), strtoupper(trim($targetEncoding)) . '//TRANSLIT', $input);
  }

  /**
   * Liefert die URL zu einem Exposé.
   * @param string $id ID der Immobilie
   * @param string $lang Zweistelliger ISO-Sprachcode
   * @param string $urlTemplate URL-Vorlage für Exposé-Links
   * @param bool $escapeXmlSpecialChars HTML-/XML-Sonderzeichen umwandeln
   * return string URL zum Exposé
   */
  function get_expose_url($id, $lang, $urlTemplate = null, $escapeXmlSpecialChars = false) {

    $url = null;

    // Exposé-URL aus Vorlage ermitteln
    if (is_string($urlTemplate) && strlen($urlTemplate) > 0) {
      $replacement = array(
        '{ID}' => $id,
        '{LANG}' => $lang,
      );
      $url = str_replace(array_keys($replacement), array_values($replacement), $urlTemplate);
    }

    // Exposé-URL automatisch ermitteln
    else {
      $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '') ? 'https://' : 'http://';
      $url .= $_SERVER['SERVER_NAME'];
      $url .= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
      $url .= '/expose.php';
      $url .= '?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $id;
      $url .= '&' . IMMOTOOL_PARAM_LANG . '=' . $lang;
    }

    if ($escapeXmlSpecialChars === true) {
      $url = htmlspecialchars($url, ENT_QUOTES);
    }

    return $url;
  }

  /**
   * Erzeugt eine Filter-Instanz.
   * @param string $name Name des Filters
   * @return object Filter-Objekt
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
      include($file);
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
  function get_phpmailer(&$setup) {

    // Instanz des PHPMailers erzeugen
    if (!class_exists('PHPMailer'))
      include_once(IMMOTOOL_BASE_PATH . 'include/class.phpmailer.php');
    $mailer = new PHPMailer();

    // Mailer konfigurieren
    immotool_functions::setup_phpmailer($mailer, $setup);
    return $mailer;
  }

  /**
   * Konfiguriert einen PHP-Mailer aus der Konfiguration.
   * @param object $mailer Instanz des PHP-Mailers
   * @param object $setup Konfiguration
   * @return object PHP-Mailer
   */
  function setup_phpmailer(&$mailer, &$setup) {
    // Mailer konfigurieren
    $mailer->IsHTML(false);
    $mailer->CharSet = 'UTF-8';
    $mailer->From = immotool_functions::encode_mail($setup->MailFrom);
    $mailer->FromName = $setup->MailFromName;
    if (is_string($setup->MailToCC) && strlen(trim($setup->MailToCC)) > 0)
      $mailer->AddCC(immotool_functions::encode_mail($setup->MailToCC));
    if (is_string($setup->MailToBCC) && strlen(trim($setup->MailToBCC)) > 0)
      $mailer->AddBCC(immotool_functions::encode_mail($setup->MailToBCC));
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
  function encode_mail($email) {
    if (!isset($GLOBALS['immotool_idna']) || !is_object($GLOBALS['immotool_idna'])) {
      include_once( IMMOTOOL_BASE_PATH . 'include/Net/IDNA.php' );
      $GLOBALS['immotool_idna'] = Net_IDNA::getInstance();
    }
    return $GLOBALS['immotool_idna']->encode($email, 'utf8');
  }

  /**
   * Erzeugt eine Galerie-Instanz.
   * @param string $name Name der Galerie
   * @return object Galerie-Objekt
   */
  function get_gallery($name) {
    $file = IMMOTOOL_BASE_PATH . 'include/class.gallery_' . strtolower($name) . '.php';
    if (!is_file($file))
      return null;
    $orderClass = 'ImmoToolGallery_' . strtolower($name);
    if (!class_exists($orderClass))
      require_once( $file );
    eval('$gallery = new ' . $orderClass . '();');
    return $gallery;
  }

  /**
   * Erzeugt eine Umkreiskarten-Instanz.
   * @param string $name Name der Umkreiskarte
   * @return object Umkreiskarten-Objekt
   */
  function get_map($name) {
    $file = IMMOTOOL_BASE_PATH . 'include/class.map_' . strtolower($name) . '.php';
    if (!is_file($file))
      return null;
    $mapClass = 'ImmoToolMap_' . strtolower($name);
    if (!class_exists($mapClass))
      require_once( $file );
    eval('$map = new ' . $mapClass . '();');
    return $map;
  }

  /**
   * Liefert die Daten eines Objektes.
   * @param string $id ID des Objektes
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
      $max = (int) IMMOTOOL_OBJECTS_CACHE_SIZE;
      while (count($GLOBALS['immotool_objects']) >= $max) {
        $keys = array_keys($GLOBALS['immotool_objects']);
        unset($GLOBALS['immotool_objects'][$keys[0]]);
      }
      include($file);
      if (!isset($GLOBALS['immotool_objects'][$id]) || !is_array($GLOBALS['immotool_objects'][$id])) {
        return null;
      }
    }
    return $GLOBALS['immotool_objects'][$id];
  }

  /**
   * Liefert den Timestamp der letzten Änderung eines Objektes.
   * @param string $id ID des Objektes
   * @return int Timestamp der letzten Änderung der object.php, oder null wenn nicht ermittelbar
   */
  function get_object_stamp($id = null) {
    if ($id == null || preg_match('/^\w*/i', $id) !== 1) {
      return null;
    }
    $file = IMMOTOOL_BASE_PATH . 'data/' . $id . '/object.php';
    return (is_file($file)) ? filemtime($file) : null;
  }

  /**
   * Erzeugt eine Sortierungs-Instanz.
   * @param string $name Name der Sortierung
   * @return object Sortierungs-Objekt
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
   * @return array AGB-Array, oder null wenn unbekannt
   */
  function get_terms() {
    $file = IMMOTOOL_BASE_PATH . 'data/terms.php';
    if (!is_file($file)) {
      return null;
    }
    if (!isset($GLOBALS['immotool_terms']) || !is_array($GLOBALS['immotool_terms'])) {
      include($file);
      if (!isset($GLOBALS['immotool_terms']) || !is_array($GLOBALS['immotool_terms'])) {
        return null;
      }
    }
    return $GLOBALS['immotool_terms'];
  }

  /**
   * Liefert die Texte eines Objektes.
   * @param string $id ID des Objektes
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
      $max = (int) IMMOTOOL_TEXTS_CACHE_SIZE;
      while (count($GLOBALS['immotool_texts']) >= $max) {
        $keys = array_keys($GLOBALS['immotool_texts']);
        unset($GLOBALS['immotool_texts'][$keys[0]]);
      }
      include($file);
      if (!isset($GLOBALS['immotool_texts'][$id]) || !is_array($GLOBALS['immotool_texts'][$id])) {
        return null;
      }
    }
    return $GLOBALS['immotool_texts'][$id];
  }

  /**
   * Erzeugt eine Video-Instanz.
   * @param string $name Name des Video-Handlers
   * @return object Video-Handler
   */
  function get_video($name) {
    $file = IMMOTOOL_BASE_PATH . 'include/class.video_' . strtolower($name) . '.php';
    if (!is_file($file))
      return null;
    $videoClass = 'ImmoToolVideo_' . strtolower($name);
    if (!class_exists($videoClass))
      require_once( $file );
    eval('$video = new ' . $videoClass . '();');
    return $video;
  }

  /**
   * Hilfsfunktion zur Ermittlung eines RGB-Farbwertes aus einem hexadezimalen
   * Farbcode.
   * @param string $hex Hex-Farbcode, z.B. #c0c0c0
   * @return array Ermittelte RGB-Farbwerte als Array
   */
  function get_rgb_from_hex($hex) {
    if (strlen($hex) > 0 && substr($hex, 0, 1) == '#')
      $hex = substr($hex, 1);
    if (strlen($hex) >= 6) {
      return array(
        'r' => hexdec(substr($hex, 0, 2)),
        'g' => hexdec(substr($hex, 2, 2)),
        'b' => hexdec(substr($hex, 4, 2)),
      );
    }
    return null;
  }

  /**
   * Allgemeine Initialisierungen.
   * @param object $setup Konfiguration
   * @param string $myconfigMethod Name der einzubindenden Funktion aus myconfig.php
   */
  function init(&$setup, $myconfigMethod = null) {
    // ggf. Konfiguration mit myconfig.php überschreiben
    immotool_functions::init_config($setup, $myconfigMethod);

    // Session initialisieren
    immotool_functions::init_session();

    // ggf. die gewählte Kategorie übernehmen
    //if (is_callable(array('immotool_setup','Categories'), true)) die( 'CALLABLE' );
    //echo '<pre>'; print_r($setup); echo '</pre>';
    if (is_callable(array('immotool_setup', 'Categories'), true) && is_array($setup->Categories) && count($setup->Categories) > 0) {
      $cat = (isset($_REQUEST[IMMOTOOL_PARAM_CAT])) ? $_REQUEST[IMMOTOOL_PARAM_CAT] : null;
      if (!is_string($cat) || array_search($cat, $setup->Categories) === false) {
        $cat = immotool_functions::get_session_value('cat', $setup->Categories[0]);
      }

      //die( 'Category: ' . $cat );
      if (array_search($cat, $setup->Categories) !== false) {
        immotool_functions::put_session_value('cat', $cat);
        if (!defined('IMMOTOOL_CAT')) {
          define('IMMOTOOL_CAT', $cat);

          // Bei geänderter Kategorie ggf. die Konfiguration nochmals aus der myconfig.php initialisieren
          if (is_string($myconfigMethod) && is_callable(array('immotool_myconfig', $myconfigMethod))) {
            eval('immotool_myconfig::' . $myconfigMethod . '( $setup );');
          }
        }
      }
    }

    // Inserate ggf. vormerken
    if ($setup->HandleFavourites) {
      $favs = immotool_functions::get_session_value('favs', array());
      $favId = (isset($_REQUEST[IMMOTOOL_PARAM_FAV])) ? $_REQUEST[IMMOTOOL_PARAM_FAV] : null;
      if (is_string($favId) && preg_match('/^\w*/i', $favId) === 1) {
        $pos = array_search($favId, $favs);
        if ($pos === false) {
          $favs[] = $favId;
        }
        else {
          unset($favs[$pos]);
        }
        immotool_functions::put_session_value('favs', $favs);
      }
    }

    // ggf. die Standard-Zeitzone für Datumsformatierungen setzen
    if (is_callable(array('immotool_setup', 'Timezone'), true) && function_exists('date_default_timezone_set')) {
      $tz = (isset($_SERVER['TZ'])) ? $_SERVER['TZ'] : null;
      if (is_string($setup->Timezone) && strlen($setup->Timezone) > 0)
        $tz = $setup->Timezone;
      if (is_string($tz) && strlen($tz) > 0) {
        date_default_timezone_set($tz);
      }
    }
  }

  /**
   * Konfiguration initialisieren.
   * @param object $setup Konfiguration
   * @param string $myconfigMethod Name der einzubindenden Funktion aus myconfig.php
   */
  function init_config(&$setup, $myconfigMethod = null) {
    // ggf. Konfiguration mit myconfig.php überschreiben
    if (is_string($myconfigMethod) && is_callable(array('immotool_myconfig', $myconfigMethod))) {
      eval('immotool_myconfig::' . $myconfigMethod . '( $setup );');
    }
  }

  /**
   * Sprache initialisieren.
   * @param string $requestedLanguage angeforderte Sprache
   * @param string $defaultLanguage Standard-Sprache
   * @param array $translations Container, zur Übergabe der Übersetzungen
   * @return string verwendeter Sprachcode
   */
  function init_language($requestedLanguage, $defaultLanguage, &$translations) {
    // Übersetzungen ermitteln
    $lang = $requestedLanguage;
    if (!is_string($lang) || !immotool_functions::is_language_supported($lang)) {
      $lang = immotool_functions::get_session_value('lang', null);
      if (!is_string($lang) || !immotool_functions::is_language_supported($lang)) {
        $lang = $defaultLanguage;
      }
    }
    immotool_functions::put_session_value('lang', $lang);
    $translations = immotool_functions::get_language_translations($lang);
    if (!is_array($translations)) {
      die('Can\'t load translation for \'' . $lang . '\'!');
    }
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

  /**
   * Prüfung, ob eine Immobilie als Favorit vorgemerkt ist.
   * @param string $favId ID der Immobilie
   * @return bool true, wenn eine Vormerkung vorliegt
   */
  function has_favourite($favId) {
    $favs = immotool_functions::get_session_value('favs', array());
    return array_search($favId, $favs) !== false;
  }

  /**
   * Liefert die Namen der verfügbaren Filter.
   * @return array Namen der verfügbaren Filter
   */
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

  /**
   * Liefert die ID's der verfügbaren Immobilien.
   * @return array ID's der verfügbaren Immobilien
   */
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

  /**
   * Liefert die Namen der verfügbaren Sortierungen.
   * @return array Namen der verfügbaren Sortierungen
   */
  function list_available_orders() {
    $dir = IMMOTOOL_BASE_PATH . 'include/';
    $orders = array();
    if (is_dir($dir)) {
      $files = immotool_functions::list_directory($dir);
      if (is_array($files)) {
        foreach ($files as $file) {
          if (is_file($dir . $file) && strpos($file, 'class.order_') === 0) {
            $order = substr($file, strlen('class.order_'));
            $order = substr($order, 0, -4);
            $orders[] = $order;
          }
        }
      }
    }
    return $orders;
  }

  /**
   * Hilfsfunktion zum Lesen eines Verzeichnisses.
   * @param string $directory Pfad zum Verzeichnis
   * @return array Namen der Dateien / Unterordner des Verzeichnisses
   */
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

  /**
   * Liefert ID's zur Seitendarstellung des Immobilienbestandes.
   * @param int $pageNumber Seitenzahl
   * @param int $elementsPerPage Einträge pro Seite
   * @param string $orderBy Name des Sortierkriteriums
   * @param string $orderDir Richtung der Sortierung (asc / desc)
   * @param array $filters Gewählte Filterkriterien
   * @param int $totalCount Überhabevariable für die Anzahl der Inserate (ohne Seitendarstellung)
   * @param string $lang Gewählte Sprache
   * @param int $maxLifeTime Maximale Lebensdauer von Cache-Dateien in Sekunden.
   * @param array $favIds Array mit ID's der vorgemerkten Inserate
   * @return array Liste mit ID's der Immobilien auf der angeforderten Seite
   */
  function list_objects($pageNumber, $elementsPerPage, $orderBy, $orderDir, $filters, &$totalCount, $lang, $maxLifeTime, $favIds) {
    // ID's der Inserate ermitteln
    $ids = null;
    if (is_string($orderBy)) {
      $orderObj = immotool_functions::get_order($orderBy);
      if ($orderObj == null)
        die('unknown order: ' . $orderBy);
      if ($orderObj != null && $orderObj->readOrRebuild($maxLifeTime)) {
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
      if ($filterObj == null || !$filterObj->readOrRebuild($maxLifeTime))
        continue;
      $filterItems = null;
      foreach (explode(',', $filterValue) as $filterVal) {
        $filterVal = trim($filterVal);
        if (strlen($filterVal) == 0)
          continue;
        $items = $filterObj->getItems($filterVal);
        if (!is_array($items))
          continue;
        if ($filterItems == null) {
          $filterItems = $items;
          continue;
        }
        foreach ($items as $item) {
          $filterItems[] = $item;
        }
      }
      if (is_array($filterItems)) {
        $ids = array_values(array_intersect($ids, $filterItems));
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

  /**
   * Mailversand durchführen.
   * @param object $setup Konfiguration
   * @param string $subject Betreff
   * @param string $body Text
   * @param string $mailToAdress E-Mail des Empfängers
   * @param string $replyToAdress E-Mail des Antwortempfängers
   * @param string $replyToName Name des Antwortempfängers
   * @return mixed Im Erfolgsfall 'true', sonst eine Fehlermeldung
   */
  function send_mail(&$setup, $subject, $body, $mailToAdress, $replyToAdress, $replyToName) {

    // Mailversand über ein externes Framework
    if (is_callable(array('immotool_myconfig', 'send_mail'))) {
      $result = immotool_myconfig::send_mail($setup, $subject, $body, $mailToAdress, $replyToAdress, $replyToName);
      if (!is_null($result))
        return $result;
    }

    // Mailversand über den lokalen PHP-Mailer
    $mailer = immotool_functions::get_phpmailer($setup);
    return immotool_functions::send_mail_via_phpmailer($mailer, $subject, $body, $mailToAdress, $replyToAdress, $replyToName);
  }

  /**
   * Mailversand via PHP-Mailer.
   * @param object $mailer PHP-Mailer
   * @param string $subject Betreff
   * @param string $body Text
   * @param string $mailToAdress E-Mail des Empfängers
   * @param string $replyToAdress E-Mail des Antwortempfängers
   * @param string $replyToName Name des Antwortempfängers
   * @return mixed Im Erfolgsfall 'true', sonst eine Fehlermeldung
   */
  function send_mail_via_phpmailer(&$mailer, $subject, $body, $mailToAdress, $replyToAdress, $replyToName) {

    // Mailversand via PHP-Mailer
    $mailer->Body = $body;
    $mailer->Subject = $subject;
    $mailer->AddAddress(immotool_functions::encode_mail($mailToAdress));
    $mailer->AddReplyTo(immotool_functions::encode_mail($replyToAdress), $replyToName);
    if ($mailer->Send())
      return true;
    return $mailer->ErrorInfo;
  }

  /**
   * Hilfsfunktion zum Lesen einer Datei.
   * @param string $file Pfad zur Datei
   * @return string Inhalt der Datei
   */
  function read_file($file) {
    return (is_string($file) && is_file($file)) ? file_get_contents($file) : false;
  }

  /**
   * Hilfsfunktion zum Lesen einer Datei aus dem Template-Verzeichnis.
   * @param string $file Name der Datei im Template-Verzeichnis
   * @param string $subfolder Name der Unterordners, aus dem die Template-Datei bevorzugt geladen werden soll
   * @return string Inhalt der Datei
   */
  function read_template($file, $subfolder = 'default') {
    if (!is_string($subfolder)) {
      $subfolder = 'default';
    }
    $path = IMMOTOOL_BASE_PATH . 'templates/' . $subfolder . '/' . $file;
    if (!is_file($path) && $subfolder != 'default') {
      $path = IMMOTOOL_BASE_PATH . 'templates/default/' . $file;
    }
    return immotool_functions::read_file($path);
  }

  /**
   * URL's in einem Text mit HTML-Links ersetzen.
   * @param string $text Eingabetext
   * @return string Ausgabetext
   */
  function replace_links(&$text) {
    $replacements = array(
      '#(https?|ftps?|mailto):\/\/([&;:=\.\/\-\?\w]+)#i' => '<a href="\1://\2" target="_blank" rel="follow">\1://\2</a>',
    );
    return preg_replace(array_keys($replacements), array_values($replacements), $text);
  }

  /**
   * Hilfsfunktion zum Ersetzen eines Platzhalters in einem Text.
   * @param string $varName Name der Platzhalter-Variablen
   * @param string $value Wert im einzufügenden Platzhalter
   * @param string $src Eingabetext
   */
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

  /**
   * Ermittlung des Textes, der zwischen zwei Begrenzungstexten steht.
   * @param string $string Eingabetext
   * @param string $start Begrenzungstext am Anfang
   * @param string $start Begrenzungstext am Ende
   * @return string Textausschnitt des Eingabetextes,
   *   der sich zwischen den beiden Begrenzungstexten befindet.
   */
  function get_string_between($string, $start, $end) {
    $string = " " . $string;
    $ini = strpos($string, $start);
    if ($ini == 0)
      return "";
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
  }

  /**
   * Session initialisieren.
   * @return array Session-Variable
   */
  function init_session() {

    // Prüfen, ob die Session eventuell bereits initialisiert wurde
    if (isset($GLOBALS['immotool_session_id']) && !is_null($GLOBALS['immotool_session_id'])) {
      return $GLOBALS['immotool_session'];
    }

    // Session-ID aus dem Cookie ermitteln
    if (isset($_COOKIE[IMMOTOOL_SESSION_COOKIE_NAME]) && is_string($_COOKIE[IMMOTOOL_SESSION_COOKIE_NAME])) {
      $GLOBALS['immotool_session_id'] = $_COOKIE[IMMOTOOL_SESSION_COOKIE_NAME];
    }
    // Neue Session-ID generieren
    else {
      $GLOBALS['immotool_session_id'] = sha1(IMMOTOOL_CRYPT_KEY . '-' . time() . '-' . rand(0, 99999));
    }

    // Session-ID als Cookie speichern
    if (!headers_sent()) {
      setcookie(
          IMMOTOOL_SESSION_COOKIE_NAME, // name
          $GLOBALS['immotool_session_id'], // value
          time() + IMMOTOOL_SESSION_COOKIE_AGE, // expires after 30 days
          IMMOTOOL_SESSION_COOKIE_PATH, // path
          IMMOTOOL_SESSION_COOKIE_DOMAIN, // domain
          IMMOTOOL_SESSION_COOKIE_SECURE   // secure
      );
    }

    // Session-Variable aus zwischengespeicherter Datei rekonstruieren
    $file = IMMOTOOL_BASE_PATH . 'sessions/' . sha1(IMMOTOOL_CRYPT_KEY . '-' . $GLOBALS['immotool_session_id']);
    if (!is_file($file)) {
      $GLOBALS['immotool_session'] = array();
      return $GLOBALS['immotool_session'];
    }
    else {
      $GLOBALS['immotool_session'] = unserialize(immotool_functions::read_file($file));
      return $GLOBALS['immotool_session'];
    }
  }

  /**
   * Session speichern.
   * @return boolean Liefert im Erfolgsfall TRUE, sonst FALSE.
   */
  function store_session() {

    if (!isset($GLOBALS['immotool_session_id']) || !is_string($GLOBALS['immotool_session_id'])) {
      echo 'Unknown session-id!';
      return false;
    }
    if (!isset($GLOBALS['immotool_session']) || !is_array($GLOBALS['immotool_session'])) {
      $GLOBALS['immotool_session'] = array();
    }

    // Session-Variable in Datei zwischenspeichern
    $data = serialize($GLOBALS['immotool_session']);
    $file = IMMOTOOL_BASE_PATH . 'sessions/' . sha1(IMMOTOOL_CRYPT_KEY . '-' . $GLOBALS['immotool_session_id']);
    $fh = fopen($file, 'w');
    if ($fh === false) {
      echo 'Can\'t write file: ' . $file;
      return false;
    }
    fwrite($fh, $data);
    fclose($fh);

    // abgelaufene Session-Dateien entfernen
    immotool_functions::cleanup_sessions();

    return true;
  }

  /**
   * Einen Wert aus der Session-Variablen ermitteln.
   * @param string $key Bezeichner des zu ermittelnden Wertes
   * @param mixed $defaultValue Standard-Wert, wenn zum Bezeichner kein Wert hinterlegt ist
   * @return mixed Wert aus der Session-Variablen, oder $defaultValue wenn nicht vorhanden.
   */
  function get_session_value($key, $defaultValue = null) {
    if (!isset($GLOBALS['immotool_session']) || !is_array($GLOBALS['immotool_session'])) {
      return $defaultValue;
    }
    return (isset($GLOBALS['immotool_session'][$key])) ?
        $GLOBALS['immotool_session'][$key] : $defaultValue;
  }

  /**
   * Einen Wert in die Session-Variable schreiben.
   * @param string $key Bezeichner des zu schreibenden Wertes
   * @param mixed $value Der zu hinterlegende Wert, oder bei null wird der Eintrag aus der Session-Variablen entfernt.
   */
  function put_session_value($key, $value = null) {
    if (!isset($GLOBALS['immotool_session']) || !is_array($GLOBALS['immotool_session'])) {
      $GLOBALS['immotool_session'] = array();
    }
    if (is_null($value)) {
      if (isset($GLOBALS['immotool_session'][$key])) {
        unset($GLOBALS['immotool_session'][$key]);
      }
    }
    else {
      $GLOBALS['immotool_session'][$key] = $value;
    }
  }

  /**
   * Abgelaufene Session-Dateien entfernen.
   * @param boolean $force Bereinigung erzwingen, auch wenn in den letzten 24 Stunden bereits eine Bereinigung durchgeführt wurde.
   * @return array Namen der gelöschten Session-Dateien
   */
  function cleanup_sessions($force = false) {
    $sessionDir = IMMOTOOL_BASE_PATH . 'sessions';
    if (!is_dir($sessionDir))
      return array();
    $now = time();

    // ggf. keine Bereinigung durchführen,
    // wenn dies bereits innerhalb der letzten 24 Stunden erfolgt ist
    $stampFile = 'sessions.stamp';
    if ($force !== true && is_file($sessionDir . '/' . $stampFile)) {
      $nextCleanupTime = filemtime($sessionDir . '/' . $stampFile) + 86400;
      if ($nextCleanupTime > $now) {
        //echo '<p>next session clean at '.date( 'd.m.Y H:i:s', $nextCleanupTime).'</p>';
        return array();
      }
    }

    // Vormerkung, dass eine Session-Bereinigung stattgefunden hat
    @touch($sessionDir . '/' . $stampFile, $now);

    // Dateien im Session-Verzeichnis ermitteln
    $droppedFiles = array();
    $minLifeTime = $now - IMMOTOOL_SESSION_COOKIE_AGE;
    $files = immotool_functions::list_directory($sessionDir);
    foreach ($files as $file) {
      if ($file != '.htaccess' && $file != 'index.html' && $file != $stampFile) {
        $path = $sessionDir . '/' . $file;
        $sessionTime = filemtime($path);
        if ($sessionTime < $minLifeTime) {
          $droppedFiles[] = $file;
          @unlink($path);
        }
      }
    }
    return $droppedFiles;
  }

  /**
   * Verarbeitung beenden.
   * @param object $setup Konfiguration
   */
  function shutdown(&$setup) {
    immotool_functions::store_session();
  }

  /**
   * Überprüfung, ob der Kontakt-Reiter für eine Immobilie in der Exposé-Ansicht angezeigt werden soll.
   * @param array $object Immobilie
   * @param object $setup Exposé-Konfiguration
   * @return bool Liefert true, wenn der Kontakt-Reiter für eine Immobilie angezeigt werden kann.
   */
  function can_show_expose_contact(&$object, &$setup) {
    $tabEnabled = array_search('contact', $setup->ViewOrder) !== false;
    $hasContactPerson = isset($object['contact']) && is_array($object['contact']) && count($object['contact']) > 0;
    $hasContactMail = isset($object['mail']) && is_string($object['mail']) && strlen(trim($object['mail'])) > 0;
    return $tabEnabled && (
        ($setup->ShowContactPerson && $hasContactPerson) ||
        ($setup->ShowContactForm && $hasContactMail)
        );
  }

  /**
   * Überprüfung, ob der Galerie-Reiter für eine Immobilie in der Exposé-Ansicht angezeigt werden soll.
   * @param array $object Immobilie
   * @param object $setup Exposé-Konfiguration
   * @return bool Liefert true, wenn der Galerie-Reiter für eine Immobilie angezeigt werden kann.
   */
  function can_show_expose_gallery(&$object, &$setup) {
    $tabEnabled = array_search('gallery', $setup->ViewOrder) !== false;
    return $tabEnabled && isset($object['images']) && is_array($object['images']) && count($object['images']) > 0;
  }

  /**
   * Überprüfung, ob der Karten-Reiter für eine Immobilie in der Exposé-Ansicht angezeigt werden soll.
   * @param array $object Immobilie
   * @param object $setup Exposé-Konfiguration
   * @param object $mapHandler Handler zur Karten-Darstellung
   * @return bool Liefert true, wenn der Karten-Reiter für eine Immobilie angezeigt werden kann.
   */
  function can_show_expose_map(&$object, &$setup, &$mapHandler) {
    $tabEnabled = array_search('map', $setup->ViewOrder) !== false;
    return $tabEnabled && is_object($mapHandler) && $mapHandler->canShowForObject($object);
  }

  /**
   * Überprüfung, ob der Medien-Reiter für eine Immobilie in der Exposé-Ansicht angezeigt werden soll.
   * @param array $object Immobilie
   * @param object $setup Exposé-Konfiguration
   * @return bool Liefert true, wenn der Medien-Reiter für eine Immobilie angezeigt werden kann.
   */
  function can_show_expose_media(&$object, &$setup) {
    $tabEnabled = array_search('media', $setup->ViewOrder) !== false;
    $hasMedia = isset($object['media']) && is_array($object['media']) && count($object['media']) > 0;
    $hasLinks = isset($object['links']) && is_array($object['links']) && count($object['links']) > 0;
    return $tabEnabled && ($hasMedia || $hasLinks);
  }

  /**
   * Überprüfung, ob der AGB-Reiter für eine Immobilie in der Exposé-Ansicht angezeigt werden soll.
   * @param object $setup Exposé-Konfiguration
   * @return bool Liefert true, wenn der AGB-Reiter für eine Immobilie angezeigt werden kann.
   */
  function can_show_expose_terms(&$setup) {
    $tabEnabled = array_search('terms', $setup->ViewOrder) !== false;
    return $tabEnabled && $setup->ShowTerms;
  }

  /**
   * Überprüfung, ob der Texte-Reiter für eine Immobilie in der Exposé-Ansicht angezeigt werden soll.
   * @param array $objectTexts Immobilien-Texte
   * @param object $setup Exposé-Konfiguration
   * @param string $lang Aktuelle Sprache
   * @return bool Liefert true, wenn der Texte-Reiter für eine Immobilie angezeigt werden kann.
   */
  function can_show_expose_texts(&$objectTexts, &$setup, $lang) {
    $tabEnabled = array_search('texts', $setup->ViewOrder) !== false;
    $hasTexts = false;
    if (is_array($objectTexts)) {
      if (!isset($setup->TextOrder) || !is_array($setup->TextOrder)) {
        $hasTexts = count($objectTexts) > 1;
      }
      else {
        foreach ($setup->TextOrder as $attrib) {
          if (isset($objectTexts[$attrib][$lang])) {
            $hasTexts = true;
            break;
          }
        }
      }
    }
    return $tabEnabled && $hasTexts;
  }

  /**
   * Hilfsfunktion zum 'wrappen' einer erzeugten Webseite.
   * Diese Funktion wird von den PHP-Wrapper-Modulen verwendet.
   * @param string $page HTML-Code der einzubindenden Seit
   * @param string $wrapType Art der eingebundenen Seite
   * @param string $wrapperScriptUrl URL zum umgebenden Script
   * @param string $immotoolBaseUrl URL zum ImmoTool-Export
   * @param array $stylesheets Liste mit verwendeten Stylesheets
   * @param array $hiddenParams Key-Value-Paar mit zusätzlichen Parametern
   * @return string HTML-Code der 'gewrappten' Seite
   */
  function wrap_page(&$page, $wrapType, $wrapperScriptUrl, $immotoolBaseUrl, $stylesheets, $hiddenParams = null) {
    // Stylesheets importieren
    $header = '';
    if (is_array($stylesheets) && count($stylesheets) > 0) {
      $header = "\n<style type=\"text/css\">";
      foreach ($stylesheets as $style)
        $header .= "\n@import \"$style\";";
      $header .= "\n</style>";
    }

    // HACK: Einbindung der Galerie-Skripte in Exposés
    if ($wrapType == 'expose') {
      $setup = new immotool_setup_expose();
      if (is_callable(array('immotool_myconfig', 'load_config_expose')))
        immotool_myconfig::load_config_expose($setup);
      $galleryHandler = immotool_functions::get_gallery($setup->GalleryHandler);
      if (!is_object($galleryHandler))
        $galleryHandler = immotool_functions::get_gallery('html');
      $header .= "\n" . $galleryHandler->getHeader();
    }

    // Haupt-URL ohne Parameter ermitteln
    $pos = strpos($wrapperScriptUrl, '?');
    $wrapperBaseUrl = ($pos !== false) ? substr($wrapperScriptUrl, 0, $pos) : $wrapperScriptUrl;
    $sep = ($pos !== false) ? '&amp;' : '?';

    // Zusätzliche Hidden-Parameter zur Verwendung in Formularen vorbereiten
    $hiddenInputs = '';
    if (is_array($hiddenParams)) {
      foreach ($hiddenParams as $key => $value) {
        $hiddenInputs .= '<input type="hidden" name="' . $key . '" value="' . $value . '"/>';
      }
    }

    // Inhalt des BODY-Tags ermitteln
    $bodyStart = strpos(strtolower($page), '<body');
    if ($bodyStart === false) {
      return '';
    }
    $body = substr($page, strpos($page, '>', $bodyStart) + 1);
    $bodyEnd = strpos(strtolower($body), '</body');
    if ($bodyEnd === false) {
      return '';
    }
    $body = $header . trim(substr($body, 0, $bodyEnd));
    //die( 'body: ' . htmlentities( $body ) );
    // Ersetzungen
    $replacements = array(
      // Inhalt des BODY-Tags ermitteln
      //'/(.*)<body([^>]*)>(.*)<\/body>(.*)/is' => '<div\2>'.$header.'\3</div>',
      // Verlinkungen innerhalb der aktuellen Seite
      '/<a([^>]*)href="\?([^"]*)"/is' => '<a\1href="' . $wrapperScriptUrl . $sep . 'wrap=' . $wrapType . '&amp;\2"',
      // index.php => Links
      '/<a([^>]*)href="index\.php"/is' => '<a\1href="' . $wrapperScriptUrl . $sep . 'wrap=index"',
      '/<a([^>]*)href="index\.php\?([^"]*)"/is' => '<a\1href="' . $wrapperScriptUrl . $sep . 'wrap=index&amp;\2"',
      // index.php => Formulare
      '/<form([^>]*)action="index\.php([^"]*)"([^>]*)>/is' => '<form\1action="' . $wrapperBaseUrl . '\2"\3><input type="hidden" name="wrap" value="index"/>' . $hiddenInputs,
      // expose.php => Links
      '/<a([^>]*)href="expose\.php"/is' => '<a\1href="' . $wrapperScriptUrl . $sep . 'wrap=expose"',
      '/<a([^>]*)href="expose\.php\?([^"]*)"/is' => '<a\1href="' . $wrapperScriptUrl . $sep . 'wrap=expose&amp;\2"',
      // expose.php => Formulare
      '/<form([^>]*)action="expose\.php([^"]*)"([^>]*)>/is' => '<form\1action="' . $wrapperBaseUrl . '\2"\3><input type="hidden" name="wrap" value="expose"/>' . $hiddenInputs,
      // download.php => Links
      '/<a([^>]*)href="download.php"/is' => '<a\1href="' . $immotoolBaseUrl . 'download.php',
      '/<a([^>]*)href="download.php\?([^"]*)"/is' => '<a\1href="' . $immotoolBaseUrl . 'download.php?\2"',
      // img.php
      '/<img([^>]*)src="img\.php"/is' => '<img\1src="' . $immotoolBaseUrl . 'img.php"',
      '/<img([^>]*)src="img\.php\?([^"]*)"/is' => '<img\1src="' . $immotoolBaseUrl . 'img.php?\2"',
      // captcha.php
      '/<img([^>]*)src="captcha\.php"/is' => '<img\1src="' . $immotoolBaseUrl . 'captcha.php"',
      '/<img([^>]*)src="captcha\.php\?([^"]*)"/is' => '<img\1src="' . $immotoolBaseUrl . 'captcha.php?\2"',
      '/src=\'captcha\.php([^\']*)\'/is' => 'src=\'' . $immotoolBaseUrl . 'captcha.php\1\'',
      // Includeverzeichnis
      '/<script([^>]*)src="include\/([^"]*)"/is' => '<script\1src="' . $immotoolBaseUrl . 'include/\2"',
      '/<link([^>]*)href="include\/([^"]*)"/is' => '<link\1href="' . $immotoolBaseUrl . 'include/\2"',
      // Datenverzeichnis
      '/<a([^>]*)href="data\/([^"]*)\.([^"]*)"/is' => '<a\1href="' . $immotoolBaseUrl . 'data/\2.\3"',
      '/<img([^>]*)src="data\/([^"]*)"/is' => '<img\1src="' . $immotoolBaseUrl . 'data/\2"',
      // Bildverzeichnis
      '/<img([^>]*)src="img\/([^"]*)"/is' => '<img\1src="' . $immotoolBaseUrl . 'img/\2"',
      '/\'img\/([^\']*)\'/is' => '\'' . $immotoolBaseUrl . 'img/\1\'',
      '/\'\.\/img\/([^\']*)\'/is' => '\'' . $immotoolBaseUrl . 'img/\1\'',
    );
    return preg_replace(array_keys($replacements), array_values($replacements), $body);
  }

  /**
   * Liefert die lesbare Ausgabe eines Attribut-Wertes.
   * @param string $group Name der Attribut-Gruppe
   * @param string $attrib Name des Attributes
   * @param array $value Attribut-Werte
   * @param array $translations Übersetzungen in der angeforderten Sprache
   * @param string $lang Sprache
   * @return string lesbare Ausgabe des Attribut-Wertes
   */
  function write_attribute_value($group, $attrib, &$value, &$translations, $lang) {
    if (!is_array($value)) {
      return null;
    }

    $txt = null;

    // ggf. individuellen Attribut-Wert aus der myconfig.php ermitteln
    if (is_callable(array('immotool_myconfig', 'write_attribute_value'))) {
      $txt = immotool_myconfig::write_attribute_value($group, $attrib, $value, $translations, $lang);
    }

    // ggf. den Texte "ab sofort" ausgeben,
    // wenn der Verfügbarkeits-Beginn in der Vergangenheit liegt
    if (is_null($txt) && $group == 'administration' && $attrib == 'availability_begin_date') {
      $stamp = (isset($value['value'])) ? $value['value'] : null;
      if (is_numeric($stamp) && $stamp <= time()) {
        $txt = (isset($translations['labels']['fromNowOn'])) ?
            $translations['labels']['fromNowOn'] : null;
      }
    }

    // ggf. Attribut-Wert zur angeforderten Sprache ermitteln
    if (is_null($txt)) {
      $txt = (isset($value[$lang])) ? $value[$lang] : null;
    }

    // ggf. den unformatierten Attribut-Wert ermitteln
    if (is_null($txt)) {
      $txt = (isset($value['value'])) ? $value['value'] : null;
    }

    return $txt;
  }

  /**
   * Liefert eine lesbare Ausgabe einer Byte-Anzahl.
   * @param int $size Anzahl Bytes
   * @return string lesbare Ausgabe der Byte-Anzahl
   */
  function write_bytes($size) {
    $unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
  }

  /**
   * Überprüfung, ob ein Hostname gültig ist.
   * siehe http://en.wikipedia.org/wiki/Hostname#Restrictions_on_valid_host_names
   * @param string $hostname Der zu prüfende Hostname
   * @return bool Liefert true, wenn es sich um einen gültigen Hostnamen handelt.
   */
  function is_valid_hostname($hostname) {

    if (!is_string($hostname))
      return false;
    $len = strlen($hostname);
    $hostname = strtolower(trim($hostname));
    if (strlen($hostname) < 1)
      return false;
    if (strlen($hostname) != $len)
      return false;

    $shortPattern = '/^[a-z0-9]+$/';
    $longPattern = '/^[a-z0-9][a-z0-9\\-]*[a-z0-9]$/';
    $labels = explode('.', $hostname);
    foreach ($labels as $label) {
      if (!is_string($label))
        return false;
      if (strlen($label) < 1)
        return false;
      $idnLabel = immotool_functions::encode_mail($label);
      if (strlen($idnLabel) < 3) {
        if (preg_match($shortPattern, $idnLabel) !== 1) {
          //echo '<p>INVALID LABEL: ' . $idnLabel . '</p>';
          return false;
        }
      }
      else {
        if (preg_match($longPattern, $idnLabel) !== 1) {
          //echo '<p>INVALID LABEL: ' . $idnLabel . '</p>';
          return false;
        }
      }
    }

    return true;
  }

  /**
   * Überprüfung, ob eine E-Mailadresse gültig ist.
   * siehe http://en.wikipedia.org/wiki/Email_address#Syntax
   * @param string $hostname Die zu prüfende E-Mailadresse
   * @return bool Liefert true, wenn es sich um eine gültige E-Mailadresse handelt.
   */
  function is_valid_mail_address($email) {
    //echo '<p>VALIDATE ' . $email . '</p>';
    if (!is_string($email))
      return false;
    $email = trim($email);
    if (strlen($email) < 1)
      return false;

    $values = explode('@', $email);
    if (!is_array($values) || count($values) != 2)
      return false;

    // Domain-Part prüfen
    if (immotool_functions::is_valid_hostname($values[1]) !== true) {
      //echo '<p>INVALID DOMAIN-PART: ' . $values[1] . '</p>';
      return false;
    }

    // Local-Part prüfen
    $pattern = '/^[a-zA-Z0-9!#\$%&\'*+\-\/=?^_`\{\|\}\.]+$/';
    if (preg_match($pattern, $values[0]) !== 1) {
      //echo '<p>INVALID LOCAL-PART: ' . $values[0] . '</p>';
      return false;
    }

    return true;
  }

}
