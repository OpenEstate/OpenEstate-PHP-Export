<?php
/*
 * PHP-Export scripts of OpenEstate-ImmoTool
 * Copyright (C) 2009-2018 OpenEstate.org
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

namespace OpenEstate\PhpExport;

/**
 * Factory for inclusion of globally provided assets
 * (e.g. Javascript, CSS).
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
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
}
