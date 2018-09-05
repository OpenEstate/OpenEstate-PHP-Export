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

namespace OpenEstate\PhpExport\Action;

use OpenEstate\PhpExport\Utils;
use function OpenEstate\PhpExport\gettext as _;

/**
 * Send an email from a contact form.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class Contact extends AbstractAction
{
    /**
     * Enable captcha verification.
     *
     * @var bool
     */
    public $captchaVerification = true;

    /**
     * Maximum age, a captcha code is considered valid (in seconds).
     *
     * @var int
     */
    public $captchaMaximumAge = 1800;

    /**
     * Enable terms verification.
     *
     * @var bool
     */
    public $termsVerification = true;

    /**
     * Parameter name for contact values.
     *
     * @var string
     */
    public $contactParameter = 'contact';

    /**
     * Parameter name for the object ID.
     *
     * @var string
     */
    public $objectIdParameter = 'objectId';

    /**
     * Language of generated emails.
     *
     * @var string
     */
    public $mailLanguage = null;

    /**
     * Delay before the action is executed (in seconds).
     *
     * @var int
     */
    public $delay = 2;

    /**
     * Contact constructor.
     *
     * @param $name
     * internal name
     */
    function __construct($name = 'Contact')
    {
        parent::__construct($name);

        // enable captcha verification by default,
        // if the GD extension is available
        $this->captchaVerification = Utils::isGdExtensionAvailable();
    }

    public function execute(\OpenEstate\PhpExport\Environment $env)
    {
        if (\is_int($this->delay) && $this->delay > 0)
            \sleep($this->delay);

        $result = array();

        // get requested object id
        $objectId = (isset($_REQUEST[$this->objectIdParameter])) ?
            $_REQUEST[$this->objectIdParameter] : null;

        // get requested contact data
        $contact = (isset($_REQUEST[$this->contactParameter])) ?
            $_REQUEST[$this->contactParameter] : null;

        // make sure, that the request variables are present
        if (Utils::isBlankString($objectId))
            $result['error'] = _('No object was provided for the contact request.');
        else if (Utils::isEmptyArray($contact))
            $result['error'] = _('No contact data was provided for the contact request.');

        // get requested object data
        $objectData = $env->getObject($objectId);
        if (!\is_array($objectData))
            $result['error'] = _('The requested object was not found.');

        // get mail recipient
        $mailTo = null;
        if (isset($objectData['contact']['person_mail']) && Utils::isNotBlankString($objectData['contact']['person_mail']))
            $mailTo = $objectData['contact']['person_mail'];
        else if (isset($objectData['contact']['company_mail']) && Utils::isNotBlankString($objectData['contact']['company_mail']))
            $mailTo = $objectData['contact']['company_mail'];
        else
            $result['error'] = _('Can\'t find a recipient address.');

        if (isset($result['error']))
            return $result;

        // trim contact data
        foreach ($contact as $key => $value)
            $contact[$key] = \trim($value);

        // validate contact data
        $validation = array();

        // validate name
        if (!isset($contact['name']) || Utils::isBlankString($contact['name']))
            $validation['name'] = _('Please enter your name.');
        else if (\strlen($contact['name']) < 5 || \strlen($contact['name']) > 100)
            $validation['name'] = _('Your name should contain at least {1} and maximal {2} characters.', 5, 100);
        else if ($contact['name'] !== \strip_tags($contact['name']))
            $validation['name'] = _('HTML code is not allowed.');

        // validate phone
        if (!isset($contact['phone']) || Utils::isBlankString($contact['phone']))
            $validation['phone'] = _('Please enter your phone number.');
        else if (\strlen($contact['phone']) < 5 || \strlen($contact['phone']) > 100)
            $validation['phone'] = _('Your phone number should contain at least {1} and maximal {2} characters.', 5, 100);
        else if ($contact['phone'] !== \strip_tags($contact['phone']))
            $validation['phone'] = _('HTML code is not allowed.');

        // validate email
        if (!isset($contact['email']) || Utils::isBlankString($contact['email']))
            $validation['email'] = _('Please enter your email address.');
        else if (Utils::isNotValidEmail($contact['email']))
            $validation['email'] = _('Your email address is invalid.');

        // validate message
        if (!isset($contact['message']) || Utils::isBlankString($contact['message']))
            $validation['message'] = _('Please enter your message.');
        else if (\strlen($contact['message']) < 10)
            $validation['message'] = _('Your message should contain at least {1} characters.', 10);
        else if ($contact['message'] !== \strip_tags($contact['message']))
            $validation['message'] = _('HTML code is not allowed.');

        // validate terms
        if ($this->termsVerification === true && (!isset($contact['terms']) || $contact['terms'] !== '1'))
            $validation['terms'] = _('Please accept the terms of use.');

        // validate captcha
        if ($this->captchaVerification === true) {
            if (!isset($contact['captcha']) || Utils::isBlankString($contact['captcha']))
                $validation['captcha'] = _('Please enter the verification code.');
            else if (Utils::isNotValidCaptcha($contact['captcha'], $env->getSession()->getCaptcha(), $this->captchaMaximumAge))
                $validation['captcha'] = _('Your verification code is invalid.');
        }

        // return an error, if a validation error occurred
        if (\count($validation)) {
            $result['error'] = _('Please check your input in the highlighted fields.');
            $result['validation'] = $validation;
            return $result;
        }

        // create mailer
        try {
            $mailTo = 'andy@openindex.de';

            $t = ($this->mailLanguage !== null && $this->mailLanguage !== $env->getLanguage()) ?
                Utils::createTranslator($env, $this->mailLanguage) :
                $env->getTranslator();

            $labels = array(
                'name' => $t->gettext('Name'),
                'phone' => $t->gettext('Phone'),
                'email' => $t->gettext('Email'),
                'language' => $t->gettext('Language'),
                'time' => $t->gettext('Time'),
                'ip' => $t->gettext('IP address'),
                'browser' => $t->gettext('Web Browser')
            );
            $maxLabelLength = 0;
            foreach ($labels as $label) {
                if (\strlen($label) > $maxLabelLength)
                    $maxLabelLength = \strlen($label);
            }

            $objectKey = (isset($objectData['nr']) && \is_string($objectData['nr'])) ?
                $objectData['nr'] :
                '#' . $objectId;

            $mailer = $env->newMailer();
            $mailer->isHTML(false);
            $mailer->addAddress($mailTo);
            $mailer->addReplyTo($contact['email'], $contact['name']);
            $mailer->Subject = $t->gettext('Contact request for object {1}', $objectKey);
            $mailer->Body = $t->gettext('A contact request was sent for object {1}.', $objectKey) . "\n";
            $mailer->Body .= "\n";
            $mailer->Body .= \str_repeat('-', 50) . "\n";
            $mailer->Body .= "\n";
            $mailer->Body .= \str_pad($labels['name'], $maxLabelLength) . " : " . $contact['name'] . "\n";
            $mailer->Body .= \str_pad($labels['phone'], $maxLabelLength) . " : " . $contact['phone'] . "\n";
            $mailer->Body .= \str_pad($labels['email'], $maxLabelLength) . " : " . $contact['email'] . "\n";
            $mailer->Body .= \str_pad($labels['language'], $maxLabelLength) . " : " . $env->getLanguageName($env->getLanguage()) . ' (' . $env->getLanguage() . ')' . "\n";
            $mailer->Body .= \str_pad($labels['time'], $maxLabelLength) . " : " . \strftime('%c') . "\n";
            $mailer->Body .= \str_pad($labels['ip'], $maxLabelLength) . " : " . $_SERVER['REMOTE_ADDR'] . "\n";
            $mailer->Body .= \str_pad($labels['browser'], $maxLabelLength) . " : " . $_SERVER['HTTP_USER_AGENT'] . "\n";
            $mailer->Body .= "\n";
            $mailer->Body .= \str_repeat('-', 50) . "\n";
            $mailer->Body .= "\n";
            $mailer->Body .= $contact['message'];

            if (!$mailer->send())
                throw new \Exception(_('Email was not sent.'));
            else
                $env->getSession()->setCaptcha(null);

        } catch (\Exception $e) {

            $result['error'] = _('The email transfer failed.') . ' ' . $e->getMessage();

        }

        return $result;
    }

    /**
     * Get the variable name of a contact value.
     *
     * @param $name
     * value name
     *
     * @return string
     * variable name
     */
    public function getVar($name)
    {
        return $this->contactParameter . '[' . $name . ']';
    }
}