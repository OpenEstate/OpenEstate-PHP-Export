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

/**
 * Website-Export, standardmäßige Video-Einbindung.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

require_once(__DIR__ . '/class.video.php');

class ImmoToolVideo_default extends ImmoToolVideo
{
    function __construct()
    {
        parent::__construct(560, 315);
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
    public function embed_dailymotion_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
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
     * Eine externe Galerie von panocreator.com einbinden.
     * @param string $linkId ID der Galerie beim Provider.
     * @param string $linkTitle Galerie der Videos.
     * @param string $linkUrl URL zum Direktaufruf der Galerie.
     * @param int $width Breite der eingebundenen Galerie in Pixeln.
     * @param int $height Höhe der eingebundenen Galerie in Pixeln.
     * @return string HTML-Code der eingebundenen Galerie.
     */
    public function embed_panocreator_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
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
     * Ein externes Video von veoh.com einbinden.
     * @param string $linkId ID des Videos beim Provider.
     * @param string $linkTitle Titel des Videos.
     * @param string $linkUrl URL zum Direktaufruf des Videos.
     * @param int $width Breite des eingebundenen Videos in Pixeln.
     * @param int $height Höhe des eingebundenen Videos in Pixeln.
     * @return string HTML-Code des eingebundenen Videos.
     */
    public function embed_veoh_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
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
    public function embed_vimeo_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
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
    public function embed_youtube_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
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
    public function getName()
    {
        return 'default';
    }
}
