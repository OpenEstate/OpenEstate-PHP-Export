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
 * Website-Export, standardmäßige Video-Einbindung.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2014, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('IN_WEBSITE'))
  exit;

require_once( IMMOTOOL_BASE_PATH . 'include/class.video.php' );

class ImmoToolVideo_default extends ImmoToolVideo {

  function __construct() {
    parent::__construct();
    $this->width = 560;
    $this->height = 315;
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
    $width = ($width > 0) ? $width : 464;
    $height = ($height > 0) ? $height : 384;
    return '<div class="video_clipfish_de" style="width:' . $width . 'px; margin-bottom:0.5em; margin-top:0.5em;">'
        . "\n"

        // IFrame
        . '<div class="video_container" style="width:' . $width . 'px; height:' . $height . 'px;">'
        . '<iframe src="http://www.clipfish.de/embed_video/?vid=' . $linkId . '&amp;as=0&amp;butcolor=990000"'
        . ' name="Clipfish Embedded Video"'
        . ' width="' . $width . '"'
        . ' height="' . $height . '"'
        . ' align="left"'
        . ' marginheight="0"'
        . ' marginwidth="0"'
        . ' frameborder="0"'
        . ' scrolling="no">'
        . '</iframe>'
        . '</div>'
        . "\n"

        // Provider-Link
        . '<div class="video_provider" style="text-align:right;">'
        . '<a href="' . htmlspecialchars($linkUrl) . '" target="_blank">' . htmlspecialchars($linkTitle) . '</a>'
        . ' @ <a href="http://www.clipfish.de/" target="_blank">clipfish.de</a>'
        . '</div>'
        . "\n"
        . '</div>';
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
    $width = ($width > 0) ? $width : 400;
    $height = ($height > 0) ? $height : 320;
    return '<div class="video_clipshack_com" style="width:' . $width . 'px; margin-bottom:0.5em; margin-top:0.5em;">'
        . "\n"

        // Flash
        . '<div class="video_container" style="width:"' . $width . 'px; height:' . $height . 'px;">'
        . '<embed src="http://clipshack.com/mediaplayer.swf"'
        . ' width="' . $width . '"'
        . ' height="' . $height . '"'
        . ' type="application/x-shockwave-flash"'
        . ' allowscriptaccess="always"'
        . ' allowfullscreen="true"'
        . ' flashvars="config=http://clipshack.com/playerconfig.aspx?key=' . $linkId . '&amp;embed=true">'
        . '</embed>'
        . '</div>'
        . "\n"

        // Provider-Link
        . '<div class="video_provider" style="text-align:right;">'
        . '<a href="' . htmlspecialchars($linkUrl) . '" target="_blank">' . htmlspecialchars($linkTitle) . '</a>'
        . ' @ <a href="http://clipshack.com/" target="_blank">clipshack.com</a>'
        . '</div>'
        . "\n"
        . '</div>';
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
    $width = ($width > 0) ? $width : 480;
    $height = ($height > 0) ? $height : 270;
    return '<div class="video_dailymotion_com" style="width:' . $width . 'px; margin-bottom:0.5em; margin-top:0.5em;">'
        . "\n"

        // IFrame
        . '<div class="video_container" style="width:' . $width . 'px; height:' . $height . 'px;">'
        . '<iframe src="https://www.dailymotion.com/embed/video/' . $linkId . '"'
        . ' width="' . $width . '"'
        . ' height="' . $height . '"'
        . ' align="left"'
        . ' marginheight="0"'
        . ' marginwidth="0"'
        . ' frameborder="0"'
        . ' scrolling="no">'
        . '</iframe>'
        . '</div>'
        . "\n"

        // Provider-Link
        . '<div class="video_provider" style="text-align:right;">'
        . '<a href="' . htmlspecialchars($linkUrl) . '" target="_blank">' . htmlspecialchars($linkTitle) . '</a>'
        . ' @ <a href="https://www.dailymotion.com/" target="_blank">dailymotion.com</a>'
        . '</div>'
        . "\n"
        . '</div>';
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
    $width = ($width > 0) ? $width : 611;
    $height = ($height > 0) ? $height : 383;
    return '<div class="video_myvideo_de" style="width:' . $width . 'px; margin-bottom:0.5em; margin-top:0.5em;">'
        . "\n"

        // IFrame
        . '<div class="video_container" style="width:' . $width . 'px; height:' . $height . 'px;">'
        . '<iframe src="https://www.myvideo.de/embed/' . $linkId . '"'
        . ' width="' . $width . '"'
        . ' height="' . $height . '"'
        . ' align="left"'
        . ' marginheight="0"'
        . ' marginwidth="0"'
        . ' frameborder="0"'
        . ' scrolling="no">'
        . '</iframe>'
        . '</div>'
        . "\n"

        // Provider-Link
        . '<div class="video_provider" style="text-align:right;">'
        . '<a href="' . htmlspecialchars($linkUrl) . '" target="_blank">' . htmlspecialchars($linkTitle) . '</a>'
        . ' @ <a href="https://www.myvideo.de/" target="_blank">myvideo.de</a>'
        . '</div>'
        . "\n"
        . '</div>';
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
    //$width = ($width > 0) ? $width : 600;
    $height = ($height > 0) ? $height : 500;
    return '<div class="gallery_panocreator_com" style="width:100%; margin-bottom:0.5em; margin-top:0.5em;">'
        . "\n"

        // IFrame
        . '<div class="gallery_container" style="width:100%; height:' . $height . 'px;">'
        . '<iframe src="https://panocreator.com/view/gallery/id/' . $linkId . '"'
        . ' width="100%"'
        . ' height="' . $height . '"'
        . ' align="left"'
        . ' marginheight="0"'
        . ' marginwidth="0"'
        . ' frameborder="0"'
        . ' scrolling="no">'
        . '</iframe>'
        . '</div>'
        . "\n"

        // Provider-Link
        . '<div class="gallery_provider" style="text-align:right;">'
        . '<a href="' . htmlspecialchars($linkUrl) . '" target="_blank">' . htmlspecialchars($linkTitle) . '</a>'
        . ' @ <a href="http://www.panocreator.com/" target="_blank">panocreator.com</a>'
        . '</div>'
        . "\n"
        . '</div>';
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
    $width = ($width > 0) ? $width : 500;
    $height = ($height > 0) ? $height : 408;
    return '<div class="video_sevenload_com" style="width:' . $width . 'px; margin-bottom:0.5em; margin-top:0.5em;">'
        . "\n"

        // Flash
        . '<div class="video_container" style="width:' . $width . 'px; height:' . $height . 'px;">'
        . '<object data="http://sevenload.com/pl/' . $linkId . '/' . $width . 'x' . $height . '/swf"'
        . ' type="application/x-shockwave-flash"'
        . ' width="' . $width . '"'
        . ' height="' . $height . '">'
        . '<param name="allowFullscreen" value="true" />'
        . '<param name="allowScriptAccess" value="always" />'
        . '<param name="movie" value="http://sevenload.com/pl/' . $linkId . '/500x408/swf" />'
        . '</object>'
        . '</div>'
        . "\n"

        // Provider-Link
        . '<div class="video_provider" style="text-align:right;">'
        . '<a href="' . htmlspecialchars($linkUrl) . '" target="_blank">' . htmlspecialchars($linkTitle) . '</a>'
        . ' @ <a href="http://sevenload.com/" target="_blank">sevenload.com</a>'
        . '</div>'
        . "\n"
        . '</div>';
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
    $width = ($width > 0) ? $width : 410;
    $height = ($height > 0) ? $height : 341;
    return '<div class="video_veoh_com" style="width:' . $width . 'px; margin-bottom:0.5em; margin-top:0.5em;">'
        . "\n"

        // Flash
        . '<div class="video_container" style="width:' . $width . 'px; height:' . $height . 'px;">'
        . '<object id="veohFlashPlayer"'
        . ' width="' . $width . '"'
        . ' height="' . $height . '">'
        . '<param name="allowFullscreen" value="true" />'
        . '<param name="allowScriptAccess" value="always" />'
        . '<param name="movie" value="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1343&amp;permalinkId=' . $linkId . '&amp;player=videodetailsembedded&amp;videoAutoPlay=0&amp;id=anonymous" />'
        . '<embed src="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1343&amp;permalinkId=' . $linkId . '&amp;player=videodetailsembedded&amp;videoAutoPlay=0&amp;id=anonymous"'
        . ' type="application/x-shockwave-flash"'
        . ' allowscriptaccess="always"'
        . ' allowfullscreen="true"'
        . ' width="' . $width . '"'
        . ' height="' . $height . '"'
        . ' id="veohFlashPlayerEmbed"'
        . ' name="veohFlashPlayerEmbed"/>'
        . '</object>'
        . '</div>'
        . "\n"

        // Provider-Link
        . '<div class="video_provider" style="text-align:right;">'
        . '<a href="' . htmlspecialchars($linkUrl) . '" target="_blank">' . htmlspecialchars($linkTitle) . '</a>'
        . ' @ <a href="http://www.veoh.com/" target="_blank">veoh.com</a>'
        . '</div>'
        . "\n"
        . '</div>';
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
    $width = ($width > 0) ? $width : 533;
    $height = ($height > 0) ? $height : 300;
    return '<div class="video_vimeo_com" style="width:' . $width . 'px; margin-bottom:0.5em; margin-top:0.5em;">'
        . "\n"

        // IFrame
        . '<div class="video_container" style="width:' . $width . 'px; height:' . $height . 'px;">'
        . '<iframe src="https://player.vimeo.com/video/' . $linkId . '?title=0&amp;byline=0&amp;portrait=0"'
        . ' width="' . $width . '"'
        . ' height="' . $height . '"'
        . ' align="left"'
        . ' marginheight="0"'
        . ' marginwidth="0"'
        . ' frameborder="0"'
        . ' scrolling="no">'
        . '</iframe>'
        . '</div>'
        . "\n"

        // Provider-Link
        . '<div class="video_provider" style="text-align:right;">'
        . '<a href="' . htmlspecialchars($linkUrl) . '" target="_blank">' . htmlspecialchars($linkTitle) . '</a>'
        . ' @ <a href="https://vimeo.com/" target="_blank">vimeo.com</a>'
        . '</div>'
        . "\n"
        . '</div>';
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
    $width = ($width > 0) ? $width : 560;
    $height = ($height > 0) ? $height : 315;
    return '<div class="video_youtube_com" style="width:' . $width . 'px; margin-bottom:0.5em; margin-top:0.5em;">'
        . "\n"

        // IFrame
        . '<div class="video_container" style="width:' . $width . 'px; height:' . $height . 'px;">'
        . '<iframe src="https://www.youtube-nocookie.com/embed/' . $linkId . '?rel=0"'
        . ' width="' . $width . '"'
        . ' height="' . $height . '"'
        . ' align="left"'
        . ' marginheight="0"'
        . ' marginwidth="0"'
        . ' frameborder="0"'
        . ' scrolling="no">'
        . '</iframe>'
        . '</div>'
        . "\n"

        // Provider-Link
        . '<div class="video_provider" style="text-align:right;">'
        . '<a href="' . htmlspecialchars($linkUrl) . '" target="_blank">' . htmlspecialchars($linkTitle) . '</a>'
        . ' @ <a href="https://www.youtube.com/" target="_blank">youtube.com</a>'
        . '</div>'
        . "\n"
        . '</div>';
  }

  /**
   * Name des Video-Handlers.
   * @return string Name
   */
  function getName() {
    return 'default';
  }

}
