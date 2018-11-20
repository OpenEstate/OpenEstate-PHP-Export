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
 * Website-Export, allgemeine Video-Einbindung.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class ImmoToolVideo
{
    public $width = 0;
    public $height = 0;

    function __construct($width = 0, $height = 0)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Ein externes Video einbinden.
     * @param string $linkId ID des Videos beim Provider.
     * @param string $linkTitle Titel des Videos.
     * @param string $linkUrl URL zum Direktaufruf des Videos.
     * @param string $provider Name des Providers.
     * @return string HTML-Code des eingebundenen Videos.
     */
    public function embed($linkId, $linkTitle, $linkUrl, $provider)
    {
        if ($provider == 'gallery@panocreator.com')
            return $this->embed_panocreator_com($linkId, $linkTitle, $linkUrl, $this->width, $this->height);

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
    public function embed_clipfish_de($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
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
    public function embed_clipshack_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
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
    public function embed_dailymotion_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
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
    public function embed_myvideo_de($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
        return null;
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
    public function embed_sevenload_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
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
    public function embed_veoh_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
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
    public function embed_vimeo_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
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
    public function embed_youtube_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
        return null;
    }

    /**
     * Name des Video-Handlers.
     * @return string Name
     */
    public function getName()
    {
        return null;
    }
}