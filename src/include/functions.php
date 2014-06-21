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
 * Website-Export, Hilfsfunktionen.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2012, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE')) {
  exit;
}

define('IMMOTOOL_SCRIPT_VERSION', '1.5.19');
//error_reporting( E_ALL );
//ini_set('display_errors','1');
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

if (is_file(IMMOTOOL_BASE_PATH . 'myconfig.php'))
  include( IMMOTOOL_BASE_PATH . 'myconfig.php' );

/**
 * Hilfsfunktionen des ImmoTool PHP-Exports.
 */
class immotool_functions {

  /**
   * Erzeugung einer Seite.
   * @param string $pageId ID der Seite
   * @param string $languageCode Gewählte Sprache
   * @param string $mainTitle Haupttitel
   * @param string $pageTitle Untertitel
   * @param string $pageHeader HTML-Code, der im head-Bereich der Seite eingefügt wird.
   * @param string $pageContent HTML-Code, der im body-Bereich der Seite eingefügt wird.
   * @param string $buildTime Dauer der Erzeugung
   * @param string $addonStylesheet URL des zusätzlichen Stylesheets.
   * @param string $showLanguageSelection Sprachauswahl darstellen.
   * @param string $metaRobots Als Meta-Tag dargestellte Robots-Einstellungen
   * @param string $linkParam zusätzliche Parameter für Links, z.B. in der Sprachauswahl
   * @param string $metaKeywords Als Meta-Tag dargestellte Keywords
   * @param string $metaDescription Als Meta-Tag dargestellte Beschreibung
   * @return string HTML-Code der erzeugten Seite
   */
  function build_page($pageId, $languageCode, $mainTitle, $pageTitle, $pageHeader, &$pageContent, $buildTime, $addonStylesheet, $showLanguageSelection = true, $metaRobots = 'index,follow', $linkParam = '', $metaKeywords = null, $metaDescription = null) {

    $page = null;
    if (defined('IMMOTOOL_CAT'))
      $page = immotool_functions::read_template('global_' . IMMOTOOL_CAT . '.html');
    if (!is_string($page))
      $page = immotool_functions::read_template('global.html');
    if (!is_string($pageHeader))
      $pageHeader = '';
    if (strlen($pageHeader) > 0)
      $pageHeader .= "\n";

    // Sprachauswahl
    $languages = immotool_functions::get_language_codes();
    $languageSelection = null;
    if ($showLanguageSelection === true) {
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
      $pageHeader .= '<meta name="description" content="' . htmlentities(trim($metaDescription)) . '" />' . "\n";
    }

    // META-Keywords
    if (is_string($metaKeywords) && strlen(trim($metaKeywords)) > 0) {
      $pageHeader .= '<meta name="keywords" content="' . htmlentities(trim($metaKeywords)) . '" />' . "\n";
    }

    // META-Robots
    if (is_string($metaRobots) && strlen(trim($metaRobots)) > 0) {
      $pageHeader .= '<meta name="robots" content="' . htmlentities(trim($metaRobots)) . '" />' . "\n";
    }
    else {
      $pageHeader .= '<meta name="robots" content="index,follow" />' . "\n";
    }

    // zusätzlicher Stylesheet
    if (is_string($addonStylesheet) && strlen(trim($addonStylesheet)) > 0) {
      if (strlen($pageHeader) > 0)
        $pageHeader .= "\n";
      $pageHeader .= '<link rel="stylesheet" href="' . htmlentities(trim($addonStylesheet)) . '" />' . "\n";
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
    $pageFooter = 'v' . IMMOTOOL_SCRIPT_VERSION . ', built in ' . number_format($buildTime, '3') . 's';
    $pageFooter .= '<br/>powered by <a href="http://openestate.org" target="_blank">OpenEstate</a>';

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
      '{SESSION_NAME}' => session_name(),
      '{PARAM_LANG}' => IMMOTOOL_PARAM_LANG,
      '{PARAM_FAV}' => IMMOTOOL_PARAM_FAV,
      '{PARAM_CAT}' => IMMOTOOL_PARAM_CAT,
      '{PARAM_CAPTCHA_SESSION}' => IMMOTOOL_PARAM_CAPTCHA_SESSION,
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
      include($file);
      if (!isset($GLOBALS['immotool_texts'][$id]) || !is_array($GLOBALS['immotool_texts'][$id])) {
        return null;
      }
    }
    return $GLOBALS['immotool_texts'][$id];
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
    // Session initialisieren
    if (!isset($_SESSION['immotool']) || !is_array($_SESSION['immotool'])) {
      $_SESSION['immotool'] = array();
    }

    // ggf. Konfiguration mit myconfig.php überschreiben
    if (is_string($myconfigMethod) && is_callable(array('immotool_myconfig', $myconfigMethod))) {
      eval('immotool_myconfig::' . $myconfigMethod . '( $setup );');
    }

    // ggf. die gewählte Kategorie übernehmen
    //if (is_callable(array('immotool_setup','Categories'), true)) die( 'CALLABLE' );
    //echo '<pre>'; print_r($setup); echo '</pre>';
    if (is_callable(array('immotool_setup', 'Categories'), true) && is_array($setup->Categories) && count($setup->Categories) > 0) {
      $cat = (isset($_REQUEST[IMMOTOOL_PARAM_CAT])) ? $_REQUEST[IMMOTOOL_PARAM_CAT] : null;
      if (!is_string($cat) || array_search($cat, $setup->Categories) === false)
        $cat = (isset($_SESSION['immotool']['cat'])) ? $_SESSION['immotool']['cat'] : $setup->Categories[0];
      //die( 'Category: ' . $cat );
      if (array_search($cat, $setup->Categories) !== false) {
        $_SESSION['immotool']['cat'] = $cat;
        if (!defined('IMMOTOOL_CAT')) {
          define('IMMOTOOL_CAT', $cat);

          // HACK: bei geänderter Kategorie ggf. die Konfiguration nochmals mit myconfig.php überschreiben
          if (is_string($myconfigMethod) && is_callable(array('immotool_myconfig', $myconfigMethod))) {
            eval('immotool_myconfig::' . $myconfigMethod . '( $setup );');
          }
        }
      }
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
    //if (is_callable(array('immotool_setup','Timezone'), true)) die( 'CALLABLE' );
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

  /**
   * Prüfung, ob eine Immobilie als Favorit vorgemerkt ist.
   * @param string $favId ID der Immobilie
   * @return bool true, wenn eine Vormerkung vorliegt
   */
  function has_favourite($favId) {
    $pos = array_search($favId, $_SESSION['immotool']['favs']);
    return $pos !== false;
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
    return file_get_contents($file);
  }

  /**
   * Hilfsfunktion zum Lesen einer Datei aus dem Template-Verzeichnis.
   * @param string $file Name der Datei im Template-Verzeichnis
   * @return string Inhalt der Datei
   */
  function read_template($file) {
    return immotool_functions::read_file(IMMOTOOL_BASE_PATH . 'templates/' . $file);
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
    if ($bodyStart === false)
      return '';
    $body = substr($page, strpos($page, '>', $bodyStart) + 1);
    $bodyEnd = strpos(strtolower($body), '</body');
    if ($bodyEnd === false)
      return '';
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

}
