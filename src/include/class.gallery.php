<?php
/*
 * PHP-Export scripts of OpenEstate-ImmoTool
 * Copyright (C) 2009-2014 OpenEstate.org
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
 * Website-Export, allgemeine Galerie.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2010, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

class ImmoToolGallery {

  /**
   * Liefert HTML-Code zur Darstellung der Galerie.
   * @return string HTML-Code
   */
  function getGallery(&$object, $selectedImg, $lang) {
    $list = '';
    if (isset($object['images']) && is_array($object['images'])) {
      foreach ($object['images'] as $pos => $image) {
        $list .= $this->getGalleryImage($object['id'], $image, $pos, $selectedImg, $lang);
      }
    }
    return '<ul>' . $list . '</ul>';
  }

  /**
   * Liefert HTML-Code zur Darstellung eines Fotos in der Galerie.
   * @return string HTML-Code
   */
  function getGalleryImage($objectId, &$image, $index, $selectedImg, $lang) {
    if (!isset($image['thumb']) || !is_string($image['thumb']))
      return '';
    $thumb = 'data/' . $objectId . '/' . $image['thumb'];
    $class = (($index + 1) == $selectedImg) ? 'class="selected"' : '';
    $link = '?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $objectId . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=gallery&amp;' . IMMOTOOL_PARAM_EXPOSE_IMG . '=' . ($index + 1) . '#img';
    $title = $image['title'][$lang];
    if (!is_string($title))
      $title = '';
    else
      $title = htmlentities($title, ENT_QUOTES, 'UTF-8');
    return '<li ' . $class . '><a href="' . $link . '" title="' . $title . '"><img src="' . $thumb . '" title="' . $title . '" alt="" border="0"/></a></li>';
  }

  /**
   * Name der Galerie.
   * @return string Name
   */
  function getName() {
    return null;
  }

  /**
   * HTML-Code zum Titelbild.
   * @return string
   */
  function getTitleImage($objectId, &$image, $lang) {
    $thumb = 'data/' . $objectId . '/title.jpg';
    if (!is_file(IMMOTOOL_BASE_PATH . $thumb))
      return null;
    $link = '?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $objectId . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=gallery&amp;' . IMMOTOOL_PARAM_EXPOSE_IMG . '=1#img';
    $title = $image['title'][$lang];
    if (!is_string($title))
      $title = '';
    else
      $title = htmlentities($title, ENT_QUOTES, 'UTF-8');
    return '<a href="' . $link . '" title="' . $title . '"><img src="' . $thumb . '" alt="" title="' . $title . '" border="0"/></a>';
  }

  /**
   * Die Galerie setzt JavaScript vorraus.
   * @return bool
   */
  function isJavaScriptRequired() {
    return false;
  }

  /**
   * Das gewählte Bild unterhalb der Galerie darstellen.
   * @return bool
   */
  function isSelectedImagePrinted() {
    return true;
  }

}
