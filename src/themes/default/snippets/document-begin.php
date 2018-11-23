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

namespace OpenEstate\PhpExport;

use function htmlspecialchars as html;

/**
 * HTML document header for the default theme.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 *
 * @var View\AbstractHtmlView $view
 * the currently used view
 */

// Don't execute the file, if it is not properly loaded.
if (!isset($view) || !\is_object($view)) return;

// get export environment
$env = $view->getEnvironment();
$theme = $view->getTheme();
$languageCode = $env->getLanguage();


// register JQuery
// see https://jquery.com/
if ($theme->isComponentEnabled(DefaultTheme::JQUERY))
    $view->addHeaders($env->getAssets()->jquery(), 100);


// register OpenEstate Icons
// created with http://fontello.com/
$view->addHeaders($env->getAssets()->openestate_icons(), 110);


// register Pure CSS framework
// see https://purecss.io/
if ($theme->isComponentEnabled(DefaultTheme::PURE)) {
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
}


// register Popper.js for listing and favorite view
// see https://popper.js.org/
if ($theme->isComponentEnabled(DefaultTheme::POPPER)) {
    if ($view instanceof View\ListingHtml || $view instanceof View\FavoriteHtml) {
        $view->addHeader(Html\Javascript::newLink(
            'openestate-popper-js',
            $view->getThemeUrl('js/popper/popper.min.js', array('v' => '1.14.5')),
            null,
            null,
            true
        ), 130);
    }
}


// register slick for expose view
// see https://kenwheeler.github.io/slick/
if ($theme->isComponentEnabled(DefaultTheme::SLICK)) {
    if ($view instanceof View\ExposeHtml)
        $view->addHeaders($env->getAssets()->slick(true), 140);
}


// register colorbox for expose view
// see http://www.jacklmoore.com/colorbox/
if ($theme->isComponentEnabled(DefaultTheme::COLORBOX)) {
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
}


// Don't send any output, if only the body part is generated.
if ($view->isBodyOnly()) return;
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
