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

namespace OpenEstate\PhpExport\Action;

use OpenEstate\PhpExport\Environment;

/**
 * Select current language.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class SetLanguage extends AbstractAction
{
    /**
     * Action name.
     *
     * @var string
     */
    const NAME = 'SetLanguage';

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
    function __construct($name = self::NAME)
    {
        parent::__construct($name);

        // add previously configured prefix to parameter names
        $this->languageParameter = Environment::parameter($this->languageParameter);
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
