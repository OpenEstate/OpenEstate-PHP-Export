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
 * Website-Export, Darstellung der Exposé-Ansicht
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
$setup = new immotool_setup_expose();
if (is_callable(array('immotool_myconfig', 'load_config_expose')))
  immotool_myconfig::load_config_expose($setup);
immotool_functions::init($setup);

// Übersetzungen ermitteln
$translations = null;
$lang = immotool_functions::init_language($_REQUEST[IMMOTOOL_PARAM_LANG], $setup->DefaultLanguage, $translations);
if (!is_array($translations))
  die('Can\'t load translations!');

// ID ermitteln
$id = $_REQUEST[IMMOTOOL_PARAM_EXPOSE_ID];
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
$imgFile = 'data/' . $object['id'] . '/title.jpg';
immotool_functions::replace_var('IMAGE', is_file(IMMOTOOL_BASE_PATH . $imgFile) ? $imgFile : null, $expose);

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
$view = $_REQUEST[IMMOTOOL_PARAM_EXPOSE_VIEW];
$viewContent = '';
$viewTitle = '';
$viewClass = array();

// Ansicht, Texte
if ($view == 'texts' && is_array($objectTexts) && count($objectTexts) > 1) {
  $viewTitle = $translations['labels']['estate.texts.title'];
  $viewClass[$view] = 'class="selected"';
  $txtCount = 0;
  foreach ($objectTexts as $attrib => $values) {
    if ($attrib == 'id')
      continue;
    $txt = $values[$lang];
    if (!is_string($txt) || strlen(trim($txt)) == 0)
      continue;
    $attribName = $translations['openestate']['attributes']['freitexte'][$attrib];
    if (is_null($attribName))
      $attribName = $attrib;
    $viewContent .= '<h3>' . $attribName . '</h3>';
    $viewContent .= '<p>' . immotool_functions::replace_links($txt) . '</p>';
    $txtCount++;
  }
  if ($txtCount == 0)
    $viewContent = '<p><i>' . $translations['labels']['estate.texts.empty'] . '</i></p>';
}

// Ansicht, Galerie
else if ($view == 'gallery' && is_array($object['images']) && count($object['images']) > 0) {
  $viewTitle = $translations['labels']['estate.gallery.title'];
  $viewClass[$view] = 'class="selected"';
  $viewContent = immotool_functions::read_template('expose_gallery.html');
  $galleryImageSrc = null;
  $galleryImageText = null;
  $galleryThumbnails = null;

  // gewähltes Bild ermitteln
  $img = $_REQUEST[IMMOTOOL_PARAM_EXPOSE_IMG];
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
  $galleryThumbnails = '<ul>';
  foreach ($object['images'] as $pos => $image) {
    if (!is_string($image['thumb']))
      continue;
    $class = (($pos + 1) == $img) ? 'class="selected"' : '';
    $galleryThumbnails .= '<li ' . $class . '><a href="?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'] . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=gallery&amp;' . IMMOTOOL_PARAM_EXPOSE_IMG . '=' . ($pos + 1) . '#img"><img src="data/' . $object['id'] . '/' . $image['thumb'] . '" alt="" border="0"/></a></li>';
  }
  $galleryThumbnails .= '</ul>';

  immotool_functions::replace_var('GALLERY_IMAGE', $galleryImageSrc, $viewContent);
  immotool_functions::replace_var('GALLERY_IMAGE_TEXT', $galleryImageText, $viewContent);
  immotool_functions::replace_var('GALLERY_THUMBNAILS', $galleryThumbnails, $viewContent);
}

// Ansicht, Kontaktformular
else if ($view == 'contact') {
  $viewTitle = $translations['labels']['estate.contact.title'];
  $viewClass[$view] = 'class="selected"';
  $viewContent = immotool_functions::read_template('expose_contact.html');

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
    $viewContent = str_replace(array_keys($replacement), array_values($replacement), $viewContent);
    immotool_functions::replace_var('CONTACT_ADRESS_VALUE2', $contactAdressLine2, $viewContent);
    immotool_functions::replace_var('CONTACT_PHONE_VALUE', $object['contact']['person_phone'], $viewContent);
    immotool_functions::replace_var('CONTACT_MOBILE_VALUE', $object['contact']['person_mobile'], $viewContent);
    immotool_functions::replace_var('CONTACT_FAX_VALUE', $object['contact']['person_fax'], $viewContent);
  }
  immotool_functions::replace_var('CONTACT_PERSON', $showContactPerson, $viewContent);

  // Kontaktformular kann nicht dargestellt werden
  $showContactForm = null;
  if (!is_string($object['mail']) || !$setup->ShowContactForm) {
    immotool_functions::replace_var('CONTACT_RESULT', null, $viewContent);
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
    immotool_functions::replace_var('CONTACT_FORM_CAPTCHA', $showCaptcha, $viewContent);

    // Formular wurde nicht abgeschickt
    if (!is_array($_POST[IMMOTOOL_PARAM_EXPOSE_CONTACT])) {
      immotool_functions::replace_var('CONTACT_RESULT', null, $viewContent);
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
            'CONTACT_RESULT', $translations['errors']['cantSendMail.invalidInput'], $viewContent);
      }

      // Mailversand vorbereiten
      else {
        $mailer = immotool_functions::get_mailer($setup);
        $mailer->AddAddress(immotool_functions::get_mail_adress($object['mail']));
        $mailer->AddReplyTo(immotool_functions::get_mail_adress($contact['email']), $contact['name']);

        // Vorlage für Mailtext ermitteln
        $mailTemplates = array('expose_contact_' . $lang . '.txt', 'expose_contact.txt');
        $found = false;
        foreach ($mailTemplates as $mailTemplate) {
          if (!is_file(IMMOTOOL_BASE_PATH . 'templates/' . $mailTemplate))
            continue;
          $found = true;
          $lines = file(IMMOTOOL_BASE_PATH . 'templates/' . $mailTemplate);
          $mailer->Subject = $lines[0];
          $mailer->Body = '';
          for ($i = 1; $i < count($lines); $i++) {
            $mailer->Body .= rtrim($lines[$i], "\r\n") . PHP_EOL;
          }
        }
        if (!$found) {
          immotool_functions::replace_var(
              'CONTACT_RESULT', $translations['errors']['cantSendMail.templateNotFound'], $viewContent);
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
          $mailer->Body = str_replace(array_keys($mailReplacement), array_values($mailReplacement), $mailer->Body);
          $mailer->Subject = str_replace(array_keys($mailReplacement), array_values($mailReplacement), $mailer->Subject);
        }

        // Fehler beim Mailversand
        if (!$mailer->Send()) {
          $replacement['{CONTACT_RESULT_TITLE}'] = $translations['errors']['cantSendMail'];
          foreach (array_keys($contact) as $key)
            $replacement['{CONTACT_FORM_' . strtoupper($key) . '_VALUE}'] = htmlentities($contact[$key]);
          immotool_functions::replace_var(
              'CONTACT_RESULT', $translations['errors']['cantSendMail.mailWasNotSend'] . '<hr/>' . $mailer->ErrorInfo, $viewContent);
        }

        // Mailversand erfolgreich durchgeführt
        else {
          $showContactForm = null;
          $replacement['{CONTACT_RESULT_TITLE}'] = $translations['labels']['estate.contact.form.submitted'];
          immotool_functions::replace_var(
              'CONTACT_RESULT', $translations['labels']['estate.contact.form.submitted.message'], $viewContent);
        }
      }
    }

    $viewContent = str_replace(array_keys($replacement), array_values($replacement), $viewContent);
  }
  immotool_functions::replace_var('CONTACT_FORM', $showContactForm, $viewContent);
}

// Ansicht, AGB
else if ($view == 'terms' && $setup->ShowTerms) {
  $viewTitle = $translations['labels']['estate.terms.title'];
  $viewClass[$view] = 'class="selected"';
  $terms = immotool_functions::get_terms();
  if (is_string($terms[$lang]))
    $viewContent = '<p>' . $terms[$lang] . '</p>';
  else
    $viewContent = '<p><i>' . $translations['labels']['estate.terms.empty'] . '</i></p>';
}

// Ansicht, Attribute
else {
  $view = 'details';
  $viewTitle = $translations['labels']['estate.details.title'];
  $viewClass[$view] = 'class="selected"';
  foreach ($object['attributes'] as $group => $values) {
    if (count($values) == 0)
      continue;
    $groupName = $translations['openestate']['groups'][$group];
    if (is_null($groupName))
      $groupName = $group;
    $viewContent .= '<h3>' . $groupName . '</h3>';
    $viewContent .= '<ul>';
    foreach ($values as $attrib => $value) {
      $attribValue = $value[$lang];
      if (is_null($attribValue))
        continue;
      $attribName = $translations['openestate']['attributes'][$group][$attrib];
      if (is_null($attribName))
        $attribName = $attrib;
      $viewContent .= '<li>' . $attribName . ': <b>' . $value[$lang] . '</b></li>';
    }
    $viewContent .= '</ul>';
  }
}

// Ansichtsmenü
$viewMenu = '<ul>';
$viewMenu .= '<li ' . $viewClass['details'] . '><a href="?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'] . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=details">' . $translations['labels']['estate.details'] . '</a></li>';
if (is_array($objectTexts) && count($objectTexts) > 1)
  $viewMenu .= '<li ' . $viewClass['texts'] . '><a href="?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'] . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=texts">' . $translations['labels']['estate.texts'] . '</a></li>';
if (is_array($object['images']) && count($object['images']) > 0)
  $viewMenu .= '<li ' . $viewClass['gallery'] . '><a href="?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'] . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=gallery">' . $translations['labels']['estate.gallery'] . '</a></li>';
if ((is_array($object['contact']) && count($object['contact']) > 0) || ($setup->ShowContactForm && is_string($object['mail'])))
  $viewMenu .= '<li ' . $viewClass['contact'] . '><a href="?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'] . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=contact">' . $translations['labels']['estate.contact'] . '</a></li>';
if ($setup->ShowTerms)
  $viewMenu .= '<li ' . $viewClass['terms'] . '><a href="?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'] . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=terms">' . $translations['labels']['estate.terms'] . '</a></li>';
$viewMenu .= '</ul>';

$replacement = array(
  '{VIEW_MENU}' => $viewMenu,
  '{VIEW_CONTENT}' => $viewContent,
  '{VIEW_TITLE}' => $viewTitle,
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
immotool_functions::replace_var('REGION_VALUE', $adressRegion, $pageContent);
immotool_functions::replace_var('ADRESS2_VALUE', $adressLine2, $pageContent);

// Ausgabe erzeugen
$buildTime = microtime() - $startup;
header("Content-Type: text/html; charset=utf-8");
echo immotool_functions::build_page('expose', $lang, $mainTitle, $pageTitle, $pageContent, $buildTime, $setup->AdditionalStylesheet, $setup->ShowLanguageSelection, $robots, '&amp;' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $id . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=' . $view);
