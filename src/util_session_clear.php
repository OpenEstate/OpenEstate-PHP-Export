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
 * Website-Export, Abgelaufene Einträge aus dem Session-Verzeichnis löschen.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

// Initialisierung
require_once(__DIR__ . '/include/functions.php');

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
