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

namespace OpenEstate\PhpExport\Action;

use OpenEstate\PhpExport\Environment;

/**
 * Select current language.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class SetLanguage extends AbstractAction
{
    /**
     * Parameter name for the language code.
     *
     * @var string
     */
    public $languageParameter = 'lang';

    /**
     * SetLanguage constructor.
     *
     * @param string $name
     * internal name
     */
    function __construct($name = 'SetLanguage')
    {
        parent::__construct($name);
    }

    public function execute(Environment $env)
    {
        $language = (isset($_REQUEST[$this->languageParameter])) ?
            $_REQUEST[$this->languageParameter] : null;
        if (!\is_string($language))
            return false;

        $language = \trim($language);
        if (!\in_array($language, $env->getLanguageCodes()))
            return false;

        $env->setLanguage($language);
        return true;
    }

    /**
     * Get parameter values for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param string|null $language
     * language code
     *
     * @return array
     * associative array with parameter values
     */
    public function getParameters(Environment $env, $language = null)
    {
        $params = parent::getParameters($env);
        if ($language !== null && \is_string($language))
            $params[$this->languageParameter] = $language;
        return $params;
    }

    /**
     * Get url for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param string|null $language
     * language code
     *
     * @return string
     * url
     */
    public function getUrl(Environment $env, $language = null)
    {
        return $env->getActionUrl($this->getParameters($env, $language));
    }
}
