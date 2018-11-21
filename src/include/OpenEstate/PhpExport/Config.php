<?php
/*
 * Copyright 2009-2018 OpenEstate.org.
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
 * Export configuration.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class Config
{
    /**
     * Name of the theme used to render pages.
     *
     * @var string
     */
    public $themeName = 'default';

    /**
     * Default language code.
     *
     * @var string
     */
    public $defaultLanguage = 'de';

    /**
     * Allow users to change the language.
     *
     * @var bool
     */
    public $allowLanguageSelection = true;

    /**
     * Charset for generated text content.
     *
     * @var string
     */
    public $charset = 'UTF-8';

    /**
     * Default time, a file is kept in cache (in seconds).
     *
     * @var string
     */
    public $cacheLifeTime = 86400; // 24 hours

    /**
     * Generate thumbnail images via PHP.
     *
     * This option requires the GD module to be available in PHP.
     *
     * @var bool
     */
    public $dynamicImageScaling = true;

    /**
     * Enable management of favored real estates.
     *
     * @var bool
     */
    public $favorites = true;

    /**
     * Enable debug output.
     *
     * @var bool
     */
    public $debug = false;

    /**
     * Enable statistics output.
     *
     * @var bool
     */
    public $statistics = false;

    /**
     * Enable minimization of generated HTML code.
     *
     * This is an experimental feature, that might lead to problems in certain situations.
     *
     * @var bool
     */
    public $minimizeHtml = false;

    /**
     * Level of compatibility for data of older PHP exports.
     *
     * Currently the values 0 and 1 may be used.
     * Value 0 supports the processing of data, that was exported for PHP export 1.6.x or 1.7.x.
     * Value 1 supports the processing of data, that was exported for the current version of PHP export.
     *
     * @var int
     */
    public $compatibility = 1;

    /**
     * Absolute path, that points to the root of the export environment.
     *
     * @var string
     */
    public $basePath;

    /**
     * URL, that points to the root of the export environment.
     *
     * @var string
     */
    public $baseUrl;

    /**
     * Config constructor.
     *
     * @param string $basePath
     * absolute path of the export environment.
     *
     * @param string $baseUrl
     * URL of the export environment.
     */
    public function __construct($basePath, $baseUrl = '.')
    {
        $this->basePath = $basePath;
        if (\substr($this->basePath, -1) === '/')
            $this->basePath = \substr($this->basePath, 0, -1);

        $this->baseUrl = $baseUrl;
        if (\substr($this->baseUrl, -1) === '/')
            $this->baseUrl = \substr($this->baseUrl, 0, -1);

        // Enable automatic thumbnail generation by default,
        // if the GD module is present.
        $this->dynamicImageScaling = Utils::isGdExtensionAvailable();
    }

    /**
     * Get URL for the action handler script.
     *
     * @param array|null $parameters
     * associative array with URL parameters
     *
     * @return string
     * URL
     */
    public function getActionUrl($parameters = null)
    {
        return Utils::joinPath($this->baseUrl, 'action.php')
            . Utils::getUrlParameters($parameters);
    }

    /**
     * Get path to the assets folder.
     *
     * @return string
     * path
     */
    public function getAssetsFolderPath()
    {
        return Utils::joinPath($this->basePath, 'assets');
    }

    /**
     * Get URL for the assets folder.
     *
     * @return string
     * URL
     */
    public function getAssetsFolderUrl()
    {
        return Utils::joinPath($this->baseUrl, 'assets');
    }

    /**
     * Get path to the cache folder.
     *
     * @return string
     * path
     */
    public function getCacheFolderPath()
    {
        return Utils::joinPath($this->basePath, 'cache');
    }

    /**
     * Get URL for the captcha script.
     *
     * @param array|null $parameters
     * associative array with URL parameters
     *
     * @return string
     * URL
     */
    public function getCaptchaUrl($parameters = null)
    {
        return Utils::joinPath($this->baseUrl, 'captcha.php')
            . Utils::getUrlParameters($parameters);
    }

    /**
     * Get path to the custom css file.
     *
     * @return string
     * path
     */
    public function getCustomCssPath()
    {
        return Utils::joinPath($this->basePath, 'custom.css');
    }

    /**
     * Get URL for the custom css file.
     *
     * @return string
     * URL
     */
    public function getCustomCssUrl()
    {
        return Utils::joinPath($this->baseUrl, 'custom.css');
    }

    /**
     * Get path to the data folder.
     *
     * @return string
     * path
     */
    public function getDataFolderPath()
    {
        return Utils::joinPath($this->basePath, 'data');
    }

    /**
     * Get URL for the data folder.
     *
     * @return string
     * URL
     */
    public function getDataFolderUrl()
    {
        return Utils::joinPath($this->baseUrl, 'data');
    }

    /**
     * Get URL for the download script.
     *
     * @param array|null $parameters
     * associative array with URL parameters
     *
     * @return string
     * URL
     */
    public function getDownloadUrl($parameters = null)
    {
        return Utils::joinPath($this->baseUrl, 'download.php')
            . Utils::getUrlParameters($parameters);
    }

    /**
     * Get URL for the expose view.
     *
     * @param array|null $parameters
     * associative array with URL parameters
     *
     * @return string
     * URL
     */
    public function getExposeUrl($parameters = null)
    {
        return Utils::joinPath($this->baseUrl, 'expose.php')
            . Utils::getUrlParameters($parameters);
    }

    /**
     * Get URL for the favorite view.
     *
     * @param array|null $parameters
     * associative array with URL parameters
     *
     * @return string
     * URL
     */
    public function getFavoriteUrl($parameters = null)
    {
        return Utils::joinPath($this->baseUrl, 'fav.php')
            . Utils::getUrlParameters($parameters);
    }

    /**
     * Get available filters.
     *
     * @return array
     * list of filter objects
     */
    public function getFilterObjects()
    {
        return Utils::getDefaultFilterObjects($this->basePath);
    }

    /**
     * Get URL for the image script.
     *
     * @param array|null $parameters
     * associative array with URL parameters
     *
     * @return string
     * URL
     */
    public function getImageUrl($parameters = null)
    {
        return Utils::joinPath($this->baseUrl, 'img.php')
            . Utils::getUrlParameters($parameters);
    }

    /**
     * Get URL for the listing view.
     *
     * @param array|null $parameters
     * associative array with URL parameters
     *
     * @return string
     * URL
     */
    public function getListingUrl($parameters = null)
    {
        return Utils::joinPath($this->baseUrl, 'index.php')
            . Utils::getUrlParameters($parameters);
    }

    /**
     * Get path to the locale folder.
     *
     * @return string
     * path
     */
    public function getLocaleFolderPath()
    {
        return Utils::joinPath($this->basePath, 'locale');
    }

    /**
     * Get available orders.
     *
     * @return array
     * list of order objects
     */
    public function getOrderObjects()
    {
        return Utils::getDefaultOrderObjects($this->basePath);
    }

    /**
     * Get path to the theme folder.
     *
     * @param string|null $theme
     * theme name
     *
     * @return string
     * path to the requested theme folder or to the parent theme folder,
     * if no theme name was provided
     */
    public function getThemeFolderPath($theme = null)
    {
        return (\is_string($theme)) ?
            Utils::joinPath($this->basePath, 'themes', $theme) :
            Utils::joinPath($this->basePath, 'themes');
    }

    /**
     * Get URL for a theme folder.
     *
     * @param string|null $theme
     * theme name
     *
     * @return string
     * URL
     */
    public function getThemeFolderUrl($theme = null)
    {
        return Utils::joinPath($this->baseUrl, 'themes', $theme);
    }

    /**
     * Get a translation using the original string.
     *
     * @param string $lang
     * language code
     *
     * @param string $original
     * original string to translate
     *
     * @return string
     * translation
     *
     * @see \Gettext\TranslatorInterface::gettext()
     */
    public function i18nGettext($lang, $original)
    {
        return null;
    }

    /**
     * Get a translation checking the plural form.
     *
     * @param string $lang
     * language code
     *
     * @param string $original
     * original string to translate
     *
     * @param string $plural
     * plural form of the original string
     *
     * @param string $value
     * value to determine plural forms
     *
     * @return string
     * translation
     *
     * @see \Gettext\TranslatorInterface::ngettext()
     */
    public function i18nGettextPlural($lang, $original, $plural, $value)
    {
        return null;
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
        switch ($name) {
            case Action\AddFavorite::NAME:
                return new Action\AddFavorite();

            case Action\Contact::NAME:
                return new Action\Contact();

            case Action\RemoveFavorite::NAME:
                return new Action\RemoveFavorite();

            case Action\SetFavoriteOrder::NAME:
                return new Action\SetFavoriteOrder();

            case Action\SetFavoritePage::NAME:
                return new Action\SetFavoritePage();

            case Action\SetFavoriteView::NAME:
                return new Action\SetFavoriteView();

            case Action\SetLanguage::NAME:
                return new Action\SetLanguage();

            case Action\SetListingFilter::NAME:
                return new Action\SetListingFilter();

            case Action\SetListingOrder::NAME:
                return new Action\SetListingOrder();

            case Action\SetListingPage::NAME:
                return new Action\SetListingPage();

            case Action\SetListingView::NAME:
                return new Action\SetListingView();

            default:
                return null;
        }
    }

    /**
     * Create a link provider instance.
     *
     * @param string $name
     * name of requested provider
     *
     * @return Provider\AbstractLinkProvider
     * created link provider or null, if it is unknown
     */
    public function newLinkProvider($name)
    {
        switch ($name) {
            case Provider\DailyMotionVideo::NAME:
                return new Provider\DailyMotionVideo();

            case Provider\DTubeVideo::NAME:
                return new Provider\DTubeVideo();

            case Provider\PanoCreatorGallery::NAME:
                return new Provider\PanoCreatorGallery();

            case Provider\RoundMeGallery::NAME:
                return new Provider\RoundMeGallery();

            case Provider\VeohVideo::NAME:
                return new Provider\VeohVideo();

            case Provider\VimeoVideo::NAME:
                return new Provider\VimeoVideo();

            case Provider\YouTubeVideo::NAME:
                return new Provider\YouTubeVideo();

            default:
                return null;
        }
    }

    /**
     * Create a mailer instance.
     *
     * @return \PHPMailer\PHPMailer\PHPMailer|null
     * created mailer or null, if the configuration failed
     */
    public function newMailer()
    {
        return new \PHPMailer\PHPMailer\PHPMailer(true);
    }

    /**
     * Create a map provider.
     *
     * @return Provider\AbstractMapProvider
     * map provider
     */
    public function newMapProvider()
    {
        return new Provider\OpenStreetMap;
    }

    /**
     * Create a session instance.
     *
     * @param Environment $env
     * export environment
     *
     * @return Session\AbstractSession
     * created session
     */
    public function newSession(Environment $env)
    {
        return new Session\CookieSession($env);
    }

    /**
     * Create a theme instance.
     *
     * @param Environment $env
     * export environment
     *
     * @return Theme\AbstractTheme|null
     * created theme or null, if the configuration failed
     */
    public function newTheme(Environment $env)
    {
        $themeFile = Utils::joinPath($this->getThemeFolderPath($this->themeName), 'theme.php');

        /** @noinspection PhpIncludeInspection */
        return (\is_file($themeFile) && \is_readable($themeFile)) ?
            require $themeFile : new Theme\BasicTheme($this->themeName, $env);
    }

    /**
     * Configure an action.
     *
     * @param Action\AbstractAction $action
     * action to configure
     */
    public function setupAction(Action\AbstractAction $action)
    {
    }

    /**
     * Configure the export environment.
     *
     * @param Environment $env
     * export environment
     */
    public function setupEnvironment(Environment $env)
    {
    }

    /**
     * Set configuration for the HTML view with object details.
     *
     * @param View\ExposeHtml $view
     * view to configure
     */
    public function setupExposeHtml(View\ExposeHtml $view)
    {
    }

    /**
     * Set configuration for the HTML view with favorite listing.
     *
     * @param View\FavoriteHtml $view
     * view to configure
     */
    public function setupFavoriteHtml(View\FavoriteHtml $view)
    {
        $view->orders = array(
            new Order\ObjectId(),
            new Order\City(),
            new Order\Area(),
            new Order\Price(),
            new Order\Title()
        );

        $view->defaultOrder = $view->orders[0]->getName();
        $view->defaultOrderDirection = 'desc';
        $view->objectsPerPage = 10;
    }

    /**
     * Configure a link provider.
     *
     * @param Provider\AbstractLinkProvider $provider
     * link provider to configure
     */
    public function setupLinkProvider(Provider\AbstractLinkProvider $provider)
    {
    }

    /**
     * Set configuration for the HTML view with object listing.
     *
     * @param View\ListingHtml $view
     * view to configure
     */
    public function setupListingHtml(View\ListingHtml $view)
    {
        $view->filters = array(
            new Filter\Action(),
            new Filter\Type(),
            new Filter\City(),
            new Filter\Region()
        );

        $view->orders = array(
            new Order\ObjectId(),
            new Order\City(),
            new Order\Area(),
            new Order\Price(),
            new Order\Title()
        );

        $view->defaultOrder = $view->orders[0]->getName();
        $view->defaultOrderDirection = 'asc';
        $view->objectsPerPage = 10;
    }

    /**
     * Configure the mailer.
     *
     * @param \PHPMailer\PHPMailer\PHPMailer $mailer
     * mailer instance
     *
     * @param Environment $env
     * export environment
     *
     * @throws \PHPMailer\PHPMailer\Exception
     * if the configuration failed
     */
    public function setupMailer(\PHPMailer\PHPMailer\PHPMailer $mailer, Environment $env)
    {
        // Set sender address for outgoing emails.
        //$mailer->setFrom('max@mustermann.de', 'Max Mustermann');

        // Send a copy of outgoing emails to these addresses (as CC).
        //$mailer->addCC('max@mustermann.de', 'Max Mustermann');
        //$mailer->addCC('monika@mustermann.de', 'Monika Mustermann');

        // Send a blind copy of outgoing emails to these addresses (as BCC).
        //$mailer->addBCC('max@mustermann.de', 'Max Mustermann');
        //$mailer->addBCC('monika@mustermann.de', 'Monika Mustermann');

        // This address receives a reading confirmation of outgoing emails.
        //$mailer->ConfirmReadingTo = 'max@mustermann.de';

        // Set charset of outgoing emails.
        $mailer->CharSet = 'UTF-8';

        //
        // Enable one of the following mail methods.
        //

        // 1st mail method:
        // Send messages using PHP mail() function.
        $mailer->isMail();

        // 2nd mail method:
        // Send messages using Sendmail.
        //$mailer->isSendmail();

        // 3rd mail method:
        // Send messages using qmail.
        //$mailer->isQmail();

        // 4th mail method:
        // Send messages using SMTP.
        //$mailer->isSMTP();
        //$mailer->Host = 'smtp.mustermann.de';
        //$mailer->Port = 25;
        //$mailer->SMTPAuth = true;
        //$mailer->Username = 'Max';
        //$mailer->Password = 'MyPassword';
        //$mailer->SMTPSecure = '';
        //$mailer->SMTPAutoTLS = true;
        //$mailer->SMTPDebug = 0;
    }

    /**
     * Configure the map provider.
     *
     * @param Provider\AbstractMapProvider $mapProvider
     * map provider instance
     */
    public function setupMapProvider(Provider\AbstractMapProvider $mapProvider)
    {
    }

    /**
     * Configure the theme.
     *
     * @param Theme\AbstractTheme $theme
     * theme instance
     */
    public function setupTheme(Theme\AbstractTheme $theme)
    {
    }
}
