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
 * Website-Export, Inserat #2, Objektdaten.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE')) {
  exit;
}

$GLOBALS['immotool_objects']['2'] = array(
  'id' => '2',
  'status' => 'published',
  'action' => 'purchase',
  'type' => 'multi_family_house',
  'type_path' => array('general_residence', 'house', 'multi_family_house'),
  'currency' => 'EUR',
  'nr' => null,
  'hidden_price' => false,
  'group_nr' => null,
  'created_at' => 1403417050,
  'modified_at' => 1403417050,
  'mail' => 'info@beispielfirma.de',
  'title' => array(
    'en' => 'an example property',
    'de' => 'eine Beispiel-Immobilie',
  ),
  'address' => array(
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
    'person_mail' => 'max.mustermann@beispielfirma.de',
    'person_phone' => '030/123456',
    'person_mobile' => null,
    'person_fax' => null,
    'person_gender' => 'MALE',
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
    'prices' => array(
      'purchase_price' => array(
        'value' => 763989.0,
        'en' => '763,989.00 EUR',
        'de' => '763.989,00 EUR',
      ),
      'plus_vat' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'vat_rate' => array(
        'value' => 19.0,
        'en' => '19',
        'de' => '19',
      ),
      'vat_value' => array(
        'value' => 163.0,
        'en' => '163.00 EUR',
        'de' => '163,00 EUR',
      ),
      'service_charges' => array(
        'value' => 153.0,
        'en' => '153.00 EUR',
        'de' => '153,00 EUR',
      ),
      'heating_costs' => array(
        'value' => 126.63,
        'en' => '126.63 EUR',
        'de' => '126,63 EUR',
      ),
      'service_charges_including_heating_costs' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'negotiable' => array(
        'value' => 'SLIGHTLY',
        'en' => 'slightly',
        'de' => 'geringfügig',
      ),
      'special_offer' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'car_parking_space_obligation' => array(
        'value' => 'EITHER_OR',
        'en' => 'either / or',
        'de' => 'entweder / oder',
      ),
      'agent_fee' => array(
        'en' => '10% of the purchase price',
        'de' => '10% des Kaufpreises',
        'en' => '10% of the purchase price',
        'de' => '10% des Kaufpreises',
      ),
      'agent_fee_including_vat' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
    ),
    'measures' => array(
      'gross_area' => array(
        'value' => 123.42,
        'unit' => 'SQM',
        'en' => 'approx. 123.42 m²',
        'de' => 'ca. 123,42 m²',
      ),
      'residential_area' => array(
        'value' => 142.82,
        'unit' => 'SQM',
        'en' => 'approx. 142.82 m²',
        'de' => 'ca. 142,82 m²',
      ),
      'kitchen_area' => array(
        'value' => 13.5,
        'unit' => 'SQM',
        'en' => 'approx. 13.5 m²',
        'de' => 'ca. 13,5 m²',
      ),
      'corridor_area' => array(
        'value' => 8.97,
        'unit' => 'SQM',
        'en' => 'approx. 8.97 m²',
        'de' => 'ca. 8,97 m²',
      ),
      'count_residential_units' => array(
        'value' => 15,
        'en' => '15',
        'de' => '15',
      ),
      'count_rooms' => array(
        'value' => 3.0,
        'en' => '3',
        'de' => '3',
      ),
      'count_bathrooms' => array(
        'value' => 1,
        'en' => '1',
        'de' => '1',
      ),
      'count_livingrooms' => array(
        'value' => 1,
        'en' => '1',
        'de' => '1',
      ),
      'count_bedrooms' => array(
        'value' => 1,
        'en' => '1',
        'de' => '1',
      ),
      'count_living_and_bedrooms' => array(
        'value' => 1,
        'en' => '1',
        'de' => '1',
      ),
      'count_separate_toilets' => array(
        'value' => 2,
        'en' => '2',
        'de' => '2',
      ),
      'count_balconies_terraces' => array(
        'value' => 3,
        'en' => '3',
        'de' => '3',
      ),
      'balcony_terrace_area' => array(
        'value' => 21.9,
        'unit' => 'SQM',
        'en' => 'approx. 21.9 m²',
        'de' => 'ca. 21,9 m²',
      ),
      'plot_area' => array(
        'value' => 476.98,
        'unit' => 'SQM',
        'en' => 'approx. 476.98 m²',
        'de' => 'ca. 476,98 m²',
      ),
      'plot_foreside_length' => array(
        'value' => 47.87,
        'unit' => 'KM',
        'en' => 'approx. 47.87 km',
        'de' => 'ca. 47,87 km',
      ),
      'garden_area' => array(
        'value' => 41.76,
        'unit' => 'SQM',
        'en' => 'approx. 41.76 m²',
        'de' => 'ca. 41,76 m²',
      ),
      'count_guestrooms' => array(
        'value' => 1,
        'en' => '1',
        'de' => '1',
      ),
      'count_parking_spaces' => array(
        'value' => 1,
        'en' => '1',
        'de' => '1',
      ),
      'car_parking_area' => array(
        'value' => 8.75,
        'unit' => 'SQM',
        'en' => 'approx. 8.75 m²',
        'de' => 'ca. 8,75 m²',
      ),
      'car_parking_type' => array(
        'value' => array('outdoor'),
        'en' => 'outdoor car parking space',
        'de' => 'Außenstellplatz',
      ),
      'usable_area' => array(
        'value' => 115.98,
        'unit' => 'SQM',
        'en' => 'approx. 115.98 m²',
        'de' => 'ca. 115,98 m²',
      ),
      'basement_area' => array(
        'value' => 15.76,
        'unit' => 'SQM',
        'en' => 'approx. 15.76 m²',
        'de' => 'ca. 15,76 m²',
      ),
      'attic_area' => array(
        'value' => 14.76,
        'unit' => 'SQM',
        'en' => 'approx. 14.76 m²',
        'de' => 'ca. 14,76 m²',
      ),
    ),
    'features' => array(
      'equipment' => array(
        'value' => 'LUXURY',
        'en' => 'luxury',
        'de' => 'Luxus',
      ),
      'count_floors' => array(
        'value' => 6,
        'en' => '6',
        'de' => '6',
      ),
      'type_of_heating' => array(
        'value' => array('self_contained_central', 'underfloor'),
        'en' => 'self-contained central heating, underfloor heating',
        'de' => 'Etagenheizung, Fußbodenheizung',
      ),
      'type_of_beaconing' => array(
        'value' => array('geothermics', 'district_heating'),
        'en' => 'district heating, geothermics',
        'de' => 'Erdwärme, Fernwärme',
      ),
      'flooring_material' => array(
        'value' => array('carpet', 'floor_boards_polished'),
        'en' => 'carpet, floor boards (polished)',
        'de' => 'Dielen (abgeschliffen), Teppich',
      ),
      'kitchen' => array(
        'value' => array('open_kitchen'),
        'en' => 'american style kitchen',
        'de' => 'offene Küche',
      ),
      'bathroom' => array(
        'value' => array('bathtub', 'window', 'washing_machine_connection'),
        'en' => 'with bathtub, with washing machine connection, with window',
        'de' => 'mit Anschluss für Waschmaschinen, mit Badewanne, mit Fenster',
      ),
      'residential_rooms' => array(
        'value' => array('washing_drying_room', 'guest_toilet'),
        'en' => 'guest toilet, washing / drying room',
        'de' => 'Gäste-WC, Wasch-/ Trockenraum',
      ),
      'residential_services' => array(
        'value' => array('cleaning', 'security_firm', 'caretaker'),
        'en' => 'cleaning, facility manager, security firm',
        'de' => 'Hausmeister, Reinigung, Wachdienst',
      ),
      'security_technology' => array(
        'value' => array('alarm_system', 'camera'),
        'en' => 'alarm system, camera',
        'de' => 'Alarmanlage, Kamera',
      ),
      'applicability' => array(
        'value' => array('wheelchair'),
        'en' => 'wheelchair access',
        'de' => 'für Rollstuhl geeignet',
      ),
      'barrier_free' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'lift' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'basement' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'underground_level' => array(
        'value' => 'YES',
        'en' => 'yes',
        'de' => 'ja',
      ),
      'garden_use' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'conservatory' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'balcony_terrace' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'balcony_terrace_direction' => array(
        'value' => array('s'),
        'en' => 'south',
        'de' => 'Süd',
      ),
      'technics' => array(
        'value' => array('sat_tv', 'dv_cabling', 'dvbt_reception'),
        'en' => 'DV cabling, DVBT reception, satellite TV',
        'de' => 'DV-Verkabelung, DVBT-Empfang, Satelliten-TV',
      ),
      'broadband_internet' => array(
        'en' => 'available',
        'de' => 'verfügbar',
        'en' => 'available',
        'de' => 'verfügbar',
      ),
      'broadband_internet_speed' => array(
        'value' => 10000,
        'en' => '10,000',
        'de' => '10.000',
      ),
      'glazing' => array(
        'value' => array('sun_protection'),
        'en' => 'sun protection',
        'de' => 'Sonnenschutz',
      ),
      'furnished' => array(
        'value' => 'PARTIAL',
        'en' => 'partially',
        'de' => 'teilweise',
      ),
      'air_conditioned' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'chimney_port' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'fireplace' => array(
        'value' => array('tiled_stove'),
        'en' => 'tiled stove',
        'de' => 'Kachelofen',
      ),
      'window' => array(
        'value' => array('aluminium', 'synthetic'),
        'en' => 'aluminum, synthetic material',
        'de' => 'Aluminium, Kunststoff',
      ),
      'shutters_interior' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'shutters_exterior_type' => array(
        'value' => array('aluminium'),
        'en' => 'aluminum',
        'de' => 'Aluminium',
      ),
      'shutters_interior_type' => array(
        'value' => array('aluminium'),
        'en' => 'aluminum',
        'de' => 'Aluminium',
      ),
      'doors_outside' => array(
        'value' => array('aluminium'),
        'en' => 'aluminum',
        'de' => 'Aluminium',
      ),
      'doors_inside' => array(
        'value' => array('wood', 'glass_panel'),
        'en' => 'glass panel, wood',
        'de' => 'Glasfüllung, Holz',
      ),
      'sauna' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'swimming_pool' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'well' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'stock_of_trees' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'permission_to_cut_down_trees' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
    ),
    'condition' => array(
      'build_year' => array(
        'value' => 1989,
        'en' => '1989',
        'de' => '1989',
      ),
      'age' => array(
        'value' => 'NEW_BUILDING',
        'en' => 'new building',
        'de' => 'Neubau',
      ),
      'condition_type' => array(
        'value' => 'WELL_TENDED',
        'en' => 'well tended',
        'de' => 'gepflegt',
      ),
      'construction_phase' => array(
        'value' => 'COMPLETED',
        'en' => 'construction completed',
        'de' => 'Bau abgeschlossen',
      ),
      'refurbishment_year' => array(
        'value' => 2009,
        'en' => '2009',
        'de' => '2009',
      ),
      'refurbishment_percentage' => array(
        'value' => 100,
        'en' => '100',
        'de' => '100',
      ),
      'renovation_percentage' => array(
        'value' => 85,
        'en' => '85',
        'de' => '85',
      ),
    ),
    'surroundings' => array(
      'next_town' => array(
        'en' => 'Berlin',
        'de' => 'Berlin',
        'en' => 'Berlin',
        'de' => 'Berlin',
      ),
      'zone' => array(
        'value' => 'SUBURBS',
        'en' => 'suburbs',
        'de' => 'Stadtrand',
      ),
      'location' => array(
        'value' => array('unobstructable'),
        'en' => 'unobstructable',
        'de' => 'unverbaubar',
      ),
      'view' => array(
        'value' => array('distant_view'),
        'en' => 'distant view',
        'de' => 'Fernblick',
      ),
      'altitude' => array(
        'value' => 53.88,
        'unit' => 'KM',
        'en' => 'approx. 53.88 km',
        'de' => 'ca. 53,88 km',
      ),
      'distance_to_bus_station' => array(
        'value' => 3.0,
        'unit' => 'KM',
        'en' => 'approx. 3 km',
        'de' => 'ca. 3 km',
      ),
      'distance_to_main_line_station' => array(
        'value' => 2.0,
        'unit' => 'KM',
        'en' => 'approx. 2 km',
        'de' => 'ca. 2 km',
      ),
      'distance_to_next_train_station' => array(
        'value' => 5.0,
        'unit' => 'KM',
        'en' => 'approx. 5 km',
        'de' => 'ca. 5 km',
      ),
      'distance_to_airport' => array(
        'value' => 3.87,
        'unit' => 'KM',
        'en' => 'approx. 3.87 km',
        'de' => 'ca. 3,87 km',
      ),
      'distance_to_motorway' => array(
        'value' => 3.73,
        'unit' => 'KM',
        'en' => 'approx. 3.73 km',
        'de' => 'ca. 3,73 km',
      ),
      'distance_to_city_centre' => array(
        'value' => 1.0,
        'unit' => 'KM',
        'en' => 'approx. 1 km',
        'de' => 'ca. 1 km',
      ),
      'distance_to_day_nursery' => array(
        'value' => 4.0,
        'unit' => 'KM',
        'en' => 'approx. 4 km',
        'de' => 'ca. 4 km',
      ),
      'distance_to_elementary_school' => array(
        'value' => 6.0,
        'unit' => 'KM',
        'en' => 'approx. 6 km',
        'de' => 'ca. 6 km',
      ),
      'distance_to_comprehensive_school' => array(
        'value' => 7.98,
        'unit' => 'KM',
        'en' => 'approx. 7.98 km',
        'de' => 'ca. 7,98 km',
      ),
      'distance_to_junior_high_school' => array(
        'value' => 4.7,
        'unit' => 'KM',
        'en' => 'approx. 4.7 km',
        'de' => 'ca. 4,7 km',
      ),
      'distance_to_secondary_school' => array(
        'value' => 5.88,
        'unit' => 'KM',
        'en' => 'approx. 5.88 km',
        'de' => 'ca. 5,88 km',
      ),
      'distance_to_high_school' => array(
        'value' => 5.3,
        'unit' => 'KM',
        'en' => 'approx. 5.3 km',
        'de' => 'ca. 5,3 km',
      ),
      'distance_to_college' => array(
        'value' => 1.0,
        'unit' => 'KM',
        'en' => 'approx. 1 km',
        'de' => 'ca. 1 km',
      ),
      'distance_to_university' => array(
        'value' => 4.0,
        'unit' => 'KM',
        'en' => 'approx. 4 km',
        'de' => 'ca. 4 km',
      ),
      'distance_to_sea' => array(
        'value' => 5.0,
        'unit' => 'KM',
        'en' => 'approx. 5 km',
        'de' => 'ca. 5 km',
      ),
      'distance_to_beach' => array(
        'value' => 7.0,
        'unit' => 'KM',
        'en' => 'approx. 7 km',
        'de' => 'ca. 7 km',
      ),
      'distance_to_lake' => array(
        'value' => 15.87,
        'unit' => 'KM',
        'en' => 'approx. 15.87 km',
        'de' => 'ca. 15,87 km',
      ),
      'distance_to_recreational_area' => array(
        'value' => 5.77,
        'unit' => 'KM',
        'en' => 'approx. 5.77 km',
        'de' => 'ca. 5,77 km',
      ),
      'distance_to_wandering_area' => array(
        'value' => 6.95,
        'unit' => 'KM',
        'en' => 'approx. 6.95 km',
        'de' => 'ca. 6,95 km',
      ),
      'distance_to_skiing_area' => array(
        'value' => 98.76,
        'unit' => 'KM',
        'en' => 'approx. 98.76 km',
        'de' => 'ca. 98,76 km',
      ),
      'distance_to_sports_facilities' => array(
        'value' => 5.56,
        'unit' => 'KM',
        'en' => 'approx. 5.56 km',
        'de' => 'ca. 5,56 km',
      ),
    ),
    'administration' => array(
      'usage' => array(
        'value' => array('residential', 'leisure'),
        'en' => 'leisure, residence',
        'de' => 'Freizeit, Wohnen',
      ),
      'availability_begin_date' => array(
        'value' => 1401573600,
        'en' => 'from now on',
        'de' => 'ab sofort',
      ),
      'availability_end_date' => array(
        'value' => 1414710000,
        'en' => 'Oct 31, 2014',
        'de' => '31.10.2014',
      ),
      'commercial_use' => array(
        'value' => 'PARTIALLY',
        'en' => 'partially',
        'de' => 'teilweise',
      ),
      'pets' => array(
        'value' => 'AS_APPOINTED',
        'en' => 'by appointment',
        'de' => 'nach Vereinbarung',
      ),
      'rooms_modifiable' => array(
        'value' => 'PARTIALLY',
        'en' => 'partially',
        'de' => 'teilweise',
      ),
      'holiday_property' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'monumental_protection' => array(
        'value' => false,
        'en' => 'no',
        'de' => 'nein',
      ),
      'effective_date' => array(
        'value' => 1404079200,
        'en' => 'Jun 30, 2014',
        'de' => '30.06.2014',
      ),
    ),
    'energy_certificate' => array(
      'available' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
      'efficiency_category' => array(
        'value' => 'B',
        'en' => 'B',
        'de' => 'B',
      ),
      'creation_date' => array(
        'value' => 1401573600,
        'en' => 'Jun 1, 2014',
        'de' => '01.06.2014',
      ),
      'expiration_date' => array(
        'value' => 1435615200,
        'en' => 'Jun 30, 2015',
        'de' => '30.06.2015',
      ),
      'type' => array(
        'value' => 'CONSUMPTION',
        'en' => 'by consumption',
        'de' => 'nach Verbrauch',
      ),
      'consumption_total' => array(
        'value' => 123.65,
        'unit' => 'KWH_PER_SQM_AND_YEAR',
        'en' => 'approx. 123.65 kWh/(m²a)',
        'de' => 'ca. 123,65 kWh/(m²a)',
      ),
      'consumption_electricity' => array(
        'value' => 100.98,
        'unit' => 'KWH_PER_SQM_AND_YEAR',
        'en' => 'approx. 100.98 kWh/(m²a)',
        'de' => 'ca. 100,98 kWh/(m²a)',
      ),
      'consumption_heating' => array(
        'value' => 12.87,
        'unit' => 'KWH_PER_SQM_AND_YEAR',
        'en' => 'approx. 12.87 kWh/(m²a)',
        'de' => 'ca. 12,87 kWh/(m²a)',
      ),
      'consumption_including_hot_water' => array(
        'value' => true,
        'en' => 'yes',
        'de' => 'ja',
      ),
    ),
  ),
  'images' => array(
    array(
      'name' => 'a92ff03e615b46a85d98ba1aba1a206cfaefff3e.jpg',
      'thumb' => 'a92ff03e615b46a85d98ba1aba1a206cfaefff3e.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_groundplan',
      'title' => array(
        'en' => 'second image',
        'de' => 'zweites Bild',
      ),
    ),
    array(
      'name' => 'dae6254fd2470f15a8161b2cc81601fa84e305e1.jpg',
      'thumb' => 'dae6254fd2470f15a8161b2cc81601fa84e305e1.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image',
      'title' => array(
        'en' => 'first image',
        'de' => 'erstes Bild',
      ),
    ),
    array(
      'name' => 'ab0ebbf74890872871f1e6b66db410b789cefc21.jpg',
      'thumb' => 'ab0ebbf74890872871f1e6b66db410b789cefc21.thumb.jpg',
      'mimetype' => 'image/jpeg',
      'type' => 'image_inner_view',
      'title' => array(
        'en' => 'third image',
        'de' => 'drittes Bild',
      ),
    ),
  ),
  'media' => array(
  ),
  'links' => array(
  ),
  'other' => array(
  ),
);
