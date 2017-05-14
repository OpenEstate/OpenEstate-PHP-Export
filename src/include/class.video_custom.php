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
 * Website-Export, individuell angepasste Video-Einbindung.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

require_once( IMMOTOOL_BASE_PATH . 'include/class.video_default.php' );

class ImmoToolVideo_custom extends ImmoToolVideo_default {

  function __construct() {
    parent::__construct();
    $this->width = 640;
    $this->height = 480;
  }

  /**
   * Ein externes Video von clipfish.de einbinden.
   * @param string $linkId ID des Videos beim Provider.
   * @param string $linkTitle Titel des Videos.
   * @param string $linkUrl URL zum Direktaufruf des Videos.
   * @param int $width Breite des eingebundenen Videos in Pixeln.
   * @param int $height Höhe des eingebundenen Videos in Pixeln.
   * @return string HTML-Code des eingebundenen Videos.
   */
  function embed_clipfish_de($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0) {

    // Standard-Einbindung von clipfish.de erzeugen
    return parent::embed_clipfish_de($linkId, $linkTitle, $linkUrl, $width, $height);
  }

  /**
   * Ein externes Video von clipshack.com einbinden.
   * @param string $linkId ID des Videos beim Provider.
   * @param string $linkTitle Titel des Videos.
   * @param string $linkUrl URL zum Direktaufruf des Videos.
   * @param int $width Breite des eingebundenen Videos in Pixeln.
   * @param int $height Höhe des eingebundenen Videos in Pixeln.
   * @return string HTML-Code des eingebundenen Videos.
   */
  function embed_clipshack_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0) {

    // Standard-Einbindung von clipshack.com erzeugen
    return parent::embed_clipshack_com($linkId, $linkTitle, $linkUrl, $width, $height);
  }

  /**
   * Ein externes Video von dailymotion.com einbinden.
   * @param string $linkId ID des Videos beim Provider.
   * @param string $linkTitle Titel des Videos.
   * @param string $linkUrl URL zum Direktaufruf des Videos.
   * @param int $width Breite des eingebundenen Videos in Pixeln.
   * @param int $height Höhe des eingebundenen Videos in Pixeln.
   * @return string HTML-Code des eingebundenen Videos.
   */
  function embed_dailymotion_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0) {

    // Standard-Einbindung von dailymotion.com erzeugen
    return parent::embed_dailymotion_com($linkId, $linkTitle, $linkUrl, $width, $height);
  }

  /**
   * Ein externes Video von myvideo.de einbinden.
   * @param string $linkId ID des Videos beim Provider.
   * @param string $linkTitle Titel des Videos.
   * @param string $linkUrl URL zum Direktaufruf des Videos.
   * @param int $width Breite des eingebundenen Videos in Pixeln.
   * @param int $height Höhe des eingebundenen Videos in Pixeln.
   * @return string HTML-Code des eingebundenen Videos.
   */
  function embed_myvideo_de($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0) {

    // Standard-Einbindung von myvideo.de erzeugen
    return parent::embed_myvideo_de($linkId, $linkTitle, $linkUrl, $width, $height);
  }

  /**
   * Eine externe Galerie von panocreator.com einbinden.
   * @param string $linkId ID der Galerie beim Provider.
   * @param string $linkTitle Galerie der Videos.
   * @param string $linkUrl URL zum Direktaufruf der Galerie.
   * @param int $width Breite der eingebundenen Galerie in Pixeln.
   * @param int $height Höhe der eingebundenen Galerie in Pixeln.
   * @return string HTML-Code der eingebundenen Galerie.
   */
  function embed_panocreator_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0) {

    // Standard-Einbindung von panocreator.com erzeugen
    return parent::embed_panocreator_com($linkId, $linkTitle, $linkUrl, $width, $height);
  }

  /**
   * Ein externes Video von sevenload.com einbinden.
   * @param string $linkId ID des Videos beim Provider.
   * @param string $linkTitle Titel des Videos.
   * @param string $linkUrl URL zum Direktaufruf des Videos.
   * @param int $width Breite des eingebundenen Videos in Pixeln.
   * @param int $height Höhe des eingebundenen Videos in Pixeln.
   * @return string HTML-Code des eingebundenen Videos.
   */
  function embed_sevenload_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0) {

    // Standard-Einbindung von sevenload.com erzeugen
    return parent::embed_sevenload_com($linkId, $linkTitle, $linkUrl, $width, $height);
  }

  /**
   * Ein externes Video von veoh.com einbinden.
   * @param string $linkId ID des Videos beim Provider.
   * @param string $linkTitle Titel des Videos.
   * @param string $linkUrl URL zum Direktaufruf des Videos.
   * @param int $width Breite des eingebundenen Videos in Pixeln.
   * @param int $height Höhe des eingebundenen Videos in Pixeln.
   * @return string HTML-Code des eingebundenen Videos.
   */
  function embed_veoh_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0) {

    // Standard-Einbindung von veoh.com erzeugen
    return parent::embed_veoh_com($linkId, $linkTitle, $linkUrl, $width, $height);
  }

  /**
   * Ein externes Video von vimeo.com einbinden.
   * @param string $linkId ID des Videos beim Provider.
   * @param string $linkTitle Titel des Videos.
   * @param string $linkUrl URL zum Direktaufruf des Videos.
   * @param int $width Breite des eingebundenen Videos in Pixeln.
   * @param int $height Höhe des eingebundenen Videos in Pixeln.
   * @return string HTML-Code des eingebundenen Videos.
   */
  function embed_vimeo_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0) {

    // Standard-Einbindung von vimeo.com erzeugen
    return parent::embed_vimeo_com($linkId, $linkTitle, $linkUrl, $width, $height);
  }

  /**
   * Ein externes Video von youtube.com einbinden.
   * @param string $linkId ID des Videos beim Provider.
   * @param string $linkTitle Titel des Videos.
   * @param string $linkUrl URL zum Direktaufruf des Videos.
   * @param int $width Breite des eingebundenen Videos in Pixeln.
   * @param int $height Höhe des eingebundenen Videos in Pixeln.
   * @return string HTML-Code des eingebundenen Videos.
   */
  function embed_youtube_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0) {

    // Standard-Einbindung von youtube.com erzeugen
    return parent::embed_youtube_com($linkId, $linkTitle, $linkUrl, $width, $height);
  }

  /**
   * Name des Video-Handlers.
   * @return string Name
   */
  function getName() {
    return 'custom';
  }

}
