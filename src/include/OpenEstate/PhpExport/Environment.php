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
     * Configuration of the export environment.
     *
     * @var Config
     */
    private $config;

    /**
     * Asset factory.
     *
     * @var Assets
     */
    private $assets;

    /**
     * Configured theme.
     *
     * @var Theme\AbstractTheme
     */
    private $theme = null;

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
     * Internal cache with object texts.
     *
     * @var array
     */
    private $objectTexts = null;

    /**
     * Maximal number of object texts to keep in the local cache.
     *
     * @var int
     */
    public $objectTextsCacheSize = 10;

    /**
     * Current language.
     *
     * @var string
     */
    private $language;

    /**
     * Available languages.
     *
     * @var array
     */
    private $languages = null;

    /**
     * Translator.
     *
     * var Translator
     */
    private $translator = null;

    /**
     * Translations loaded from data directory.
     *
     * var array
     */
    private $translations = null;

    /**
     * Name of the action parameter.
     *
     * @var string
     */
    public $actionParameter = 'action';

    /**
     * Environment constructor.
     *
     * @param Config $config
     * configuration
     *
     * @param bool $initSession
     * load session during initialization
     *
     * @throws \Exception
     * if the environment is not valid
     */
    function __construct(Config $config, $initSession = true)
    {
        $this->config = $config;
        $this->config->setupEnvironment($this);

        // create theme
        $this->theme = $this->config->newTheme($this);
        if (!($this->theme instanceof Theme\AbstractTheme))
            throw new \Exception('The theme does not implement AbstractTheme!');
        $this->config->setupTheme($this->theme);

        // create asset factory
        $this->assets = new Assets($this);

        if ($this->getConfig()->compatibility == 0) {
            if (!defined('IN_WEBSITE'))
                define('IN_WEBSITE', 1);
        }

        // init languages
        $languageFile = $this->getPath('data/language.php');

        $this->languages = array();
        if (\is_file($languageFile)) {
            if ($this->getConfig()->compatibility == 0) {
                /** @noinspection PhpIncludeInspection */
                require $languageFile;

                if (isset($GLOBALS['immotool_languages'])) {
                    $this->languages = $GLOBALS['immotool_languages'];
                    unset($GLOBALS['immotool_languages']);
                }
            } else {
                /** @noinspection PhpIncludeInspection */
                $this->languages = require $languageFile;
            }
        }

        // init session
        if ($initSession === true) {
            $this->session = $this->config->newSession($this);
            $this->session->init();
        } else {
            $this->session = null;
        }

        // detect user language
        $lang = ($this->session !== null) ?
            $this->session->getLanguage() : null;
        if (!\is_string($lang)) {
            foreach (Utils::getUserLanguages() as $userLang) {
                $userLang = \strtolower($userLang);
                foreach ($this->getLanguageCodes() as $availableLang) {
                    $availableLang = \strtolower($availableLang);
                    if ($userLang == $availableLang) {
                        $lang = $availableLang;
                        break;
                    }
                }
                if ($lang !== null) break;
            }
        }
        $this->setLanguage(($lang !== null) ?
            $lang : $this->config->defaultLanguage);
    }

    /**
     * Environment destructor.
     */
    function __destruct()
    {
        $this->shutdown();
        $this->assets = null;
        $this->config = null;
        $this->languages = null;
        $this->objects = null;
        $this->objectTexts = null;
        $this->session = null;
        $this->theme = null;
        $this->translations = null;
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
     * Get export configuration.
     *
     * @return Config
     * configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get current language.
     *
     * @return string
     * ISO language code
     */
    public function getLanguage()
    {
        return $this->language;
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
    public function &getObject($id = null)
    {
        $null = null;

        if (!\is_int($id) && !\is_string($id))
            return $null;
        //if (\is_string($id) && \preg_match('/^\w*/i', $id) !== 1)
        //    return $null;

        $file = $this->getPath('data/' . basename($id) . '/object.php');
        if (!\is_file($file))
            return $null;

        if (!\is_array($this->objects))
            $this->objects = array();

        if (!isset($this->objects[$id])) {
            //echo 'LOAD OBJECT ' . $id . '<br>';
            $max = (int)$this->objectsCacheSize;
            while (\count($this->objects) >= $max) {
                $keys = \array_keys($this->objects);
                unset($this->objects[$keys[0]]);
            }

            $data = null;
            if ($this->getConfig()->compatibility == 0) {
                /** @noinspection PhpIncludeInspection */
                include($file);

                if (isset($GLOBALS['immotool_objects'][$id])) {
                    $data = $GLOBALS['immotool_objects'][$id];
                    unset($GLOBALS['immotool_objects'][$id]);
                }
            } else {
                /** @noinspection PhpIncludeInspection */
                $data = include($file);
            }
            if (!\is_array($data))
                return $null;

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
        if (!\is_string($id) || preg_match('/^\w*/i', $id) !== 1)
            return null;

        return Utils::getFileStamp($this->getPath('data/' . $id . '/object.php'));
    }

    /**
     * Get the text data of a real estate object.
     *
     * @param string $id
     * object ID
     *
     * @return array|null
     * an array with real estate text data or null, if not found
     */
    public function &getObjectText($id = null)
    {
        $null = null;

        if (!\is_int($id) && !\is_string($id))
            return $null;
        //if (\is_string($id) && \preg_match('/^\w*/i', $id) !== 1)
        //    return $null;

        $file = $this->getPath('data/' . basename($id) . '/texts.php');
        if (!\is_file($file))
            return $null;

        if (!\is_array($this->objectTexts))
            $this->objectTexts = array();

        if (!isset($this->objectTexts[$id])) {
            //echo 'LOAD OBJECT ' . $id . '<br>';
            $max = (int)$this->objectTextsCacheSize;
            while (\count($this->objectTexts) >= $max) {
                $keys = \array_keys($this->objectTexts);
                unset($this->objectTexts[$keys[0]]);
            }

            if ($this->getConfig()->compatibility == 0) {
                /** @noinspection PhpIncludeInspection */
                include($file);

                if (isset($GLOBALS['immotool_texts'][$id])) {
                    $data = $GLOBALS['immotool_texts'][$id];
                    unset($GLOBALS['immotool_texts'][$id]);
                }
            } else {

                /** @noinspection PhpIncludeInspection */
                $data = include($file);
                if (!\is_array($data))
                    return $null;
            }

            $this->objectTexts[$id] =& $data;
        }

        return $this->objectTexts[$id];
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
        if (!\is_string($path))
            return $this->config->basePath;

        if (\substr($path, 0, 1) !== '/')
            $path = '/' . $path;

        return $this->config->basePath . $path;
    }

    /**
     * Get instance of the current session.
     *
     * @return Session\AbstractSession
     * session instance
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Get instance of the current theme.
     *
     * @return Theme\AbstractTheme
     * theme instance
     */
    public function getTheme()
    {
        return $this->theme;
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

        if (!\is_string($path))
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
     * @param string|null $path
     * file name within the theme
     *
     * @param array|null $parameters
     * associative array of URL parameters
     *
     * @return string
     * URL of the file in the theme
     */
    public function getThemeUrl($theme, $path = null, $parameters = null)
    {
        if ($theme === null)
            return null;

        $themePath = 'themes/' . $theme;
        if (\is_string($path))
            $themePath .= (\substr($path, 0, 1) !== '/') ?
                '/' . $path : $path;

        return $this->getUrl($themePath, $parameters);
    }

    /**
     * Get translations provided in data directory.
     *
     * @return array
     * array with translations
     */
    public function &getTranslations()
    {
        if (\is_array($this->translations))
            return $this->translations;

        $i18n = array();
        return $i18n;
    }

    /**
     * Get the translator.
     *
     * @return \Gettext\BaseTranslator
     * translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Get the URL of a file in the export environment.
     *
     * @param string|null $path
     * relative path of a file in the export environment
     *
     * @param array|null $parameters
     * associative array of URL parameters
     *
     * @return string
     * URL of the file in the export environment
     */
    public function getUrl($path = null, $parameters = null)
    {
        $url = $this->config->baseUrl;

        if (\is_string($path))
            $url .= (\substr($path, 0, 1) !== '/') ?
                '/' . $path : $path;

        return $url . Utils::getUrlParameters($parameters);
    }

    /**
     * Test, if debug mode is enabled.
     *
     * @return bool
     * true, if debug mode is enabled
     */
    public function isDebugMode()
    {
        return $this->config->debug === true;
    }

    /**
     * Test, if production mode is enabled.
     *
     * @return bool
     * true, if production mode is enabled
     */
    public function isProductionMode()
    {
        return !$this->isDebugMode();
    }

    /**
     * Create an action instance.
     *
     * @param string $name
     * name of requested action
     *
     * @return Action\AbstractAction
     * created action or null, if it is unknown
     */
    public function newAction($name)
    {
        return $this->config->newAction($name);
    }

    /**
     * Create the HTML view with object details.
     *
     * @return View\ExposeHtml
     * created view
     */
    public function newExposeHtml()
    {
        $view = ($this->theme !== null) ?
            $this->theme->newExposeHtml() : null;

        if (!($view instanceof View\ExposeHtml)) {
            Utils::logError(new \Exception('The expose view does not implement ExposeHtml!'));
            return null;
        }

        $view->setCharset($this->config->charset);
        $this->theme->setupExposeHtml($view);
        $this->config->setupExposeHtml($view);
        return $view;
    }

    /**
     * Create the HTML view with favorite listing.
     *
     * @return View\FavoriteHtml
     * created view
     */
    public function newFavoriteHtml()
    {
        $view = ($this->theme !== null) ?
            $this->theme->newFavoriteHtml() : null;

        if (!($view instanceof View\FavoriteHtml)) {
            Utils::logError(new \Exception('The listing view does not implement FavoriteHtml!'));
            return null;
        }

        $view->setCharset($this->config->charset);
        $this->theme->setupFavoriteHtml($view);
        $this->config->setupFavoriteHtml($view);
        return $view;
    }

    /**
     * Create the HTML view with object listing.
     *
     * @return View\ListingHtml
     * created view
     */
    public function newListingHtml()
    {
        $view = ($this->theme !== null) ?
            $this->theme->newListingHtml() : null;

        if (!($view instanceof View\ListingHtml)) {
            Utils::logError(new \Exception('The listing view does not implement ListingHtml!'));
            return null;
        }

        $view->setCharset($this->config->charset);
        $this->theme->setupListingHtml($view);
        $this->config->setupListingHtml($view);
        return $view;
    }

    /**
     * Create a mailer instance.
     *
     * @return \PHPMailer\PHPMailer\PHPMailer|null
     * created mailer or null, if the configuration failed
     *
     * @throws \PHPMailer\PHPMailer\Exception
     * if the mailer configuration failed
     */
    public function newMailer()
    {
        $mailer = $this->config->newMailer($this);
        $this->config->setupMailer($mailer, $this);
        return $mailer;
    }

    /**
     * Process the requested action.
     *
     * @return mixed|null
     * action result or null, if no action was executed
     *
     * @throws \Exception
     * if the execution of the action failed
     */
    public function processAction()
    {
        if (!isset($_REQUEST[$this->actionParameter]) || !\is_string($_REQUEST[$this->actionParameter]))
            return null;

        $action = $this->config->newAction($_REQUEST[$this->actionParameter]);
        if ($action === null)
            throw new \Exception('The requested action was not found!');
        if (!($action instanceof Action\AbstractAction))
            throw new \Exception('The requested action does not implement AbstractAction!');

        return $action->execute($this);
    }

    /**
     * Set current language.
     *
     * @param string $languageCode
     * ISO language code
     */
    public function setLanguage($languageCode)
    {
        $this->language = \str_replace('/', '', $languageCode);
        if ($this->session !== null)
            $this->session->setLanguage($this->language);

        // create translator
        $this->translator = Utils::createTranslator($this);
        $this->translator->register();

        // load further translations from data directory
        $dataTranslationsFile = $this->getPath('data/i18n_' . $this->language . '.php');
        $this->translations = array();
        if (\is_file($dataTranslationsFile)) {
            if ($this->getConfig()->compatibility == 0) {
                /** @noinspection PhpIncludeInspection */
                include $dataTranslationsFile;

                if (isset($GLOBALS['immotool_translations'][$this->language])) {
                    $this->translations = $GLOBALS['immotool_translations'][$this->language];
                    unset($GLOBALS['immotool_translations'][$this->language]);
                }
            } else {
                /** @noinspection PhpIncludeInspection */
                $this->translations = include $dataTranslationsFile;
            }
        }
    }

    /**
     * Shutdown export environment.
     */
    public function shutdown()
    {
        if ($this->session !== null) {
            $this->session->write();
            $this->session = null;
        }
    }
}
