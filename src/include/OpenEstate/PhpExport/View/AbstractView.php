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
     * Internal name of the view.
     *
     * @var string
     */
    private $name;

    /**
     * Name of the theme used by this view.
     *
     * @var string
     */
    public $theme;

    /**
     * BasicView constructor.
     *
     * @param string $name
     * internal name of the view
     *
     * @param string $theme
     * name of the theme
     */
    function __construct($name, $theme = null)
    {
        $this->name = $name;
        $this->theme = (\is_string($theme)) ?
            $theme : 'default';
    }

    /**
     * Generate the view.
     *
     * @param \OpenEstate\PhpExport\Environment $env
     * export environment
     *
     * @return string
     * generated HTML code
     */
    abstract public function generate(\OpenEstate\PhpExport\Environment &$env);

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
     * Get the language of the current view.
     *
     * @return string
     * language code
     */
    public function getLanguage()
    {
        return 'de';
    }

    /**
     * Get internal name of the view.
     *
     * @return string
     * internal name
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * Get the name of the theme.
     *
     * @return string
     * theme name
     */
    public function getThemeName()
    {
        return $this->theme;
    }

    /**
     * Get absolute path to a theme file.
     *
     * @param \OpenEstate\PhpExport\Environment $env
     * export environment
     *
     * @param string $path
     * relative path within the theme directory
     *
     * @return string|null
     * absolute path to the theme file or null, if it is not available
     */
    public function getThemeFile(\OpenEstate\PhpExport\Environment &$env, $path = null)
    {
        $fullPath = $env->getThemePath($this->theme, $path);
        return ($fullPath !== null && \is_file($fullPath)) ?
            $fullPath : null;
    }

    /**
     * Get URL to a theme file.
     *
     * @param \OpenEstate\PhpExport\Environment $env
     * export environment
     *
     * @param string $path
     * relative path within the theme directory
     *
     * @return string|null
     * URL to the theme file or null, if it is not available
     */
    public function getThemeUrl(\OpenEstate\PhpExport\Environment &$env, $path = null)
    {
        $url = $env->getThemeUrl($this->theme, $path);
        return ($url !== null && \is_file($url)) ?
            $url : null;
    }

    /**
     * Execute a theme file and return its result.
     *
     * @param \OpenEstate\PhpExport\Environment $env
     * export environment
     *
     * @param string $file
     * file from the theme directory to include
     *
     * @return mixed
     * response from the theme file
     *
     * @throws \OpenEstate\PhpExport\Exception\ThemeException
     * if the theme file can't be processed
     */
    public function loadThemeFile(\OpenEstate\PhpExport\Environment &$env, $file)
    {
        $script = $this->getThemeFile($env, $file);
        if ($script === null || !\is_file($script) || !\is_readable($script))
            throw new \OpenEstate\PhpExport\Exception\ThemeException(
                'Can\'t find "' . $file . '" for theme "' . $this->theme . '"!',
                $script
            );

        try {
            if (!\ob_start())
                throw new \Exception('Can\'t start output buffering!');

            /** @noinspection PhpIncludeInspection */
            include $script;

            return ($this->isTextType()) ?
                \trim(\ob_get_clean()) :
                \ob_get_clean();
        } catch (\Exception $e) {
            throw new \OpenEstate\PhpExport\Exception\ThemeException(
                'Can\'t process "' . $file . '" for theme "' . $this->theme . '"!',
                $script,
                0,
                $e
            );
        }
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
     * @param \OpenEstate\PhpExport\Environment $env
     * export environment
     *
     * @return mixed
     * generated response body
     */
    public function process(\OpenEstate\PhpExport\Environment &$env)
    {
        if (!\headers_sent()) {
            $contentType = $this->getContentType();
            if ($contentType != null)
                \header('Content-Type: ' . $contentType);

        }

        return $this->generate($env);
    }
}
