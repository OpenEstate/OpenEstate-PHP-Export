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

// Don't execute the file, if it is not properly loaded.
if (!isset($this) || !\is_object($this)) return;

use \OpenEstate\PhpExport\Html\Stylesheet;
use \OpenEstate\PhpExport\Html\Javascript;
use \OpenEstate\PhpExport\Html\Meta;

$t = $env->i18n();
$this->setTitle($t->gettext('Current real estate offers.'));
$this->addHeader(Meta::newRobots('noindex,follow'), 500);
$this->addHeader(Stylesheet::newLink('theme-css', $this->getThemeUrl($env, 'css/theme.css')), 1000);
$this->addHeader(Javascript::newLink('theme-js', $this->getThemeUrl($env, 'js/theme.js')), 1001);
$this->addHeaders($env->getAssets()->jquery(), 600);

// Write document header.
if (!$this->isBodyOnly()) include('snippets/document-begin.php');
include('snippets/body-begin.php');


echo '<p>' . $t->gettext('This is an example...') . '</p>';


// Write document footer.
include('snippets/body-end.php');
if (!$this->isBodyOnly()) include('snippets/document-end.php');
