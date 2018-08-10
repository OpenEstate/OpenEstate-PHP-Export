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
 * Export environment.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class Environment
{
    /**
     * Absolute path, that points to the root of the export environment.
     *
     * @var string
     */
    private $basePath;

    /**
     * URL, that points to the root of the export environment.
     *
     * @var string
     */
    private $baseUrl;

    /**
     * Asset factory.
     *
     * @var Assets
     */
    private $assets;

    /**
     * Session of the requesting user.
     *
     * @var Session\AbstractSession
     */
    private $session = null;

    /**
     * Internal cache with objects data.
     *
     * @var array
     */
    private $objects = null;

    /**
     * Maximal number of objects to keep in the local cache.
     *
     * @var int
     */
    public $objectsCacheSize = 10;

    /**
     * Available languages.
     *
     * @var array
     */
    private $languages = null;

    /**
     * Environment constructor.
     *
     * @param string $basePath
     * absolute path of the export environment.
     *
     * @param string $baseUrl
     * URL of the export environment.
     *
     * @throws Exception\InvalidEnvironmentException
     * if the environment is not valid
     */
    function __construct($basePath, $baseUrl = null)
    {
        if ($basePath === null || !\is_string($basePath) || !\is_dir($basePath))
            throw new Exception\InvalidEnvironmentException('No valid base path was specified!');

        $this->basePath = $basePath;
        $this->baseUrl = (\is_string($baseUrl)) ?
            $baseUrl : './';
        $this->assets = new Assets($this);
    }

    /**
     * Environment destructor.
     */
    function __destruct()
    {
        $this->shutdown();
        $this->assets = null;
        $this->objects = null;
        $this->session = null;
    }

    /**
     * Get assets factory for this environment.
     *
     * @return Assets
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * Get ISO codes of available languages.
     *
     * @return array
     * ISO codes with available languages
     */
    public function getLanguageCodes()
    {
        return (\is_array($this->languages)) ?
            array_keys($this->languages) :
            array();
    }

    /**
     * Get name of a language.
     *
     * @param string $code
     * ISO language code
     *
     * @return string|null
     * name of the language or null, if the code is unknown
     */
    public function getLanguageName($code)
    {
        return (\is_array($this->languages) && isset($this->languages[$code])) ?
            $this->languages[$code] :
            null;
    }

    /**
     * Get the data of a real estate object.
     *
     * @param string $id
     * object ID
     *
     * @return array|null
     * an array with real estate data or null, if not found
     */
    public function getObject($id = null)
    {
        if ($id === null || !\is_string($id) || \preg_match('/^\w*/i', $id) !== 1)
            return null;

        $file = $this->getPath('data/' . $id . '/object.php');
        if (!\is_file($file))
            return null;

        if (!\is_array($this->objects))
            $this->objects = array();

        if (!isset($this->objects[$id])) {
            //echo 'LOAD OBJECT ' . $id . '<br>';
            $max = (int)$this->objectsCacheSize;
            while (\count($this->objects) >= $max) {
                $keys = \array_keys($this->objects);
                unset($this->objects[$keys[0]]);
            }
            /** @noinspection PhpIncludeInspection */
            $data = include($file);
            if (!\is_array($data))
                return null;

            $this->objects[$id] =& $data;
        }

        return $this->objects[$id];
    }

    /**
     * Get ID's of available real estate objects.
     *
     * @return array
     * array with object ID's
     */
    public function getObjectIds()
    {
        $dir = $this->getPath('data');
        $ids = array();
        if (\is_dir($dir)) {
            $files = Utils::listDirectory($dir);
            if (\is_array($files)) {
                foreach ($files as $file) {
                    if (\is_dir($dir . '/' . $file))
                        $ids[] = $file;
                }
            }
        }
        return $ids;
    }

    /**
     * Get the timestamp, when a real estate object was last modified.
     *
     * @param string $id
     * object ID
     *
     * @return int|null
     * timestamp of last modification or null, if not found
     */
    public function getObjectStamp($id = null)
    {
        if ($id == null || preg_match('/^\w*/i', $id) !== 1)
            return null;

        return Utils::getFileStamp($this->getPath('data/' . $id . '/object.php'));
    }

    /**
     * Get the absolute path of a file in the export environment.
     *
     * @param string $path
     * relative path of a file in the export environment
     *
     * @return string
     * absolute path of the file in the export environment
     */
    public function getPath($path = null)
    {
        if ($path === null || !\is_string($path))
            return $this->basePath;

        if (\substr($path, 0, 1) !== '/')
            $path = '/' . $path;

        return $this->basePath . $path;
    }

    /**
     * Get a value from the session store.
     *
     * @param $key
     * value name in the session store
     *
     * @return mixed|null
     * value from session store
     */
    public function getSessionValue($key)
    {
        return ($this->session !== null) ?
            $this->session->get($key) : null;
    }

    /**
     * Get the absolute path of a theme file.
     *
     * @param string $theme
     * theme name
     *
     * @param string $path
     * file name within the theme
     *
     * @return string
     * absolute path of the file in the theme
     */
    public function getThemePath($theme, $path = null)
    {
        if ($theme === null)
            return null;

        $themePath = $this->getPath('themes/' . $theme);
        if (!\is_dir($themePath))
            return null;

        if ($path === null || !\is_string($path))
            return $themePath;

        if (\substr($path, 0, 1) !== '/')
            $path = '/' . $path;

        return $themePath . $path;
    }

    /**
     * Get the URL of a theme file.
     *
     * @param string $theme
     * theme name
     *
     * @param string $path
     * file name within the theme
     *
     * @return string
     * URL of the file in the theme
     */
    public function getThemeUrl($theme, $path = null)
    {
        if ($theme === null)
            return null;

        $themeUrl = $this->getUrl('themes/' . $theme);
        if ($path === null || !\is_string($path))
            return $themeUrl;

        if (\substr($path, 0, 1) !== '/')
            $path = '/' . $path;

        return $themeUrl . $path;
    }

    /**
     * Get the URL of a file in the export environment.
     *
     * @param string $path
     * relative path of a file in the export environment
     *
     * @return string
     * URL of the file in the export environment
     */
    public function getUrl($path = null)
    {
        if ($path === null || !\is_string($path))
            return $this->baseUrl;

        if (\substr($path, 0, 1) !== '/')
            $path = '/' . $path;

        return $this->baseUrl . $path;
    }

    /**
     * Initialize the export environment.
     */
    public function init()
    {
        // init languages
        $languageFile = $this->getPath('data/language.php');

        /** @noinspection PhpIncludeInspection */
        $this->languages = (\is_file($languageFile)) ?
            $this->languages = require($languageFile) :
            array();

        // init session
        $this->session = new Session\CookieSession();
        $this->session->init($this);
    }

    /**
     * Set a value in session store.
     *
     * @param string $key
     * value name in the session store
     *
     * @param mixed $value
     * the value to store, or null to remove the value from session
     */
    public function setSessionValue($key, $value)
    {
        if ($this->session !== null)
            $this->session->set($key, $value);

    }

    /**
     * Shutdown export environment.
     */
    public function shutdown()
    {
        if ($this->session !== null) {
            $this->session->write($this);
            $this->session = null;
        }
    }
}
