<?php
/*
 * PHP-Export scripts of OpenEstate-ImmoTool
 * Copyright (C) 2009-2017 OpenEstate.org
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
 * @copyright 2009-2014, OpenEstate.org
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
   * Pfad zum Prototype-Javascript
   * @var string
   */
  var $PrototypeScript = 'include/lightbox2/js/prototype.js';

  /**
   * Pfad zum Scriptaculous-Javascript
   * @var string
   */
  var $ScriptaculousScript = 'include/lightbox2/js/scriptaculous.js?load=effects,builder';

  /**
   * Pfad zum Lightbox-Javascript
   * @var string
   */
  var $LightboxScript = 'include/lightbox2/js/lightbox.js';

  /**
   * Pfad zum Lightbox-Stylesheet
   * @var string
   */
  var $LightboxStyle = 'include/lightbox2/css/lightbox.css';

  /**
   * Liefert HTML-Code zur Darstellung eines Fotos in der Galerie.
   * @return string HTML-Code
   */
  function getGalleryImage($objectId, &$image, $index, $selectedImg, $lang) {

    // ggf. das Galeriebild dynamisch skalieren
    if ($this->exposeSetup != null && $this->exposeSetup->DynamicImageScaling === true && extension_loaded('gd')) {
      if (!isset($image['name']) || !is_string($image['name']))
        return '';
      $img = 'data/' . $objectId . '/' . $image['name'];
      if (!is_file(IMMOTOOL_BASE_PATH . $img))
        return null;
      $thumb = 'img.php?id=' . $objectId .
          '&amp;img=' . $image['name'] .
          '&amp;x=' . $this->exposeSetup->GalleryImageSize[0] .
          '&amp;y=' . $this->exposeSetup->GalleryImageSize[1];
    }

    // Galeriebild direkt ausliefern
    else {
      if (!isset($image['thumb']) || !is_string($image['thumb']))
        return '';
      $thumb = 'data/' . $objectId . '/' . $image['thumb'];
      if (!is_file(IMMOTOOL_BASE_PATH . $thumb))
        return null;
    }

    $file = 'data/' . $objectId . '/' . $image['name'];
    $title = (isset($image['title'][$lang])) ? $image['title'][$lang] : '';
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
    $header = array();

    // include Prototype
    if (is_string($this->PrototypeScript)) {
      $header[] = '<script type="text/javascript" src="' . $this->PrototypeScript . '"></script>';
    }

    // include Scriptaculous
    if (is_string($this->ScriptaculousScript)) {
      $header[] = '<script type="text/javascript" src="' . $this->ScriptaculousScript . '"></script>';
    }

    // init Lightbox
    $options = $this->getHeaderOptions();
    if (!is_array($options))
      $options = array();
    $headerOptions = array();
    foreach ($options as $key => $value)
      $headerOptions[] = $key . ': ' . $value;
    $header[] = '<script type="text/javascript">
<!--
LightboxOptions = Object.extend({
  ' . implode(",\n  ", $headerOptions) . '
}, window.LightboxOptions || {});
//-->
</script>';

    // include Lightbox
    if (is_string($this->LightboxScript)) {
      $header[] = '<script type="text/javascript" src="' . $this->LightboxScript . '"></script>';
    }

    // include Lightbox stylesheet
    if (is_string($this->LightboxStyle)) {
      $header[] = '<link rel="stylesheet" href="' . $this->LightboxStyle . '" type="text/css" media="screen" />';
    }

    return implode("\n", $header);
  }

  /**
   * Liefert ein Array mit Konfigurations-Werten der Lightbox-Galerie.
   * @return array Lightbox-Konfiguration
   */
  function getHeaderOptions() {
    return array(
      'fileLoadingImage' => '"./img/lightbox2/loading.gif"',
      'fileBottomNavCloseImage' => '"./img/lightbox2/closelabel.gif"',
      'overlayOpacity' => '0.5',
      'animate' => 'true',
      'resizeSpeed' => '8',
      'borderSize' => '10',
      'labelImage' => '"Image"',
      'labelOf' => '"of"',
    );
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

    $file = 'data/' . $objectId . '/' . $image['name'];
    if (!is_file(IMMOTOOL_BASE_PATH . $file)) {
      return null;
    }

    // ggf. das Titelbild dynamisch skalieren
    if ($this->exposeSetup != null && $this->exposeSetup->DynamicImageScaling === true && extension_loaded('gd')) {
      $thumb = 'img.php?id=' . $objectId .
          '&amp;img=' . $image['name'] .
          '&amp;x=' . $this->exposeSetup->TitleImageSize[0] .
          '&amp;y=' . $this->exposeSetup->TitleImageSize[1];
    }

    // Titelbild direkt ausliefern
    else {
      $thumb = 'data/' . $objectId . '/' . $image['thumb'];
      if (!is_file(IMMOTOOL_BASE_PATH . $thumb)) {
        return null;
      }
    }

    $link = '?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $objectId . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=gallery&amp;' . IMMOTOOL_PARAM_EXPOSE_IMG . '=1#img';
    $title = (isset($image['title'][$lang])) ? $image['title'][$lang] : '';
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
