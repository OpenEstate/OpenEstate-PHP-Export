<?php
/*
 * Copyright 2009-2019 OpenEstate.org.
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
 * A translator, that allows customized
 * translations through the configuration class.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
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
    public function gettext($original, ...$parameters)
    {
        $lang = $this->getLanguage();
        $translation = $this->env->getConfig()->i18nGettext($lang, $original);
        if (!\is_string($translation))
            $translation = $this->env->getTheme()->i18nGettext($lang, $original);

        $text = (\is_string($translation)) ?
            $translation :
            parent::gettext($original);

        return (Utils::isEmptyArray($parameters)) ?
            $text : $this->replaceParameters($text, $parameters);
    }

    /**
     * @see \Gettext\TranslatorInterface::ngettext()
     *
     * {@inheritdoc}
     */
    public function ngettext($original, $plural, $value, ...$parameters)
    {
        $lang = $this->getLanguage();
        $translation = $this->env->getConfig()->i18nGettextPlural($lang, $original, $plural, $value);
        if (!\is_string($translation))
            $translation = $this->env->getTheme()->i18nGettextPlural($lang, $original, $plural, $value);

        $text = (\is_string($translation)) ?
            $translation :
            parent::ngettext($original, $plural, $value);

        return (Utils::isEmptyArray($parameters)) ?
            $text : $this->replaceParameters($text, $parameters);
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

    /**
     * Replace parameters in a translated text.
     *
     * @param string $text
     * translated text with placeholders
     *
     * @param array $parameters
     * variables
     *
     * @return string
     * translated text with replaced parameters
     */
    protected function replaceParameters($text, array $parameters)
    {
        if (Utils::isEmptyArray($parameters))
            return $text;

        //echo '<pre>'.print_r($parameters).'</pre>';

        $replacement = array();
        for ($i = 0; $i < \count($parameters); $i++) {
            $replacement['{' . $i . '}'] = $parameters[$i];
        }

        return \str_replace(
            \array_keys($replacement),
            \array_values($replacement),
            $text
        );
    }
}
