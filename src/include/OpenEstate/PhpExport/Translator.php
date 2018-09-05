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
 * A translator, that allows customized
 * translations through the configuration class.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class Translator extends \Gettext\Translator
{
    /**
     * Export environment.
     *
     * @var Environment
     */
    protected $env;

    /**
     * Custom ISO language code.
     *
     * @var string
     */
    private $languageCode;

    /**
     * Translator constructor.
     *
     * @param Environment $env
     * export environment
     *
     * @param string|null $languageCode
     * custom ISO language code
     */
    public function __construct($env, $languageCode = null)
    {
        $this->env = $env;
        $this->languageCode = $languageCode;
    }

    /**
     * Get language code of the translator.
     *
     * @return string
     * language code
     */
    public function getLanguage()
    {
        return (\is_string($this->languageCode)) ?
            $this->languageCode :
            $this->env->getLanguage();
    }

    /**
     * @see \Gettext\TranslatorInterface::gettext()
     *
     * {@inheritdoc}
     */
    public function gettext($original)
    {
        $lang = $this->getLanguage();
        $translation = $this->env->getConfig()->i18nGettext($lang, $original);
        if (!\is_string($translation))
            $translation = $this->env->getTheme()->i18nGettext($lang, $original);
        return (\is_string($translation)) ?
            $translation :
            parent::gettext($original);
    }

    /**
     * @see \Gettext\TranslatorInterface::ngettext()
     *
     * {@inheritdoc}
     */
    public function ngettext($original, $plural, $value)
    {
        $lang = $this->getLanguage();
        $translation = $this->env->getConfig()->i18nGettextPlural($lang, $original, $plural, $value);
        if (!\is_string($translation))
            $translation = $this->env->getTheme()->i18nGettextPlural($lang, $original, $plural, $value);
        return (\is_string($translation)) ?
            $translation :
            parent::ngettext($original, $plural, $value);
    }

    /**
     * @see \Gettext\BaseTranslator
     *
     * {@inheritdoc}
     */
    public static function includeFunctions()
    {
        include_once __DIR__ . '/translator_functions.php';
    }
}
