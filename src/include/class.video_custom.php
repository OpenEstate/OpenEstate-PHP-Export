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
 * Website-Export, individuell angepasste Video-Einbindung.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

require_once(__DIR__ . '/class.video_default.php');

class ImmoToolVideo_custom extends ImmoToolVideo_default
{
    function __construct()
    {
        parent::__construct(640, 480);
    }

    /**
     * Ein externes Video von d.tube einbinden.
     * @param string $linkId ID des Videos beim Provider.
     * @param string $linkTitle Titel des Videos.
     * @param string $linkUrl URL zum Direktaufruf des Videos.
     * @param int $width Breite des eingebundenen Videos in Pixeln.
     * @param int $height Höhe des eingebundenen Videos in Pixeln.
     * @return string HTML-Code des eingebundenen Videos.
     */
    public function embed_d_tube($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
        // Standard-Einbindung von d.tube erzeugen
        return parent::embed_d_tube($linkId, $linkTitle, $linkUrl, $width, $height);
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
        // Standard-Einbindung von dailymotion.com erzeugen
        return parent::embed_dailymotion_com($linkId, $linkTitle, $linkUrl, $width, $height);
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
        // Standard-Einbindung von panocreator.com erzeugen
        return parent::embed_panocreator_com($linkId, $linkTitle, $linkUrl, $width, $height);
    }

    /**
     * Eine externe Galerie von round.me einbinden.
     * @param string $linkId ID der Galerie beim Provider.
     * @param string $linkTitle Galerie der Videos.
     * @param string $linkUrl URL zum Direktaufruf der Galerie.
     * @param int $width Breite der eingebundenen Galerie in Pixeln.
     * @param int $height Höhe der eingebundenen Galerie in Pixeln.
     * @return string HTML-Code der eingebundenen Galerie.
     */
    public function embed_round_me($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
        // Standard-Einbindung von round.me erzeugen
        return parent::embed_round_me($linkId, $linkTitle, $linkUrl, $width, $height);
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
    public function embed_vimeo_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
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
    public function embed_youtube_com($linkId, $linkTitle, $linkUrl, $width = 0, $height = 0)
    {
        // Standard-Einbindung von youtube.com erzeugen
        return parent::embed_youtube_com($linkId, $linkTitle, $linkUrl, $width, $height);
    }

    /**
     * Name des Video-Handlers.
     * @return string Name
     */
    public function getName()
    {
        return 'custom';
    }
}
