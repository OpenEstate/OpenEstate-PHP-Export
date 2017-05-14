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
 * Website-Export, dynamischer Stylesheet.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

// Initialisierung der Skript-Umgebung
define('IN_WEBSITE', 1);
if (!defined('IMMOTOOL_BASE_PATH'))
  define('IMMOTOOL_BASE_PATH', '');
require_once(IMMOTOOL_BASE_PATH . 'config.php');
require_once(IMMOTOOL_BASE_PATH . 'include/functions.php');
header('Content-Type: text/css; charset=utf-8');

// Konfiguration ermitteln
$setup = new immotool_setup_style();
if (is_callable(array('immotool_myconfig', 'load_config_style')))
  immotool_myconfig::load_config_style($setup);
$showGeneralStyles = $setup->ShowGeneralStyles;
if (isset($_REQUEST['wrapped']) && $_REQUEST['wrapped'] == '1') {
  $showGeneralStyles = false;
}
?>
/**
* Allgemeines
*/

<?php
if ($showGeneralStyles === true) {
  ?>
* {
color: <?php echo $setup->GeneralTextColor; ?>;
font-family: <?php echo $setup->GeneralTextFont; ?>;
}

body {
background-color: <?php echo $setup->BodyBackgroundColor; ?>;
font-size: <?php echo $setup->BodyFontSize; ?>;
margin: 0;
}

a,
a:link,
a:active,
a:visited {
color: #909090;
text-decoration: none;
}

a:hover {
color: #303030;
text-decoration: underline;
}

h1 {
font-size: 1.6em;
margin-left: 0.5em;
margin-bottom: 1em;
}

h2 {
font-size: 1.3em;
}

h3 {
font-size: 1.1em;
}
  <?php
}
?>
#openestate_contentpane {
}

#openestate_contentpane img {
background-color: <?php echo $setup->BodyBackgroundColor; ?>;
}

#openestate_header {
}

#openestate_content
{
}

#openestate_footer {
clear: both;
text-align: right;
margin: 1em;
}

.openestate_clear {
clear: both;
}

.openestate_nowrap {
white-space: nowrap;
}

.openestate_light {
background-color: <?php echo $setup->LightBackgroundColor; ?>;
}

.openestate_dark {
background-color: <?php echo $setup->DarkBackgroundColor; ?>;
}


/**
* Allgemein, Fehlermeldungen
*/

#openestate_error {
border: 1px solid <?php echo $setup->BorderColor; ?>;
background-color: <?php echo $setup->DarkBackgroundColor; ?>;
margin: 1em;
padding: 1em;
text-align: center;
}

#openestate_error h1 {
margin: 0;
margin-bottom: 5px;
}


/**
* Allgemein, Sprachauswahl
*/

#openestate_languages ul {
text-align: right;
font-weight: bold;
border-bottom: 1px solid <?php echo $setup->BorderColor; ?>;
list-style-type: none !important;
padding: 3px 10px 3px 10px !important;
margin: 1em 0 1em 0 !important;
}

#openestate_languages ul li {
display: inline !important;
}

#openestate_languages ul li a {
padding: 3px 4px;
border: 1px solid <?php echo $setup->BorderColor; ?>;
background-color: <?php echo $setup->DarkBackgroundColor; ?>;
color: #666;
margin-right: 0px;
text-decoration: none;
border-bottom: none;
}

#openestate_languages ul li.selected a,
#openestate_languages ul li a:hover {
background-color: <?php echo $setup->LightBackgroundColor; ?>;
color: #000;
position: relative;
top: 1px;
padding-top: 4px;
}


/**
* Immobilienliste, Seitenzähler
*/

#openestate_listing_pagination_top ul,
#openestate_listing_pagination_bottom ul {
clear: both;
text-align: left;
font-weight: bold;
list-style-type: none !important;
padding: 3px 10px 3px 10px !important;
margin: 0.5em 0 0.5em 0 !important;
}

#openestate_listing_pagination_top ul {
border-bottom: 1px solid <?php echo $setup->BorderColor; ?>;
}

#openestate_listing_pagination_bottom ul {
border-top: 1px solid <?php echo $setup->BorderColor; ?>;
}

#openestate_listing_pagination_top ul li,
#openestate_listing_pagination_bottom ul li {
display: inline !important;
}

#openestate_listing_pagination_top ul li a,
#openestate_listing_pagination_bottom ul li a {
padding: 3px 4px;
border: 1px solid <?php echo $setup->BorderColor; ?>;
background-color: <?php echo $setup->DarkBackgroundColor; ?>;
color: #666;
margin-right: 0px;
text-decoration: none;
}

#openestate_listing_pagination_top ul li a {
border-bottom: none;
}

#openestate_listing_pagination_bottom ul li a {
border-top: none;
}

#openestate_listing_pagination_top ul li.selected a,
#openestate_listing_pagination_bottom ul li.selected a,
#openestate_listing_pagination_top ul li a:hover,
#openestate_listing_pagination_bottom ul li a:hover {
background-color: <?php echo $setup->LightBackgroundColor; ?>;
color: #000;
position: relative;
}

#openestate_listing_pagination_top ul li.selected a,
#openestate_listing_pagination_top ul li a:hover {
top: 1px;
padding-top: 4px;
}

#openestate_listing_pagination_bottom ul li.selected a,
#openestate_listing_pagination_bottom ul li a:hover {
top: -1px;
padding-bottom: 4px;
}


/**
* Immobilienliste, Formulare zur Eingrenzung
*/

#openestate_listing_menu {
text-align: left;
background-color: <?php echo $setup->LightBackgroundColor; ?>;
border-bottom: 1px dashed <?php echo $setup->BorderColor; ?>;
padding-top: 0.5em;
padding-bottom: 1em;
margin-left: 1em;
margin-right: 1em;
}

#openestate_listing_menu div {
display: inline;
}

#openestate_listing_menu input,
#openestate_listing_menu select {
border: 1px solid <?php echo $setup->BorderColor; ?>;
background-color: <?php echo $setup->LightBackgroundColor; ?>;
}

#openestate_listing_menu input.openestate_search_button {
border: none;
background-image: url(img/search.png);
background-repeat: no-repeat;
width: 22px;
height: 22px;
cursor: pointer;
}

#openestate_listing_menu input.openestate_cancel_button {
border: none;
background-image: url(img/cancel.png);
background-repeat: no-repeat;
width: 22px;
height: 22px;
cursor: pointer;
}

#openestate_listing_menu input.openestate_remove_button {
border: none;
background-image: url(img/delete.png);
background-repeat: no-repeat;
width: 22px;
height: 22px;
cursor: pointer;
}

.openestate_listing_buttons {
margin-left: 1em;
float: right;
}


/**
* Immobilienliste, Kurzexposé
*/

#openestate_empty_list {
padding: 2em;
text-align: center;
font-weight: bold;
font-size: 1.2em;
}

#openestate_listing_modes {
float: right;
margin-right: 0.5em;
}


/**
* Immobilienliste, Kurzexposé, Listenansicht
*/

.openestate_listing_entry {
clear: both;
margin: 1em;
border: 1px solid <?php echo $setup->BorderColor; ?>;
padding: 0.5em;
}

.openestate_listing_entry h2 {
margin: 0;
margin-bottom: 0.5em;
}

.openestate_listing_entry .image {
float: left;
}

.openestate_listing_entry .image img {
border: 1px solid <?php echo $setup->BorderColor; ?>;
}

.openestate_listing_entry .col_1 ul,
.openestate_listing_entry .col_2 ul {
margin: 0 !important;
float: left;
}

.openestate_listing_entry .options {
clear: both;
}

.openestate_listing_entry .options ul {
margin: 0 !important;
padding: 0 !important;
padding-top: 0.5em !important;
list-style-type: none !important;
text-align: left;
}

.openestate_listing_entry .options ul li {
text-align: left;
display: inline !important;
padding-right: 0.5em !important;
}


/**
* Immobilienliste, Kurzexposé, Galerieansicht
*/

.openestate_listing_image {
height: auto;
float: left;
text-align:center;
margin: 0.5em;
-moz-border-radius:0.8em;
-khtml-border-radius:0.8em;
background-color: <?php echo $setup->DarkBackgroundColor; ?>;
border: 3px solid <?php echo $setup->BorderColor; ?>;
border-style: outset;
padding: 0.5em;
margin-top: 0.5em;
margin-bottom: 0.5em;
vertical-align:middle;
}

.openestate_listing_image .info_box {
display: none;
margin: 0;
margin-bottom: 0.1em;
text-align:left;
}

.openestate_listing_image:hover .info_box {
display: block;
padding: 1em;
margin-left:-2em;
margin-top: -4em;
position: absolute;
z-index: 3;
background-color: <?php echo $setup->DarkBackgroundColor; ?>;
border: 2px solid <?php echo $setup->BorderColor; ?>;
width: 250px;
}

.openestate_listing_image:hover .info_box h2 {
margin:0;
margin-bottom:0.5em;
font-size: 1.0em;
}

.openestate_listing_image:hover .info_box h2 a {
text-decoration: none;
}

.openestate_listing_image:hover .info_box h2 a:hover {
text-decoration: underline;
}

.openestate_listing_image:hover .info_box ul {
margin:0 !important;
padding:0 !important;
padding-left:1em !important;
}

.openestate_listing_image:hover .info_box ul li {
font-size: 0.9em;
}

.openestate_listing_image:hover .info_box div.options {
clear: both;
}

.openestate_listing_image:hover .info_box div.options ul {
margin: 0 !important;
padding: 0 !important;
padding-top: 0.5em !important;
list-style-type: none !important;
text-align: left;
}

.openestate_listing_image:hover .info_box div.options ul li {
display: block !important;
text-align: left;
float: left;
clear: left;
}


/**
* Exposéansicht
*/

#openestate_expose_header {
margin-left: 1em;
margin-right: 1em;
}

#openestate_expose_header ul {
list-style-type: none !important;
padding: 0 !important;
margin: 0 !important;
min-width: 300px;
}

#openestate_expose_header ul li {
font-weight: bold;
letter-spacing: 1px;
}

#openestate_expose_header ul li div {
display: inline-block !important;
text-align: right;
width: 125px;
margin-right: 0.5em;
font-weight: normal;
letter-spacing: 0;
}

#openestate_expose_header_image {
float: right;
}

#openestate_expose_view {
clear: both;
margin-left: 1em;
margin-right: 1em;
padding-top: 1em;
}

#openestate_expose_view_menu {
position: relative;
top: 1px;
}

#openestate_expose_view_content {
border: 1px solid <?php echo $setup->BorderColor; ?>;
border-top: 1px solid <?php echo $setup->BodyBackgroundColor; ?>;
padding-left: 1em;
padding-right: 1em;
margin-bottom: 2em;
}


/**
* Exposéansicht, Menü
*/

#openestate_expose_menu_top ul,
#openestate_expose_menu_bottom ul {
clear: both;
text-align: left;
font-weight: bold;
list-style-type: none !important;
padding: 3px 10px 3px 10px !important;
margin: 0.5em 0 0.5em 0 !important;
}

#openestate_expose_menu_top ul {
border-bottom: 1px solid <?php echo $setup->BorderColor; ?>;
}

#openestate_expose_menu_bottom ul {
border-top: 1px solid <?php echo $setup->BorderColor; ?>;
}

#openestate_expose_menu_top ul li,
#openestate_expose_menu_bottom ul li {
display: inline !important;
}

#openestate_expose_menu_top ul li a,
#openestate_expose_menu_bottom ul li a {
padding: 3px 4px;
border: 1px solid <?php echo $setup->BorderColor; ?>;
background-color: <?php echo $setup->DarkBackgroundColor; ?>;
color: #666;
margin-right: 0px;
text-decoration: none;
}

#openestate_expose_menu_top ul li a {
border-bottom: none;
}

#openestate_expose_menu_bottom ul li a {
border-top: none;
}

#openestate_expose_menu_top ul li.selected a,
#openestate_expose_menu_bottom ul li.selected a,
#openestate_expose_menu_top ul li a:hover,
#openestate_expose_menu_bottom ul li a:hover {
background-color: <?php echo $setup->LightBackgroundColor; ?>;
color: #000;
position: relative;
}

#openestate_expose_menu_top ul li.selected a,
#openestate_expose_menu_top ul li a:hover {
top: 1px;
padding-top: 4px;
}

#openestate_expose_menu_bottom ul li.selected a,
#openestate_expose_menu_bottom ul li a:hover {
top: -1px;
padding-bottom: 4px;
}


/**
* Exposéansicht, Detailmenü
*/
#openestate_expose_view_menu {
margin-bottom: 0;
}

#openestate_expose_view_menu ul {
clear: both;
text-align: left;
font-weight: bold;
border-bottom: 1px solid <?php echo $setup->BorderColor; ?>;
list-style-type: none !important;
padding: 3px 10px 3px 10px !important;
margin: 0 !important;
margin-top: 1em !important;
}

#openestate_expose_view_menu ul li {
display: inline !important;
}

#openestate_expose_view_menu ul li a {
padding: 3px 4px;
border: 1px solid <?php echo $setup->BorderColor; ?>;
background-color: <?php echo $setup->DarkBackgroundColor; ?>;
color: #666;
margin-right: 0px;
text-decoration: none;
border-bottom: none;
}

#openestate_expose_view_menu ul li.selected a,
#openestate_expose_view_menu ul li a:hover {
background-color: <?php echo $setup->LightBackgroundColor; ?>;
color: #000;
position: relative;
top: 1px;
padding-top: 4px;
}


/**
* Exposéansicht, Galerie
*/

#openestate_expose_gallery {
margin-bottom: 1em;
overflow: auto;
}

#openestate_expose_gallery_image {
text-align:center;
display: block;
padding: 0.5em;
}

#openestate_expose_gallery_image img {
}

#openestate_expose_gallery_thumbnails {
background-color: <?php echo $setup->DarkBackgroundColor; ?>;
padding: 0.5em;
}

#openestate_expose_gallery_thumbnails ul {
text-align: center;
list-style-type: none !important;
padding: 0 !important;
margin: 0 !important;
}

#openestate_expose_gallery_thumbnails ul li {
vertical-align: top;
display: inline !important;
}

#openestate_expose_gallery_thumbnails ul li a img {
border: 0.5em solid <?php echo $setup->DarkBackgroundColor; ?>;
}

#openestate_expose_gallery_thumbnails ul li.selected a img,
#openestate_expose_gallery_thumbnails ul li a:hover img {
border: 0.5em solid <?php echo $setup->BorderColor; ?>;
}


/**
* Exposéansicht, Kontaktformular
*/

#openestate_expose_contact_form table {
width: 100%;
max-width: 640px;
}

#openestate_expose_contact_form td.col1 {
text-align: right;
vertical-align: top;
padding-right: 1em;
padding-bottom: 0.5em;
white-space: nowrap;
width: 100px;
}

#openestate_expose_contact_form td.col2 {
padding-bottom: 0.5em;
}

#openestate_expose_contact_form td.col3 {
width: 75px;
white-space: nowrap;
text-align: right;
padding-right: 1em;
padding-bottom: 0.5em;
}

#openestate_expose_contact_form td.col4 {
width: 100px;
padding-bottom: 0.5em;
}

#openestate_expose_contact_form_captcha_col1 {
width: 125px;
}

#openestate_expose_contact_form_captcha_col2 {
vertical-align: top;
}


#openestate_expose_contact_form input {
width: 100%;
border: 1px solid <?php echo $setup->BorderColor; ?>;
}

#openestate_expose_contact_form textarea {
width: 100%;
border: 1px solid <?php echo $setup->BorderColor; ?>;
}

#openestate_expose_contact_form td.buttons {
padding-top: 1em;
text-align: center;
}

#openestate_expose_contact_form input.box {
width: auto;
}

#openestate_expose_contact_form td.buttons input {
width: auto;
}

#openestate_expose_contact_form td.error label {
color: red;
font-weight: bold;
}

#openestate_expose_contact_form td.error input {
border: 2px solid red;
font-weight: bold;
}

#openestate_expose_contact_form td.error textarea {
border: 2px solid red;
}

#openestate_expose_contact_form_message {
height: 150px;
}

#openestate_expose_contact_form_terms_area {
height: 100px;
font-family: Courier, "Courier New", monospace;
font-size: 1em;
margin-top: 0.5em;
}

#openestate_expose_contact_result {
width: 100%;
max-width: 640px;
margin-top: 1em;
margin-bottom: 2em;
text-align: center;
background-color: <?php echo $setup->DarkBackgroundColor; ?>;
border: 1px solid <?php echo $setup->BorderColor; ?>;
padding: 1em;
padding-top: 0;
}

/**
* Exposéansicht, Kontaktperson
*/
#openestate_expose_contact_person ul {
min-width: 300px;
list-style-type: none !important;
padding: 0 !important;
margin: 0 !important;
}

#openestate_expose_contact_person ul li {
font-weight: bold;
letter-spacing: 1px;
}

#openestate_expose_contact_person ul li div {
display: inline-block !important;
text-align: right;
width: 100px;
margin-right: 1em;
font-weight: normal;
letter-spacing: 0;
}

/**
* Exposéansicht, Umkreiskarte
*/
#openestate_map {
margin-bottom: 1em;
}

#openestate_map iframe {
width: 100%;
height: 500px;
border: 1px solid <?php echo $setup->BorderColor; ?>;
}
