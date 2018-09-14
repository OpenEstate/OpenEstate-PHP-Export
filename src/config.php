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
 * Custom export configuration.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
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

        // Set charset for generated text content.
        //$this->charset = 'UTF-8';

        // Set default language code.
        //$this->defaultLanguage = 'de';

        // Allow or disallow users to change the language.
        //$this->allowLanguageSelection = true;

        // Set time zone.
        // If no time zone was specified, the default time zone of the server is used.
        // see also: http://www.php.net/manual/de/timezones.php
        //$this->timezone = '';

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
     * Configure export environment.
     *
     * @param Environment $env
     * export environment
     */
    public function setupEnvironment(Environment $env)
    {
        parent::setupEnvironment($env);

        // Maximal number of objects to keep in the local cache.
        //$env->objectsCacheSize = 10;
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

        //$view->filters = array(
        //    new Filter\Action(),
        //    new Filter\Type(),
        //    new Filter\City(),
        //    new Filter\Region(),
        //    new Filter\Country(),
        //    new Filter\Age(),
        //    new Filter\Equipment(),
        //    new Filter\GroupNr(),
        //    new Filter\Rooms(),
        //    new Filter\Furnished(),
        //    new Filter\SpecialOffer(),
        //);

        //$view->orders = array(
        //    new Order\ObjectId(),
        //    new Order\City(),
        //    new Order\Area(),
        //    new Order\Price(),
        //    new Order\Title()
        //);

        //$view->defaultOrder = $view->orders[0];
        //$view->defaultOrderDirection = 'asc';
        $view->objectsPerPage = 8;
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
        $mailer->setFrom('andy@suicide-squad.de');
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
        parent::setupTheme($theme);
    }
}
