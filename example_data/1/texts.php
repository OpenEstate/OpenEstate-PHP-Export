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
 * Website-Export, Inserat #1, Beschreibungstexte.
 * $Id$
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE')) {
  exit;
}

$GLOBALS['immotool_texts']['1'] = array(
  'id' => '1',
  'kurz_beschr' => array(
    'en' => 'short description of the property',
    'de' => 'Kurzbeschreibung der Immobilie',
  ),
  'objekt_beschr' => array(
    'en' => 'general description of the property',
    'de' => 'Beschreibung der Immobilie',
  ),
  'lage_beschr' => array(
    'en' => 'location description of the property',
    'de' => 'Lagebeschreibung der Immobilie',
  ),
  'ausstatt_beschr' => array(
    'en' => 'feature description of the property',
    'de' => 'Ausstattungsbeschreibung der Immobilie',
  ),
  'preis_beschr' => array(
    'en' => 'price description of the property',
    'de' => 'Preisbeschreibung der Immobilie',
  ),
  'provision_beschr' => array(
    'en' => 'agent fee description of the property',
    'de' => 'Provisionsbeschreibung der Immobilie',
  ),
  'sonstige_angaben' => array(
    'en' => 'further description of the property',
    'de' => 'weitere Angaben',
  ),
  'keywords' => array(
    'en' => 'keywords of the property',
    'de' => 'Schlüsselwörter',
  ),
);
