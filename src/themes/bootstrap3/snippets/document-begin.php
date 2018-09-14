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

namespace OpenEstate\PhpExport;

use function htmlspecialchars as html;

/**
 * HTML document header for the Bootstrap3 theme.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @var View\AbstractHtmlView $view
 * the currently used view
 */

// Don't execute the file, if it is not properly loaded.
if (!isset($view) || !\is_object($view)) return;

// get export environment
$env = $view->getEnvironment();
$languageCode = $env->getLanguage();


// register JQuery
// see https://jquery.com/
$view->addHeaders($env->getAssets()->jquery(), 100);


// register OpenEstate Icons
// created with http://fontello.com/
$view->addHeaders($env->getAssets()->openestate_icons(), 110);


// register Bootstrap3 framework
// see https://getbootstrap.com/
$view->addHeader(Html\Stylesheet::newLink(
    'openestate-bootstrap-css',
    $view->getThemeUrl('css/bootstrap.min.css', array('v' => '3.3.7'))
), 120);
$view->addHeader(Html\Stylesheet::newLink(
    'openestate-bootstrap-theme-css',
    $view->getThemeUrl('css/bootstrap-theme.min.css', array('v' => '3.3.7'))
), 121);
$view->addHeader(Html\Javascript::newLink(
    'openestate-bootstrap-js',
    $view->getThemeUrl('js/bootstrap.min.js', array('v' => '3.3.7')),
    null,
    null,
    true
), 122);
$view->addHeader(Html\Javascript::newLink(
    'openestate-bootstrap-ie10-js',
    $view->getThemeUrl('js/ie10-viewport-bug-workaround.js', array('v' => '3.3.7')),
    null,
    null,
    true
), 123);


// register slick for expose view
// see https://kenwheeler.github.io/slick/
if ($view instanceof View\ExposeHtml) {
    $view->addHeaders($env->getAssets()->slick(true), 130);
}

?>

<!DOCTYPE html>
<html lang="<?= html($languageCode) ?>">
<head>
    <meta charset="<?= html($view->getCharset()) ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html($view->getTitle()) ?></title>
    <?= $view->generateHeader() ?>
</head>
<body>
