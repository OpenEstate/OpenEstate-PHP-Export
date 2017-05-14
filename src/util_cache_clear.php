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
 * Website-Export, Einträge aus dem Cache-Verzeichnis explizit löschen.
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
define('CACHE_PATH', IMMOTOOL_BASE_PATH . 'cache');

// Einträge im Cache-Verzeichnis ermitteln
$files = immotool_functions::list_directory(CACHE_PATH);

// Einträge aus dem Cache-Verzeichnis entfernen
echo '<h2>Remove cache files</h2>';
echo '<b>from: ' . CACHE_PATH . '</b>';
if (!is_array($files) || count($files) <= 0) {
  echo '<br/>Directory is empty!';
}
else {
  echo '<ul>';
  foreach ($files as $file) {
    if ($file == 'index.html' || $file == '.htaccess') {
      continue;
    }
    $path = CACHE_PATH . '/' . $file;
    if (!is_file($path)) {
      continue;
    }
    echo '<li>';
    echo '<b>' . $file . '</b>';
    echo ' &rarr; ';
    echo (unlink($path) === true) ? 'OK' : 'ERROR';
    echo '</li>';
  }
  echo '</ul>';
}
