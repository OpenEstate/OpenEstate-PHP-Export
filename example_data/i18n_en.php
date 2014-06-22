<?php
/**
 * Website-Export, Übersetzungen, English / Englisch.
 * $Id$
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2013, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
{
  exit;
}

$GLOBALS['immotool_translations']['en'] = array(
  'labels' => array(
    'title' => 'Beispielfirma',
    'title.index' => 'Summary',
    'title.fav' => 'Favorites',
    'tab.index' => 'Summary',
    'tab.fav' => 'Favorites',
    'estate' => 'Exposé',
    'estate.id' => 'Property ID',
    'estate.nr' => 'Property nr',
    'estate.type' => 'Property type',
    'estate.action' => 'Marketing type',
    'estate.group' => 'Group nr',
    'estate.area' => 'Area',
    'estate.country' => 'Country',
    'estate.city' => 'Place',
    'estate.postal' => 'Postcode',
    'estate.price' => 'Price',
    'estate.rooms' => 'Zimmerzahl',
    'estate.title' => 'Title',
    'estate.address' => 'Address',
    'estate.region' => 'Region',
    'estate.details' => 'Details',
    'estate.details.title' => 'Details about the property',
    'estate.texts' => 'Description',
    'estate.texts.title' => 'Desciptions about the property',
    'estate.texts.empty' => 'No descriptions are available for the selected language.',
    'estate.gallery' => 'Gallery',
    'estate.gallery.title' => 'Pictures of the property',
    'estate.map' => 'Map',
    'estate.map.title' => 'Map',
    'estate.map.directLink' => 'enlarge map',
    'estate.contact' => 'Contact',
    'estate.contact.title' => 'Contact',
    'estate.contact.person' => 'Your contact person',
    'estate.contact.person.name' => 'Name',
    'estate.contact.person.address' => 'Address',
    'estate.contact.person.phone' => 'Phone',
    'estate.contact.person.mobile' => 'Mobile',
    'estate.contact.person.fax' => 'Fax',
    'estate.contact.form' => 'Send message via e-mail',
    'estate.contact.form.name' => 'Name',
    'estate.contact.form.firstname' => 'First name',
    'estate.contact.form.email' => 'E-mail',
    'estate.contact.form.phone' => 'Phone',
    'estate.contact.form.street' => 'Street',
    'estate.contact.form.streetnr' => 'Nr',
    'estate.contact.form.postal' => 'Postcode',
    'estate.contact.form.city' => 'Place',
    'estate.contact.form.message' => 'Message',
    'estate.contact.form.captcha' => 'Confirmation',
    'estate.contact.form.captcha.refresh' => 'Refresh',
    'estate.contact.form.terms' => 'Yes, I accept the following disclaimer.',
    'estate.contact.form.submit' => 'Send message',
    'estate.contact.form.submitted' => 'The message was sent successfully!',
    'estate.contact.form.submitted.message' => 'Thank you for your request. We will be in touch shortly.',
    'estate.terms' => 'Terms',
    'estate.terms.title' => 'Terms & conditions',
    'estate.terms.empty' => 'No terms are available. Please contact the agent for further information.',
    'estate.media' => 'Media',
    'estate.media.title' => 'Media of the property',
    'estate.media.downloads' => 'Downloads',
    'estate.media.links' => 'Links',
    'estate.media.videos' => 'Videos',
    'estate.media.empty' => 'No medias are available for the property.',
    'lastModification' => 'last modification',
    'order.asc' => 'ascending',
    'order.desc' => 'descending',
    'link.expose.view' => 'Details',
    'link.expose.pdf' => 'Download',
    'link.expose.fav' => 'Add to favorites',
    'link.expose.unfav' => 'Remove favorite',
    'link.expose.contact' => 'Contact us',
    'link.expose.videos' => 'Videos',
    'view.gallery' => 'gallery view',
    'view.table' => 'table view',
    'action.search' => 'execute search',
    'action.reset' => 'reset input',
    'action.clearFavs' => 'delete favorites',
    'action.clearFavs.question' => 'Do you really want to delete all of your favorites?',
    'fromNowOn' => 'from now on',
    'openestate.equipment' => 'type of equipment',
    'openestate.equipment.basic' => 'basic',
    'openestate.equipment.exclusive' => 'exclusive',
    'openestate.equipment.luxury' => 'luxury',
    'openestate.equipment.standard' => 'standard',
    'openestate.furnished' => 'furnished',
    'openestate.count_rooms' => 'number of rooms',
    'openestate.age' => 'age',
    'openestate.age.new_building' => 'new building',
    'openestate.age.old_building' => 'old building',
    'openestate.special_offer' => 'special offer',
  ),
  'errors' => array(
    'warning' => 'Warning!',
    'anErrorOccured' => 'An error occurred!',
    'noEstatesFound' => 'No properties were found!',
    'cantLoadEstate' => 'The requested property was not found!',
    'cantSendMail' => 'An error occured during e-mail transmission!',
    'cantSendMail.invalidInput' => 'The provided informations are incomplete. Please correct the highlighted fields.',
    'cantSendMail.invalidRequest' => 'The contact request is invalid. Please try to submit the contact form again.',
    'cantSendMail.templateNotFound' => 'Can\'t find an e-mail template!',
    'cantSendMail.mailWasNotSend' => 'Can\'t send e-mail. Please try again later. If the problem occurs again, please use a different way of contact (e.g. phone).',
  ),
  'openestate' => array(
    'actions' => array(
      'rent' => 'rent',
      'short_term_rent' => 'short-term rental',
      'purchase' => 'purchase',
      'lease' => 'lease',
      'emphyteusis' => 'emphyteusis',
    ),
    'attachment' => array(
      'image' => 'photo',
      'image_inner_view' => 'interior view',
      'image_outer_view' => 'exterior view',
      'image_groundplan' => 'floorplan',
      'image_map' => 'map',
      'image_panorama' => 'panorama',
    ),
    'attributes' => array(
      'prices' => array(
        'purchase_price' => 'purchase price',
        'purchase_price_per_area' => 'purchase price per sqm',
        'purchase_price_on_annual_rental_income' => 'buying price based on annual rental income',
        'rent_excluding_service_charges' => 'rent (excluding service charges)',
        'rent_excluding_service_charges_per_area' => 'rent per sqm (excluding service charges)',
        'basic_rent' => 'basic rent',
        'rent_including_service_charges' => 'rent including service charges',
        'rent_including_service_charges_per_area' => 'rent including service charges per sqm',
        'rent_flat_rate' => 'rent flat rate',
        'rent_flat_rate_per' => 'rent flat rate per',
        'additional_rental_charges' => 'additional rental charges',
        'lease' => 'lease',
        'lease_per_area' => 'lease per sqm',
        'lease_duration' => 'lease duration',
        'plus_vat' => 'plus VAT',
        'vat_rate' => 'VAT rate',
        'vat_value' => 'value added tax',
        'plus_registration_fee' => 'plus registration fee',
        'car_parking_space_included' => 'car parking space included',
        'unconventioned_price' => 'unconventioned price',
        'conventioned' => 'conventioned',
        'vinculated' => 'vinculated',
        'service_charges' => 'service charges',
        'heating_costs' => 'heating costs',
        'service_charges_including_heating_costs' => 'service charges including heating costs',
        'negotiable' => 'price is negotiable',
        'special_offer' => 'special offer',
        'common_charge' => 'common charge / condo fee',
        'takeover_costs' => 'takeover costs',
        'monthly_maintenance_fund' => 'share of maintenance (sinking) fund per month',
        'total_maintenance_fund' => 'amount of maintenance (sinking) fund',
        'net_return' => 'net return (in %)',
        'rental_income_actual_per_annum' => 'actual rental income per annum',
        'rental_income_debit_per_annum' => 'target rental income per annum',
        'rental_income_per_month' => 'monthly rental income',
        'shared_capital' => 'shared capital',
        'car_parking_space_rent' => 'car space rent',
        'car_parking_space_price' => 'purchase price for car parking space',
        'car_parking_space_obligation' => 'obligation to takeover car parking space',
        'price_proportion_ground' => 'price proportion of the plot',
        'development_costs' => 'development costs',
        'demolition_costs' => 'demolition costs',
        'deposit' => 'deposit',
        'deposit_amount' => 'deposit (amount)',
        'agent_fee' => 'agent fee / commission',
        'agent_fee_including_vat' => 'agent fee including VAT',
        'minimal_price' => 'minimal price',
        'internal_agent_fee' => 'internal agent fee',
        'internal_agent_fee_including_vat' => 'internal agent fee including VAT',
      ),
      'measures' => array(
        'total_area' => 'total area',
        'gross_area' => 'gross area',
        'residential_area' => 'residential area',
        'kitchen_area' => 'kitchen area',
        'corridor_area' => 'corridor area',
        'count_residential_units' => 'number of residential units',
        'count_rooms' => 'number of rooms',
        'count_bathrooms' => 'number of bathrooms',
        'count_livingrooms' => 'number of living rooms',
        'count_bedrooms' => 'number of bedrooms',
        'count_living_and_bedrooms' => 'number of living / bed rooms',
        'count_separate_toilets' => 'number of separate toilets',
        'count_balconies_terraces' => 'number of balconies / terraces',
        'balcony_terrace_area' => 'area balcony / terrace',
        'commercial_area' => 'commercial area',
        'count_commercial_units' => 'number of commercial units',
        'retail_area' => 'retail area',
        'storage_area' => 'storage area',
        'sales_area' => 'sales area',
        'administration_area' => 'administration area',
        'front_window_area' => 'front window (area)',
        'front_window_length' => 'front window (length)',
        'office_area' => 'office area',
        'office_area_part' => 'part of office area',
        'count_office_units' => 'number of office units',
        'plot_area' => 'plot area',
        'plot_foreside_length' => 'plot foreside length',
        'garden_area' => 'garden area',
        'divisible_from_area' => 'divisible from',
        'seating_area' => 'seating area',
        'guest_terrace' => 'guest terrace',
        'count_guest_terraces' => 'number of guest terraces',
        'count_guestroom_seats' => 'number of guestroom seats',
        'count_guestrooms' => 'number of guestrooms',
        'count_beds' => 'number of beds',
        'count_conference_rooms' => 'number of conference rooms',
        'count_parking_spaces' => 'number of parking spaces',
        'car_parking_area' => 'car parking area',
        'car_parking_type' => 'type of car parking space',
        'usable_area' => 'usable area',
        'open_space' => 'open space',
        'basement_area' => 'basement area',
        'rentable_area' => 'rentable area',
        'attic_area' => 'attic area',
        'rented_area' => 'rented area',
        'heatable_area' => 'heatable area',
        'remaining_areas' => 'remaining areas',
        'cubature_volume' => 'cubature',
        'cubic_index' => 'cubic index',
        'base_area_index' => 'floor area index',
        'floor_area_index' => 'floor area index',
        'gross_floor_area' => 'gross floor area',
        'width' => 'width',
        'length' => 'length',
        'height' => 'height',
      ),
      'features' => array(
        'equipment' => 'type of equipment',
        'floor' => 'floor',
        'count_floors' => 'total number of floors',
        'type_of_heating' => 'type of heating',
        'type_of_beaconing' => 'type of beaconing',
        'flooring_material' => 'flooring material',
        'climate_building_standard' => 'climate-house standard',
        'building_style' => 'building style',
        'kitchen' => 'kitchen',
        'bathroom' => 'bathroom',
        'residential_rooms' => 'further rooms',
        'commercial_rooms' => 'further rooms',
        'canteen_cafeteria' => 'canteen / cafeteria',
        'residential_services' => 'services',
        'commercial_services' => 'services',
        'security_technology' => 'security technology',
        'applicability' => 'applicability',
        'barrier_free' => 'barrier-free',
        'lift' => 'lift',
        'basement' => 'basement',
        'underground_level' => 'with an underground level',
        'garden_use' => 'garden / -use',
        'conservatory' => 'conservatory',
        'balcony_terrace' => 'balcony / terrace',
        'balcony_terrace_direction' => 'direction balcony / terrace',
        'technics' => 'technics',
        'broadband_internet' => 'broadband internet',
        'broadband_internet_speed' => 'broadband speed',
        'electrical_connection_value' => 'electrical connection value (in kW)',
        'glazing' => 'glazing',
        'lit_up' => 'lit-up',
        'furnished' => 'furnished',
        'air_conditioned' => 'air-conditioned',
        'chimney_port' => 'chimney port',
        'fireplace' => 'fireplace (type)',
        'window' => 'window',
        'shutters_interior' => 'interior shutters',
        'shutters_exterior_type' => 'exterior shutters',
        'shutters_interior_type' => 'interior shutters (type)',
        'doors_outside' => 'doors (outside)',
        'doors_inside' => 'doors (inside)',
        'warehouse_height' => 'warehouse height',
        'floor_loading' => 'floor loading (kg/sqm)',
        'ceiling_load' => 'ceiling load (in kg/sqm)',
        'crane' => 'crane',
        'crane_lifting_capacity' => 'crane lifting capacity(in kg)',
        'lifting_platform' => 'lifting platform',
        'lifting_platform_capacity' => 'lifting platform capacity (in kg)',
        'freight_elevator' => 'freight elevator',
        'freight_elevator_capacity' => 'freight elevator capacity (in kg)',
        'ramp' => 'ramp',
        'pit' => 'pit',
        'oil_separator' => 'oil separator',
        'telephone_available' => 'telephone available',
        'attached_gastronomy' => 'attached gastronomy',
        'wellness_area' => 'wellness area',
        'brewery_obligation' => 'brewery obligation',
        'sports_facilities' => 'sports facilities',
        'ninepins_alley' => 'ninepins alley',
        'bowling' => 'bowling',
        'sauna' => 'sauna',
        'swimming_pool' => 'swimming pool',
        'solarium' => 'solarium',
        'well' => 'well',
        'stock_of_trees' => 'stock of trees',
        'permission_to_cut_down_trees' => 'permission to cut down trees',
      ),
      'condition' => array(
        'build_year' => 'year of construction',
        'age' => 'age',
        'condition_type' => 'type of condition',
        'construction_phase' => 'construction phase',
        'refurbishment_year' => 'year of refurbishment',
        'refurbishment_percentage' => 'refurbishment (in %)',
        'renovation_percentage' => 'renovation (in %)',
        'development' => 'development',
        'contaminated_sites' => 'contaminated sites',
      ),
      'surroundings' => array(
        'next_town' => 'next town',
        'zone' => 'zone',
        'commercial_zone' => 'commercial zone',
        'supply_possible' => 'supply possible',
        'supply_access' => 'supply',
        'location' => 'location',
        'view' => 'view',
        'altitude' => 'meters in altitude',
        'distance_to_bus_station' => 'distance to bus station',
        'distance_to_main_line_station' => 'distance to main-line station',
        'distance_to_next_train_station' => 'distance to local train station (underground/innercity train)',
        'distance_to_airport' => 'distance to airport',
        'distance_to_motorway' => 'distance to motorway',
        'distance_to_city_centre' => 'distance to city centre',
        'distance_to_next_shopping' => 'distance to next shopping facility',
        'distance_to_next_gastronomy' => 'distance to next gastronomy',
        'distance_to_day_nursery' => 'distance to day nursery',
        'distance_to_elementary_school' => 'distance to elementary school',
        'distance_to_comprehensive_school' => 'distance to comprehensive school',
        'distance_to_junior_high_school' => 'distance to junior high school',
        'distance_to_secondary_school' => 'distance to secondary school',
        'distance_to_high_school' => 'distance to high school',
        'distance_to_college' => 'distance to college',
        'distance_to_university' => 'distance to university',
        'distance_to_sea' => 'distance to sea',
        'distance_to_beach' => 'distance to beach',
        'distance_to_lake' => 'distance to lake',
        'distance_to_recreational_area' => 'distance to recreational area',
        'distance_to_wandering_area' => 'distance to wandering area',
        'distance_to_skiing_area' => 'distance to skiing area',
        'distance_to_sports_facilities' => 'distance to sports facilities',
      ),
      'administration' => array(
        'usage' => 'type of use',
        'charge_begin_date' => 'begin of charged period',
        'charge_end_date' => 'value for period ending',
        'charge_time_unit' => 'charge time unit',
        'wbs_required' => 'authorities certificate required',
        'emphyteusis_duration' => 'emphyteusis duration (in years)',
        'rented' => 'rented',
        'rented_percentage' => 'rented (in %)',
        'availability_begin' => 'available from',
        'availability_begin_date' => 'available from',
        'availability_end' => 'available until',
        'availability_end_date' => 'available until',
        'access_begin_date' => 'access to property from',
        'occupation_period_minimum' => 'minimal period of occupation',
        'occupation_period_maximum' => 'maximal period of occupation',
        'people_maximum_count' => 'maximal number of people',
        'commercial_use' => 'commercial use is possible',
        'count_flatmates_total' => 'number of flatmates',
        'count_flatmates_male' => 'number of male flatmates',
        'count_flatmates_female' => 'number of female flatmates',
        'age_of_flatmates_minimal' => 'minimal age of flatmates',
        'age_of_flatmates_maximal' => 'maximal age of flatmates',
        'count_requested_flatmates' => 'number of requested flatmates',
        'age_of_requested_flatmates_minimal' => 'minimal age of requested flatmates',
        'age_of_requested_flatmates_maximal' => 'maximal age of requested flatmates',
        'gender_of_requested_flatmates' => 'gender of requested flatmates',
        'non_smoker' => 'non-smoker',
        'pets' => 'pets',
        'rooms_modifiable' => 'rooms modifiable',
        'location_in_building' => 'location in building',
        'granny_flat' => 'granny flat',
        'skyscraper' => 'skyscraper',
        'commercial_industries' => 'industries',
        'holiday_property' => 'suitable as holiday property',
        'building_permission_available' => 'building permission to hand',
        'buildable_with' => 'buildable with',
        'buildable_according_to' => 'buildable according to',
        'buildable_in_short_term' => 'short-termed start of development',
        'demolition_required' => 'requires demolition',
        'monumental_protection' => 'under monumental protection',
        'communal_district' => 'communal district',
        'parcel' => 'parcel',
        'sub_plot' => 'sub-plot',
        'communal_code' => 'communal code',
        'regional_notes' => 'additional regional notes',
        'apartment_nr' => 'flat number',
        'auction_date' => 'auction date',
        'effective_date' => 'effective',
      ),
      'energy_certificate' => array(
        'available' => 'energy certificate available',
        'efficiency_category' => 'energy efficiency category',
        'creation_date' => 'created at',
        'expiration_date' => 'expires on',
        'type' => 'type of certificate',
        'consumption_total' => 'total consumption value',
        'consumption_electricity' => 'electricity consumption value',
        'consumption_heating' => 'heating consumption value',
        'consumption_including_hot_water' => 'consumption including hot water',
        'demand_total' => 'total demand value',
        'demand_electricity' => 'electricity demand value',
        'demand_heating' => 'heating demand value',
        'austria_thermal_heat_demand' => 'thermal heat demand',
        'austria_total_energy_efficiency_factor' => 'total energy efficiency factor',
      ),
      'descriptions' => array(
        'detailled_description' => 'detailed description',
        'location_description' => 'location description',
        'feature_description' => 'feature description',
        'price_description' => 'pricing description',
        'agent_fee_information' => 'details about agency fee',
        'additional_information' => 'further information',
        'short_description' => 'short description',
        'keywords' => 'keywords',
      ),
    ),
    'groups' => array(
      'prices' => 'prices',
      'measures' => 'measures',
      'features' => 'features',
      'condition' => 'condition',
      'surroundings' => 'surroundings',
      'administration' => 'administration',
      'energy_certificate' => 'energy certificate',
      'descriptions' => 'descriptions',
    ),
    'types' => array(
      'general_agriculture' => 'agriculture',
      'animal_husbandry' => 'animal husbandry',
      'barn' => 'barn',
      'cultivation' => 'cultivation',
      'farm' => 'farm',
      'rest_of_a_farm' => 'rest of a farm',
      'fish_farming' => 'fish farming',
      'forestry_hunting' => 'forestry / hunting',
      'gardening' => 'gardening',
      'outlying_farm' => 'outlying farm',
      'viniculture' => 'viniculture',
      'general_car_parking_space' => 'car parking space',
      'carport' => 'carport',
      'car_park_unit' => 'unit within parking structure',
      'double_garage' => 'double garage',
      'duplex_garage' => 'duplex garage',
      'free_car_parking_space' => 'free parking space',
      'garage' => 'garage',
      'outdoor_car_parking_space' => 'outdoor car parking space',
      'underground_car_park_unit' => 'unit within underground parking',
      'general_commercial' => 'commercial property',
      'gastronomy' => 'catering business',
      'bistro_cafe' => 'bistro / café',
      'discotheque' => 'discotheque',
      'gastronomy_with_housing' => 'gastronomy with apartment',
      'pizzeria' => 'pizzeria',
      'restaurant' => 'restaurant',
      'restaurant_bar' => 'restaurant / bar',
      'hall_warehouse' => 'hall / warehouse',
      'cold_storage_warehouse' => 'cold storage warehouse',
      'cooling_house' => 'cooling house',
      'forwarding_warehouse' => 'forwarding warehouse',
      'high_rack_warehouse' => 'high rack warehouse',
      'industrial_warehouse' => 'industrial warehouse',
      'industrial_warehouse_with_open_space' => 'industrial warehouse with open space',
      'open_space' => 'open space',
      'storage_area' => 'storage area',
      'warehouse' => 'warehouse',
      'hospitality_industry' => 'hospitality industry',
      'guesthouse' => 'guesthouse',
      'guest_room' => 'guest room',
      'hostel' => 'hostel',
      'hotel' => 'hotel',
      'youth_hostel' => 'youth hostel',
      'hospital_clinic' => 'hospital / clinic',
      'sanatorium' => 'sanatorium',
      'housing_complex' => 'housing complex',
      'senior_housing_complex' => 'senior housing complex',
      'leisure_sports_facility' => 'leisure / sports facility',
      'fitness_studio' => 'fitness studio',
      'riding_estate' => 'riding estate',
      'sports_facility' => 'sports facilities',
      'tanning_salon' => 'tanning salon',
      'theme_park' => 'theme park',
      'office_commercial_building' => 'office / commercial building',
      'commercial_building' => 'commercial building',
      'commercial_center' => 'commercial center',
      'doctors_office_house' => 'doctor\'s office house',
      'office_building' => 'office building',
      'office_center' => 'office center',
      'office_commercial_premise' => 'office / business premise',
      'office_surgery' => 'office / surgery',
      'office' => 'office',
      'office_area' => 'office area',
      'office_floor' => 'office floor',
      'surgery' => 'surgery',
      'surgery_floor' => 'surgery floor',
      'production' => 'production',
      'industrial_facility' => 'industrial facility',
      'manufactoring_area' => 'manufacturing area',
      'residential_commercial_building' => 'residential / commercial house',
      'retail' => 'retail',
      'consumer_store' => 'consumer store',
      'department_store' => 'department store',
      'exhibition_area' => 'exhibition area',
      'kiosk' => 'kiosk',
      'premise' => 'premise',
      'sales_area' => 'sales area',
      'self_service_market' => 'self-service market',
      'shop' => 'shop',
      'shopping_center' => 'shopping center',
      'showroom' => 'showroom',
      'store' => 'store',
      'warehouse_store' => 'warehouse store',
      'service_area' => 'service area',
      'petrol_station' => 'petrol station',
      'service_center' => 'service center',
      'workshop' => 'workshop',
      'general_piece_of_land' => 'plot of land',
      'agricultural_forestry_ground' => 'agricultural / forestry ground',
      'commercial_ground' => 'commercial ground',
      'garden_ground' => 'garden ground',
      'industrial_ground' => 'industrial ground',
      'leisure_ground' => 'leisure ground',
      'mixed_use_ground' => 'mixed-use ground',
      'residential_ground' => 'residential ground',
      'special_use_ground' => 'ground for special use',
      'general_residence' => 'residential property',
      'apartment_share' => 'apartment- sharing community',
      'apartment_share_seniors' => 'seniors apartment share',
      'apartment_share_students' => 'students apartment share',
      'assisted_living' => 'assisted living',
      'house' => 'house',
      'duplex_house' => 'duplex house',
      'multi_family_house' => 'multi-family house',
      'multi_family_house_with_commercial' => 'multi-family house with commercial',
      'town_house' => 'town house',
      'two_family_house' => 'two-family house',
      'single_family_house' => 'single-family house',
      'bungalow' => 'bungalow',
      'chalet' => 'chalet',
      'country_house' => 'country house',
      'farmhouse' => 'farmhouse',
      'leisure_home' => 'leisure home',
      'alpine_hut' => 'alpine hut',
      'beach_house' => 'beach house',
      'cottage' => 'cottage',
      'summer_house' => 'summer house',
      'semidetached_house' => 'semidetached house',
      'terraced_house' => 'terraced house',
      'terraced_house_cornerside' => 'terraced house (at corner)',
      'terraced_house_middle' => 'terraced house (at middle)',
      'terraced_house_tail' => 'terraced house (at tail)',
      'villa' => 'villa',
      'special_house' => 'special house',
      'castle' => 'castle',
      'mansion' => 'mansion',
      'palace' => 'palace',
      'residence' => 'apartment',
      'apartment' => 'apartment',
      'atelier' => 'atelier',
      'attic_apartment' => 'attic apartment',
      'attic_under_construction' => 'attic under construction',
      'ground_floor_apartment' => 'ground floor apartment',
      'higher_floor_apartment' => 'apartment on higher floor',
      'loft' => 'loft',
      'maisonette' => 'maisonette',
      'mezzanine' => 'mezzanine',
      'one_room_apartment' => 'one-room apartment',
      'penthouse_apartment' => 'penthouse apartment',
      'souterrain_apartment' => 'souterrain apartment',
      'studio' => 'studio',
      'terrace_apartment' => 'terrace apartment',
      'room' => 'room',
      'assembler_room' => 'assembler room',
    ),
  ),
);
?>