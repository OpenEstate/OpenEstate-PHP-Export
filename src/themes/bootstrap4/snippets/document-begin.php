<?php
/*
 * Copyright 2009-2019 OpenEstate.org.
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
 * HTML document header for the Bootstrap4 theme.
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
if ($theme->isComponentEnabled(Bootstrap4Theme::JQUERY))
    $view->addHeaders($env->getAssets()->jquery(), 100);


// register OpenEstate Icons
// created with http://fontello.com/
$view->addHeaders($env->getAssets()->openestate_icons(), 110);


// register Bootstrap4 framework
// see https://getbootstrap.com/
if ($theme->isComponentEnabled(Bootstrap4Theme::BOOTSTRAP)) {
    $view->addHeader(Html\Stylesheet::newLink(
        'openestate-bootstrap-css',
        $view->getThemeUrl('css/bootstrap.min.css', array('v' => '4.1.3'))
    ), 120);
    $view->addHeader(Html\Javascript::newLink(
        'openestate-bootstrap-js',
        $view->getThemeUrl('js/bootstrap.bundle.min.js', array('v' => '4.1.3')),
        null,
        null,
        true
    ), 121);
}


// register slick for expose view
// see https://kenwheeler.github.io/slick/
if ($theme->isComponentEnabled(Bootstrap4Theme::SLICK)) {
    if ($view instanceof View\ExposeHtml)
        $view->addHeaders($env->getAssets()->slick(true), 130);
}


// Don't send any output, if only the body part is generated.
if ($view->isBodyOnly()) return;
?>

<!DOCTYPE html>
<html lang="<?= html($languageCode) ?>">
<head>
    <meta charset="<?= html($view->getCharset()) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= html($view->getTitle()) ?></title>
    <?= $view->generateHeader() ?>
</head>
<body>
