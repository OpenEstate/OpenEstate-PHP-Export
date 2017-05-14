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
 * Website-Export, Konfigurationen.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE')) {
  exit;
}

/**
 * Allgemeine Konfiguration
 */
class immotool_setup {

  /**
   * Standardmäßig verwendete Sprache.
   * Erlaubt sind ISO-Sprachcodes, für die eine Übersetzung hinterlegt ist.
   * @var string
   */
  var $DefaultLanguage = 'de';

  /**
   * Zusätzlich verwendeter CSS-Stylesheet.
   * Dies erlaubt Anpassungen am Layout, ohne bestehende Skripte zu ändern.
   * @var string
   */
  var $AdditionalStylesheet = '';

  /**
   * Standardmäßig verwendete Zeitzone.
   * Liste der unterstützten Zeitzonen: http://www.php.net/manual/de/timezones.php
   * Wenn keine Zeitzone angegeben wurde, wird die Zeitzone des Servers verwendet.
   * @var string
   */
  var $Timezone = 'Europe/Berlin';

  /**
   * Sprachauswahl anzeigen.
   * Die automatisch erzeugte Sprachauswahl kann nach Bedarf deaktiviert werden.
   * Erlaubt ist true (=ja) oder false (=nein)
   * @var bool
   */
  var $ShowLanguageSelection = true;

  /**
   * Verwendeter Zeichensatz für erzeugte Inhalte.
   * Erzeugte Texte werden vor der Ausgabe ggf. in diesen Zeichensatz umgewandelt.
   * Erlaubt sind die in der PHP-Installation unterstützten Zeichensätze.
   * @var string
   */
  var $Charset = 'UTF-8';

  /**
   * Verwendeter Content-Type für erzeugte Inhalte.
   * Wenn ein Wert angegeben wurde, wird dieser Content-Type als Header in
   * der HTTP-Response dargestellt.
   * @var string
   */
  var $ContentType = 'text/html; charset=UTF-8';

  /**
   * Verzeichnis, aus welchem die Templates bevorzugt geladen werden.
   * Der angegebene Name muss als Unterverzeichnis im templates-Verzeichnis
   * vorhanden sein, welches die individuell angepassten Template-Dateien
   * enthält.
   * @var string
   */
  var $TemplateFolder = 'default';

  /**
   * Diese E-Mailadresse wird als Absender in den versendeten E-Mails verwendet.
   * @var string
   */
  var $MailFrom = 'max@mustermann.de';

  /**
   * Diese Name wird als Absender in den versendeten E-Mails verwendet.
   * @var string
   */
  var $MailFromName = 'Max Mustermann';

  /**
   * An diese E-Mailadresse wird eine Kopie der versendeten E-Mails versendet.
   * Wenn keine Adresse hinterlegt ist, wird keine Kopie versendet.
   * @var string
   */
  var $MailToCC = '';

  /**
   * An diese E-Mailadresse wird eine Blindkopie der versendeten E-Mails versendet.
   * Wenn keine Adresse hinterlegt ist, wird keine Blindkopie versendet.
   * @var string
   */
  var $MailToBCC = '';

  /**
   * Art des Mailversands.
   * mögliche Optionen sind 'mail', 'sendmail', 'smtp'
   * @var string
   */
  var $MailMethod = 'mail';

  /**
   * Pfad zum Sendmail-Programm.
   * wenn $MailMethod='sendmail'
   * @var string
   */
  var $MailSendmailPath = '/usr/sbin/sendmail';

  /**
   * Hostname für Mailversand via SMTP.
   * wenn $MailMethod='smtp'
   * @var string
   */
  var $MailSmtpHost = 'localhost';

  /**
   * Port-Nummer für Mailversand via SMTP.
   * wenn $MailMethod='smtp'
   * @var int
   */
  var $MailSmtpPort = 25;

  /**
   * Verschlüsselung des Mailversandes via SMTP.
   * mögliche Optionen sind '', 'ssl', 'tls'
   * wenn $MailMethod='smtp'
   * @var string
   */
  var $MailSmtpSecurity = '';

  /**
   * Anmeldung am SMTP-Server.
   * wenn $MailMethod='smtp'
   * Erlaubt ist true (=ja) oder false (=nein)
   * @var bool
   */
  var $MailSmtpAuth = false;

  /**
   * Benutzername zur Anmeldung am SMTP-Server.
   * wenn $MailMethod='smtp' und $MailSmtpAuth=true
   * @var string
   */
  var $MailSmtpAuthLogin = '';

  /**
   * Passwort zur Anmeldung am SMTP-Server.
   * wenn $MailMethod='smtp' und $MailSmtpAuth=true
   * @var string
   */
  var $MailSmtpAuthPassword = '';

  /**
   * Den SMTP-Versand im 'debug'-Modus ausführen.
   * Dies kann hilfreich sein, um eventuelle Fehler in der Konfiguration zu finden.
   * wenn $MailMethod='smtp'
   * Erlaubt ist true (=ja) oder false (=nein)
   * @var bool
   */
  var $MailSmtpDebug = false;

  /**
   * URL-Vorlage für Exposé-Links.
   * Wird an verschiedenen Stellen verwendet, um Direktlinks auf ein Exposé zu erzeugen
   * Beispiel:
   * $ExposeUrlTemplate = 'http://www.mustermann-makler.de/immobilien/expose.php?id={ID}&lang={LANG}';
   * @var string
   */
  var $ExposeUrlTemplate = null;

  /**
   * Kategorien.
   * Wird an verschiedenen Stellen verwendet, um unterschiedliches Verhalten für
   * verschiedene Kategorien zu realisieren.
   * Beispiel:
   * $Categories = array( 'wohnen', 'gewerbe', 'anlage' );
   * @var string
   */
  var $Categories = array();

  /**
   * Lebensdauer von Cache-Dateien.
   * Eine im Cache-Verzeichnis abgelegte Dateien wird nach einem bestimmten
   * Zeitraum verworfen und neu erzeugt. Die Dauer der Gültigkeit einer
   * Cache-Datei wird in Sekunden erfasst.
   * Beispiel:
   * $CacheLifeTime = 3600; // eine Stunde
   * $CacheLifeTime = 86400; // ein Tag
   * @var int
   */
  var $CacheLifeTime = 10800; // drei Stunden

  /**
   * Verkleinerte Vorschaubilder dynamisch via PHP erzeugen.
   * Die Option kann nur verwendet werden, wenn das GD-Modul in PHP verfügbar ist.
   * Erlaubt ist true (=ja) oder false (=nein)
   * @var bool
   */
  var $DynamicImageScaling = true;

  /**
   * Vormerkungen von Immobilien aktivieren.
   * Erlaubt ist true (=ja) oder false (=nein)
   * @var bool
   */
  var $HandleFavourites = true;

}

/**
 * Konfiguration der Index-Ansicht
 */
class immotool_setup_index extends immotool_setup {

  /**
   * Anzahl der Einträge, die auf einer Index-Seite dargestellt werden
   * @var int
   */
  var $ElementsPerPage = 10;

  /**
   * Verwendete Sortierungs-Optionen.
   * @var array
   */
  var $OrderOptions = array('id', 'city', 'postal', 'title', 'price', 'rooms', 'area');

  /**
   * Art der Sortierung beim ersten Besuch der Index-Ansicht.
   * Muss in $OrderOptions enthalten sein.
   * @var string
   */
  var $DefaultOrderBy = 'id';

  /**
   * Richtung der Sortierung beim ersten Besuch der Index-Ansicht.
   * 'asc' für aufsteigend, 'desc' für absteigend
   * @var string
   */
  var $DefaultOrderDir = 'asc';

  /**
   * Standardmäßig als Tabelle oder Galerie darstellen.
   * 'entry' für tabellarische Darstellung oder
   * 'gallery' für Galerie-Darstellungen
   * @var string
   */
  var $DefaultMode = 'entry';

  /**
   * Verwendete Filter-Optionen.
   * @var array
   */
  var $FilterOptions = array('action', 'type');

  /**
   * Alle verfügbaren Immobilienarten filtern.
   * Wenn aktiviert (true), werden alle verfügbaren Immobilienarten als
   * Filterkriterium dargestellt. Wenn nicht aktiviert (false), werden nur die
   * verfügbaren Haupt-Immobilienarten als Filterkriterium dargestellt.
   * Erlaubt ist true (=ja) oder false (=nein)
   * @var bool
   */
  var $FilterAllEstateTypes = true;

  /**
   * Größe der verkleinerten Vorschaubilder in der Listenansicht der
   * Immobilienübersicht.
   * Die Option kann nur verwendet werden, wenn das GD-Modul in PHP verfügbar ist
   * und '$DynamicImageScaling = true' gesetzt wurde.
   * Die erste Zahl stellt die Breite, die zweite Zahl die Höhe in Pixeln dar.
   * Erlaubt sind ganze Zahlen größer 0.
   * @var array
   */
  var $ListingImageSize = array(100, 75);

  /**
   * Größe der verkleinerten Vorschaubilder in der Galerieansicht der
   * Immobilienübersicht (in Pixel).
   * Die Option kann nur verwendet werden, wenn das GD-Modul in PHP verfügbar ist
   * und '$DynamicImageScaling = true' gesetzt wurde.
   * Die erste Zahl stellt die Breite, die zweite Zahl die Höhe in Pixeln dar.
   * Erlaubt sind ganze Zahlen größer 0.
   * @var array
   */
  var $GalleryImageSize = array(150, 150);

  /**
   * Anzahl der Attribute, die pro Attributgruppe für eine Immobilie in der
   * Immobilien-Übersicht dargestellt werden.
   * @var int
   */
  var $AttributesPerGroup = 2;

  /**
   * Auflistung von Attributen, die in der Immobilien-Übersicht bevorzugt
   * dargestellt werden sollen.
   * @var array
   */
  var $PreferredAttributes = array();

  /**
   * Auflistung von Attributen, die in der Immobilien-Übersicht nicht
   * dargestellt werden sollen.
   * @var array
   */
  var $HiddenAttributes = array('prices.special_offer', 'prices.agent_fee', 'prices.agent_fee_including_vat');

}

/**
 * Konfiguration der Exposé-Ansicht
 */
class immotool_setup_expose extends immotool_setup {

  /**
   * AGB's im Exposé darstellen.
   * Erlaubt ist true (=ja) oder false (=nein)
   * @var bool
   */
  var $ShowTerms = true;

  /**
   * Kontaktperson im Exposé darstellen.
   * Erlaubt ist true (=ja) oder false (=nein)
   * @var bool
   */
  var $ShowContactPerson = true;

  /**
   * Kontaktformular im Exposé darstellen.
   * Erlaubt ist true (=ja) oder false (=nein)
   * @var bool
   */
  var $ShowContactForm = true;

  /**
   * Grafischen Bestätigungscode (CAPTCHA) im Exposé-Kontaktformular darstellen.
   * Erlaubt ist true (=ja) oder false (=nein)
   * @var bool
   */
  var $ShowContactCaptcha = true;

  /**
   * Text mit rechtlichen Richtlinien im Kontaktformular bestätigen.
   * Erlaubt ist true (=ja) oder false (=nein)
   * @var bool
   */
  var $ShowContactTerms = false;

  /**
   * Pflichtfelder bei der Verarbeitung des Kontaktformulares.
   * Felder, bei denen die Eingabe keine Pflicht ist, können aus dem Array entfernt werden.
   * @var array
   */
  var $ContactRequiredFields = array('name', 'firstname', 'email', 'phone', 'street', 'streetnr', 'city', 'postal', 'message');

  /**
   * Einbindung einer externen Bildergalerie.
   * @var string
   */
  var $GalleryHandler = 'colorbox';

  /**
   * Einbindung einer Umkreiskarte.
   * @var string
   */
  var $MapHandler = 'osm';

  /**
   * Einbindung externer Videos.
   * @var string
   */
  var $VideoHandler = 'default';

  /**
   * Art der Darstellung.
   * Erlaubt ist 'tabular' (=Reiterdarstellung) oder 'listing' (=hintereinander)
   * @var string
   */
  var $ViewMode = 'tabular';

  /**
   * Reihenfolge der Darstellung.
   * @var array
   */
  var $ViewOrder = array('details', 'texts', 'gallery', 'map', 'media', 'contact', 'terms');

  /**
   * Reihenfolge der dargestellten Attribut-Gruppen im Reiter 'Details'.
   * @var array
   */
  var $DetailsOrder = array('prices', 'measures', 'features', 'surroundings', 'condition', 'administration', 'energy_certificate');

  /**
   * Reihenfolge der dargestellten Beschreibungstexte im Reiter 'Beschreibung'.
   * @var array
   */
  var $TextOrder = array('detailled_description', 'location_description', 'feature_description', 'price_description', 'agent_fee_information', 'additional_information', 'short_description');

  /**
   * Größe des verkleinerten Titelbildes in der Exposéansicht (in Pixel).
   * Die Option kann nur verwendet werden, wenn das GD-Modul in PHP verfügbar ist
   * und '$DynamicImageScaling = true' gesetzt wurde.
   * Die erste Zahl stellt die Breite, die zweite Zahl die Höhe in Pixeln dar.
   * Erlaubt sind ganze Zahlen größer 0.
   * @var array
   */
  var $TitleImageSize = array(200, 150);

  /**
   * Größe der verkleinerten Galeriebilder in der Exposéansicht (in Pixel).
   * Die Option kann nur verwendet werden, wenn das GD-Modul in PHP verfügbar ist
   * und '$DynamicImageScaling = true' gesetzt wurde.
   * Die erste Zahl stellt die Breite, die zweite Zahl die Höhe in Pixeln dar.
   * Erlaubt sind ganze Zahlen größer 0.
   * @var array
   */
  var $GalleryImageSize = array(100, 75);

  /**
   * Textfelder, die als META-Description verwendet werden sollen. Das erste
   * Textfeld in der Liste, zu welchem ein Text eingetragen wurde, wird als
   * META-Description in der Exposé-Ansicht dargestellt.
   * @var array
   */
  var $MetaDescriptionTexts = array('short_description', 'detailled_description');

  /**
   * Auflistung von Attributen, die in der Exposé-Ansicht im Titelbereich
   * dargestellt werden sollen.
   * @var array
   */
  var $TitleAttributes = array();

  /**
   * Auflistung von Attributen, die in der Exposé-Ansicht an oberster Stelle
   * innerhalb ihrer Gruppe dargestellt werden sollen.
   * @var array
   */
  var $PreferredAttributes = array();

  /**
   * Auflistung von Attributen, die in der Exposé-Ansicht grundsätzlich
   * nicht dargestellt werden sollen.
   * @var array
   */
  var $HiddenAttributes = array('prices.special_offer', 'descriptions.keywords');

}

/**
 * Konfiguration des dynamischen Stylesheets
 */
class immotool_setup_style extends immotool_setup {

  /**
   * Allgemeine Stylesheets definieren (body, h1, h2, h3, a ...).
   * @var bool
   */
  var $ShowGeneralStyles = true;

  /**
   * Allgemein verwendete Textfarbe.
   * wenn $ShowGeneralStyles = true
   * @var string
   */
  var $GeneralTextColor = '#303030';

  /**
   * Allgemein verwendete Schriftart.
   * wenn $ShowGeneralStyles = true
   * @var string
   */
  var $GeneralTextFont = 'sans-serif';

  /**
   * Allgemein verwendete Hintergrundfarbe.
   * wenn $ShowGeneralStyles = true
   * @var string
   */
  var $BodyBackgroundColor = '#ffffff';

  /**
   * Allgemein verwendete Schriftgröße.
   * wenn $ShowGeneralStyles = true
   * @var string
   */
  var $BodyFontSize = '12px';

  /**
   * Hintergrund, hell.
   * @var string
   */
  var $LightBackgroundColor = '#ffffff';

  /**
   * Hintergrund, dunkel.
   * @var string
   */
  var $DarkBackgroundColor = '#e6ffe6';

  /**
   * Farbwert für Umrandungen / Rahmen.
   * @var string
   */
  var $BorderColor = '#6c6';

}

/**
 * Konfiguration der Immobilien-Feeds.
 */
class immotool_setup_feeds extends immotool_setup {

  /**
   * Atom-Feed veröffentlichen.
   * @var bool
   */
  var $PublishAtomFeed = true;

  /**
   * RSS-Feed veröffentlichen.
   * @var bool
   */
  var $PublishRssFeed = true;

  /**
   * Trovit-Feed veröffentlichen.
   * @var bool
   */
  var $PublishTrovitFeed = false;

  /**
   * Anzahl maximaler Einträge im Atom-Feed.
   * Zur unlimitierten Darstellung: $AtomFeedLimit = null;
   * @var int
   */
  var $AtomFeedLimit = 15;

  /**
   * Titelbild der Immobilie im Atom-Feed anzeigen.
   * @var bool
   */
  var $AtomFeedWithImage = true;

  /**
   * Anzahl maximaler Einträge im RSS-Feed.
   * Zur unlimitierten Darstellung: $RssFeedLimit = null;
   * @var int
   */
  var $RssFeedLimit = 15;

  /**
   * Titelbild der Immobilie im RSS-Feed anzeigen.
   * @var bool
   */
  var $RssFeedWithImage = true;

  /**
   * Objekt-Nummer an Stelle der Objekt-ID bei Feed-Exporten veröffentlichen.
   * @var bool
   */
  var $ExportPublicId = false;

  /**
   * Art der Sortierung in den erzeugten Feeds.
   * @var string
   */
  var $OrderBy = 'lastmod';

  /**
   * Richtung der Sortierung in den erzeugten Feeds.
   * 'asc' für aufsteigend, 'desc' für absteigend
   * @var string
   */
  var $OrderDir = 'desc';

}
