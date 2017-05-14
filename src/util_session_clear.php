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
 * Website-Export, Abgelaufene Einträge aus dem Session-Verzeichnis löschen.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

// Initialisierung der Skript-Umgebung
define('IN_WEBSITE', 1);
if (!defined('IMMOTOOL_BASE_PATH')) {
  define('IMMOTOOL_BASE_PATH', '');
}
require_once(IMMOTOOL_BASE_PATH . 'include/functions.php');

//  Einträge aus dem Session-Verzeichnis löschen
$files = immotool_functions::cleanup_sessions(true);

//  gelöschte Einträge aus dem Session-Verzeichnis auflisten
echo '<h2>Removed session files</h2>';
if (!is_array($files) || count($files) <= 0) {
  echo '<p>Nothing was removed</p>';
}
else {
  echo '<ul>';
  foreach ($files as $file) {
    echo '<li>' . $file . '</li>';
  }
  echo '</ul>';
}
