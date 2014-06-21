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
 * Website-Export, Darstellung der Exposé-Ansicht.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Hilfsfunktionen des ImmoTool-Exposés.
 */
class immotool_expose {

  /**
   * Kontaktdaten / Formular zur Immobilie darstellen.
   * @param array $object Immobilie
   * @param object $setup Konfiguration
   * @param array $translations Übersetzungen
   * @param string $lang Sprache
   * @return string HTML-Code
   */
  function contact(&$object, &$setup, &$translations, $lang) {
    $output = '<h2>' . $translations['labels']['estate.contact.title'] . '</h2>';
    $output .= immotool_functions::read_template('expose_contact.html');

    // Ansprechpartner darstellen
    $showContactPerson = null;
    if (is_array($object['contact']) && count($object['contact']) > 0) {
      $showContactPerson = $translations['labels']['estate.contact.person'];

      // Anschrift
      $contactAdressLine1 = '';
      $contactAdressLine2 = '';
      if (is_string($object['contact']['street'])) {
        $contactAdressLine1 .= $object['contact']['street'];
        if (is_string($object['contact']['street_nr']))
          $contactAdressLine1 .= ' ' . $object['contact']['street_nr'];
      }
      if (is_string($object['contact']['postal']))
        $contactAdressLine2 .= $object['contact']['postal'] . ' ';
      if (is_string($object['contact']['city']))
        $contactAdressLine2 .= $object['contact']['city'] . ' ';
      if (is_string($object['contact']['city_part']))
        $contactAdressLine2 .= '(' . $object['contact']['city_part'] . ') ';
      if (strlen($contactAdressLine2) > 0 && strlen($contactAdressLine1) == 0) {
        $contactAdressLine1 = $contactAdressLine2;
        $contactAdressLine2 = null;
      }

      $replacement = array(
        '{CONTACT_NAME_TITLE}' => $translations['labels']['estate.contact.person.name'],
        '{CONTACT_NAME_VALUE}' => $object['contact']['person_fullname'],
        '{CONTACT_ADRESS_TITLE}' => $translations['labels']['estate.contact.person.adress'],
        '{CONTACT_ADRESS_VALUE}' => $contactAdressLine1,
        '{CONTACT_PHONE_TITLE}' => $translations['labels']['estate.contact.person.phone'],
        '{CONTACT_MOBILE_TITLE}' => $translations['labels']['estate.contact.person.mobile'],
        '{CONTACT_FAX_TITLE}' => $translations['labels']['estate.contact.person.fax'],
        '{CONTACT_FORM_NAME}' => $translations['labels']['estate.contact.form.name'],
        '{CONTACT_FORM_EMAIL}' => $translations['labels']['estate.contact.form.email'],
        '{CONTACT_FORM_PHONE}' => $translations['labels']['estate.contact.form.phone'],
        '{CONTACT_FORM_MESSAGE}' => $translations['labels']['estate.contact.form.message'],
        '{CONTACT_FORM_SUBMIT}' => $translations['labels']['estate.contact.form.submit'],
      );
      $output = str_replace(array_keys($replacement), array_values($replacement), $output);
      immotool_functions::replace_var('CONTACT_ADRESS_VALUE2', $contactAdressLine2, $output);
      immotool_functions::replace_var('CONTACT_PHONE_VALUE', $object['contact']['person_phone'], $output);
      immotool_functions::replace_var('CONTACT_MOBILE_VALUE', $object['contact']['person_mobile'], $output);
      immotool_functions::replace_var('CONTACT_FAX_VALUE', $object['contact']['person_fax'], $output);
    }
    immotool_functions::replace_var('CONTACT_PERSON', $showContactPerson, $output);

    // Kontaktformular kann nicht dargestellt werden
    $showContactForm = null;
    if (!is_string($object['mail']) || !$setup->ShowContactForm) {
      immotool_functions::replace_var('CONTACT_RESULT', null, $output);
    }

    // Kontaktformular darstellen
    else {
      $showContactForm = $translations['labels']['estate.contact.form'];
      $replacement = array(
        '{CONTACT_FORM_NAME}' => $translations['labels']['estate.contact.form.name'],
        '{CONTACT_FORM_NAME_VALUE}' => '',
        '{CONTACT_FORM_NAME_ERROR}' => '',
        '{CONTACT_FORM_EMAIL}' => $translations['labels']['estate.contact.form.email'],
        '{CONTACT_FORM_EMAIL_VALUE}' => '',
        '{CONTACT_FORM_EMAIL_ERROR}' => '',
        '{CONTACT_FORM_PHONE}' => $translations['labels']['estate.contact.form.phone'],
        '{CONTACT_FORM_PHONE_VALUE}' => '',
        '{CONTACT_FORM_PHONE_ERROR}' => '',
        '{CONTACT_FORM_MESSAGE}' => $translations['labels']['estate.contact.form.message'],
        '{CONTACT_FORM_MESSAGE_VALUE}' => '',
        '{CONTACT_FORM_MESSAGE_ERROR}' => '',
        '{CONTACT_FORM_MESSAGE_ATTRIBS}' => 'class="field"',
        '{CONTACT_FORM_SUBMIT}' => $translations['labels']['estate.contact.form.submit'],
      );

      $showCaptcha = null;
      if ($setup->ShowContactCaptcha) {
        $showCaptcha = $translations['labels']['estate.contact.form.captcha'];
        $replacement['{CONTACT_FORM_CAPTCHA_REFRESH}'] = $translations['labels']['estate.contact.form.captcha.refresh'];
        $replacement['{CONTACT_FORM_CAPTCHA_VALUE}'] = '';
        $replacement['{CONTACT_FORM_CAPTCHA_ERROR}'] = '';
      }
      immotool_functions::replace_var('CONTACT_FORM_CAPTCHA', $showCaptcha, $output);

      // Formular wurde nicht abgeschickt
      if (!is_array($_POST[IMMOTOOL_PARAM_EXPOSE_CONTACT])) {
        immotool_functions::replace_var('CONTACT_RESULT', null, $output);
      }

      // Formular wurde abgeschickt
      else {
        $errors = array();
        $contact = $_POST[IMMOTOOL_PARAM_EXPOSE_CONTACT];
        if (!is_string($contact['name']) || strlen(trim($contact['name'])) == 0)
          $errors[] = 'name';
        if (!is_string($contact['email']) || preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $contact['email']) !== 1)
          $errors[] = 'email';
        if (!is_string($contact['phone']) || strlen(trim($contact['phone'])) == 0)
          $errors[] = 'phone';
        if (!is_string($contact['message']) || strlen(trim($contact['message'])) == 0)
          $errors[] = 'message';
        if ($setup->ShowContactCaptcha) {
          //die( session_name() . '<hr/>' . $_SESSION['captchacode'] . '!=' . $_POST[ IMMOTOOL_PARAM_EXPOSE_CAPTCHA ] );
          if (!is_string($_POST[IMMOTOOL_PARAM_EXPOSE_CAPTCHA]) || strlen($_POST[IMMOTOOL_PARAM_EXPOSE_CAPTCHA]) <= 0)
            $errors[] = 'captcha';
          else if (!is_string($_SESSION['captchacode']) || strlen($_SESSION['captchacode']) <= 0)
            $errors[] = 'captcha';
          else if (trim(strtolower($_SESSION['captchacode'])) != trim(strtolower($_POST[IMMOTOOL_PARAM_EXPOSE_CAPTCHA])))
            $errors[] = 'captcha';
        }

        // Die Eingaben sind unvollständig
        if (count($errors) > 0) {
          foreach ($errors as $field)
            $replacement['{CONTACT_FORM_' . strtoupper($field) . '_ERROR}'] = ' class="error"';

          foreach (array_keys($contact) as $key)
            $replacement['{CONTACT_FORM_' . strtoupper($key) . '_VALUE}'] = htmlentities($contact[$key], ENT_QUOTES, 'UTF-8');

          $replacement['{CONTACT_RESULT_TITLE}'] = $translations['errors']['cantSendMail'];
          immotool_functions::replace_var(
              'CONTACT_RESULT', $translations['errors']['cantSendMail.invalidInput'], $output);
        }

        // Mailversand vorbereiten
        else {

          // Vorlage für Mailtext ermitteln
          $mailBody = null;
          $mailSubject = null;
          $mailTemplates = array('expose_contact_' . $lang . '.txt', 'expose_contact.txt');
          foreach ($mailTemplates as $mailTemplate) {
            if (!is_file(IMMOTOOL_BASE_PATH . 'templates/' . $mailTemplate))
              continue;
            $lines = file(IMMOTOOL_BASE_PATH . 'templates/' . $mailTemplate);
            $mailSubject = $lines[0];
            $mailBody = '';
            for ($i = 1; $i < count($lines); $i++) {
              $mailBody .= rtrim($lines[$i], "\r\n") . PHP_EOL;
            }
          }
          if (is_null($mailBody) || is_null($mailSubject)) {
            immotool_functions::replace_var(
                'CONTACT_RESULT', $translations['errors']['cantSendMail.templateNotFound'], $output);
            $replacement['{CONTACT_RESULT_TITLE}'] = $translations['errors']['cantSendMail'];
            foreach (array_keys($contact) as $key)
              $replacement['{CONTACT_FORM_' . strtoupper($key) . '_VALUE}'] = htmlentities($contact[$key]);
          }
          else {

            // Inhalte in Mailtext übernehmen
            $requestUrl = (strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
            $requestUrl .= $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
            $requestUrl .= '?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'];
            $subjectId = '#' . $object['id'];
            if (is_string($object['nr']) && strlen($object['nr']) > 0)
              $subjectId .= ' / ' . $object['nr'];
            $mailReplacement = array(
              '{SUBJECT_ID}' => $subjectId,
              '{CONTACT_NAME}' => $contact['name'],
              '{CONTACT_EMAIL}' => $contact['email'],
              '{CONTACT_PHONE}' => $contact['phone'],
              '{CONTACT_MESSAGE}' => $contact['message'],
              '{REQUEST_OBJECT_ID}' => $object['id'],
              '{REQUEST_OBJECT_NR}' => $object['nr'],
              '{REQUEST_URL}' => $requestUrl,
              '{REQUEST_TIME}' => date('r'),
              '{REQUEST_IP}' => $_SERVER['REMOTE_ADDR'],
            );

            $mailBody = str_replace(array_keys($mailReplacement), array_values($mailReplacement), $mailBody);
            $mailSubject = str_replace(array_keys($mailReplacement), array_values($mailReplacement), $mailSubject);
            $mailResult = immotool_functions::send_mail($setup, $mailSubject, $mailBody, $object['mail'], $contact['email'], $contact['name']);

            // Mailversand erfolgreich durchgeführt
            if ($mailResult === true) {
              $showContactForm = null;
              $replacement['{CONTACT_RESULT_TITLE}'] = $translations['labels']['estate.contact.form.submitted'];
              immotool_functions::replace_var(
                  'CONTACT_RESULT', $translations['labels']['estate.contact.form.submitted.message'], $output);
            }

            // Fehler beim Mailversand
            else {
              $replacement['{CONTACT_RESULT_TITLE}'] = $translations['errors']['cantSendMail'];
              foreach (array_keys($contact) as $key)
                $replacement['{CONTACT_FORM_' . strtoupper($key) . '_VALUE}'] = htmlentities($contact[$key]);
              immotool_functions::replace_var(
                  'CONTACT_RESULT', $translations['errors']['cantSendMail.mailWasNotSend'] . '<hr/>' . $mailResult, $output);
            }
          }
        }
      }
      $output = str_replace(array_keys($replacement), array_values($replacement), $output);
    }
    immotool_functions::replace_var('CONTACT_FORM', $showContactForm, $output);
    return $output;
  }

  /**
   * Details zur Immobilie darstellen.
   * @param array $object Immobilie
   * @param object $setup Konfiguration
   * @param array $translations Übersetzungen
   * @param string $lang Sprache
   * @return string HTML-Code
   */
  function details(&$object, &$setup, &$translations, $lang) {
    $output = '<h2>' . $translations['labels']['estate.details.title'] . '</h2>';
    foreach ($object['attributes'] as $group => $values) {
      if (count($values) == 0)
        continue;
      $groupName = $translations['openestate']['groups'][$group];
      if (is_null($groupName))
        $groupName = $group;
      $output .= '<h3>' . $groupName . '</h3>';
      $output .= '<ul>';
      foreach ($values as $attrib => $value) {
        $attribValue = $value[$lang];
        if (is_null($attribValue))
          continue;
        $attribName = $translations['openestate']['attributes'][$group][$attrib];
        if (is_null($attribName))
          $attribName = $attrib;
        $output .= '<li>' . $attribName . ': <b>' . $value[$lang] . '</b></li>';
      }
      $output .= '</ul>';
    }
    return $output;
  }

  /**
   * Galerie zur Immobilie darstellen.
   * @param array $object Immobilie
   * @param object $setup Konfiguration
   * @param array $translations Übersetzungen
   * @param string $lang Sprache
   * @param object $galleryHandler
   * @param object $galleryHandlerDefault
   * @return string HTML-Code
   */
  function gallery(&$object, &$setup, &$translations, $lang, &$galleryHandler, &$galleryHandlerDefault) {
    $output = '<h2>' . $translations['labels']['estate.gallery.title'] . '</h2>';
    $output .= immotool_functions::read_template('expose_gallery.html');
    $galleryImageSrc = null;
    $galleryImageText = null;
    $galleryThumbnails = null;

    // gewähltes Bild ermitteln
    $img = (isset($_REQUEST[IMMOTOOL_PARAM_EXPOSE_IMG])) ? $_REQUEST[IMMOTOOL_PARAM_EXPOSE_IMG] : null;
    if (!is_numeric($img) || $img < 1)
      $img = 1;
    else if ($img > count($object['images']))
      $img = count($object['images']);
    $galleryImage = $object['images'][$img - 1];
    if (is_array($galleryImage) && is_string($galleryImage['name'])) {
      $galleryImageSrc = 'data/' . $object['id'] . '/' . $galleryImage['name'];
      $galleryImageText = $galleryImage['title'][$lang];
    }

    // Vorschau-Galerie erzeugen
    $galleryThumbnails = '';

    // JS-Galerie
    if (is_object($galleryHandler) && $galleryHandler->isJavaScriptRequired()) {
      $galleryThumbnails .= '<script type="text/javascript">' . "\n";
      $galleryThumbnails .= '<!--' . "\n";
      $gallerySrc = $galleryHandler->getGallery($object, $img, $lang);
      $galleryThumbnails .= 'document.write(\'' . str_replace("'", "\\'", $gallerySrc) . '\');' . "\n";
      $galleryThumbnails .= '-->' . "\n";
      $galleryThumbnails .= '</script>';
    }

    // HTML-Galerie, <noscript>
    if (is_object($galleryHandler) && $galleryHandler->isJavaScriptRequired()) {
      $galleryThumbnails .= '<noscript>';
    }

    // HTML-Galerie
    $htmlHandler = (is_object($galleryHandler) && !$galleryHandler->isJavaScriptRequired()) ? $galleryHandler : $galleryHandlerDefault;
    $galleryThumbnails .= $htmlHandler->getGallery($object, $img, $lang);

    // HTML-Galerie, </noscript>
    if (is_object($galleryHandler) && $galleryHandler->isJavaScriptRequired()) {
      $galleryThumbnails .= '</noscript>';
    }

    // Galerie einbinden
    immotool_functions::replace_var('GALLERY_THUMBNAILS', $galleryThumbnails, $output);

    // gewähltes Bild unterhalb der Galerie
    if (is_object($galleryHandler) && !$galleryHandler->isSelectedImagePrinted()) {

      // Bild als <noscript> darstellen
      if ($galleryHandler->isJavaScriptRequired()) {
        immotool_functions::replace_var('GALLERY_IMAGE', null, $output);
        immotool_functions::replace_var('NOSCRIPT_GALLERY_IMAGE', $galleryImageSrc, $output);
        immotool_functions::replace_var('GALLERY_IMAGE_TEXT', $galleryImageText, $output);
      }

      // Bild grundsätzlich nicht darstellen
      else {
        immotool_functions::replace_var('GALLERY_IMAGE', null, $output);
        immotool_functions::replace_var('NOSCRIPT_GALLERY_IMAGE', null, $output);
        immotool_functions::replace_var('GALLERY_IMAGE_TEXT', null, $output);
      }
    }
    else {
      immotool_functions::replace_var('GALLERY_IMAGE', $galleryImageSrc, $output);
      immotool_functions::replace_var('GALLERY_IMAGE_TEXT', $galleryImageText, $output);
    }
    return $output;
  }

  /**
   * AGB des Anbieters darstellen.
   * @param object $setup Konfiguration
   * @param array $translations Übersetzungen
   * @param string $lang Sprache
   * @return string HTML-Code
   */
  function terms(&$setup, &$translations, $lang) {
    $terms = immotool_functions::get_terms();
    $output = '<h2>' . $translations['labels']['estate.terms.title'] . '</h2>';
    if (is_string($terms[$lang]))
      $output .= '<p>' . $terms[$lang] . '</p>';
    else
      $output .= '<p><i>' . $translations['labels']['estate.terms.empty'] . '</i></p>';
    return $output;
  }

  /**
   * Texte zur Immobilie darstellen.
   * @param array $objectTexts Texte zur Immobilie
   * @param object $setup Konfiguration
   * @param array $translations Übersetzungen
   * @param string $lang Sprache
   * @return string HTML-Code
   */
  function texts(&$objectTexts, &$setup, &$translations, $lang) {
    $output = '<h2>' . $translations['labels']['estate.texts.title'] . '</h2>';
    foreach ($objectTexts as $attrib => $values) {
      if ($attrib == 'id')
        continue;
      $txt = $values[$lang];
      if (!is_string($txt) || strlen(trim($txt)) == 0)
        continue;
      $attribName = $translations['openestate']['attributes']['freitexte'][$attrib];
      if (is_null($attribName))
        $attribName = $attrib;
      $output .= '<h3>' . $attribName . '</h3>';
      $output .= '<p>' . immotool_functions::replace_links($txt) . '</p>';
    }
    return (strlen($output) > 0) ? $output :
        '<p><i>' . $translations['labels']['estate.texts.empty'] . '</i></p>';
  }

}

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

// Konfiguration ermitteln
$setup = new immotool_setup_expose();
if (is_callable(array('immotool_myconfig', 'load_config_expose')))
  immotool_myconfig::load_config_expose($setup);
immotool_functions::init($setup);

// Übersetzungen ermitteln
$translations = null;
$lang = (isset($_REQUEST[IMMOTOOL_PARAM_LANG])) ? $_REQUEST[IMMOTOOL_PARAM_LANG] : null;
$lang = immotool_functions::init_language($lang, $setup->DefaultLanguage, $translations);
if (!is_array($translations))
  die('Can\'t load translations!');

// ID ermitteln
$id = (isset($_REQUEST[IMMOTOOL_PARAM_EXPOSE_ID])) ? $_REQUEST[IMMOTOOL_PARAM_EXPOSE_ID] : null;
if (preg_match('/^\w*/i', $id) !== 1) {
  die($translations['errors']['cantLoadEstate']);
}
$object = immotool_functions::get_object($id);
$objectTexts = immotool_functions::get_text($id);
if (!is_array($object)) {
  die($translations['errors']['cantLoadEstate']);
}

// Parameter der Seite
$idValue = (is_string($object['nr']) && strlen($object['nr']) > 0) ? $object['nr'] : '#' . $object['id'];
$mainTitle = $translations['labels']['title'];
$pageTitle = strip_tags($translations['labels']['estate'] . ' ' . $idValue);
$robots = 'index,follow';

// Galerie-Handler ermitteln
$galleryHandlerDefault = immotool_functions::get_gallery('html');
$galleryHandler = immotool_functions::get_gallery($setup->GalleryHandler);
if (!is_object($galleryHandler))
  $galleryHandler = $galleryHandlerDefault;

// Inhalt der Seite erzeugen
$expose = immotool_functions::read_template('expose.html');

// Hauptmenü
$exposeMenu = '<ul>';
$exposeMenu .= '<li class="selected"><a href="?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'] . '">' . $pageTitle . '</a></li>';
$favTitle = immotool_functions::has_favourite($object['id']) ? $translations['labels']['link.expose.unfav'] : $translations['labels']['link.expose.fav'];
$exposeMenu .= '<li><a href="?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'] . '&amp;' . IMMOTOOL_PARAM_FAV . '=' . $object['id'] . '" rel="nofollow">' . $favTitle . '</a></li>';
$pdf = 'data/' . $object['id'] . '/' . $object['id'] . '_' . $lang . '.pdf';
if (is_file(IMMOTOOL_BASE_PATH . $pdf))
  $exposeMenu .= '<li><a href="' . $pdf . '" target="_blank">' . $translations['labels']['link.expose.pdf'] . '</a></li>';
$exposeMenu .= '<li style="float:right;"><a href="index.php?' . IMMOTOOL_PARAM_INDEX_VIEW . '=fav" rel="nofollow">' . $translations['labels']['title.fav'] . '</a></li>';
$exposeMenu .= '<li style="float:right;"><a href="index.php">' . $translations['labels']['title.index'] . '</a></li>';
$exposeMenu .= '</ul>';

// Titelbild
$titleImg = null;
if (isset($object['images']) && is_array($object['images']) && count($object['images']) > 0) {
  $keys = array_keys($object['images']);
  $titleImg = $galleryHandler->getTitleImage($object['id'], $object['images'][$keys[0]], $lang);
}
immotool_functions::replace_var('IMAGE', $titleImg, $expose);

// Anschrift
$adressLine1 = '';
$adressLine2 = '';
if (is_string($object['adress']['street'])) {
  $adressLine1 .= $object['adress']['street'];
  if (is_string($object['adress']['street_nr']))
    $adressLine1 .= ' ' . $object['adress']['street_nr'];
}
if (is_string($object['adress']['postal']))
  $adressLine2 .= $object['adress']['postal'] . ' ';
if (is_string($object['adress']['city']))
  $adressLine2 .= $object['adress']['city'] . ' ';
if (is_string($object['adress']['city_part']))
  $adressLine2 .= '(' . $object['adress']['city_part'] . ') ';
if (strlen($adressLine2) > 0 && strlen($adressLine1) == 0) {
  $adressLine1 = $adressLine2;
  $adressLine2 = null;
}

// Region
$adressRegion = '';
if (is_string($object['adress']['country_name'][$lang])) {
  $adressRegion .= $object['adress']['country_name'][$lang];
  if (is_string($object['adress']['region']) && strlen($object['adress']['region']) > 0)
    $adressRegion .= ' / ' . $object['adress']['region'];
}

// Ansichten erzeugen
$view = (isset($_REQUEST[IMMOTOOL_PARAM_EXPOSE_VIEW])) ? $_REQUEST[IMMOTOOL_PARAM_EXPOSE_VIEW] : null;
$viewContent = '';
$viewMenu = null;
$viewMode = $setup->ViewMode;
if (!is_string($viewMode))
  $viewMode = 'tabular';
$viewOrder = $setup->ViewOrder;
if (!is_array($viewOrder))
  $viewOrder = array('details', 'texts', 'gallery', 'contact', 'terms');

// Darstellung als Reiter
if ($viewMode == 'tabular') {

  $viewClass = array();

  // aktueller Reiter: Texte
  if ($view == 'texts' && is_array($objectTexts) && count($objectTexts) > 1) {
    $viewContent .= immotool_expose::texts($objectTexts, $setup, $translations, $lang);
    $viewClass[$view] = 'class="selected"';
  }

  // aktueller Reiter: Galerie
  else if ($view == 'gallery' && is_array($object['images']) && count($object['images']) > 0) {
    $viewContent .= immotool_expose::gallery($object, $setup, $translations, $lang, $galleryHandler, $galleryHandlerDefault);
    $viewClass[$view] = 'class="selected"';
  }

  // aktueller Reiter: Kontaktformular
  else if ($view == 'contact') {
    $viewContent .= immotool_expose::contact($object, $setup, $translations, $lang);
    $viewClass[$view] = 'class="selected"';
  }

  // aktueller Reiter: AGB
  else if ($view == 'terms' && $setup->ShowTerms) {
    $viewContent .= immotool_expose::terms($setup, $translations, $lang);
    $viewClass[$view] = 'class="selected"';
  }

  // aktueller Reiter: Details
  else {
    $view = 'details';
    $viewContent .= immotool_expose::details($object, $setup, $translations, $lang);
    $viewClass[$view] = 'class="selected"';
  }

  // Reitermenü erzeugen
  $viewMenu = '<ul>';
  foreach ($viewOrder as $v) {

    if ($v == 'texts') {
      if (!is_array($objectTexts))
        continue;
      if (count($objectTexts) <= 1)
        continue;
    }
    if ($v == 'gallery') {
      if (!is_array($object['images']))
        continue;
      if (count($object['images']) <= 0)
        continue;
    }
    if ($v == 'contact') {
      if (!is_array($object['contact']))
        continue;
      if (count($object['contact']) <= 0)
        continue;
      if (!$setup->ShowContactForm)
        continue;
      if (!is_string($object['mail']))
        continue;
    }
    if ($v == 'terms') {
      if (!$setup->ShowTerms)
        continue;
    }

    $c = (isset($viewClass[$v]) && is_string($viewClass[$v])) ? $viewClass[$v] : '';
    $viewMenu .= '<li ' . $c . '>' .
        '<a href="?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'] . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=' . $v . '">' .
        $translations['labels']['estate.' . $v] . '</a></li>';
  }
  $viewMenu .= '</ul>';
}

// Darstellung als Auflistung
else {
  foreach ($viewOrder as $v) {

    // Abschnitt: Texte
    if ($v == 'texts' && is_array($objectTexts) && count($objectTexts) > 1) {
      $viewContent .= immotool_expose::texts($objectTexts, $setup, $translations, $lang);
    }

    // Abschnitt: Galerie
    else if ($v == 'gallery' && is_array($object['images']) && count($object['images']) > 0) {
      $viewContent .= immotool_expose::gallery($object, $setup, $translations, $lang, $galleryHandler, $galleryHandlerDefault);
    }

    // Abschnitt: Kontaktformular
    else if ($v == 'contact') {
      $viewContent .= immotool_expose::contact($object, $setup, $translations, $lang);
    }

    // Abschnitt: AGB
    else if ($v == 'terms' && $setup->ShowTerms) {
      $viewContent .= immotool_expose::terms($setup, $translations, $lang);
    }

    // Abschnitt: Details
    else if ($v == 'details') {
      $viewContent .= immotool_expose::details($object, $setup, $translations, $lang);
    }
  }
}

$replacement = array(
  '{VIEW_CONTENT}' => $viewContent,
  '{EXPOSE_MENU}' => $exposeMenu,
  '{TITLE}' => $object['title'][$lang],
  '{ID}' => $object['id'],
  '{ID_TITLE}' => (is_string($object['nr'])) ? $translations['labels']['estate.nr'] : $translations['labels']['estate.id'],
  '{ID_VALUE}' => $idValue,
  '{ACTION_TITLE}' => $translations['labels']['estate.action'],
  '{ACTION_VALUE}' => $translations['openestate']['actions'][$object['action']],
  '{TYPE_TITLE}' => $translations['labels']['estate.type'],
  '{TYPE_VALUE}' => $translations['openestate']['types'][$object['type']],
  '{ADRESS_TITLE}' => $translations['labels']['estate.adress'],
  '{ADRESS_VALUE}' => trim($adressLine1),
  '{REGION_TITLE}' => $translations['labels']['estate.region'],
);

$pageContent = str_replace(array_keys($replacement), array_values($replacement), $expose);
immotool_functions::replace_var('VIEW_MENU', $viewMenu, $pageContent);
immotool_functions::replace_var('REGION_VALUE', $adressRegion, $pageContent);
immotool_functions::replace_var('ADRESS2_VALUE', $adressLine2, $pageContent);

$pageHeader = '';
if (is_object($galleryHandler))
  $pageHeader .= "\n" . $galleryHandler->getHeader();

// Ausgabe erzeugen
$buildTime = microtime() - $startup;
$output = immotool_functions::build_page('expose', $lang, $mainTitle, $pageTitle, trim($pageHeader), $pageContent, $buildTime, $setup->AdditionalStylesheet, $setup->ShowLanguageSelection, $robots, '&amp;' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $id . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=' . $view);
if (is_string($setup->Charset) && strlen(trim($setup->Charset)) > 0) {
  $output = immotool_functions::encode($output, $setup->Charset);
}
if (is_string($setup->ContentType) && strlen(trim($setup->ContentType)) > 0) {
  header('Content-Type: ' . $setup->ContentType);
}
echo $output;
