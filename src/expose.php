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
 * Website-Export, Darstellung der Exposé-Ansicht.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Hilfsfunktionen des ImmoTool-Exposés.
 */
if (!class_exists('immotool_expose')) {

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
      $output .= immotool_functions::read_template('expose_contact.html', $setup->TemplateFolder);

      // Ansprechpartner darstellen
      $showContactPerson = null;
      if ($setup->ShowContactPerson === true && is_array($object['contact']) && count($object['contact']) > 0) {
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
          '{CONTACT_ADDRESS_TITLE}' => $translations['labels']['estate.contact.person.address'],
          '{CONTACT_ADDRESS_VALUE}' => $contactAdressLine1,
          '{CONTACT_PHONE_TITLE}' => $translations['labels']['estate.contact.person.phone'],
          '{CONTACT_MOBILE_TITLE}' => $translations['labels']['estate.contact.person.mobile'],
          '{CONTACT_FAX_TITLE}' => $translations['labels']['estate.contact.person.fax'],
        );
        $output = str_replace(array_keys($replacement), array_values($replacement), $output);
        immotool_functions::replace_var('CONTACT_ADDRESS_VALUE2', $contactAdressLine2, $output);
        immotool_functions::replace_var('CONTACT_PHONE_VALUE', $object['contact']['person_phone'], $output);
        immotool_functions::replace_var('CONTACT_MOBILE_VALUE', $object['contact']['person_mobile'], $output);
        immotool_functions::replace_var('CONTACT_FAX_VALUE', $object['contact']['person_fax'], $output);
      }
      immotool_functions::replace_var('CONTACT_PERSON', $showContactPerson, $output);

      // Kontaktformular kann nicht dargestellt werden
      $mailResult = false;
      $showContactForm = null;
      if (!is_string($object['mail']) || $setup->ShowContactForm !== true) {
        immotool_functions::replace_var('CONTACT_RESULT', null, $output);
      }

      // Kontaktformular darstellen
      else {
        $showContactForm = $translations['labels']['estate.contact.form.submit'];
        $replacement = array(
          '{CONTACT_FORM_NAME}' => $translations['labels']['estate.contact.form.name'] . ':',
          '{CONTACT_FORM_NAME_VALUE}' => '',
          '{CONTACT_FORM_NAME_ERROR}' => '',
          '{CONTACT_FORM_FIRSTNAME}' => $translations['labels']['estate.contact.form.firstname'] . ':',
          '{CONTACT_FORM_FIRSTNAME_VALUE}' => '',
          '{CONTACT_FORM_FIRSTNAME_ERROR}' => '',
          '{CONTACT_FORM_EMAIL}' => $translations['labels']['estate.contact.form.email'] . ':',
          '{CONTACT_FORM_EMAIL_VALUE}' => '',
          '{CONTACT_FORM_EMAIL_ERROR}' => '',
          '{CONTACT_FORM_PHONE}' => $translations['labels']['estate.contact.form.phone'] . ':',
          '{CONTACT_FORM_PHONE_VALUE}' => '',
          '{CONTACT_FORM_PHONE_ERROR}' => '',
          '{CONTACT_FORM_STREET}' => $translations['labels']['estate.contact.form.street'] . ':',
          '{CONTACT_FORM_STREET_VALUE}' => '',
          '{CONTACT_FORM_STREET_ERROR}' => '',
          '{CONTACT_FORM_STREETNR}' => $translations['labels']['estate.contact.form.streetnr'] . ':',
          '{CONTACT_FORM_STREETNR_VALUE}' => '',
          '{CONTACT_FORM_STREETNR_ERROR}' => '',
          '{CONTACT_FORM_POSTAL}' => $translations['labels']['estate.contact.form.postal'] . ':',
          '{CONTACT_FORM_POSTAL_VALUE}' => '',
          '{CONTACT_FORM_POSTAL_ERROR}' => '',
          '{CONTACT_FORM_CITY}' => $translations['labels']['estate.contact.form.city'] . ':',
          '{CONTACT_FORM_CITY_VALUE}' => '',
          '{CONTACT_FORM_CITY_ERROR}' => '',
          '{CONTACT_FORM_MESSAGE}' => $translations['labels']['estate.contact.form.message'] . ':',
          '{CONTACT_FORM_MESSAGE_VALUE}' => '',
          '{CONTACT_FORM_MESSAGE_ERROR}' => '',
          '{CONTACT_FORM_MESSAGE_ATTRIBS}' => 'class="field"',
        );

        // Pflichtfelder markieren
        $requiredFields = $setup->ContactRequiredFields;
        if (!is_array($requiredFields))
          $requiredFields = array();
        foreach ($requiredFields as $field) {
          $key = '{CONTACT_FORM_' . strtoupper($field) . '}';
          if (!isset($replacement[$key]))
            continue;
          //$replacement[$key] .= '&nbsp;<img src="img/required.png" alt="" title="" border="0" />';
          $replacement[$key] = '<img src="img/required.png" alt="" title="" border="0" />&nbsp;' . $replacement[$key];
        }

        // Captcha darstellen
        $showCaptcha = null;
        if ($setup->ShowContactCaptcha) {
          $showCaptcha = $translations['labels']['estate.contact.form.captcha'] . ':';
          $replacement['{CONTACT_FORM_CAPTCHA_REFRESH}'] = $translations['labels']['estate.contact.form.captcha.refresh'];
          $replacement['{CONTACT_FORM_CAPTCHA_VALUE}'] = '';
          $replacement['{CONTACT_FORM_CAPTCHA_ERROR}'] = '';
        }
        immotool_functions::replace_var('CONTACT_FORM_CAPTCHA', $showCaptcha, $output);

        // Rechtserklärung darstellen
        $showTerms = null;
        if ($setup->ShowContactTerms) {
          $showTerms = $translations['labels']['estate.contact.form.terms'];
          $replacement['{CONTACT_FORM_TERMS_ERROR}'] = '';
          $replacement['{CONTACT_FORM_TERMS_ATTRIBS}'] = '';

          // Text der Rechtserklärung ermitteln
          $terms = '';
          $termTemplates = array('expose_contact_terms_' . $lang . '.txt', 'expose_contact_terms.txt');
          foreach ($termTemplates as $termTemplate) {
            $terms = immotool_functions::read_template($termTemplate, $setup->TemplateFolder);
            if ($terms !== false && is_string($terms) && strlen($terms) > 0)
              break;
          }
          $replacement['{CONTACT_FORM_TERMS_TEXT}'] = ($terms !== false && is_string($terms)) ? trim($terms) : '';
        }
        immotool_functions::replace_var('CONTACT_FORM_TERMS', $showTerms, $output);

        // Formular wurde nicht abgeschickt
        if (!isset($_POST[IMMOTOOL_PARAM_EXPOSE_CONTACT]) || !is_array($_POST[IMMOTOOL_PARAM_EXPOSE_CONTACT])) {
          immotool_functions::replace_var('CONTACT_RESULT', null, $output);
        }

        // Formular wurde abgeschickt
        else {

          $errors = array();
          $contact = $_POST[IMMOTOOL_PARAM_EXPOSE_CONTACT];

          if (array_search('name', $requiredFields) !== false) {
            if (!is_string($contact['name']) || strlen(trim($contact['name'])) == 0)
              $errors[] = 'name';
          }
          if (array_search('firstname', $requiredFields) !== false) {
            if (!is_string($contact['firstname']) || strlen(trim($contact['firstname'])) == 0)
              $errors[] = 'firstname';
          }
          if (array_search('street', $requiredFields) !== false) {
            if (!is_string($contact['street']) || strlen(trim($contact['street'])) == 0)
              $errors[] = 'street';
          }
          if (array_search('streetnr', $requiredFields) !== false) {
            if (!is_string($contact['streetnr']) || strlen(trim($contact['streetnr'])) == 0)
              $errors[] = 'streetnr';
          }
          if (array_search('city', $requiredFields) !== false) {
            if (!is_string($contact['city']) || strlen(trim($contact['city'])) == 0)
              $errors[] = 'city';
          }
          if (array_search('postal', $requiredFields) !== false) {
            if (!is_string($contact['postal']) || strlen(trim($contact['postal'])) == 0)
              $errors[] = 'postal';
          }
          if (array_search('email', $requiredFields) !== false) {
            if (!is_string($contact['email']) || immotool_functions::is_valid_mail_address($contact['email']) !== true)
              $errors[] = 'email';
          }
          if (array_search('phone', $requiredFields) !== false) {
            if (!is_string($contact['phone']) || strlen(trim($contact['phone'])) == 0)
              $errors[] = 'phone';
          }
          if (array_search('message', $requiredFields) !== false) {
            if (!is_string($contact['message']) || strlen(trim($contact['message'])) == 0)
              $errors[] = 'message';
          }
          if ($setup->ShowContactCaptcha) {
            $captchaCode = immotool_functions::get_session_value('captchaCode', null);
            if (!is_string($_POST[IMMOTOOL_PARAM_EXPOSE_CAPTCHA]) || strlen($_POST[IMMOTOOL_PARAM_EXPOSE_CAPTCHA]) <= 0)
              $errors[] = 'captcha';
            else if (is_null($captchaCode))
              $errors[] = 'captcha';
            else if (trim(strtolower($captchaCode)) != trim(strtolower($_POST[IMMOTOOL_PARAM_EXPOSE_CAPTCHA])))
              $errors[] = 'captcha';
            else
              immotool_functions::put_session_value('captchaCode', null);
          }
          if ($setup->ShowContactTerms) {
            if (!isset($contact['terms']) || $contact['terms'] != '1')
              $errors[] = 'terms';
            else
              $replacement['{CONTACT_FORM_TERMS_ATTRIBS}'] = 'checked="checked"';
          }

          // Spambot-Prüfung
          $validRequest = true;
          $contactTimeStamp = immotool_functions::get_session_value('contactTimeStamp', null);
          if (!is_numeric($contactTimeStamp)) {
            $validRequest = false;
          }
          else {
            $diff = time() - $contactTimeStamp;
            $validRequest = $diff > 5 && $diff < 3600;
            //echo '<p>' . $diff . '</p>';
          }

          // Die Eingaben sind unvollständig
          if (count($errors) > 0 || !$validRequest) {
            foreach ($errors as $field)
              $replacement['{CONTACT_FORM_' . strtoupper($field) . '_ERROR}'] = ' error';

            foreach (array_keys($contact) as $key)
              $replacement['{CONTACT_FORM_' . strtoupper($key) . '_VALUE}'] = htmlentities($contact[$key], ENT_QUOTES, 'UTF-8');

            $replacement['{CONTACT_RESULT_TITLE}'] = $translations['errors']['cantSendMail'];
            $errorMsg = ($validRequest) ?
                $translations['errors']['cantSendMail.invalidInput'] :
                $translations['errors']['cantSendMail.invalidRequest'];
            immotool_functions::replace_var('CONTACT_RESULT', $errorMsg, $output);
          }

          // Mailversand vorbereiten
          else {

            // Vorlage für Mailtext ermitteln
            $mailBody = null;
            $mailSubject = null;
            $mailTemplates = array('expose_contact_mail_' . $lang . '.txt', 'expose_contact_mail.txt');
            foreach ($mailTemplates as $mailTemplate) {
              $mail = immotool_functions::read_template($mailTemplate, $setup->TemplateFolder);
              if ($mail === false || !is_string($mail) || strlen($mail) < 1)
                continue;
              $m = explode("\n", $mail, 2);
              if (count($m) < 2)
                continue;
              $mailSubject = trim($m[0]);
              $mailBody = trim($m[1]);
              break;
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
              $requestUrl = immotool_functions::get_expose_url($object['id'], $lang, $setup->ExposeUrlTemplate, false);
              $subjectId = '#' . $object['id'];
              if (is_string($object['nr']) && strlen($object['nr']) > 0)
                $subjectId .= ' / ' . $object['nr'];
              $mailReplacement = array(
                '{SUBJECT_ID}' => $subjectId,
                '{REQUEST_OBJECT_ID}' => $object['id'],
                '{REQUEST_OBJECT_NR}' => $object['nr'],
                '{REQUEST_OBJECT_TITLE}' => (isset($object['title'][$lang])) ? $object['title'][$lang] : '',
                '{REQUEST_URL}' => $requestUrl,
                '{REQUEST_TIME}' => date('r'),
                '{REQUEST_IP}' => $_SERVER['REMOTE_ADDR'],
              );
              foreach (array_keys($contact) as $key) {
                $mailReplacement['{CONTACT_' . strtoupper($key) . '}'] = $contact[$key];
              }

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
      immotool_functions::replace_var('CONTACT_FORM_SUBMIT', $showContactForm, $output);
      immotool_functions::replace_var('CONTACT_FORM_TITLE', ($showContactForm != null || $mailResult === true) ?
              $translations['labels']['estate.contact.form'] : null, $output);
      if ($showContactForm !== null)
        immotool_functions::put_session_value('contactTimeStamp', time());
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
      $groups = (isset($setup->DetailsOrder) && is_array($setup->DetailsOrder)) ? $setup->DetailsOrder : array_keys($object['attributes']);
      $hiddenAttribs = (isset($setup->HiddenAttributes) && is_array($setup->HiddenAttributes)) ? $setup->HiddenAttributes : array();
      $preferredAttribs = (isset($setup->PreferredAttributes) && is_array($setup->PreferredAttributes)) ? $setup->PreferredAttributes : array();
      foreach ($groups as $group) {
        $values = (isset($object['attributes'][$group])) ? $object['attributes'][$group] : null;
        if (!is_array($values) || count($values) < 1) {
          continue;
        }

        // Namen der darstellbaren Attribute ermitteln
        //$attribs = array_keys($values);
        $attribs = array();
        foreach ($preferredAttribs as $attribKey) {
          //if (array_search($attribKey, $hiddenAttribs)!==false) continue;
          $attrib = explode('.', strtolower(trim($attribKey)));
          if (count($attrib) != 2)
            continue;
          if ($attrib[0] != $group)
            continue;
          if (!isset($values[$attrib[1]]))
            continue;
          $attribs[] = $attrib[1];
        }
        foreach (array_keys($values) as $attrib) {
          $attribKey = strtolower(trim($group) . '.' . trim($attrib));
          if (array_search($attribKey, $hiddenAttribs) !== false)
            continue;
          if (array_search($attrib, $attribs) !== false)
            continue;
          $attribs[] = $attrib;
        }

        $groupName = $translations['openestate']['groups'][$group];
        if (is_null($groupName)) {
          $groupName = $group;
        }
        $groupNameWritten = false;
        foreach ($attribs as $attrib) {
          $attribKey = strtolower(trim($group) . '.' . trim($attrib));
          if (array_search($attribKey, $hiddenAttribs) !== false) {
            continue;
          }
          $attribValue = immotool_functions::write_attribute_value($group, $attrib, $values[$attrib], $translations, $lang);
          if (!is_string($attribValue) || strlen(trim($attribValue)) < 1) {
            continue;
          }
          if (!$groupNameWritten) {
            $groupNameWritten = true;
            $output .= '<h3>' . $groupName . '</h3>';
            $output .= '<ul>';
          }
          $attribName = $translations['openestate']['attributes'][$group][$attrib];
          if (is_null($attribName)) {
            $attribName = $attrib;
          }
          $output .= '<li>' . $attribName . ': <b>' . $attribValue . '</b></li>';
        }
        if ($groupNameWritten)
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
      $output .= immotool_functions::read_template('expose_gallery.html', $setup->TemplateFolder);
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
          immotool_functions::replace_var('GALLERY_IMAGE_TEXT', $galleryImageText, $output);
          immotool_functions::replace_var('NOSCRIPT_GALLERY_IMAGE', $galleryImageSrc, $output);
        }

        // Bild grundsätzlich nicht darstellen
        else {
          immotool_functions::replace_var('GALLERY_IMAGE', null, $output);
          immotool_functions::replace_var('GALLERY_IMAGE_TEXT', null, $output);
          immotool_functions::replace_var('NOSCRIPT_GALLERY_IMAGE', null, $output);
        }
      }
      else {
        immotool_functions::replace_var('GALLERY_IMAGE', $galleryImageSrc, $output);
        immotool_functions::replace_var('GALLERY_IMAGE_TEXT', $galleryImageText, $output);
        immotool_functions::replace_var('NOSCRIPT_GALLERY_IMAGE', null, $output);
      }
      return $output;
    }

    /**
     * Umkreiskarte zur Immobilie darstellen.
     * @param array $object Immobilie
     * @param object $setup Konfiguration
     * @param array $translations Übersetzungen
     * @param string $lang Sprache
     * @return string HTML-Code
     */
    function map(&$object, &$setup, &$translations, $lang, &$mapHandler) {
      $output = '<h2>' . $translations['labels']['estate.map.title'] . '</h2>';
      $mapHeader = $mapHandler->getHeaderContent($object, $translations, $lang);
      if (is_string($mapHeader))
        $output .= $mapHeader;
      $mapBody = $mapHandler->getBodyContent($object, $translations, $lang);
      if (is_string($mapBody))
        $output .= $mapBody;
      return $output;
    }

    /**
     * Medien zur Immobilie darstellen.
     * @param array $object Immobilie
     * @param object $setup Konfiguration
     * @param array $translations Übersetzungen
     * @param string $lang Sprache
     * @return string HTML-Code
     */
    function media(&$object, &$setup, &$translations, $lang) {
      $output = '<h2>' . $translations['labels']['estate.media.title'] . '</h2>';
      $mediaCount = (isset($object['media']) && is_array($object['media'])) ? count($object['media']) : 0;
      $linkCount = (isset($object['links']) && is_array($object['links'])) ? count($object['links']) : 0;
      if ($mediaCount < 1 && $linkCount < 1) {
        $output .= '<p>' . $translations['labels']['estate.media.empty'] . '</p>';
        return $output;
      }

      // Video-Handler ermitteln
      $videoHandler = (is_string($setup->VideoHandler)) ? immotool_functions::get_video($setup->VideoHandler) : null;

      // Video-Liste
      if ($linkCount > 0 && is_object($videoHandler)) {
        $videosWritten = false;
        foreach ($object['links'] as $link) {
          $linkUrl = (isset($link['url'])) ? $link['url'] : null;
          $linkId = (isset($link['id'])) ? $link['id'] : null;
          $provider = (isset($link['provider'])) ? $link['provider'] : null;
          if (!is_string($linkUrl) || !is_string($linkId) || !is_string($provider))
            continue;
          if (strpos($provider, 'video@') !== 0)
            continue;
          if (!$videosWritten) {
            $output .= '<h3>' . $translations['labels']['estate.media.videos'] . '</h3>';
            //$output .= '<ul>';
            $videosWritten = true;
          }

          $linkTitle = (isset($link['title'][$lang]) && is_string($link['title'][$lang]) && strlen($link['title'][$lang]) > 0) ?
              $link['title'][$lang] : $linkId;

          $video = $videoHandler->embed($linkId, $linkTitle, $linkUrl, $provider);
          if (is_string($video)) {
            //$output .= '<li>';
            $output .= $video;
            //$output .= '</li>';
          }
          else {
            //echo '<p>PROBLEM WITH: ' . $linkUrl . ' / ' . $provider . '</p>';
            //echo '<pre>'; print_r( $video ); echo '</pre>';
          }
        }
        //if ($videosWritten) {
        //  $output .= '</ul>';
        //}
      }

      // Download-Liste
      if ($mediaCount > 0) {
        $mediaWritten = false;
        foreach ($object['media'] as $media) {
          if (!isset($media['name']) || !is_string($media['name']))
            continue;
          if (!$mediaWritten) {
            $output .= '<h3>' . $translations['labels']['estate.media.downloads'] . '</h3>';
            $output .= '<ul>';
            $mediaWritten = true;
          }
          $mediaLink = 'data/' . $object['id'] . '/' . $media['name'];
          $mediaTitle = (isset($media['title'][$lang]) && is_string($media['title'][$lang]) && strlen($media['title'][$lang]) > 0) ?
              $media['title'][$lang] : $media['name'];

          $output .= '<li>';
          $output .= '<a href="' . htmlspecialchars($mediaLink) . '" target="_blank">' . htmlspecialchars($mediaTitle) . '</a>';
          $output .= '</li>';
        }
        if ($mediaWritten) {
          $output .= '</ul>';
        }
      }

      // Link-Liste
      if ($linkCount > 0) {
        $linksWritten = false;
        foreach ($object['links'] as $link) {
          $linkUrl = (isset($link['url'])) ? $link['url'] : null;
          $provider = (isset($link['provider'])) ? $link['provider'] : null;
          if (!is_string($linkUrl) || !is_null($provider))
            continue;
          if (!$linksWritten) {
            $output .= '<h3>' . $translations['labels']['estate.media.links'] . '</h3>';
            $output .= '<ul>';
            $linksWritten = true;
          }
          $linkTitle = (isset($link['title'][$lang]) && is_string($link['title'][$lang]) && strlen($link['title'][$lang]) > 0) ?
              $link['title'][$lang] : $linkUrl;

          $output .= '<li>';
          $output .= '<a href="' . htmlspecialchars($linkUrl) . '" target="_blank">' . htmlspecialchars($linkTitle) . '</a>';
          $output .= '</li>';
        }
        if ($linksWritten) {
          $output .= '</ul>';
        }
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
      $attribs = (isset($setup->TextOrder) && is_array($setup->TextOrder)) ? $setup->TextOrder : array_keys($objectTexts);
      $hiddenAttribs = (isset($setup->HiddenAttributes) && is_array($setup->HiddenAttributes)) ? $setup->HiddenAttributes : array();
      foreach ($attribs as $attrib) {
        if ($attrib == 'id' || !isset($objectTexts[$attrib])) {
          continue;
        }
        $attribKey = 'descriptions.' . trim(strtolower($attrib));
        if (array_search($attribKey, $hiddenAttribs) !== false) {
          continue;
        }
        $value = $objectTexts[$attrib];
        $attribValue = immotool_functions::write_attribute_value('descriptions', $attrib, $value, $translations, $lang);
        if (!is_string($attribValue) || strlen(trim($attribValue)) < 1) {
          continue;
        }
        $attribName = $translations['openestate']['attributes']['descriptions'][$attrib];
        if (is_null($attribName)) {
          $attribName = $attrib;
        }
        $output .= '<h3>' . $attribName . '</h3>';
        $output .= '<p>' . immotool_functions::replace_links($attribValue) . '</p>';
      }
      return (strlen($output) > 0) ? $output :
          '<p><i>' . $translations['labels']['estate.texts.empty'] . '</i></p>';
    }

  }

}

// Initialisierung der Skript-Umgebung
$startupTime = microtime();
define('IN_WEBSITE', 1);
if (!defined('IMMOTOOL_BASE_PATH')) {
  define('IMMOTOOL_BASE_PATH', '');
}
require_once(IMMOTOOL_BASE_PATH . 'config.php');
require_once(IMMOTOOL_BASE_PATH . 'private.php');
require_once(IMMOTOOL_BASE_PATH . 'include/functions.php');
require_once(IMMOTOOL_BASE_PATH . 'data/language.php');

// Initialisierung der Immobilien-Ansicht
$setup = new immotool_setup_expose();
immotool_functions::init($setup, 'load_config_expose');

// Übersetzungen ermitteln
$translations = null;
$lang = (isset($_REQUEST[IMMOTOOL_PARAM_LANG])) ? $_REQUEST[IMMOTOOL_PARAM_LANG] : null;
$lang = immotool_functions::init_language($lang, $setup->DefaultLanguage, $translations);
if (!is_array($translations)) {
  if (!headers_sent()) {
    // 500-Fehlercode zurückliefern,
    // wenn die Übersetzungstexte nicht geladen werden konnten
    header('HTTP/1.0 500 Internal Server Error');
  }
  immotool_functions::print_error('Can\'t load translations!', $lang, $startupTime, $setup, $translations);
  return;
}

// angeforderte Immobilie ermitteln
$object = null;
$objectTexts = null;
$id = (isset($_REQUEST[IMMOTOOL_PARAM_EXPOSE_ID])) ? $_REQUEST[IMMOTOOL_PARAM_EXPOSE_ID] : null;
if (preg_match('/^\w+/i', $id) !== 1) {
  if (!headers_sent()) {
    // 400-Fehlercode zurückliefern,
    // wenn die übermittelte Objekt-ID ungültig ist
    header('HTTP/1.0 400 Bad Request');
  }
  immotool_functions::print_error($translations['errors']['cantLoadEstate'], $lang, $startupTime, $setup, $translations);
  return;
}
else {
  $object = immotool_functions::get_object($id);
  $objectTexts = immotool_functions::get_text($id);
  if (!is_array($object)) {
    if (!headers_sent()) {
      // 404-Fehlercode zurückliefern,
      // wenn keine Immobilie zur übermittelten Objekt-ID gefunden wurde
      header('HTTP/1.0 404 Not Found');
    }
    immotool_functions::print_error($translations['errors']['cantLoadEstate'], $lang, $startupTime, $setup, $translations);
    return;
  }
}

// Parameter der Seite
$idValue = (is_string($object['nr']) && strlen($object['nr']) > 0) ? $object['nr'] : '#' . $object['id'];
$mainTitle = $translations['labels']['title'];
$pageTitle = strip_tags($translations['labels']['estate'] . ' ' . $idValue);

// Galerie-Handler ermitteln
$galleryHandlerDefault = immotool_functions::get_gallery('html');
$galleryHandlerDefault->setExposeSetup($setup);
$galleryHandler = immotool_functions::get_gallery($setup->GalleryHandler);
if (!is_object($galleryHandler))
  $galleryHandler = $galleryHandlerDefault;
else
  $galleryHandler->setExposeSetup($setup);

// Umkreiskarten-Handler ermitteln
$mapHandler = (is_string($setup->MapHandler)) ? immotool_functions::get_map($setup->MapHandler) : null;

// Inhalt der Seite erzeugen
$expose = immotool_functions::read_template('expose.html', $setup->TemplateFolder);

// Hauptmenü
$exposeMenu = '<ul>';
$exposeMenu .= '<li class="selected"><a href="?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'] . '{DEFAULT_LINK_PARAMS}">' . $pageTitle . '</a></li>';
if ($setup->HandleFavourites) {
  $favTitle = immotool_functions::has_favourite($object['id']) ? $translations['labels']['link.expose.unfav'] : $translations['labels']['link.expose.fav'];
  $exposeMenu .= '<li><a href="?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'] . '&amp;' . IMMOTOOL_PARAM_FAV . '=' . $object['id'] . '{DEFAULT_LINK_PARAMS}" rel="nofollow">' . $favTitle . '</a></li>';
}
$pdf = 'data/' . $object['id'] . '/' . $object['id'] . '_' . $lang . '.pdf';
if (is_file(IMMOTOOL_BASE_PATH . $pdf)) {
  $pdfLink = 'download.php?id=' . $object['id'] . '&amp;lang=' . $lang;
  $exposeMenu .= '<li><a href="' . $pdfLink . '" target="_blank">' . $translations['labels']['link.expose.pdf'] . '</a></li>';
}
if ($setup->HandleFavourites) {
  $exposeMenu .= '<li style="float:right;"><a href="index.php?' . IMMOTOOL_PARAM_INDEX_VIEW . '=fav{DEFAULT_LINK_PARAMS}" rel="nofollow">' . $translations['labels']['tab.fav'] . '</a></li>';
}
$exposeMenu .= '<li style="float:right;"><a href="index.php?' . IMMOTOOL_PARAM_INDEX_VIEW . '=index{DEFAULT_LINK_PARAMS}">' . $translations['labels']['tab.index'] . '</a></li>';
$exposeMenu .= '</ul>';

// Titelbild
$titleImg = null;
if (isset($object['images']) && is_array($object['images']) && count($object['images']) > 0) {
  $keys = array_keys($object['images']);
  $titleImg = $galleryHandler->getTitleImage($object['id'], $object['images'][$keys[0]], $lang);
}
immotool_functions::replace_var('IMAGE', $titleImg, $expose);

// Anschrift
$addressLine1 = '';
$addressLine2 = '';
if (is_string($object['address']['street'])) {
  $addressLine1 .= $object['address']['street'];
  if (is_string($object['address']['street_nr']))
    $addressLine1 .= ' ' . $object['address']['street_nr'];
}
if (is_string($object['address']['postal']))
  $addressLine2 .= $object['address']['postal'] . ' ';
if (is_string($object['address']['city']))
  $addressLine2 .= $object['address']['city'] . ' ';
if (is_string($object['address']['city_part']))
  $addressLine2 .= '(' . $object['address']['city_part'] . ') ';
if (strlen($addressLine2) > 0 && strlen($addressLine1) == 0) {
  $addressLine1 = $addressLine2;
  $addressLine2 = null;
}

// Region
$addressRegion = '';
if (is_string($object['address']['country_name'][$lang])) {
  $addressRegion .= $object['address']['country_name'][$lang];
  if (is_string($object['address']['region']) && strlen($object['address']['region']) > 0)
    $addressRegion .= ' / ' . $object['address']['region'];
}

// Titel-Attribute
$titleAttribTemplate = immotool_functions::get_string_between($expose, '{ATTRIBUTE_VALUE.}', '{.ATTRIBUTE_VALUE}');
if (is_string($titleAttribTemplate) && strlen($titleAttribTemplate) > 0) {
  $titleAttribs = '';
  if (isset($setup->TitleAttributes) && is_array($setup->TitleAttributes)) {
    foreach ($setup->TitleAttributes as $attribKey) {
      $attrib = explode('.', strtolower(trim($attribKey)));
      if (!isset($object['attributes'][$attrib[0]][$attrib[1]]))
        continue;
      $attribTitle = $translations['openestate']['attributes'][$attrib[0]][$attrib[1]];
      $attribValue = immotool_functions::write_attribute_value(
              $group, $attrib, $object['attributes'][$attrib[0]][$attrib[1]], $translations, $lang);
      //$titleAttribs .= '<li><div>'.$attribTitle.':</div>'.$attribValue.'</li>';

      $replacement = array(
        '{ATTRIBUTE_TITLE}' => $attribTitle,
        '{ATTRIBUTE_VALUE}' => $attribValue);

      $titleAttribs .= str_replace(array_keys($replacement), array_values($replacement), $titleAttribTemplate);
    }
  }
  $expose = str_replace('{ATTRIBUTE_VALUE.}' . $titleAttribTemplate . '{.ATTRIBUTE_VALUE}', $titleAttribs, $expose);
}

// Ansichten erzeugen
$view = (isset($_REQUEST[IMMOTOOL_PARAM_EXPOSE_VIEW])) ? $_REQUEST[IMMOTOOL_PARAM_EXPOSE_VIEW] : null;
$viewContent = '';
$viewMenu = null;
$viewMode = $setup->ViewMode;
if (!is_string($viewMode))
  $viewMode = 'tabular';
$viewOrder = $setup->ViewOrder;
if (!is_array($viewOrder) || count($viewOrder) <= 0) {
  $viewOrder = array('details', 'texts', 'gallery', 'contact', 'terms');
}
if (!is_string($view) || strlen(trim($view)) == 0 || array_search($view, $viewOrder) === false) {
  $view = $viewOrder[0];
}

// Überprüfung, ob die verschiedenen Bereiche dargestellt werden können
$canShow = array();
$canShow['contact'] = immotool_functions::can_show_expose_contact($object, $setup);
$canShow['gallery'] = immotool_functions::can_show_expose_gallery($object, $setup);
$canShow['map'] = immotool_functions::can_show_expose_map($object, $setup, $mapHandler);
$canShow['media'] = immotool_functions::can_show_expose_media($object, $setup);
$canShow['terms'] = immotool_functions::can_show_expose_terms($setup);
$canShow['texts'] = immotool_functions::can_show_expose_texts($objectTexts, $setup, $lang);

// Darstellung als Reiter
if ($viewMode == 'tabular') {

  $viewClass = array();

  // aktueller Reiter: Texte
  if ($view == 'texts' && $canShow[$view] === true) {
    $viewContent .= immotool_expose::texts($objectTexts, $setup, $translations, $lang);
    $viewClass[$view] = 'class="selected"';
  }

  // aktueller Reiter: Galerie
  else if ($view == 'gallery' && $canShow[$view] === true) {
    $viewContent .= immotool_expose::gallery($object, $setup, $translations, $lang, $galleryHandler, $galleryHandlerDefault);
    $viewClass[$view] = 'class="selected"';
  }

  // aktueller Reiter: Umkreiskarte
  else if ($view == 'map' && $canShow[$view] === true) {
    $viewContent .= immotool_expose::map($object, $setup, $translations, $lang, $mapHandler);
    $viewClass[$view] = 'class="selected"';
  }

  // aktueller Reiter: Medien
  else if ($view == 'media' && $canShow[$view] === true) {
    $viewContent .= immotool_expose::media($object, $setup, $translations, $lang);
    $viewClass[$view] = 'class="selected"';
  }

  // aktueller Reiter: Kontaktformular
  else if ($view == 'contact' && $canShow[$view] === true) {
    $viewContent .= immotool_expose::contact($object, $setup, $translations, $lang);
    $viewClass[$view] = 'class="selected"';
  }

  // aktueller Reiter: AGB
  else if ($view == 'terms' && $canShow[$view] === true) {
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
    if (!isset($canShow[$v]) || $canShow[$v] === true) {
      $c = (isset($viewClass[$v]) && is_string($viewClass[$v])) ? $viewClass[$v] : '';
      $viewMenu .= '<li ' . $c . '>' .
          '<a href="expose.php?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $object['id'] . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=' . $v . '{DEFAULT_LINK_PARAMS}">' .
          $translations['labels']['estate.' . $v] . '</a></li>';
    }
  }
  $viewMenu .= '</ul>';
}

// Darstellung als Auflistung
else {
  foreach ($viewOrder as $v) {

    // Abschnitt: Texte
    if ($v == 'texts' && $canShow[$v] === true) {
      $viewContent .= immotool_expose::texts($objectTexts, $setup, $translations, $lang);
    }

    // Abschnitt: Galerie
    else if ($v == 'gallery' && $canShow[$v] === true) {
      $viewContent .= immotool_expose::gallery($object, $setup, $translations, $lang, $galleryHandler, $galleryHandlerDefault);
    }

    // Abschnitt: Umkreiskarte
    else if ($v == 'map' && $canShow[$v] === true) {
      $viewContent .= immotool_expose::map($object, $setup, $translations, $lang, $mapHandler);
    }

    // Abschnitt: Medien
    else if ($v == 'media' && $canShow[$v] === true) {
      $viewContent .= immotool_expose::media($object, $setup, $translations, $lang);
    }

    // Abschnitt: Kontaktformular
    else if ($v == 'contact' && $canShow[$v] === true) {
      $viewContent .= immotool_expose::contact($object, $setup, $translations, $lang);
    }

    // Abschnitt: AGB
    else if ($v == 'terms' && $canShow[$v] === true) {
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
  '{ADDRESS_TITLE}' => $translations['labels']['estate.address'],
  '{ADDRESS_VALUE}' => trim($addressLine1),
  '{REGION_TITLE}' => $translations['labels']['estate.region'],
);

$pageContent = str_replace(array_keys($replacement), array_values($replacement), $expose);
immotool_functions::replace_var('VIEW_MENU', $viewMenu, $pageContent);
immotool_functions::replace_var('REGION_VALUE', $addressRegion, $pageContent);
immotool_functions::replace_var('ADDRESS2_VALUE', $addressLine2, $pageContent);

$pageHeader = '';
if (is_object($galleryHandler)) {
  $galleryHeader = $galleryHandler->getHeader();
  if (!is_null($galleryHeader))
    $pageHeader .= "\n" . $galleryHeader;
}

// Ausgabe erzeugen
$metaRobots = 'index,follow';
$metaKeywords = (isset($objectTexts['keywords'][$lang])) ?
    $objectTexts['keywords'][$lang] : null;
$metaDescription = null;
if (is_array($setup->MetaDescriptionTexts)) {
  foreach ($setup->MetaDescriptionTexts as $attrib) {
    $metaDescription = (isset($objectTexts[$attrib][$lang])) ? $objectTexts[$attrib][$lang] : null;
    if ($metaDescription != null) {
      if (is_string($metaDescription) && strlen(trim($metaDescription)) > 0) {
        break;
      }
      else {
        $metaDescription = null;
      }
    }
  }
}
$linkParam = '&amp;' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $id . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=' . $view;
$output = immotool_functions::build_page($setup, 'expose', $lang, $mainTitle, $pageTitle, trim($pageHeader), $pageContent, $startupTime, $metaRobots, $metaKeywords, $metaDescription, $linkParam);
if (is_string($setup->Charset) && strlen(trim($setup->Charset)) > 0) {
  $output = immotool_functions::encode($output, $setup->Charset);
}
if (is_string($setup->ContentType) && strlen(trim($setup->ContentType)) > 0) {
  header('Content-Type: ' . $setup->ContentType);
}
echo $output;
immotool_functions::shutdown($setup);
