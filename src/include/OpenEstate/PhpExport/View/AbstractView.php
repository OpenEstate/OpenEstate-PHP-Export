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

namespace OpenEstate\PhpExport\View;

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Theme\AbstractTheme;

/**
 * An abstract basic view.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
abstract class AbstractView
{
    /**
     * Export environment.
     *
     * @var Environment
     */
    protected $env;

    /**
     * HTTP response code for this view.
     *
     * @var int|null
     */
    private $httpResponseCode = null;

    /**
     * AbstractView constructor.
     *
     * @param Environment $env
     * export environment
     */
    function __construct(Environment $env)
    {
        $this->env = $env;
    }

    /**
     * AbstractView destructor.
     */
    public function __destruct()
    {
    }

    /**
     * Generate the view.
     *
     * @return string
     * generated output for the view
     */
    abstract protected function generate();

    /**
     * Get the content type of the current view.
     *
     * @return string
     * content type
     */
    public function getContentType()
    {
        return null;
    }

    /**
     * Get the export environment.
     *
     * @return Environment
     * export environment
     */
    public function getEnvironment()
    {
        return $this->env;
    }

    /**
     * Get the HTTP response code for this view.
     *
     * @return int|null
     * HTTP response code or null, if it was not set
     */
    final public function getHttpResponseCode()
    {
        return $this->httpResponseCode;
    }

    /**
     * Get parameter values for this view.
     *
     * @return array
     * associative array with parameter values
     */
    public function getParameters()
    {
        return array();
    }

    /**
     * Get the theme.
     *
     * @return AbstractTheme
     * theme
     */
    public function getTheme()
    {
        return $this->env->getTheme();
    }

    /**
     * Get absolute path to a theme file.
     *
     * @param string $path
     * relative path within the theme directory
     *
     * @return string|null
     * absolute path to the theme file or null, if it is not available
     */
    public function getThemeFile($path = null)
    {
        return $this->env->getThemePath($path);
    }

    /**
     * Get URL to a theme file.
     *
     * @param string $path
     * relative path within the theme directory
     *
     * @param array|null $parameters
     * associative array of URL parameters
     *
     * @return string|null
     * URL to the theme file or null, if it is not available
     */
    public function getThemeUrl($path = null, $parameters = null)
    {
        return $this->env->getThemeUrl($path, $parameters);
    }

    /**
     * Get url for this view.
     *
     * @param Environment $env
     * export environment
     *
     * @return string
     * url
     */
    public abstract function getUrl(Environment $env);

    /**
     * Execute a theme file and return its result.
     *
     * @param string $file
     * file from the theme directory to include
     *
     * @return mixed
     * response from the theme file
     *
     * @throws \Exception
     * if the theme file can't be processed
     */
    public function loadThemeFile($file)
    {
        $theme = $this->env->getTheme();
        $script = $this->getThemeFile($file);
        if (!\is_file($script) || !\is_readable($script))
            throw new \Exception('Can\'t find "' . $file . '" for theme "' . $theme->getName() . '" at "' . $script . '"!');

        if (!\ob_start())
            throw new \Exception('Can\'t start output buffering!');

        /**
         * Make the current view instance available for theme files as $view variable.
         *
         * @noinspection PhpUnusedLocalVariableInspection
         */
        $view = $this;

        /** @noinspection PhpIncludeInspection */
        include $script;

        return ($this->isTextType()) ?
            \trim(\ob_get_clean()) :
            \ob_get_clean();
    }

    /**
     * Determine, if the content type is text/html.
     *
     * @return bool
     * true, if the content type is text/html
     */
    public function isHtmlType()
    {
        $contentType = $this->getContentType();
        return \is_string($contentType)
            && \strpos(\trim(\strtolower($contentType)), 'text/html') === 0;
    }

    /**
     * Determine, if the content type is text/*.
     *
     * @return bool
     * true, if the content type is text/*
     */
    public function isTextType()
    {
        $contentType = $this->getContentType();
        return \is_string($contentType)
            && \strpos(\trim(\strtolower($contentType)), 'text/') === 0;
    }

    /**
     * Generate current view and return the response body.
     *
     * @param bool $sendHeaders
     * send header before returning the generated output
     *
     * @return mixed
     * generated output for the view
     */
    public function process($sendHeaders = true)
    {
        $output = $this->generate();

        if ($sendHeaders && !\headers_sent()) {
            $contentType = $this->getContentType();
            if ($contentType != null)
                \header('Content-Type: ' . $contentType);

            if (\is_int($this->httpResponseCode))
                \http_response_code($this->httpResponseCode);
        }

        return $output;
    }

    /**
     * Set the HTTP response code for this view.
     *
     * @param int|null $httpResponseCode
     * HTTP response code or null to send the default code
     */
    public function setHttpResponseCode($httpResponseCode)
    {
        $this->httpResponseCode = ($httpResponseCode !== null) ?
            (int)$httpResponseCode : null;
    }
}
