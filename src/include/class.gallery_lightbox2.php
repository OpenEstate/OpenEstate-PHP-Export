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
 * Website-Export, JS-Galerie, basierend auf Lightbox2.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2010, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @link http://www.huddletogether.com/projects/lightbox2/
 */

if (!defined('IN_WEBSITE'))
  exit;

require_once( IMMOTOOL_BASE_PATH . 'include/class.gallery.php' );

class ImmoToolGallery_lightbox2 extends ImmoToolGallery {

  /**
   * Header inkl. Abhängigkeiten erzeugen.
   * @var bool
   */
  var $CompleteHeader = true;

  /**
   * Liefert HTML-Code zur Darstellung eines Fotos in der Galerie.
   * @return string HTML-Code
   */
  function getGalleryImage($objectId, &$image, $index, $selectedImg, $lang) {
    if (!is_string($image['thumb']))
      return '';
    $thumb = 'data/' . $objectId . '/' . $image['thumb'];
    $file = 'data/' . $objectId . '/' . $image['name'];
    $title = $image['title'][$lang];
    if (!is_string($title))
      $title = '';
    else
      $title = htmlentities($title, ENT_QUOTES, 'UTF-8');
    return '<li><a href="' . $file . '" rel="lightbox[gallery]" title="' . $title . '"><img src="' . $thumb . '" title="' . $title . '" alt="" border="0"/></a></li>';
  }

  /**
   * Liefert HTML-Code Einbindung der Galerie-Bibliothek erzeugen.
   * @return string HTML-Code
   */
  function getHeader() {
    $fullHeader = '';
    if ($this->CompleteHeader) {
      $fullHeader .= '<script type="text/javascript" src="include/lightbox2/js/prototype.js"></script>' . "\n";
      $fullHeader .= '<script type="text/javascript" src="include/lightbox2/js/scriptaculous.js?load=effects,builder"></script>';
    }
    return $fullHeader . '
<script type="text/javascript">
<!--
LightboxOptions = Object.extend({
    fileLoadingImage: \'./img/lightbox2/loading.gif\',
    fileBottomNavCloseImage: \'./img/lightbox2/closelabel.gif\',
    overlayOpacity: 0.5,
    animate: true,
    resizeSpeed: 7,
    borderSize: 10,
    labelImage: "Image",
    labelOf: "of"
}, window.LightboxOptions || {});
-->
</script>
<script type="text/javascript" src="include/lightbox2/js/lightbox.js"></script>
<link rel="stylesheet" href="include/lightbox2/css/lightbox.css" type="text/css" media="screen" />';
  }

  /**
   * Name der Galerie.
   * @return string Name
   */
  function getName() {
    return 'lightbox2';
  }

  /**
   * HTML-Code zum Titelbild.
   * @return string
   */
  function getTitleImage($objectId, &$image, $lang) {
    $thumb = 'data/' . $objectId . '/title.jpg';
    if (!is_file(IMMOTOOL_BASE_PATH . $thumb))
      return null;
    $file = 'data/' . $objectId . '/' . $image['name'];
    $link = '?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $objectId . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=gallery&amp;' . IMMOTOOL_PARAM_EXPOSE_IMG . '=1#img';
    $title = $image['title'][$lang];
    if (!is_string($title))
      $title = '';
    else
      $title = htmlentities($title, ENT_QUOTES, 'UTF-8');
    $html = '<a href="' . $file . '" rel="lightbox[title]" title="' . $title . '"><img src="' . $thumb . '" alt="" title="' . $title . '" border="0"/></a>';

    // Weitere Galeriebilder versteckt anzeigen,
    // um bei Klick auf das Titelbild eine Galerie-Navigation zu ermöglichen.
    $object = immotool_functions::get_object($objectId);
    if (is_array($object) && isset($object['images']) && is_array($object['images'])) {
      $html .= '<div style="visibility:hidden; position:absolute;">';
      foreach ($object['images'] as $img) {
        if ($img['name'] == $image['name'])
          continue;
        $file = 'data/' . $objectId . '/' . $img['name'];
        $title = $img['title'][$lang];
        if (!is_string($title))
          $title = '';
        else
          $title = htmlentities($title, ENT_QUOTES, 'UTF-8');
        $html .= '<a href="' . $file . '" rel="lightbox[title]" title="' . $title . '">&nbsp;</a>';
      }
      $html .= '</div>';
    }
    return $html;
  }

  /**
   * Die Galerie setzt JavaScript vorraus.
   * @return bool
   */
  function isJavaScriptRequired() {
    return true;
  }

  /**
   * Das gewählte Bild unterhalb der Galerie darstellen.
   * @return bool
   */
  function isSelectedImagePrinted() {
    return false;
  }

}
