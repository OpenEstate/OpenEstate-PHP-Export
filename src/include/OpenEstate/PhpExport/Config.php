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
 * Export configuration.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
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
     * Time zone.
     *
     * If no time zone was specified, the default time zone of the server is used.
     *
     * @var string
     * @see http://www.php.net/manual/de/timezones.php List of available time zones.
     */
    public $timezone = '';

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
     * Enable minimization of generated HTML code.
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

        // Disable automatic thumbnail generation,
        // if the GD module is not present.
        $this->dynamicImageScaling = Utils::isGdExtensionAvailable();
    }

    /**
     * Get URL for the action handler.
     *
     * @param array|null $parameters
     * parameters for the action handler
     *
     * @return string
     * URL
     */
    public function getActionUrl($parameters=null)
    {
        return $this->baseUrl
            . '/action.php'
            . Utils::getUrlParameters($parameters);
    }

    /**
     * Get URL for the expose view.
     *
     * @param array|null $parameters
     * parameters for the expose view
     *
     * @return string
     * URL
     */
    public function getExposeUrl($parameters=null)
    {
        return $this->baseUrl
            . '/expose.php'
            . Utils::getUrlParameters($parameters);
    }

    /**
     * Get URL for the favorite view.
     *
     * @param array|null $parameters
     * parameters for the favorite view
     *
     * @return string
     * URL
     */
    public function getFavoriteUrl($parameters=null)
    {
        return $this->baseUrl
            . '/fav.php'
            . Utils::getUrlParameters($parameters);
    }

    /**
     * Get URL for the listing view.
     *
     * @param array|null $parameters
     * parameters for the listing view
     *
     * @return string
     * URL
     */
    public function getListingUrl($parameters=null)
    {
        return $this->baseUrl
            . '/index.php'
            . Utils::getUrlParameters($parameters);
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
     * @param $plural
     * plural form of the original string
     *
     * @param $value
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
     * @param $name
     * name of requested action
     *
     * @return Action\AbstractAction
     * created action or null, if it is unknown
     */
    public function newAction($name)
    {
        switch ($name) {
            case 'AddFavorite':
                return new Action\AddFavorite();

            case 'Contact':
                return new Action\Contact();

            case 'RemoveFavorite':
                return new Action\RemoveFavorite();

            case 'SetLanguage':
                return new Action\SetLanguage();

            case 'SetFavoriteOrder':
                return new Action\SetFavoriteOrder();

            case 'SetFavoritePage':
                return new Action\SetFavoritePage();

            case 'SetFavoriteView':
                return new Action\SetFavoriteView();

            case 'SetListingFilter':
                return new Action\SetListingFilter();

            case 'SetListingOrder':
                return new Action\SetListingOrder();

            case 'SetListingPage':
                return new Action\SetListingPage();

            case 'SetListingView':
                return new Action\SetListingView();

            default:
                return null;
        }
    }

    /**
     * Create a mailer instance.
     *
     * @param Environment $env
     * export environment
     *
     * @return \PHPMailer\PHPMailer\PHPMailer|null
     * created mailer or null, if the configuration failed
     */
    public function newMailer(Environment $env)
    {
        return new \PHPMailer\PHPMailer\PHPMailer(true);
    }

    /**
     * Create a session instance.
     *
     * @param $env
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
        $themeFile = $env->getThemePath($this->themeName, 'theme.php');

        /** @noinspection PhpIncludeInspection */
        return (\is_file($themeFile) && \is_readable($themeFile)) ?
            require $themeFile : new Theme\BasicTheme($this->themeName, $env);
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
        $view->defaultOrderDirection = 'desc';
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
        $mailer->CharSet = $this->charset;

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
     * Configure the theme.
     *
     * @param Theme\AbstractTheme $theme
     * theme instance
     */
    public function setupTheme(Theme\AbstractTheme $theme)
    {
    }
}
