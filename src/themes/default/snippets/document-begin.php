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
 * HTML document header for the default theme.
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


// register Pure CSS framework
// see https://purecss.io/
$view->addHeader(Html\Stylesheet::newLink(
    'openestate-pure-base-css',
    $view->getThemeUrl('css/pure/base-min.css', array('v' => '1.0.0'))
), 120);
$view->addHeader(Html\Stylesheet::newLink(
    'openestate-pure-buttons-css',
    $view->getThemeUrl('css/pure/buttons-min.css', array('v' => '1.0.0'))
), 121);
$view->addHeader(Html\Stylesheet::newLink(
    'openestate-pure-forms-css',
    $view->getThemeUrl('css/pure/forms-min.css', array('v' => '1.0.0'))
), 122);

if ($view instanceof View\ExposeHtml) {
    $view->addHeader(Html\Stylesheet::newLink(
        'openestate-pure-grids-css',
        $view->getThemeUrl('css/pure/grids-min.css', array('v' => '1.0.0'))
    ), 123);
    $view->addHeader(Html\Stylesheet::newLink(
        'openestate-pure-grids-responsive-css',
        $view->getThemeUrl('css/pure/grids-responsive-min.css', array('v' => '1.0.0'))
    ), 124);
}

//$view->addHeader(Html\Stylesheet::newLink(
//        'openestate-pure-menus-css',
//        $view->getThemeUrl('css/pure/menus-min.css', array('v' => '1.0.0'))
//), 125);
//$view->addHeader(Html\Stylesheet::newLink(
//        'openestate-pure-tables-css',
//        $view->getThemeUrl('css/pure/tables-min.css', array('v' => '1.0.0'))
//), 126);


// register Popper.js for listing and favorite view
// see https://popper.js.org/
if ($view instanceof View\ListingHtml || $view instanceof View\FavoriteHtml) {
    $view->addHeader(Html\Javascript::newLink(
        'openestate-popper-js',
        $view->getThemeUrl('js/popper/popper.min.js', array('v' => '1.14.4')),
        null,
        null,
        true
    ), 130);
}


// register slick for expose view
// see https://kenwheeler.github.io/slick/
if ($view instanceof View\ExposeHtml) {
    $view->addHeaders($env->getAssets()->slick(true), 140);
}

// register colorbox for expose view
// see http://www.jacklmoore.com/colorbox/
if ($view instanceof View\ExposeHtml) {
    $view->addHeader(Html\Stylesheet::newLink(
        'openestate-colorbox-css',
        $view->getThemeUrl('js/colorbox/colorbox.css', array('v' => '1.6.4'))
    ), 150);
    $view->addHeader(Html\Javascript::newLink(
        'openestate-colorbox-js',
        $view->getThemeUrl('js/colorbox/jquery.colorbox-min.js', array('v' => '1.6.4')),
        null,
        null,
        true
    ), 151);
}

?>

<!DOCTYPE html>
<html lang="<?= html($languageCode) ?>">
<head>
    <meta charset="<?= html($view->getCharset()) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html($view->getTitle()) ?></title>
    <?= $view->generateHeader() ?>
</head>
<body>
