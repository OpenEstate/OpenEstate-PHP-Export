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

/**
 * HTML document header for the default theme.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @var \OpenEstate\PhpExport\View\ListingHtml $view
 * the currently used view
 */

// Don't execute the file, if it is not properly loaded.
if (!isset($view) || !\is_object($view)) return;

// get export environment
$env = $view->getEnvironment();
$languageCode = $env->getLanguage();

?>

<!DOCTYPE html>
<html lang="<?php echo \htmlentities($languageCode); ?>">
<head>
    <meta charset="<?php echo \htmlentities($view->getCharset()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo \htmlentities($view->getTitle()); ?></title>
    <?php echo $view->generateHeader(); ?>
</head>
<body>
