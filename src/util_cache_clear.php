<?php
/*
 * Copyright 2009-2018 OpenEstate.org.
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
 * Website-Export, Einträge aus dem Cache-Verzeichnis explizit löschen.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

// Initialisierung
require_once(__DIR__ . '/include/functions.php');
define('CACHE_PATH', immotool_functions::get_path('cache'));

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
