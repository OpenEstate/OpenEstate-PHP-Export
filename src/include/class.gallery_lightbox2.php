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
 * Website-Export, JS-Galerie, basierend auf Lightbox2.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @link http://www.huddletogether.com/projects/lightbox2/
 */

require_once(__DIR__ . '/class.gallery.php');

class ImmoToolGallery_lightbox2 extends ImmoToolGallery
{
    /**
     * Header inkl. Abhängigkeiten erzeugen.
     * @var bool
     */
    public $CompleteHeader = true;

    /**
     * Pfad zum JQuery-Javascript
     * @var string
     */
    public $JQueryScript = 'include/jquery/jquery.min.js';

    /**
     * Pfad zum Lightbox-Javascript
     * @var string
     */
    public $LightboxScript = 'include/lightbox2/lightbox.min.js';

    /**
     * Pfad zum Lightbox-Stylesheet
     * @var string
     */
    public $LightboxStyle = 'include/lightbox2/css/lightbox.min.css';

    /**
     * Liefert HTML-Code zur Darstellung eines Fotos in der Galerie.
     * @return string HTML-Code
     */
    public function getGalleryImage($objectId, &$image, $index, $selectedImg, $lang)
    {

        // ggf. das Galeriebild dynamisch skalieren
        if ($this->exposeSetup != null && $this->exposeSetup->DynamicImageScaling === true && extension_loaded('gd')) {
            if (!isset($image['name']) || !is_string($image['name']))
                return '';
            $img = 'data/' . $objectId . '/' . $image['name'];
            if (!is_file(immotool_functions::get_path($img)))
                return null;
            $thumb = 'img.php?id=' . $objectId .
                '&amp;img=' . $image['name'] .
                '&amp;x=' . $this->exposeSetup->GalleryImageSize[0] .
                '&amp;y=' . $this->exposeSetup->GalleryImageSize[1];
        } // Galeriebild direkt ausliefern
        else {
            if (!isset($image['thumb']) || !is_string($image['thumb']))
                return '';
            $thumb = 'data/' . $objectId . '/' . $image['thumb'];
            if (!is_file(immotool_functions::get_path($thumb)))
                return null;
        }

        $file = 'data/' . $objectId . '/' . $image['name'];
        $title = (isset($image['title'][$lang])) ? $image['title'][$lang] : '';
        if (!is_string($title))
            $title = '';
        else
            $title = htmlentities($title, ENT_QUOTES, 'UTF-8');
        return '<li><a href="' . $file . '" data-lightbox="gallery" data-title="' . $title . '" title="' . $title . '"><img src="' . $thumb . '" alt="' . $title . '" title="' . $title . '" border="0"/></a></li>';
    }

    /**
     * Liefert HTML-Code Einbindung der Galerie-Bibliothek erzeugen.
     * @return string HTML-Code
     */
    public function getHeader()
    {
        $header = array();

        // include JQuery
        if (is_string($this->JQueryScript)) {
            $header[] = '<script type="text/javascript" src="' . $this->JQueryScript . '"></script>';
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
lightbox.option({
  ' . implode(",\n  ", $headerOptions) . '
});
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
    public function getHeaderOptions()
    {
        return array(
            'albumLabel' => '"Image %1 of %2"',
            'wrapAround' => 'true'
        );
    }

    /**
     * Name der Galerie.
     * @return string Name
     */
    public function getName()
    {
        return 'lightbox2';
    }

    /**
     * HTML-Code zum Titelbild.
     * @return string
     */
    public function getTitleImage($objectId, &$image, $lang)
    {

        $file = 'data/' . $objectId . '/' . $image['name'];
        if (!is_file(immotool_functions::get_path($file))) {
            return null;
        }

        // ggf. das Titelbild dynamisch skalieren
        if ($this->exposeSetup != null && $this->exposeSetup->DynamicImageScaling === true && extension_loaded('gd')) {
            $thumb = 'img.php?id=' . $objectId .
                '&amp;img=' . $image['name'] .
                '&amp;x=' . $this->exposeSetup->TitleImageSize[0] .
                '&amp;y=' . $this->exposeSetup->TitleImageSize[1];
        } // Titelbild direkt ausliefern
        else {
            $thumb = 'data/' . $objectId . '/' . $image['thumb'];
            if (!is_file(immotool_functions::get_path($thumb))) {
                return null;
            }
        }

        $link = '?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $objectId . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=gallery&amp;' . IMMOTOOL_PARAM_EXPOSE_IMG . '=1#img';
        $title = (isset($image['title'][$lang])) ? $image['title'][$lang] : '';
        if (!is_string($title))
            $title = '';
        else
            $title = htmlentities($title, ENT_QUOTES, 'UTF-8');
        $html = '<a href="' . $file . '" data-lightbox="title" data-title="' . $title . '" title="' . $title . '"><img src="' . $thumb . '" alt="' . $title . '" title="' . $title . '" border="0"/></a>';

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
                $html .= '<a href="' . $file . '" data-lightbox="title" data-title="' . $title . '" title="' . $title . '">&nbsp;</a>';
            }
            $html .= '</div>';
        }
        return $html;
    }

    /**
     * Die Galerie setzt JavaScript vorraus.
     * @return bool
     */
    public function isJavaScriptRequired()
    {
        return true;
    }

    /**
     * Das gewählte Bild unterhalb der Galerie darstellen.
     * @return bool
     */
    public function isSelectedImagePrinted()
    {
        return false;
    }
}
