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

namespace OpenEstate\PhpExport\View;

/**
 * An abstract basic view.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class AbstractView
{
    /**
     * Export environment.
     *
     * @var \OpenEstate\PhpExport\Environment
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
     * @param \OpenEstate\PhpExport\Environment $env
     * export environment
     */
    function __construct(\OpenEstate\PhpExport\Environment $env)
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
     * @return \OpenEstate\PhpExport\Environment
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
     * @return \OpenEstate\PhpExport\Theme\AbstractTheme
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
        return $this->env->getTheme()->getPath($path);
    }

    /**
     * Get URL to a theme file.
     *
     * @param string $path
     * relative path within the theme directory
     *
     * @param $parameters
     * associative array of URL parameters
     *
     * @return string|null
     * URL to the theme file or null, if it is not available
     */
    public function getThemeUrl($path = null, $parameters = null)
    {
        return $this->env->getTheme()->getUrl($path, $parameters);
    }

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
