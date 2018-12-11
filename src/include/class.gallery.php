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
 * Website-Export, allgemeine Galerie.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class ImmoToolGallery
{
    public $exposeSetup = null;

    /**
     * Liefert HTML-Code zur Darstellung der Galerie.
     * @return string HTML-Code
     */
    public function getGallery(&$object, $selectedImg, $lang)
    {
        $list = '';
        if (isset($object['images']) && is_array($object['images'])) {
            foreach ($object['images'] as $pos => $image) {
                $list .= $this->getGalleryImage($object['id'], $image, $pos, $selectedImg, $lang);
            }
        }
        return '<ul>' . $list . '</ul>';
    }

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

        $class = (($index + 1) == $selectedImg) ? 'class="selected"' : '';
        $link = '?' . IMMOTOOL_PARAM_EXPOSE_ID . '=' . $objectId . '&amp;' . IMMOTOOL_PARAM_EXPOSE_VIEW . '=gallery&amp;' . IMMOTOOL_PARAM_EXPOSE_IMG . '=' . ($index + 1) . '#img';
        $title = (isset($image['title'][$lang])) ? $image['title'][$lang] : '';
        if (!is_string($title))
            $title = '';
        else
            $title = htmlentities($title, ENT_QUOTES, 'UTF-8');
        return '<li ' . $class . '><a href="' . $link . '" title="' . $title . '"><img src="' . $thumb . '" title="' . $title . '" alt="" border="0"/></a></li>';
    }

    public function getHeader()
    {
        return null;
    }

    /**
     * Name der Galerie.
     * @return string Name
     */
    public function getName()
    {
        return null;
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
        return '<a href="' . $link . '" title="' . $title . '"><img src="' . $thumb . '" alt="' . $title . '" title="' . $title . '" border="0"/></a>';
    }

    /**
     * Die Galerie setzt JavaScript vorraus.
     * @return bool
     */
    public function isJavaScriptRequired()
    {
        return false;
    }

    /**
     * Das gewählte Bild unterhalb der Galerie darstellen.
     * @return bool
     */
    public function isSelectedImagePrinted()
    {
        return true;
    }

    /**
     * Registriert die Konfiguration des aufrufenden Exposés.
     */
    public function setExposeSetup(&$setup)
    {
        $this->exposeSetup = $setup;
    }
}
