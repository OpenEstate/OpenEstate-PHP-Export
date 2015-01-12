<?php
/*
 * PHP-Export scripts of OpenEstate-ImmoTool
 * Copyright (C) 2009-2015 OpenEstate.org
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
 * Website-Export, Inserat #1, Objektdaten.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE')) {
  exit;
}

$GLOBALS['immotool_objects']['1'] = array(
  'id' => '1',
  'action' => 'miete',
  'type' => 'etagenwohnung',
  'type_path' => array('main_wohnen', 'wohnung', 'etagenwohnung'),
  'currency' => 'EUR',
  'nr' => null,
  'hidden_price' => false,
  'group_nr' => null,
  'mail' => 'info@beispielfirma.de',
  'title' => array(
    'en' => 'an example property',
    'de' => 'eine Beispiel-Immobilie',
  ),
  'adress' => array(
    'country' => 'DE',
    'country_name' => array(
      'en' => 'Germany',
      'de' => 'Deutschland',
    ),
    'postal' => '12345',
    'city' => 'Berlin',
    'city_part' => null,
    'street' => null,
    'street_nr' => null,
    'region' => null,
    'latitude' => null,
    'longitude' => null,
  ),
  'contact' => array(
    'country' => 'DE',
    'country_name' => array(
      'en' => 'Germany',
      'de' => 'Deutschland',
    ),
    'postal' => '12345',
    'city' => 'Berlin',
    'city_part' => null,
    'street' => 'Beispielstraße',
    'street_nr' => '123',
    'region' => null,
    'latitude' => null,
    'longitude' => null,
    'person_title' => null,
    'person_firstname' => 'Max',
    'person_middlename' => null,
    'person_lastname' => 'Mustermann',
    'person_fullname' => 'Max Mustermann',
    'person_gender' => 'MALE',
    'person_mail' => 'max.mustermann@beispielfirma.de',
    'person_phone' => '030/123456',
    'person_mobile' => null,
    'person_fax' => null,
    'company_name' => 'Beispielfirma',
    'company_name_addition' => null,
    'company_type' => null,
    'company_business' => null,
    'company_department' => null,
    'company_position' => null,
    'company_mail' => 'info@beispielfirma.de',
    'company_phone' => '030/123456',
    'company_mobile' => null,
    'company_fax' => null,
    'company_website' => 'http://www.beispielfirma.de',
  ),
  'attributes' => array(
    'preise' => array(
      'kaltmiete' => array(
        'value' => 1327.81,
        'en' => '1.327,81 EUR',
        'de' => '1.327,81 EUR',
      ),
      'kaltmiete_netto' => array(
        'value' => 1143.42,
        'en' => '1.143,42 EUR',
        'de' => '1.143,42 EUR',
      ),
      'kaltmiete_qm' => array(
        'value' => 123.0,
        'en' => '123,00 EUR',
        'de' => '123,00 EUR',
      ),
      'warmmiete' => array(
        'value' => 1523.87,
        'en' => '1.523,87 EUR',
        'de' => '1.523,87 EUR',
      ),
      'warmmiete_qm' => array(
        'value' => 136.87,
        'en' => '136,87 EUR',
        'de' => '136,87 EUR',
      ),
      'mietzuschlaege' => array(
        'value' => 150.0,
        'en' => '150,00 EUR',
        'de' => '150,00 EUR',
      ),
      'zzg_mehrwertsteuer' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'mwst' => array(
        'value' => 19.0,
        'en' => '19',
        'de' => '19',
      ),
      'mwst_betrag' => array(
        'value' => 163.0,
        'en' => '163,00 EUR',
        'de' => '163,00 EUR',
      ),
      'nebenkosten' => array(
        'value' => 153.0,
        'en' => '153,00 EUR',
        'de' => '153,00 EUR',
      ),
      'heizkosten' => array(
        'value' => 126.63,
        'en' => '126,63 EUR',
        'de' => '126,63 EUR',
      ),
      'nebenkosten_inkl_heizkosten' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'sonderangebot' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'verhandelbar' => array(
        'value' => 'GERING',
        'en' => 'slightly',
        'de' => 'geringfügig',
      ),
      'hausgeld' => array(
        'value' => 117.98,
        'en' => 'approximately 117,98 EUR',
        'de' => 'ca. 117,98 EUR',
      ),
      'stellplatz_miete' => array(
        'value' => 14.87,
        'en' => '14,87 EUR',
        'de' => '14,87 EUR',
      ),
      'stellplatz_pflicht' => array(
        'value' => 'ENTWEDER_ODER',
        'en' => 'either / or',
        'de' => 'entweder/oder',
      ),
      'kaution' => array(
        'en' => '1 monthly rent',
        'de' => '1 Monatsmiete',
      ),
      'kaution_betrag' => array(
        'value' => 641.87,
        'en' => '641,87 EUR',
        'de' => '641,87 EUR',
      ),
      'courtage_aussen' => array(
        'en' => '3 monthly rents',
        'de' => '3 Monatsmiete',
      ),
      'courtage_aussen_tax' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
    ),
    'flaechen' => array(
      'bruttoflaeche' => array(
        'value' => 123.42,
        'en' => 'approximately 123,42 m²',
        'de' => 'ca. 123,42 m²',
      ),
      'wohnflaeche' => array(
        'value' => 142.82,
        'en' => 'approximately 142,82 m²',
        'de' => 'ca. 142,82 m²',
      ),
      'kuechenflaeche' => array(
        'value' => 13.5,
        'en' => 'approximately 13,5 m²',
        'de' => 'ca. 13,5 m²',
      ),
      'flurflaeche' => array(
        'value' => 8.97,
        'en' => 'approximately 8,97 m²',
        'de' => 'ca. 8,97 m²',
      ),
      'anz_zimmer' => array(
        'value' => 3.0,
        'en' => '3',
        'de' => '3',
      ),
      'anz_badezimmer' => array(
        'value' => 1,
        'en' => '1',
        'de' => '1',
      ),
      'anz_wohnzimmer' => array(
        'value' => 1,
        'en' => '1',
        'de' => '1',
      ),
      'anz_schlafzimmer' => array(
        'value' => 1,
        'en' => '1',
        'de' => '1',
      ),
      'anz_wohn_schlafzimmmer' => array(
        'value' => 1,
        'en' => '1',
        'de' => '1',
      ),
      'anz_sep_wc' => array(
        'value' => 2,
        'en' => '2',
        'de' => '2',
      ),
      'anz_balkon_terrassen' => array(
        'value' => 3,
        'en' => '3',
        'de' => '3',
      ),
      'balkonflaeche' => array(
        'value' => 21.9,
        'en' => 'approximately 21,9 m²',
        'de' => 'ca. 21,9 m²',
      ),
      'gartenflaeche' => array(
        'value' => 41.76,
        'en' => 'approximately 41,76 m²',
        'de' => 'ca. 41,76 m²',
      ),
      'anz_gaestezimmer' => array(
        'value' => 1,
        'en' => '1',
        'de' => '1',
      ),
      'anz_stellplaetze' => array(
        'value' => 1,
        'en' => '1',
        'de' => '1',
      ),
      'stellplatzflaeche' => array(
        'value' => 8.75,
        'en' => 'approximately 8,75 m²',
        'de' => 'ca. 8,75 m²',
      ),
      'stellplatzart' => array(
        'value' => array('aussen'),
        'en' => 'outdoor car parking space',
        'de' => 'Außenstellplatz',
      ),
      'nutzflaeche' => array(
        'value' => 115.977,
        'en' => 'approximately 115,98 m²',
        'de' => 'ca. 115,98 m²',
      ),
      'kellerflaeche' => array(
        'value' => 15.76,
        'en' => 'approximately 15,76 m²',
        'de' => 'ca. 15,76 m²',
      ),
      'dachbodenflaeche' => array(
        'value' => 14.76,
        'en' => 'approximately 14,76 m²',
        'de' => 'ca. 14,76 m²',
      ),
    ),
    'ausstattung' => array(
      'ausstattung_art' => array(
        'value' => 'LUXUS',
        'en' => 'luxury',
        'de' => 'Luxus',
      ),
      'etage_gesamt' => array(
        'value' => 6,
        'en' => '6',
        'de' => '6',
      ),
      'heizungsart' => array(
        'value' => array('etage', 'fussboden'),
        'en' => 'self-contained central heating, underfloor heating',
        'de' => 'Etagenheizung, Fußbodenheizung',
      ),
      'befeuerung' => array(
        'value' => array('erdwaerme', 'fernwaerme'),
        'en' => 'geothermics, district heating',
        'de' => 'Erdwärme, Fernwärme',
      ),
      'bodenbelag' => array(
        'value' => array('dielen_geschliffen', 'teppich'),
        'en' => 'floor boards (polished), carpet',
        'de' => 'Dielen (abgeschliffen), Teppich',
      ),
      'kueche' => array(
        'value' => array('offene_kueche'),
        'en' => 'american style kitchen',
        'de' => 'offene Küche',
      ),
      'bad' => array(
        'value' => array('anschluss_waschmaschine', 'fenster', 'wanne'),
        'en' => 'with washing machine connection, with window, with bathtub',
        'de' => 'mit Anschluss für Waschmaschinen, mit Fenster, mit Badewanne',
      ),
      'raeume' => array(
        'value' => array('gaeste_wc', 'wasch_trockenraum'),
        'en' => 'guest toilet, washing / drying room',
        'de' => 'Gäste-WC, Wasch-/ Trockenraum',
      ),
      'serviceleistungen' => array(
        'value' => array('hausmeister', 'reinigung', 'wachdienst'),
        'en' => 'Facility manager, cleaning, security firm',
        'de' => 'Hausmeister, Reinigung, Wachdienst',
      ),
      'sicherheitstechnik' => array(
        'value' => array('alarmanlage', 'kamera'),
        'en' => 'alarm system, camera',
        'de' => 'Alarmanlage, Kamera',
      ),
      'eignung' => array(
        'value' => array('rollstuhl'),
        'en' => 'wheelchair access',
        'de' => 'für Rollstuhl geeignet',
      ),
      'barrierefrei' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'pers_lift' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'keller' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'gartennutzung' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'wintergarten' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'balkon_terrasse' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'balkon_terrasse_ausrichtung' => array(
        'value' => array('s'),
        'en' => 'south',
        'de' => 'Süd',
      ),
      'technik' => array(
        'value' => array('dvbt_empfang', 'dv_verkabelung', 'sat_tv'),
        'en' => 'DVBT reception, DV cabling, satellite TV',
        'de' => 'DVBT-Empfang, DV-Verkabelung, Satelliten-TV',
      ),
      'breitband' => array(
        'en' => 'available',
        'de' => 'verfügbar',
      ),
      'breitband_geschwindigkeit' => array(
        'value' => 10000,
        'en' => '10.000',
        'de' => '10.000',
      ),
      'verglasung' => array(
        'value' => array('doppelt', 'sonnenschutz'),
        'en' => 'double-glazed, sun protection',
        'de' => 'doppelt verglast, Sonnenschutz',
      ),
      'moebliert' => array(
        'value' => 'TEIL',
        'en' => 'partial',
        'de' => 'teil',
      ),
      'klimatisiert' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'kamin' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'kamin_art' => array(
        'value' => array('kachelofen'),
        'en' => 'tiled stove',
        'de' => 'Kachelofen',
      ),
      'fenster' => array(
        'value' => array('alu', 'kunststoff'),
        'en' => 'aluminum, synthetic material',
        'de' => 'Aluminium, Kunststoff',
      ),
      'rolladen' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'rolladen_art' => array(
        'value' => array('alu'),
        'en' => 'aluminum',
        'de' => 'Aluminium',
      ),
      'fensterladen' => array(
        'value' => array('alu'),
        'en' => 'aluminum',
        'de' => 'Aluminium',
      ),
      'tueren_innen' => array(
        'value' => array('glasfuellung', 'holz'),
        'en' => 'glass panel, wood',
        'de' => 'Glasfüllung, Holz',
      ),
      'sauna' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'swimmingpool' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
    ),
    'zustand' => array(
      'zustand' => array(
        'value' => 'GEPFLEGT',
        'en' => 'well tended',
        'de' => 'gepflegt',
      ),
      'renovierung_prozent' => array(
        'value' => 85,
        'en' => '85',
        'de' => '85',
      ),
      'sanierung_prozent' => array(
        'value' => 100,
        'en' => '100',
        'de' => '100',
      ),
      'alter' => array(
        'value' => 'NEUBAU',
        'en' => 'new building',
        'de' => 'Neubau',
      ),
      'baujahr' => array(
        'value' => 1989,
        'en' => '1989',
        'de' => '1989',
      ),
      'sanierung' => array(
        'value' => 2009,
        'en' => '2009',
        'de' => '2009',
      ),
      'bauphase' => array(
        'value' => 'ABGESCHLOSSEN',
        'en' => 'construction completed',
        'de' => 'Bau abgeschlossen',
      ),
    ),
    'umfeld' => array(
      'lage_gebiet' => array(
        'value' => 'STADTRAND',
        'en' => 'suburbs',
        'de' => 'Stadtrand',
      ),
      'umfeld_von' => array(
        'en' => 'Berlin',
        'de' => 'Berlin',
      ),
      'distanz_bus' => array(
        'value' => 3.0,
        'en' => '3',
        'de' => '3',
      ),
      'distanz_bhf' => array(
        'value' => 2.0,
        'en' => '2',
        'de' => '2',
      ),
      'distanz_nahbhf' => array(
        'value' => 5.0,
        'en' => '5',
        'de' => '5',
      ),
      'distanz_flug' => array(
        'value' => 3.87,
        'en' => '3,87',
        'de' => '3,87',
      ),
      'distanz_autobahn' => array(
        'value' => 3.73,
        'en' => '3,73',
        'de' => '3,73',
      ),
      'distanz_zentrum' => array(
        'value' => 1.0,
        'en' => '1',
        'de' => '1',
      ),
      'distanz_kita' => array(
        'value' => 4.0,
        'en' => '4',
        'de' => '4',
      ),
      'distanz_grundschule' => array(
        'value' => 6.0,
        'en' => '6',
        'de' => '6',
      ),
      'distanz_gesamtschule' => array(
        'value' => 7.98,
        'en' => '7,98',
        'de' => '7,98',
      ),
      'distanz_hauptschule' => array(
        'value' => 4.7,
        'en' => '4,7',
        'de' => '4,7',
      ),
      'distanz_realschule' => array(
        'value' => 5.88,
        'en' => '5,88',
        'de' => '5,88',
      ),
      'distanz_gymnasium' => array(
        'value' => 5.3,
        'en' => '5,3',
        'de' => '5,3',
      ),
      'distanz_hochschule' => array(
        'value' => 1.0,
        'en' => '1',
        'de' => '1',
      ),
      'distanz_universitaet' => array(
        'value' => 4.0,
        'en' => '4',
        'de' => '4',
      ),
      'distanz_meer' => array(
        'value' => 5.0,
        'en' => '5',
        'de' => '5',
      ),
      'distanz_strand' => array(
        'value' => 7.0,
        'en' => '7',
        'de' => '7',
      ),
      'distanz_see' => array(
        'value' => 15.87,
        'en' => '15,87',
        'de' => '15,87',
      ),
      'distanz_naherhohlung' => array(
        'value' => 5.77,
        'en' => '5,77',
        'de' => '5,77',
      ),
      'distanz_wandergebiet' => array(
        'value' => 6.95,
        'en' => '6,95',
        'de' => '6,95',
      ),
      'distanz_skigebiet' => array(
        'value' => 98.76,
        'en' => '98,76',
        'de' => '98,76',
      ),
      'distanz_sportanlagen' => array(
        'value' => 5.56,
        'en' => '5,56',
        'de' => '5,56',
      ),
      'lage' => array(
        'value' => array('unverbaubar'),
        'en' => 'unobstructable',
        'de' => 'unverbaubar',
      ),
      'ausblick' => array(
        'value' => array('fernblick'),
        'en' => 'distant view',
        'de' => 'Fernblick',
      ),
      'lage_hoehenmeter' => array(
        'value' => 53.88,
        'en' => 'approximately 53,88 m',
        'de' => 'ca. 53,88 m',
      ),
    ),
    'energiepass' => array(
      'vorhanden' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'klasse' => array(
        'value' => 'B',
        'en' => 'B',
        'de' => 'B',
      ),
      'erstellung_datum' => array(
        'value' => 1401573600,
        'en' => '01.06.2014',
        'de' => '01.06.2014',
      ),
      'ablauf_datum' => array(
        'value' => 1435615200,
        'en' => '30.06.2015',
        'de' => '30.06.2015',
      ),
      'art' => array(
        'value' => 'VERBRAUCH',
        'en' => 'by consumption',
        'de' => 'nach Verbrauch',
      ),
      'verbrauch_gesamt' => array(
        'value' => 123.65,
        'en' => '123,65',
        'de' => '123,65',
      ),
      'verbrauch_strom' => array(
        'value' => 100.98,
        'en' => '100,98',
        'de' => '100,98',
      ),
      'verbrauch_heizung' => array(
        'value' => 12.87,
        'en' => '12,87',
        'de' => '12,87',
      ),
      'verbrauch_inkl_warmwasser' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
    ),
    'verwaltung' => array(
      'nutzungsart' => array(
        'value' => array('freizeit', 'wohnen'),
        'en' => 'leisure, residence',
        'de' => 'Freizeit, Wohnen',
      ),
      'stand_vom' => array(
        'value' => 1404079200,
        'en' => '30.06.2014',
        'de' => '30.06.2014',
      ),
      'verfuegbar_abdate' => array(
        'value' => 1401573600,
        'en' => 'from now on',
        'de' => 'ab sofort',
      ),
      'verfuegbar_bisdate' => array(
        'value' => 1414710000,
        'en' => '31.10.2014',
        'de' => '31.10.2014',
      ),
      'weitergabe_generell' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'haustiere' => array(
        'value' => 'VEREINBARUNG',
        'en' => 'by appointment',
        'de' => 'nach Vereinbarung',
      ),
      'gewerbliche_nutzung' => array(
        'value' => 'TEIL',
        'en' => 'partially',
        'de' => 'teilweise',
      ),
      'als_ferien' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'hochhaus' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'denkmalgeschuetzt' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'wbs_sozialwohnung' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'raeume_veraenderbar' => array(
        'value' => 'TEIL',
        'en' => 'partially',
        'de' => 'teilweise',
      ),
      'lage_im_bau' => array(
        'value' => 'VORDERHAUS',
        'en' => 'front building',
        'de' => 'Vorderhaus',
      ),
    ),
  ),
  'images' => array(
    array(
      'name' => 'img_0.jpg',
      'thumb' => 'img_0.thumb.jpg',
      'type' => 'image',
      'mimetype' => 'image/jpeg',
      'title' => array(
        'en' => 'first image',
        'de' => 'erstes Bild',
      ),
    ),
    array(
      'name' => 'img_1.jpg',
      'thumb' => 'img_1.thumb.jpg',
      'type' => 'image_groundplan',
      'mimetype' => 'image/jpeg',
      'title' => array(
        'en' => 'second image',
        'de' => 'zweites Bild',
      ),
    ),
    array(
      'name' => 'img_2.jpg',
      'thumb' => 'img_2.thumb.jpg',
      'type' => 'image_inner_view',
      'mimetype' => 'image/jpeg',
      'title' => array(
        'en' => 'third image',
        'de' => 'drittes Bild',
      ),
    ),
  ),
  'media' => array(
  ),
  'other' => array(
    'immobiliare' => array(
    ),
  ),
);
