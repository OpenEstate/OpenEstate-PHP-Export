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
    public $theme = 'default';

    /**
     * Default language code.
     *
     * @var string
     */
    public $defaultLanguage = 'de';

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
     * Config constructor.
     */
    public function __construct()
    {
        $this->dynamicImageScaling = extension_loaded('gd') === true;
    }

    /**
     * Create the HTML page for a single object.
     *
     * @return View\ExposeHtml
     * created view
     */
    public function newExposeHtmlView()
    {
        $view = new View\ExposeHtml('ExposeHtml', $this->charset, $this->theme);
        $this->setupExposeHtmlView($view);
        return $view;
    }

    /**
     * Create the HTML page for object listings.
     *
     * @return View\ListingHtml
     * created view
     */
    public function newListingHtmlView()
    {
        $view = new View\ListingHtml('ListingHtml', $this->charset, $this->theme);
        $this->setupListingHtmlView($view);
        return $view;
    }

    /**
     * Create a mailer instance.
     *
     * @return \PHPMailer\PHPMailer\PHPMailer|null
     * created mailer or null, if the mailer configuration failed
     */
    public function newMailer()
    {
        try
        {
            $mailer = new \PHPMailer\PHPMailer\PHPMailer(true);
            $this->setupMailer($mailer);
            return $mailer;
        }
        catch (\PHPMailer\PHPMailer\Exception $e)
        {
            Utils::logError($e);
            return null;
        }
    }

    /**
     * Configure the export environment.
     *
     * @param Environment $env
     * export environment
     */
    public function setupEnvironment(Environment &$env)
    {
    }

    /**
     * Configure the HTML page for a single object.
     *
     * @param View\ExposeHtml $view
     * view to configure
     */
    public function setupExposeHtmlView(View\ExposeHtml &$view)
    {
    }

    /**
     * Configure the HTML page for object listings.
     *
     * @param View\ListingHtml $view
     * view to configure
     */
    public function setupListingHtmlView(View\ListingHtml &$view)
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

        $view->order =& $view->orders[0];
        $view->direction = 'desc';
        $view->objectsPerPage = 10;
    }

    /**
     * Configure the mailer.
     *
     * @param \PHPMailer\PHPMailer\PHPMailer $mailer
     * mailer instance
     *
     * @throws \PHPMailer\PHPMailer\Exception
     * if the configuration failed
     */
    public function setupMailer(\PHPMailer\PHPMailer\PHPMailer &$mailer)
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
        //$mailer->SMTPDebug = 1;
    }
}
