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
 * Website-Export, JS-Galerie, basierend auf Colorbox.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @link http://colorpowered.com/colorbox/
 */

require_once(__DIR__ . '/class.gallery.php');

class ImmoToolGallery_colorbox extends ImmoToolGallery
{
    /**
     * Pfad zum JQuery-Javascript
     * @var string
     */
    public $JQueryScript = 'include/jquery/jquery.min.js';

    /**
     * Pfad zum Colorbox-Javascript
     * @var string
     */
    public $ColorboxScript = 'include/colorbox/jquery.colorbox-min.js';

    /**
     * Pfad zum Colorbox-Stylesheet
     * @var string
     */
    public $ColorboxStyle = 'include/colorbox/colorbox.css';

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
        return '<li><a href="' . $file . '" rel="gallery" title="' . $title . '"><img src="' . $thumb . '" alt="' . $title . '" title="' . $title . '" border="0"/></a></li>';
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

        // include Colorbox
        if (is_string($this->ColorboxScript)) {
            $header[] = '<script type="text/javascript" src="' . $this->ColorboxScript . '"></script>';
        }

        // init Colorbox
        $options = $this->getHeaderOptions();
        if (!is_array($options))
            $options = array();
        $headerOptions = array();
        foreach ($options as $key => $value)
            $headerOptions[] = $key . ': ' . $value;
        $header[] = '<script type="text/javascript">
<!--
jQuery(document).ready(function(){
  jQuery("a[rel=\'gallery\']").colorbox({' . implode(', ', $headerOptions) . '});
  jQuery("a[rel=\'title\']").colorbox({' . implode(', ', $headerOptions) . '});
});
//-->
</script>';

        // include Colorbox stylesheet
        if (is_string($this->ColorboxStyle)) {
            $header[] = '<link rel="stylesheet" href="' . $this->ColorboxStyle . '" type="text/css" media="screen" />';
        }

        return implode("\n", $header);
    }

    /**
     * Liefert ein Array mit Konfigurations-Werten der Colorbox-Galerie.
     * @return array Colorbox-Konfiguration
     */
    public function getHeaderOptions()
    {
        return array(
            //'transition' => '"fade"',
            //'speed' => '350',
            'maxWidth' => '"90%"',
            'maxHeight' => '"90%"',
        );
    }

    /**
     * Name der Galerie.
     * @return string Name
     */
    public function getName()
    {
        return 'colorbox';
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
        $html = '<a href="' . $file . '" rel="title" title="' . $title . '"><img src="' . $thumb . '" alt="' . $title . '" title="' . $title . '" border="0"/></a>';

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
                $html .= '<a href="' . $file . '" rel="title" title="' . $title . '">&nbsp;</a>';
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
