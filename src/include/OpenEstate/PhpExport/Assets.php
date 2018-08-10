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
    const JQUERY_JS = 'jquery-js';

    /**
     * Currently provided version of Colorbox.
     *
     * @var string
     */
    const COLORBOX_VERSION = '1.6.4';

    /**
     * Internal name of the Colorbox Stylesheet.
     *
     * @var string
     */
    const COLORBOX_CSS = 'colorbox-css';

    /**
     * Internal name of the Colorbox JavaScript.
     *
     * @var string
     */
    const COLORBOX_JS = 'colorbox-js';

    /**
     * Internal name of the Colorbox JavaScript for localisation.
     *
     * @var string
     */
    const COLORBOX_I18N_JS = 'colorbox-i18n-js';

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
    function __construct(Environment &$env)
    {
        $this->env =& $env;
    }

    /**
     * Create all header elements for Colorbox.
     *
     * @param string $lang
     * language code
     *
     * @param bool $minimized
     * use minimized files
     *
     * @return array
     * array of HTML header elements
     */
    public function colorbox($lang = null, $minimized = true)
    {
        $elements = array();
        $elements[] = $this->colorbox_css($minimized);
        $elements[] = $this->colorbox_js($minimized);

        $i18n = $this->colorbox_i18n_js($lang, $minimized);
        if ($i18n !== null)
            $elements[] = $i18n;

        return $elements;
    }

    /**
     * Create Stylesheet header for Colorbox.
     *
     * @param bool $minimized
     * use minimized files
     *
     * @return Html\Stylesheet
     * HTML header element
     */
    public function colorbox_css($minimized = true)
    {
        $asset = 'assets/colorbox/colorbox.css';
        return Html\Stylesheet::newLink(
            self::COLORBOX_CSS,
            $this->env->getUrl($asset) . '?v=' . self::COLORBOX_VERSION
        );
    }

    /**
     * Create JavaScript header for Colorbox translations.
     *
     * @param $lang
     * language
     *
     * @param bool $minimized
     * use minimized files
     *
     * @return Html\Javascript |Html\Javascript|null
     * HTML header element or null, if the language is not available
     *
     * @noinspection PhpUnusedParameterInspection
     */
    public function colorbox_i18n_js($lang, $minimized = true)
    {
        $asset = 'assets/colorbox/i18n/jquery.colorbox-' . $lang . '.js';
        if (!\is_string($lang))
            return null;

        $i18nFile = $this->env->getPath($asset);
        if (!\is_file($i18nFile))
            return null;

        return Html\Javascript::newLink(
            self::COLORBOX_I18N_JS,
            $this->env->getUrl($asset) . '?v=' . self::COLORBOX_VERSION
        );
    }

    /**
     * Create JavaScript header for Colorbox.
     *
     * @param bool $minimized
     * use minimized files
     *
     * @return Html\Javascript
     * HTML header element
     */
    public function colorbox_js($minimized = true)
    {
        $asset = ($minimized === true) ?
            'assets/colorbox/jquery.colorbox-min.js' :
            'assets/colorbox/jquery.colorbox.js';

        return Html\Javascript::newLink(
            self::COLORBOX_JS,
            $this->env->getUrl($asset) . '?v=' . self::COLORBOX_VERSION
        );
    }

    /**
     * Create all header elements for JQuery.
     *
     * @param bool $minimized
     * use minimized files
     *
     * @return array
     * array of HTML header elements
     */
    public function jquery($minimized = true)
    {
        $elements = array();
        $elements[] = $this->jquery_js($minimized);
        return $elements;
    }

    /**
     * Create JavaScript header for JQuery.
     *
     * @param bool $minimized
     * use minimized files
     *
     * @return Html\Javascript
     * HTML header element
     */
    public function jquery_js($minimized = true)
    {
        $asset = ($minimized === true) ?
            'assets/jquery/jquery.min.js' :
            'assets/jquery/jquery.js';

        return Html\Javascript::newLink(
            self::JQUERY_JS,
            $this->env->getUrl($asset) . '?v=' . self::JQUERY_VERSION
        );
    }
}
