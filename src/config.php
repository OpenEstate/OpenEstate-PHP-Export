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
 * Custom export configuration.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class MyConfig extends Config
{
    /**
     * Global configuration.
     *
     * @param string $basePath
     * absolute path of the export environment.
     *
     * @param string $baseUrl
     * URL of the export environment.
     */
    public function __construct($basePath, $baseUrl = '.')
    {
        parent::__construct($basePath, $baseUrl);

        // Set name of the theme used to render pages.
        //$this->themeName = 'default';

        // Set default language code.
        //$this->defaultLanguage = 'de';

        // Allow or disallow users to change the language.
        //$this->allowLanguageSelection = true;

        // Set charset for generated text content.
        //$this->charset = 'UTF-8';

        // Default time, a file is kept in cache (in seconds).
        //$this->cacheLifeTime = 86400; // 24 hours

        // Enable or disable automatic thumbnail creation.
        // This option requires the GD module to be available in PHP.
        //$this->dynamicImageScaling = true;

        // Enable or disable management of favored real estates.
        //$this->favorites = true;

        // Enable or disable debugging.
        //$this->debug = false;

        // Enable or disable statistics.
        //$this->statistics = false;

        // Enable compatibility for data of PHP-export 1.6.x or 1.7.x.
        //$this->compatibility = 0;
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
        return parent::getActionUrl($parameters);
    }

    /**
     * Get path to the assets folder.
     *
     * @return string
     * path
     */
    public function getAssetsFolderPath()
    {
        return parent::getAssetsFolderPath();
    }

    /**
     * Get URL for the assets folder.
     *
     * @return string
     * URL
     */
    public function getAssetsFolderUrl()
    {
        return parent::getAssetsFolderUrl();
    }

    /**
     * Get path to the cache folder.
     *
     * @return string
     * path
     */
    public function getCacheFolderPath()
    {
        return parent::getCacheFolderPath();
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
        return parent::getCaptchaUrl($parameters);
    }

    /**
     * Get path to the custom css file.
     *
     * @return string
     * path
     */
    public function getCustomCssPath()
    {
        return parent::getCustomCssPath();
    }

    /**
     * Get URL for the custom css file.
     *
     * @return string
     * URL
     */
    public function getCustomCssUrl()
    {
        return parent::getCustomCssUrl();
    }

    /**
     * Get path to the data folder.
     *
     * @return string
     * path
     */
    public function getDataFolderPath()
    {
        return parent::getDataFolderPath();
    }

    /**
     * Get URL for the data folder.
     *
     * @return string
     * URL
     */
    public function getDataFolderUrl()
    {
        return parent::getDataFolderUrl();
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
        return parent::getDownloadUrl($parameters);
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
        return parent::getExposeUrl($parameters);
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
        return parent::getFavoriteUrl($parameters);
    }

    /**
     * Get available filters.
     *
     * @return array
     * list of filter objects
     */
    public function getFilterObjects()
    {
        return parent::getFilterObjects();
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
        return parent::getImageUrl($parameters);
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
        return parent::getListingUrl($parameters);
    }

    /**
     * Get path to the locale folder.
     *
     * @return string
     * path
     */
    public function getLocaleFolderPath()
    {
        return parent::getLocaleFolderPath();
    }

    /**
     * Get available orders.
     *
     * @return array
     * list of order objects
     */
    public function getOrderObjects()
    {
        return parent::getOrderObjects();
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
        return parent::getThemeFolderPath($theme);
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
        return parent::getThemeFolderUrl($theme);
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
        return parent::i18nGettext($lang, $original);
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
        return parent::i18nGettextPlural($lang, $original, $plural, $value);
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
        return parent::newAction($name);
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
        return parent::newLinkProvider($name);
    }

    /**
     * Create a mailer instance.
     *
     * @return \PHPMailer\PHPMailer\PHPMailer|null
     * created mailer or null, if the configuration failed
     */
    public function newMailer()
    {
        return parent::newMailer();
    }

    /**
     * Create a map provider.
     *
     * @return Provider\AbstractMapProvider
     * map provider
     */
    public function newMapProvider()
    {
        return parent::newMapProvider();
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
        return parent::newSession($env);
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
        return parent::newTheme($env);
    }

    /**
     * Configure an action.
     *
     * @param Action\AbstractAction $action
     * action to configure
     */
    public function setupAction(Action\AbstractAction $action)
    {
        parent::setupAction($action);
    }

    /**
     * Configure export environment.
     *
     * @param Environment $env
     * export environment
     */
    public function setupEnvironment(Environment $env)
    {
        parent::setupEnvironment($env);

        // Set maximal number of objects to keep in the local cache.
        //$env->objectsCacheSize = 10;

        // Set maximal number of object texts to keep in the local cache.
        //$env->objectTextsCacheSize = 10;

        // Set name of the action parameter.
        //$env->actionParameter = 'action';
    }

    /**
     * Set configuration for the HTML view with object details.
     *
     * @param View\ExposeHtml $view
     * view to configure
     */
    public function setupExposeHtml(View\ExposeHtml $view)
    {
        parent::setupExposeHtml($view);

        // Set name of the object ID parameter.
        //$view->objectIdParameter = 'id';
    }

    /**
     * Set configuration for the HTML view with favorite listing.
     *
     * @param View\FavoriteHtml $view
     * view to configure
     */
    public function setupFavoriteHtml(View\FavoriteHtml $view)
    {
        parent::setupFavoriteHtml($view);

        // Set maximal number of objects shown on a page.
        //$view->objectsPerPage = 10;

        // Set available orderings in the listing.
        //$view->orders = array(
        //    new Order\Area(),
        //    new Order\City(),
        //    new Order\GroupNr(),
        //    new Order\LastMod(),
        //    new Order\ObjectId(),
        //    new Order\ObjectNr(),
        //    new Order\Postal(),
        //    new Order\Price(),
        //    new Order\Rooms(),
        //    new Order\Title()
        //);

        // Set default ordering of the listing.
        //$view->defaultOrder = $view->orders[0]->getName();

        // Set default ordering direction of the listing.
        //$view->defaultOrderDirection = 'desc';

        // Set default view of the listing.
        //$view->defaultView = 'detail';

        // Set columns with object attributes.
        //$view->objectColumns = array(
        //
        //    // first column
        //    array('type', 'action', 'address', 'country'),
        //
        //    // second column
        //    array('price', 'area', 'measures.count_rooms', 'measures.count_residential_units', 'administration.auction_date'),
        //);

        // Set maximal number of entries per attribute column.
        //$view->objectColumnsLimit = 4;
    }

    /**
     * Configure a link provider.
     *
     * @param Provider\AbstractLinkProvider $provider
     * link provider to configure
     */
    public function setupLinkProvider(Provider\AbstractLinkProvider $provider)
    {
        parent::setupLinkProvider($provider);
    }

    /**
     * Set configuration for the HTML view with object listing.
     *
     * @param View\ListingHtml $view
     * view to configure
     */
    public function setupListingHtml(View\ListingHtml $view)
    {
        parent::setupListingHtml($view);

        // Set maximal number of objects shown on a page.
        //$view->objectsPerPage = 10;

        // Set available filters in the listing.
        //$view->filters = array(
        //    new Filter\Action(),
        //    new Filter\Age(),
        //    new Filter\City(),
        //    new Filter\Country(),
        //    new Filter\Equipment(),
        //    new Filter\Furnished(),
        //    new Filter\GroupNr(),
        //    new Filter\Region(),
        //    new Filter\Rooms(),
        //    new Filter\SpecialOffer(),
        //    new Filter\Type(),
        //);

        // Set default filter values of the listing.
        //$view->defaultFilterValues = array(
        //    'Action' => 'purchase',
        //    'Type' => 'house',
        //);

        // Set available orderings in the listing.
        //$view->orders = array(
        //    new Order\Area(),
        //    new Order\City(),
        //    new Order\GroupNr(),
        //    new Order\LastMod(),
        //    new Order\ObjectId(),
        //    new Order\ObjectNr(),
        //    new Order\Postal(),
        //    new Order\Price(),
        //    new Order\Rooms(),
        //    new Order\Title()
        //);

        // Set default ordering of the listing.
        //$view->defaultOrder = $view->orders[0]->getName();

        // Set default ordering direction of the listing.
        //$view->defaultOrderDirection = 'desc';

        // Set default view of the listing.
        //$view->defaultView = 'detail';

        // Set columns with object attributes.
        //$view->objectColumns = array(
        //
        //    // first column
        //    array('type', 'action', 'address', 'country'),
        //
        //    // second column
        //    array('price', 'area', 'measures.count_rooms', 'measures.count_residential_units', 'administration.auction_date'),
        //);

        // Set maximal number of entries per attribute column.
        //$view->objectColumnsLimit = 4;
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
        parent::setupMailer($mailer, $env);

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
     * Configure the map provider.
     *
     * @param Provider\AbstractMapProvider $mapProvider
     * map provider instance
     */
    public function setupMapProvider(Provider\AbstractMapProvider $mapProvider)
    {
        parent::setupMapProvider($mapProvider);
    }

    /**
     * Configure the theme.
     *
     * @param Theme\AbstractTheme $theme
     * theme instance
     */
    public function setupTheme(Theme\AbstractTheme $theme)
    {
        parent::setupTheme($theme);
    }
}
