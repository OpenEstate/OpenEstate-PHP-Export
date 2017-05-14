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
 * Website-Export, individuelle Konfigurationen.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
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
    // Keinen Content-Type verwenden, wenn eine Einbindung durch einen Wrapper stattfindet
    if (defined('OPENESTATE_WRAPPER'))
      $config->ContentType = null;

    // ggf. individuelle Konfigurationswerte einfügen
    //$config->DefaultLanguage = 'en';
    //$config->AdditionalStylesheet = '';
    //$config->ShowLanguageSelection = false;
    //$config->CacheLifeTime = 86400;
    //$config->DynamicImageScaling = false;
    //$config->HandleFavourites = false;
    //$config->TemplateFolder = 'default';
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
    //$config->ViewMode = 'listing';
    //$config->ViewOrder = array( 'gallery', 'texts', 'details', 'map', 'contact', 'terms' );
    //$config->DetailsOrder = array( 'prices', 'measures', 'features', 'surroundings', 'condition', 'administration' );
    //$config->TextOrder = array( 'detailled_description', 'location_description', 'feature_description', 'price_description', 'agent_fee_information', 'additional_information', 'short_description' );
    //$config->MapHandler = 'google';
    //$config->VideoHandler = 'custom';
    //$config->GalleryHandler = null;
    //$config->GalleryImageSize = array( 100, 75 );
    //$config->TitleImageSize = array( 200, 150 );
    //$config->TitleAttributes = array( 'prices.rent_excluding_service_charges', 'prices.purchase_price' );
    //$config->PreferredAttributes = array( 'prices.purchase_price', 'prices.rent_excluding_service_charges', 'prices.service_charges', 'prices.heating_costs', 'prices.rent_including_service_charges' );
    //$config->HiddenAttributes = array( 'prices.special_offer', 'prices.agent_fee', 'prices.agent_fee_including_vat', 'descriptions.keywords' );
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
    //$config->OrderOptions = array( 'area', 'city', 'id', 'nr', 'postal', 'price', 'rooms', 'title' );
    //$config->FilterOptions = array( 'action', 'age', 'city', 'country', 'equipment', 'furnished', 'group', 'region', 'rooms', 'specialoffer', 'type' );
    //$config->ListingImageSize = array( 100, 75 );
    //$config->GalleryImageSize = array( 150, 150 );
    //$config->AttributesPerGroup = 3;
    //$config->PreferredAttributes = array( 'prices.purchase_price', 'prices.nettorendite', 'prices.rent_excluding_service_charges', 'prices.service_charges' );
    //$config->HiddenAttributes = array( 'prices.special_offer', 'prices.agent_fee', 'prices.agent_fee_including_vat' );
  }

  /**
   * Konfiguration des Stylesheets überschreiben.
   * @param object $config Konfigurations-Objekt
   */
  function load_config_style(&$config) {
    // allgemeine Konfiguration (siehe oben)
    immotool_myconfig::load_config_default($config);

    // Keine allgemeinen Stylesheets verwenden, wenn eine Einbindung durch einen Wrapper stattfindet
    $config->ShowGeneralStyles = !defined('OPENESTATE_WRAPPER');

    // ggf. individuelle Konfigurationswerte einfügen
    //$config->GeneralTextColor = '#303030';
    //$config->GeneralTextFont = 'sans-serif';
    //$config->BodyBackgroundColor = '#ffffff';
    //$config->BodyFontSize = '12px';
    //$config->LightBackgroundColor = '#ffffff';
    //$config->DarkBackgroundColor = '#e6ffe6';
    //$config->BorderColor = '#6c6';
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
    //$config->PublishRssFeed = false;
    //$config->PublishTrovitFeed = false;
    //$config->AtomFeedLimit = 10;
    //$config->AtomFeedWithImage = false;
    //$config->RssFeedLimit = 10;
    //$config->RssFeedWithImage = false;
    //$config->OrderBy = 'id';
    //$config->OrderDir = 'desc';
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
    return null;
  }

}
