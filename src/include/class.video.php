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
 * Website-Export, allgemeine Video-Einbindung.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

class ImmoToolVideo {

  var $width = 0;
  var $height = 0;

  function __construct() {

  }

  /**
   * Ein externes Video einbinden.
   * @param string $linkId ID des Videos beim Provider.
   * @param string $linkTitle Titel des Videos.
   * @param string $linkUrl URL zum Direktaufruf des Videos.
   * @param string $provider Name des Providers.
   * @return string HTML-Code des eingebundenen Videos.
   */
  function embed($linkId, $linkTitle, $linkUrl, $provider) {
    /* if (!is_array($link)) return null;

      $url = (isset($link['url']))? $link['url']: null;
      $id = (isset($link['id']))? $link['id']: null;
      $provider = (isset($link['provider']))? $link['provider']: null;
      if (!is_string($id) || !is_string($provider)) return null;

      $title = (isset($link['title'][$lang]))? $link['title'][$lang]: null;
      if (!is_string($title) || strlen($title)<1) $title = $id; */

    if ($provider == 'video@clipfish.de')
      return $this->embed_clipfish_de($linkId, $linkTitle, $linkUrl, $this->width, $this->height);

    if ($provider == 'video@clipshack.com')
      return $this->embed_clipshack_com($linkId, $linkTitle, $linkUrl, $this->width, $this->height);

    if ($provider == 'video@dailymotion.com')
      return $this->embed_dailymotion_com($linkId, $linkTitle, $linkUrl, $this->width, $this->height);

    if ($provider == 'video@myvideo.de')
      return $this->embed_myvideo_de($linkId, $linkTitle, $linkUrl, $this->width, $this->height);

    if ($provider == 'video@sevenload.com')
      return $this->embed_sevenload_com($linkId, $linkTitle, $linkUrl, $this->width, $this->height);

    if ($provider == 'video@veoh.com')
      return $this->embed_veoh_com($linkId, $linkTitle, $linkUrl, $this->width, $this->height);

    if ($provider == 'video@vimeo.com')
      return $this->embed_vimeo_com($linkId, $linkTitle, $linkUrl, $this->width, $this->height);

    if ($provider == 'video@youtube.com')
      return $this->embed_youtube_com($linkId, $linkTitle, $linkUrl, $this->width, $this->height);

    return null;
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
    return null;
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
    return null;
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
    return null;
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
    return null;
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
    return null;
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
    return null;
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
    return null;
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
    return null;
  }

  /**
   * Name des Video-Handlers.
   * @return string Name
   */
  function getName() {
    return null;
  }

}
