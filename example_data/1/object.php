<?php
/*
 * PHP-Export scripts of OpenEstate-ImmoTool
 * Copyright (C) 2009-2018 OpenEstate.org
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
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

return array(
  'id' => '1',
  'status' => 'published',
  'action' => 'rent',
  'type' => 'apartment',
  'type_path' => array('general_residence', 'residence', 'apartment'),
  'currency' => 'EUR',
  'nr' => 'A123',
  'hidden_price' => false,
  'group_nr' => '0',
  'created_at' => 1536607716,
  'modified_at' => 1536626514,
  'mail' => 'noreply@openestate.org',
  'title' => array(
    'de' => 'Eine beispielhafte Wohnung 1',
    'en' => 'An example flat 1',
  ),
  'address' => array(
    'country' => 'DE',
    'country_name' => array(
      'de' => 'Deutschland',
      'en' => 'Germany',
    ),
    'postal' => '12345',
    'city' => 'Berlin',
    'city_part' => 'Mitte',
    'street' => 'Beispielstraße',
    'street_nr' => '123',
    'region' => 'Berlin',
    'latitude' => 52.520608060019,
    'longitude' => 13.400573730469,
  ),
  'contact' => array(
    'country' => 'DE',
    'country_name' => array(
      'de' => 'Deutschland',
      'en' => 'Germany',
    ),
    'postal' => '12345',
    'city' => 'Berlin',
    'city_part' => 'Mitte',
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
    'person_mail' => 'noreply@openestate.org',
    'person_phone' => '030/123456789',
    'person_mobile' => null,
    'person_fax' => null,
    'person_gender' => 'MALE',
  ),
  'attributes' => array(
    'prices' => array(
      'rent_excluding_service_charges' => array(
        'value' => 847.00,
        'de' => '847,00 EUR',
        'en' => '847.00 EUR',
      ),
      'basic_rent' => array(
        'value' => 400.00,
        'de' => '400,00 EUR',
        'en' => '400.00 EUR',
      ),
      'rent_including_service_charges' => array(
        'value' => 750.00,
        'de' => '750,00 EUR',
        'en' => '750.00 EUR',
      ),
      'service_charges' => array(
        'value' => 450.00,
        'de' => '450,00 EUR',
        'en' => '450.00 EUR',
      ),
      'heating_costs' => array(
        'value' => 150.00,
        'de' => '150,00 EUR',
        'en' => '150.00 EUR',
      ),
      'service_charges_including_heating_costs' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'deposit' => array(
        'de' => '2 Monatsmieten',
        'en' => '2 monthly rents',
        'de' => '2 Monatsmieten',
        'en' => '2 monthly rents',
      ),
      'agent_fee' => array(
        'de' => '3 Monatsmieten',
        'en' => '3 monthly rents',
        'de' => '3 Monatsmieten',
        'en' => '3 monthly rents',
      ),
      'agent_fee_including_vat' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'agent_fee_required' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
    ),
    'measures' => array(
      'residential_area' => array(
        'value' => 89.00,
        'unit' => 'SQM',
        'de' => 'ca. 89 m²',
        'en' => 'approx. 89 m²',
      ),
      'kitchen_area' => array(
        'value' => 10.00,
        'unit' => 'SQM',
        'de' => 'ca. 10 m²',
        'en' => 'approx. 10 m²',
      ),
      'count_rooms' => array(
        'value' => 2.00,
        'de' => '2',
        'en' => '2',
      ),
      'count_bathrooms' => array(
        'value' => 1,
        'de' => '1',
        'en' => '1',
      ),
      'count_livingrooms' => array(
        'value' => 1,
        'de' => '1',
        'en' => '1',
      ),
      'count_bedrooms' => array(
        'value' => 1,
        'de' => '1',
        'en' => '1',
      ),
      'count_living_and_bedrooms' => array(
        'value' => 1,
        'de' => '1',
        'en' => '1',
      ),
      'count_separate_toilets' => array(
        'value' => 0,
        'de' => '0',
        'en' => '0',
      ),
      'count_balconies' => array(
        'value' => 1,
        'de' => '1',
        'en' => '1',
      ),
      'balcony_area' => array(
        'value' => 10.00,
        'unit' => 'SQM',
        'de' => 'ca. 10 m²',
        'en' => 'approx. 10 m²',
      ),
      'count_terraces' => array(
        'value' => 0,
        'de' => '0',
        'en' => '0',
      ),
      'count_loggia' => array(
        'value' => 0,
        'de' => '0',
        'en' => '0',
      ),
      'count_guestrooms' => array(
        'value' => 0,
        'de' => '0',
        'en' => '0',
      ),
      'count_parking_spaces' => array(
        'value' => 1,
        'de' => '1',
        'en' => '1',
      ),
      'car_parking_area' => array(
        'value' => 12.00,
        'unit' => 'SQM',
        'de' => 'ca. 12 m²',
        'en' => 'approx. 12 m²',
      ),
      'car_parking_type' => array(
        'value' => array('at_underground_car_park'),
        'de' => 'in Tiefgarage',
        'en' => 'in underground parking',
      ),
      'basement_area' => array(
        'value' => 15.00,
        'unit' => 'SQM',
        'de' => 'ca. 15 m²',
        'en' => 'approx. 15 m²',
      ),
    ),
    'features' => array(
      'equipment' => array(
        'value' => 'STANDARD',
        'de' => 'normal',
        'en' => 'standard',
      ),
      'floor' => array(
        'de' => '5',
        'en' => '5',
        'de' => '5',
        'en' => '5',
      ),
      'count_floors' => array(
        'value' => 6,
        'de' => '6',
        'en' => '6',
      ),
      'type_of_heating' => array(
        'value' => array('central'),
        'de' => 'Zentralheizung',
        'en' => 'central heating system',
      ),
      'type_of_beaconing' => array(
        'value' => array('gas'),
        'de' => 'Gas',
        'en' => 'gas',
      ),
      'flooring_material' => array(
        'value' => array('tiles', 'parquet'),
        'de' => 'Fliesen, Parkett',
        'en' => 'parquet, tiles',
      ),
      'kitchen' => array(
        'value' => array('fitted_kitchen', 'open_kitchen'),
        'de' => 'Einbauküche, offene Küche',
        'en' => 'american style kitchen, fitted kitchen',
      ),
      'bathroom' => array(
        'value' => array('shower', 'bathtub'),
        'de' => 'mit Badewanne, mit Dusche',
        'en' => 'with bathtub, with shower',
      ),
      'residential_rooms' => array(
        'value' => array('storage_room'),
        'de' => 'Abstellraum',
        'en' => 'storage room',
      ),
      'security_technology' => array(
        'value' => array('alarm_system'),
        'de' => 'Alarmanlage',
        'en' => 'alarm system',
      ),
      'applicability' => array(
        'value' => array('seniors'),
        'de' => 'für Senioren geeignet',
        'en' => 'suitable for the elterly',
      ),
      'barrier_free' => array(
        'value' => false,
        'de' => 'nein',
        'en' => 'no',
      ),
      'lift' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'basement' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'garden_use' => array(
        'value' => false,
        'de' => 'nein',
        'en' => 'no',
      ),
      'conservatory' => array(
        'value' => false,
        'de' => 'nein',
        'en' => 'no',
      ),
      'balcony_terrace' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'balcony_terrace_direction' => array(
        'value' => array('s'),
        'de' => 'Süd',
        'en' => 'south',
      ),
      'technics' => array(
        'value' => array('cable_tv', 'dv_cabling', 'dvbt_reception', 'umts_reception'),
        'de' => 'DV-Verkabelung, DVBT-Empfang, Kabel-TV, UMTS-Empfang',
        'en' => 'DV cabling, DVBT reception, UMTS reception, cable TV',
      ),
      'broadband_internet' => array(
        'de' => 'DSL 50000',
        'en' => 'DSL 50000',
        'de' => 'DSL 50000',
        'en' => 'DSL 50000',
      ),
      'broadband_internet_speed' => array(
        'value' => 50000,
        'de' => '50.000',
        'en' => '50,000',
      ),
      'glazing' => array(
        'value' => array('noise_insulation'),
        'de' => 'Schallschutz',
        'en' => 'noise insulation',
      ),
      'furnished' => array(
        'value' => 'PARTIAL',
        'de' => 'teilweise',
        'en' => 'partially',
      ),
      'air_conditioned' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'chimney_port' => array(
        'value' => false,
        'de' => 'nein',
        'en' => 'no',
      ),
      'window' => array(
        'value' => array('aluminium'),
        'de' => 'Aluminium',
        'en' => 'aluminum',
      ),
      'shutters_interior' => array(
        'value' => false,
        'de' => 'nein',
        'en' => 'no',
      ),
      'doors_inside' => array(
        'value' => array('wood'),
        'de' => 'Holz',
        'en' => 'wood',
      ),
      'sauna' => array(
        'value' => false,
        'de' => 'nein',
        'en' => 'no',
      ),
      'swimming_pool' => array(
        'value' => false,
        'de' => 'nein',
        'en' => 'no',
      ),
    ),
    'condition' => array(
      'build_year' => array(
        'value' => 2005,
        'de' => '2005',
        'en' => '2005',
      ),
      'age' => array(
        'value' => 'NEW_BUILDING',
        'de' => 'Neubau',
        'en' => 'new building',
      ),
      'condition_type' => array(
        'value' => 'FIRST_OCCUPANCY_AFTER_REFURBISHMENT',
        'de' => 'Erstbezug nach Sanierung',
        'en' => 'first occupancy after refurbishment',
      ),
      'construction_phase' => array(
        'value' => 'COMPLETED',
        'de' => 'Bau abgeschlossen',
        'en' => 'construction completed',
      ),
    ),
    'surroundings' => array(
      'next_town' => array(
        'de' => 'Potsdam',
        'en' => 'Potsdam',
        'de' => 'Potsdam',
        'en' => 'Potsdam',
      ),
      'zone' => array(
        'value' => 'MIXED',
        'de' => 'gemischt',
        'en' => 'mixed',
      ),
      'location' => array(
        'value' => array('unobstructable'),
        'de' => 'unverbaubar',
        'en' => 'unobstructable',
      ),
      'view' => array(
        'value' => array('distant_view'),
        'de' => 'Fernblick',
        'en' => 'distant view',
      ),
      'distance_to_bus_station' => array(
        'value' => 500.00,
        'unit' => 'M',
        'de' => 'ca. 500 m',
        'en' => 'approx. 500 m',
      ),
      'distance_to_airport' => array(
        'value' => 10.00,
        'unit' => 'KM',
        'de' => 'ca. 10 km',
        'en' => 'approx. 10 km',
      ),
      'distance_to_city_centre' => array(
        'value' => 5.00,
        'unit' => 'KM',
        'de' => 'ca. 5 km',
        'en' => 'approx. 5 km',
      ),
      'distance_to_sports_facilities' => array(
        'value' => 1500.00,
        'unit' => 'M',
        'de' => 'ca. 1.500 m',
        'en' => 'approx. 1,500 m',
      ),
    ),
    'administration' => array(
      'usage' => array(
        'value' => array('residential'),
        'de' => 'Wohnen',
        'en' => 'residence',
      ),
      'commercial_use' => array(
        'value' => 'PARTIALLY',
        'de' => 'teilweise',
        'en' => 'partially',
      ),
      'pets' => array(
        'value' => 'AS_APPOINTED',
        'de' => 'nach Vereinbarung',
        'en' => 'by appointment',
      ),
      'rooms_modifiable' => array(
        'value' => 'NO',
        'de' => 'nein',
        'en' => 'no',
      ),
      'location_in_building' => array(
        'value' => 'FRONT_BUILDING',
        'de' => 'Vorderhaus',
        'en' => 'front building',
      ),
      'skyscraper' => array(
        'value' => false,
        'de' => 'nein',
        'en' => 'no',
      ),
      'holiday_property' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'monumental_protection' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
    ),
    'energy_certificate' => array(
      'available' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'efficiency_category' => array(
        'value' => 'A',
        'de' => 'A',
        'en' => 'A',
      ),
      'creation_date' => array(
        'value' => 1527804000,
        'de' => '01.06.2018',
        'en' => 'Jun 1, 2018',
      ),
      'expiration_date' => array(
        'value' => 1596146400,
        'de' => '31.07.2020',
        'en' => 'Jul 31, 2020',
      ),
      'type' => array(
        'value' => 'DEMAND',
        'de' => 'nach Bedarf',
        'en' => 'by demand',
      ),
      'demand_total' => array(
        'value' => 500.00,
        'unit' => 'KWH_PER_SQM_AND_YEAR',
        'de' => 'ca. 500 kWh/(m²a)',
        'en' => 'approx. 500 kWh/(m²a)',
      ),
      'demand_electricity' => array(
        'value' => 250.00,
        'unit' => 'KWH_PER_SQM_AND_YEAR',
        'de' => 'ca. 250 kWh/(m²a)',
        'en' => 'approx. 250 kWh/(m²a)',
      ),
      'demand_heating' => array(
        'value' => 250.00,
        'unit' => 'KWH_PER_SQM_AND_YEAR',
        'de' => 'ca. 250 kWh/(m²a)',
        'en' => 'approx. 250 kWh/(m²a)',
      ),
    ),
  ),
  'images' => array(
    array(
      'name' => 'b0bc1ede6406553b7216a60654f7206e9a56ddba.jpg',
      'thumb' => 'b0bc1ede6406553b7216a60654f7206e9a56ddba.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_outer_view',
      'title' => array(
        'de' => 'Gebäude',
        'en' => 'Building',
      ),
    ),
    array(
      'name' => '767adb0e31b08b46b1289d2973fc99f3317f646b.jpg',
      'thumb' => '767adb0e31b08b46b1289d2973fc99f3317f646b.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_inner_view',
      'title' => array(
        'de' => 'Wohnzimmer',
        'en' => 'Lounge',
      ),
    ),
    array(
      'name' => 'c922bce61cdf19bf8854f6c06b6db21ce90a2a24.jpg',
      'thumb' => 'c922bce61cdf19bf8854f6c06b6db21ce90a2a24.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_inner_view',
      'title' => array(
        'de' => 'Wohnzimmer',
        'en' => 'Lounge',
      ),
    ),
    array(
      'name' => '41814d7db38b1fa093f9b776833656365f67796b.jpg',
      'thumb' => '41814d7db38b1fa093f9b776833656365f67796b.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_inner_view',
      'title' => array(
        'de' => 'Wohnzimmer',
        'en' => 'Lounge',
      ),
    ),
    array(
      'name' => '874732f2ad095dc1775ee7d3a5230d3c355bf56b.jpg',
      'thumb' => '874732f2ad095dc1775ee7d3a5230d3c355bf56b.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_inner_view',
      'title' => array(
        'de' => 'Schlafzimmer',
        'en' => 'Bedroom',
      ),
    ),
    array(
      'name' => '1b3b7710284e29fc79fb7eaead054654f8b32d45.jpg',
      'thumb' => '1b3b7710284e29fc79fb7eaead054654f8b32d45.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_inner_view',
      'title' => array(
        'de' => 'Schlafzimmer',
        'en' => 'Bedroom',
      ),
    ),
    array(
      'name' => '23df26da43a17958bebfd0ef9c126271aa5c6dc6.jpg',
      'thumb' => '23df26da43a17958bebfd0ef9c126271aa5c6dc6.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_inner_view',
      'title' => array(
        'de' => 'Esszimmer',
        'en' => 'Dining room',
      ),
    ),
    array(
      'name' => '3a29f4b339b81e86732727819504f4a18847d130.jpg',
      'thumb' => '3a29f4b339b81e86732727819504f4a18847d130.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_inner_view',
      'title' => array(
        'de' => 'Küche',
        'en' => 'Kitchen',
      ),
    ),
    array(
      'name' => 'e794f5c22bb0c50ddbaafef97577cdf9280ddf36.jpg',
      'thumb' => 'e794f5c22bb0c50ddbaafef97577cdf9280ddf36.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_groundplan',
      'title' => array(
        'de' => 'Grundriss',
        'en' => 'Ground plan',
      ),
    ),
  ),
  'media' => array(
    array(
      'name' => 'bfd009f500c057195ffde66fae64f92fa5f59b72.pdf',
      'mimetype' => 'application/pdf',
      'type' => 'image_groundplan',
      'title' => array(
        'de' => 'Beispiel einer PDF-Datei im Anhang',
        'en' => 'An example PDF attachment',
      ),
    ),
    array(
      'name' => '1443f857128cbc546e7ac45fa20cc3593e56e339.m4v',
      'mimetype' => 'video/mp4',
      'type' => null,
      'title' => array(
        'de' => 'Beispiel einer Video-Datei im Anhang',
        'en' => 'An example video attachment',
      ),
    ),
    array(
      'name' => '43a2cd33c498456f757a943c074b52f44d38d2d0.ogv',
      'mimetype' => 'video/ogg',
      'type' => null,
      'title' => array(
        'de' => 'Beispiel einer Video-Datei im Anhang',
        'en' => 'An example video attachment',
      ),
    ),
  ),
  'links' => array(
    array(
      'id' => 'FyWfUEGyrdk',
      'provider' => 'video@youtube.com',
      'url' => 'https://www.youtube.com/watch?v=FyWfUEGyrdk',
      'title' => array(
        'de' => 'Beispiel-Video von YouTube',
        'en' => 'Example video at YouTube',
      ),
    ),
    array(
      'id' => 's13dLaTIHSg',
      'provider' => 'video@youtube.com',
      'url' => 'https://www.youtube.com/watch?v=s13dLaTIHSg',
      'title' => array(
        'de' => 'Weiteres Beispiel-Video von YouTube',
        'en' => 'Another example video at YouTube',
      ),
    ),
    array(
      'url' => 'https://openestate.org',
      'title' => array(
        'de' => 'Beispiel eines externen Links',
        'en' => 'Example of an external link',
      ),
    ),
  ),
  'other' => array(
  ),
);
