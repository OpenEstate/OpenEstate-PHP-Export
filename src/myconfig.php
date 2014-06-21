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
 * Website-Export, individuelle Konfigurationen.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2010, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE')) {
  exit;
}

class immotool_myconfig {

  /**
   * Allgemeine Konfiguration überschreiben.
   * @param object $config Konfigurations-Objekt
   */
  function load_config_default(&$config) {
    // ggf. individuelle Konfigurationswerte einfügen
    //$config->DefaultLanguage = 'en';
    //$config->AdditionalStylesheet = '';
    //$config->ShowLanguageSelection = false;
    //$config->CacheLifeTime = 86400;
  }

  /**
   * Konfiguration der Exposédarstellung überschreiben.
   * @param object $config Konfigurations-Objekt
   */
  function load_config_expose(&$config) {
    // allgemeine Konfiguration (siehe oben)
    immotool_myconfig::load_config_default($config);

    // ggf. individuelle Konfigurationswerte einfügen
    //$config->ShowTerms = false;
    //$config->ShowContactPerson = false;
    //$config->ShowContactForm = false;
    //$config->ShowContactCaptcha = false;
    //$config->ShowContactTerms = true;
    //$config->ContactRequiredFields = array( 'name', 'firstname', 'email', 'message' );
    //$config->GalleryHandler = null;
    //$config->ViewMode = 'listing';
    //$config->ViewOrder = array( 'gallery', 'texts', 'details', 'contact', 'terms' );
  }

  /**
   * Konfiguration der Immobilienübersicht überschreiben.
   * @param object $config Konfigurations-Objekt
   */
  function load_config_index(&$config) {
    // allgemeine Konfiguration (siehe oben)
    immotool_myconfig::load_config_default($config);

    // ggf. individuelle Konfigurationswerte einfügen
    //$config->ElementsPerPage = 5;
    //$config->OrderOptions = array( 'id', 'city', 'postal' );
    //$config->FilterOptions = array( 'action', 'type' );
  }

  /**
   * Konfiguration des Stylesheets überschreiben.
   * @param object $config Konfigurations-Objekt
   */
  function load_config_style(&$config) {
    // allgemeine Konfiguration (siehe oben)
    immotool_myconfig::load_config_default($config);

    // ggf. individuelle Konfigurationswerte einfügen
    //$config->ShowGeneralStyles = false;
    //$config->GeneralTextColor = 'black';
  }

  /**
   * Konfiguration der Immobilien-Feeds überschreiben.
   * @param object $config Konfigurations-Objekt
   */
  function load_config_feeds(&$config) {
    // allgemeine Konfiguration (siehe oben)
    immotool_myconfig::load_config_default($config);

    // ggf. individuelle Konfigurationswerte einfügen
    //$config->PublishAtomFeed = false;
    //$config->PublishImmobiliareFeed = false;
    //$config->PublishRssFeed = false;
    //$config->PublishTrovitFeed = false;
    //$config->AtomFeedLimit = 10;
    //$config->RssFeedLimit = 10;
  }

  /**
   * Übersetzungen überschreiben / ergänzen.
   * @param array $translations verwendete Übersetzungen
   * @param string $lang zweistelliger ISO-Sprachcode
   */
  function load_translations(&$translations, $lang) {
    // ggf. individuelle Übersetzungen einfügen
    //if ($lang=='de')
    //{
    //  $translations['labels']['title.index'] = 'Übersicht';
    //  $translations['labels']['title.fav'] = 'Vormerkliste';
    //}
    //else if ($lang=='en')
    //{
    //  $translations['labels']['title.index'] = 'Summary';
    //  $translations['labels']['title.fav'] = 'Favourites';
    //}
  }

  /**
   * Mailversand durchführen.
   * @param object $setup Konfigurations-Objekt
   * @param string $subject Betreff
   * @param string $body Mitteilung
   * @param string $mailToAdress Mailadresse des Empfängers
   * @param string $replyToAdress Mailadresse des Antwort-Empfängers
   * @param string $replyToName Name des Antwort-Empfängers
   * @return mixed Im Erfolgsfall 'true', sonst eine Fehlermeldung oder 'null' wenn kein Versand über die Funktion stattfand
   */
  function send_mail(&$setup, $subject, $body, $mailToAdress, $replyToAdress, $replyToName) {
    return null;
  }

}
