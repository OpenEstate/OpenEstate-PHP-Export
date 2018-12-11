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

namespace OpenEstate\PhpExport;

/**
 * Factory for inclusion of globally provided assets
 * (e.g. Javascript, CSS).
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class Assets
{
    /**
     * Currently provided version of JQuery.
     *
     * @var string
     */
    const JQUERY_VERSION = '3.3.1';

    /**
     * Internal name of the JQuery JavaScript.
     *
     * @var string
     */
    const JQUERY_JS = 'openestate-jquery-js';

    /**
     * Internal name for the OpenEstate Icons Stylesheet.
     *
     * @var string
     */
    const OPENESTATE_ICONS_CSS = 'openestate-icons-css';

    /**
     * Internal name for the animated OpenEstate Icons Stylesheet.
     *
     * @var string
     */
    const OPENESTATE_ICONS_ANIMATION_CSS = 'openestate-icons-animation-css';

    /**
     * Currently provided version of slick.
     *
     * @var string
     */
    const SLICK_VERSION = '1.8.1';

    /**
     * Internal name of the slick JavaScript.
     *
     * @var string
     */
    const SLICK_JS = 'openestate-slick-js';

    /**
     * Internal name of the slick Stylesheet.
     *
     * @var string
     */
    const SLICK_CSS = 'openestate-slick-css';

    /**
     * Internal name of the slick default theme Stylesheet.
     *
     * @var string
     */
    const SLICK_THEME_CSS = 'openestate-slick-theme-css';

    /**
     * Export environment.
     *
     * @var Environment
     */
    private $env;

    /**
     * Assets constructor.
     *
     * @param Environment $env
     * export environment
     */
    function __construct(Environment $env)
    {
        $this->env = $env;
    }

    /**
     * Create all header elements for JQuery.
     *
     * @param bool $minimized
     * use minimized files
     *
     * @param bool $defer
     * defer execution of the external JavaScript
     *
     * @return array
     * array of HTML header elements
     */
    public function jquery($minimized = true, $defer = true)
    {
        $elements = array();
        $elements[] = $this->jquery_js($minimized, $defer);
        return $elements;
    }

    /**
     * Create JavaScript header for JQuery.
     *
     * @param bool $minimized
     * use minimized files
     *
     * @param bool $defer
     * defer execution of the external JavaScript
     *
     * @return Html\Javascript
     * HTML header element
     */
    public function jquery_js($minimized = true, $defer = true)
    {
        $asset = ($minimized === true) ?
            'jquery/jquery.min.js' :
            'jquery/jquery.js';

        return Html\Javascript::newLink(
            self::JQUERY_JS,
            $this->env->getAssetsUrl($asset, array('v' => self::JQUERY_VERSION)),
            null,
            null,
            $defer
        );
    }

    /**
     * Create all header elements for OpenEstate Icons.
     *
     * @param bool $minimized
     * use minimized files
     *
     * @return array
     * array of HTML header elements
     */
    public function openestate_icons($minimized = true)
    {
        $elements = array();
        $elements[] = $this->openestate_icons_css($minimized);
        $elements[] = $this->openestate_icons_animation_css($minimized);
        return $elements;
    }

    /**
     * Create Stylesheet header for animated OpenEstate Icons.
     *
     * @param bool $minimized
     * use minimized files
     *
     * @return Html\Stylesheet
     * HTML header element
     */
    public function openestate_icons_animation_css($minimized = true)
    {
        $asset = 'openestate-icons/css/openestate-animation.css';
        return Html\Stylesheet::newLink(
            self::OPENESTATE_ICONS_ANIMATION_CSS,
            $this->env->getAssetsUrl($asset, array('v' => VERSION))
        );
    }

    /**
     * Create Stylesheet header for OpenEstate Icons.
     *
     * @param bool $minimized
     * use minimized files
     *
     * @return Html\Stylesheet
     * HTML header element
     */
    public function openestate_icons_css($minimized = true)
    {
        $asset = 'openestate-icons/css/openestate-icons.css';
        return Html\Stylesheet::newLink(
            self::OPENESTATE_ICONS_CSS,
            $this->env->getAssetsUrl($asset, array('v' => VERSION))
        );
    }

    /**
     * Create all header elements for slick.
     *
     * @param bool $minimized
     * use minimized files
     *
     * @param bool $includeDefaultTheme
     * also import the default theme for slick
     *
     * @return array
     * array of HTML header elements
     */
    public function slick($minimized = true, $includeDefaultTheme = false)
    {
        $elements = array();
        $elements[] = $this->slick_js($minimized);
        $elements[] = $this->slick_css($minimized);
        if ($includeDefaultTheme === true)
            $elements[] = $this->slick_theme_css($minimized);
        return $elements;
    }

    /**
     * Create JavaScript header for slick.
     *
     * @param bool $minimized
     * use minimized files
     *
     * @param bool $defer
     * defer execution of the external JavaScript
     *
     * @return Html\Javascript
     * HTML header element
     */
    public function slick_js($minimized = true, $defer = true)
    {
        $asset = ($minimized === true) ?
            'slick/slick.min.js' :
            'slick/slick.js';

        return Html\Javascript::newLink(
            self::SLICK_JS,
            $this->env->getAssetsUrl($asset, array('v' => self::SLICK_VERSION)),
            null,
            null,
            $defer
        );
    }

    /**
     * Create Stylesheet header for slick.
     *
     * @param bool $minimized
     * use minimized files
     *
     * @return Html\Stylesheet
     * HTML header element
     */
    public function slick_css($minimized = true)
    {
        $asset = 'slick/slick.css';
        return Html\Stylesheet::newLink(
            self::SLICK_CSS,
            $this->env->getAssetsUrl($asset, array('v' => self::SLICK_VERSION))
        );
    }

    /**
     * Create Stylesheet header for slick default theme.
     *
     * @param bool $minimized
     * use minimized files
     *
     * @return Html\Stylesheet
     * HTML header element
     */
    public function slick_theme_css($minimized = true)
    {
        $asset = 'slick/slick-theme.css';
        return Html\Stylesheet::newLink(
            self::SLICK_THEME_CSS,
            $this->env->getAssetsUrl($asset, array('v' => self::SLICK_VERSION))
        );
    }
}
