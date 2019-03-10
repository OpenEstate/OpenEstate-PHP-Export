<?php
/*
 * Copyright 2009-2019 OpenEstate.org.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Website-Export, Inserat #13, Objektdaten.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

return array(
  'id' => '13',
  'status' => 'published',
  'action' => 'purchase',
  'type' => 'single_family_house',
  'type_path' => array('general_residence', 'house', 'single_family_house'),
  'currency' => 'EUR',
  'nr' => 'B782',
  'hidden_price' => false,
  'group_nr' => '0',
  'created_at' => 1536626260,
  'modified_at' => 1536626844,
  'mail' => 'test@test.de',
  'title' => array(
    'de' => 'Ein beispielhaftes Haus 8',
    'en' => 'An example house 8',
  ),
  'address' => array(
    'country' => 'DE',
    'country_name' => array(
      'de' => 'Deutschland',
      'en' => 'Germany',
    ),
    'postal' => '12345',
    'city' => 'Berlin',
    'city_part' => 'Reinickendorf',
    'street' => 'Beispeilstraße',
    'street_nr' => '654',
    'region' => null,
    'latitude' => 52.527292468827,
    'longitude' => 13.321952819824,
  ),
  'contact' => array(
    'country' => 'DE',
    'country_name' => array(
      'de' => 'Deutschland',
      'en' => 'Germany',
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
    'person_mail' => 'test@test.de',
    'person_phone' => '030/123456',
    'person_mobile' => null,
    'person_fax' => null,
    'person_gender' => null,
    'company_name' => 'Mustermann Immobilien GmbH',
    'company_name_addition' => null,
    'company_type' => 'GmbH',
    'company_business' => null,
    'company_department' => null,
    'company_position' => null,
    'company_mail' => 'test@test.de',
    'company_phone' => '030/123456',
    'company_mobile' => null,
    'company_fax' => null,
    'company_website' => null,
  ),
  'attributes' => array(
    'prices' => array(
      'purchase_price' => array(
        'value' => 413316.00,
        'de' => '413.316,00 EUR',
        'en' => '413,316.00 EUR',
      ),
      'heating_costs' => array(
        'value' => 75.00,
        'de' => '75,00 EUR',
        'en' => '75.00 EUR',
      ),
      'negotiable' => array(
        'value' => 'SLIGHTLY',
        'de' => 'geringfügig',
        'en' => 'slightly',
      ),
      'agent_fee' => array(
        'de' => '3% des Kaufpreises',
        'en' => '3% of the purchase price',
        'de' => '3% des Kaufpreises',
        'en' => '3% of the purchase price',
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
        'value' => 214.23,
        'unit' => 'SQM',
        'de' => 'ca. 214,23 m²',
        'en' => 'approx. 214.23 m²',
      ),
      'kitchen_area' => array(
        'value' => 15.00,
        'unit' => 'SQM',
        'de' => 'ca. 15 m²',
        'en' => 'approx. 15 m²',
      ),
      'count_rooms' => array(
        'value' => 5.00,
        'de' => '5',
        'en' => '5',
      ),
      'count_bathrooms' => array(
        'value' => 2,
        'de' => '2',
        'en' => '2',
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
        'value' => 2,
        'de' => '2',
        'en' => '2',
      ),
      'count_separate_toilets' => array(
        'value' => 2,
        'de' => '2',
        'en' => '2',
      ),
      'count_balconies' => array(
        'value' => 1,
        'de' => '1',
        'en' => '1',
      ),
      'balcony_area' => array(
        'value' => 21.00,
        'unit' => 'SQM',
        'de' => 'ca. 21 m²',
        'en' => 'approx. 21 m²',
      ),
      'count_terraces' => array(
        'value' => 1,
        'de' => '1',
        'en' => '1',
      ),
      'plot_area' => array(
        'value' => 597.00,
        'unit' => 'SQM',
        'de' => 'ca. 597 m²',
        'en' => 'approx. 597 m²',
      ),
      'plot_foreside_length' => array(
        'value' => 15.00,
        'unit' => 'M',
        'de' => 'ca. 15 m',
        'en' => 'approx. 15 m',
      ),
      'garden_area' => array(
        'value' => 478.00,
        'unit' => 'SQM',
        'de' => 'ca. 478 m²',
        'en' => 'approx. 478 m²',
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
        'value' => 16.00,
        'unit' => 'SQM',
        'de' => 'ca. 16 m²',
        'en' => 'approx. 16 m²',
      ),
      'car_parking_type' => array(
        'value' => array('carport'),
        'de' => 'Carport',
        'en' => 'carport',
      ),
      'attic_area' => array(
        'value' => 36.00,
        'unit' => 'SQM',
        'de' => 'ca. 36 m²',
        'en' => 'approx. 36 m²',
      ),
    ),
    'features' => array(
      'equipment' => array(
        'value' => 'EXCLUSIVE',
        'de' => 'gehoben',
        'en' => 'exclusive',
      ),
      'count_floors' => array(
        'value' => 2,
        'de' => '2',
        'en' => '2',
      ),
      'type_of_heating' => array(
        'value' => array('central', 'underfloor'),
        'de' => 'Fußbodenheizung, Zentralheizung',
        'en' => 'central heating system, underfloor heating',
      ),
      'type_of_beaconing' => array(
        'value' => array('gas'),
        'de' => 'Gas',
        'en' => 'gas',
      ),
      'flooring_material' => array(
        'value' => array('laminate', 'carpet'),
        'de' => 'Laminat, Teppich',
        'en' => 'carpet, laminate',
      ),
      'kitchen' => array(
        'value' => array('open_kitchen'),
        'de' => 'offene Küche',
        'en' => 'american style kitchen',
      ),
      'bathroom' => array(
        'value' => array('shower', 'bathtub', 'window'),
        'de' => 'mit Badewanne, mit Dusche, mit Fenster',
        'en' => 'with bathtub, with shower, with window',
      ),
      'residential_rooms' => array(
        'value' => array('attic'),
        'de' => 'Dachboden',
        'en' => 'attic',
      ),
      'security_technology' => array(
        'value' => array('alarm_system'),
        'de' => 'Alarmanlage',
        'en' => 'alarm system',
      ),
      'applicability' => array(
        'value' => array('wheelchair'),
        'de' => 'für Rollstuhl geeignet',
        'en' => 'wheelchair access',
      ),
      'barrier_free' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'basement' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'underground_level' => array(
        'value' => 'YES',
        'de' => 'ja',
        'en' => 'yes',
      ),
      'garden_use' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'conservatory' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'balcony_terrace' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'balcony_terrace_direction' => array(
        'value' => array('w'),
        'de' => 'West',
        'en' => 'west',
      ),
      'technics' => array(
        'value' => array('sat_tv', 'dvbt_reception', 'umts_reception'),
        'de' => 'DVBT-Empfang, Satelliten-TV, UMTS-Empfang',
        'en' => 'DVBT reception, UMTS reception, satellite TV',
      ),
      'broadband_internet' => array(
        'de' => 'DSL 10000',
        'en' => 'DSL 10000',
        'de' => 'DSL 10000',
        'en' => 'DSL 10000',
      ),
      'broadband_internet_speed' => array(
        'value' => 10000,
        'de' => '10.000',
        'en' => '10,000',
      ),
      'glazing' => array(
        'value' => array('burglar_proofed', 'double_glazed'),
        'de' => 'doppelt verglast, einbruchsicheres Glas',
        'en' => 'burglar-proofed glass, double-glazed',
      ),
      'furnished' => array(
        'value' => 'NO',
        'de' => 'nein',
        'en' => 'no',
      ),
      'chimney_port' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'fireplace' => array(
        'value' => array('brick_built_oven'),
        'de' => 'gemauerter Ofen',
        'en' => 'brick-built oven',
      ),
      'window' => array(
        'value' => array('wood'),
        'de' => 'Holz',
        'en' => 'wood',
      ),
      'shutters_interior' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'shutters_exterior_type' => array(
        'value' => array('wood'),
        'de' => 'Holz',
        'en' => 'wood',
      ),
      'shutters_interior_type' => array(
        'value' => array('wood'),
        'de' => 'Holz',
        'en' => 'wood',
      ),
      'doors_outside' => array(
        'value' => array('wood', 'glass_panel'),
        'de' => 'Glasfüllung, Holz',
        'en' => 'glass panel, wood',
      ),
      'doors_inside' => array(
        'value' => array('wood'),
        'de' => 'Holz',
        'en' => 'wood',
      ),
      'roof_shape' => array(
        'value' => array('flat'),
        'de' => 'Flachdach',
        'en' => 'flat roof',
      ),
      'sauna' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'swimming_pool' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'stock_of_trees' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'permission_to_cut_down_trees' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
    ),
    'condition' => array(
      'age' => array(
        'value' => 'NEW_BUILDING',
        'de' => 'Neubau',
        'en' => 'new building',
      ),
      'condition_type' => array(
        'value' => 'WELL_TENDED',
        'de' => 'gepflegt',
        'en' => 'well tended',
      ),
    ),
    'surroundings' => array(
      'next_town' => array(
        'de' => 'Berlin',
        'en' => 'Berlin',
        'de' => 'Berlin',
        'en' => 'Berlin',
      ),
      'zone' => array(
        'value' => 'SUBURBS',
        'de' => 'Stadtrand',
        'en' => 'suburbs',
      ),
      'location' => array(
        'value' => array('forestal_area', 'unobstructable'),
        'de' => 'Waldlage, unverbaubar',
        'en' => 'forestal area, unobstructable',
      ),
      'view' => array(
        'value' => array('distant_view'),
        'de' => 'Fernblick',
        'en' => 'distant view',
      ),
      'distance_to_bus_station' => array(
        'value' => 2.00,
        'unit' => 'KM',
        'de' => 'ca. 2 km',
        'en' => 'approx. 2 km',
      ),
      'distance_to_main_line_station' => array(
        'value' => 15.00,
        'unit' => 'KM',
        'de' => 'ca. 15 km',
        'en' => 'approx. 15 km',
      ),
      'distance_to_next_train_station' => array(
        'value' => 5.00,
        'unit' => 'KM',
        'de' => 'ca. 5 km',
        'en' => 'approx. 5 km',
      ),
      'distance_to_airport' => array(
        'value' => 55.00,
        'unit' => 'KM',
        'de' => 'ca. 55 km',
        'en' => 'approx. 55 km',
      ),
      'distance_to_motorway' => array(
        'value' => 7.00,
        'unit' => 'KM',
        'de' => 'ca. 7 km',
        'en' => 'approx. 7 km',
      ),
      'distance_to_city_centre' => array(
        'value' => 45.00,
        'unit' => 'KM',
        'de' => 'ca. 45 km',
        'en' => 'approx. 45 km',
      ),
      'distance_to_high_school' => array(
        'value' => 3.00,
        'unit' => 'KM',
        'de' => 'ca. 3 km',
        'en' => 'approx. 3 km',
      ),
      'distance_to_sports_facilities' => array(
        'value' => 1.50,
        'unit' => 'KM',
        'de' => 'ca. 1,5 km',
        'en' => 'approx. 1.5 km',
      ),
    ),
    'administration' => array(
      'usage' => array(
        'value' => array('investment', 'residential'),
        'de' => 'Anlage / Rendite, Wohnen',
        'en' => 'investment, residence',
      ),
      'rented' => array(
        'value' => false,
        'de' => 'nein',
        'en' => 'no',
      ),
      'rooms_modifiable' => array(
        'value' => 'PARTIALLY',
        'de' => 'teilweise',
        'en' => 'partially',
      ),
      'holiday_property' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'monumental_protection' => array(
        'value' => false,
        'de' => 'nein',
        'en' => 'no',
      ),
    ),
    'energy_certificate' => array(
      'available' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
      'efficiency_category' => array(
        'value' => 'B',
        'de' => 'B',
        'en' => 'B',
      ),
      'creation_date' => array(
        'value' => 1522533600,
        'de' => '01.04.2018',
        'en' => 'Apr 1, 2018',
      ),
      'expiration_date' => array(
        'value' => 1617141600,
        'de' => '31.03.2021',
        'en' => 'Mar 31, 2021',
      ),
      'type' => array(
        'value' => 'CONSUMPTION',
        'de' => 'nach Verbrauch',
        'en' => 'by consumption',
      ),
      'consumption_total' => array(
        'value' => 456.00,
        'unit' => 'KWH_PER_SQM_AND_YEAR',
        'de' => 'ca. 456 kWh/(m²a)',
        'en' => 'approx. 456 kWh/(m²a)',
      ),
      'consumption_electricity' => array(
        'value' => 12.00,
        'unit' => 'KWH_PER_SQM_AND_YEAR',
        'de' => 'ca. 12 kWh/(m²a)',
        'en' => 'approx. 12 kWh/(m²a)',
      ),
      'consumption_heating' => array(
        'value' => 78.00,
        'unit' => 'KWH_PER_SQM_AND_YEAR',
        'de' => 'ca. 78 kWh/(m²a)',
        'en' => 'approx. 78 kWh/(m²a)',
      ),
      'consumption_including_hot_water' => array(
        'value' => true,
        'de' => 'ja',
        'en' => 'yes',
      ),
    ),
  ),
  'images' => array(
    array(
      'name' => 'db307b1e99dbb45eaaefc41d234669467d39e530.jpg',
      'thumb' => 'db307b1e99dbb45eaaefc41d234669467d39e530.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_outer_view',
      'title' => array(
        'de' => null,
        'en' => null,
      ),
    ),
    array(
      'name' => 'aed38bbc3effaf0f41b946b2e6271c56e3d84beb.jpg',
      'thumb' => 'aed38bbc3effaf0f41b946b2e6271c56e3d84beb.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_inner_view',
      'title' => array(
        'de' => null,
        'en' => null,
      ),
    ),
    array(
      'name' => '1a0f9fca65aded74749b1faaa2681165fcc8af97.jpg',
      'thumb' => '1a0f9fca65aded74749b1faaa2681165fcc8af97.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_outer_view',
      'title' => array(
        'de' => null,
        'en' => null,
      ),
    ),
    array(
      'name' => '34e5729aca979321a7e168142d65f3b273bb1d0e.jpg',
      'thumb' => '34e5729aca979321a7e168142d65f3b273bb1d0e.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_outer_view',
      'title' => array(
        'de' => null,
        'en' => null,
      ),
    ),
    array(
      'name' => '0ed84d25cf04cfb1e32619519e7e100ea7bd43be.jpg',
      'thumb' => '0ed84d25cf04cfb1e32619519e7e100ea7bd43be.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_outer_view',
      'title' => array(
        'de' => null,
        'en' => null,
      ),
    ),
    array(
      'name' => '0493d64d91278d3c2fc0d0abb199e89eb750fc2e.jpg',
      'thumb' => '0493d64d91278d3c2fc0d0abb199e89eb750fc2e.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_outer_view',
      'title' => array(
        'de' => null,
        'en' => null,
      ),
    ),
    array(
      'name' => '7b0d289c6e4403209d07b54334b16e422e7aa81c.jpg',
      'thumb' => '7b0d289c6e4403209d07b54334b16e422e7aa81c.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_outer_view',
      'title' => array(
        'de' => null,
        'en' => null,
      ),
    ),
    array(
      'name' => '8b2fa7d6d8970a03889d046e6e5d71f040e2fc64.jpg',
      'thumb' => '8b2fa7d6d8970a03889d046e6e5d71f040e2fc64.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_outer_view',
      'title' => array(
        'de' => null,
        'en' => null,
      ),
    ),
    array(
      'name' => '6d14a65aabc6e7b8e7a4498d239a9adb5e6f0aef.jpg',
      'thumb' => '6d14a65aabc6e7b8e7a4498d239a9adb5e6f0aef.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_outer_view',
      'title' => array(
        'de' => null,
        'en' => null,
      ),
    ),
    array(
      'name' => 'c9a04a007018a8fb09c7a2402db5b542fcf0802e.jpg',
      'thumb' => 'c9a04a007018a8fb09c7a2402db5b542fcf0802e.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_groundplan',
      'title' => array(
        'de' => null,
        'en' => null,
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
  ),
  'links' => array(
    array(
      'id' => '4rb8aOzy9t4',
      'provider' => 'video@youtube.com',
      'url' => 'https://www.youtube.com/watch?v=4rb8aOzy9t4',
      'title' => array(
        'de' => 'Beispiel-Video von YouTube',
        'en' => 'Example video at YouTube',
      ),
    ),
    array(
      'id' => 'SNAZGy7JStQ',
      'provider' => 'video@youtube.com',
      'url' => 'https://www.youtube.com/watch?v=SNAZGy7JStQ',
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
