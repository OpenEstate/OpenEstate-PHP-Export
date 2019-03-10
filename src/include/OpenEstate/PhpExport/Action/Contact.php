<?php
/*
 * Copyright 2009-2019 OpenEstate.org.
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

namespace OpenEstate\PhpExport\Action;

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Utils;
use function OpenEstate\PhpExport\gettext as _;

/**
 * Send an email from a contact form.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class Contact extends AbstractAction
{
    /**
     * Action name.
     *
     * @var string
     */
    const NAME = 'Contact';

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
     * Enable privacy policy verification.
     *
     * @var bool
     */
    public $privacyPolicyVerification = true;

    /**
     * Enable cancellation policy verification.
     *
     * @var bool
     */
    public $cancellationPolicyVerification = true;

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
     * @param string $name
     * internal name
     */
    function __construct($name = self::NAME)
    {
        parent::__construct($name);

        // add previously configured prefix to parameter names
        $this->contactParameter = Environment::parameter($this->contactParameter);
        $this->objectIdParameter = Environment::parameter($this->objectIdParameter);

        // enable captcha verification by default,
        // if the GD extension is available
        $this->captchaVerification = Utils::isGdExtensionAvailable();
    }

    public function execute(Environment $env)
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
            $validation['name'] = _('Your name should contain at least {0} and maximal {1} characters.', 5, 100);
        else if ($contact['name'] !== \strip_tags($contact['name']))
            $validation['name'] = _('HTML code is not allowed.');

        // validate phone
        if (!isset($contact['phone']) || Utils::isBlankString($contact['phone']))
            $validation['phone'] = _('Please enter your phone number.');
        else if (\strlen($contact['phone']) < 5 || \strlen($contact['phone']) > 100)
            $validation['phone'] = _('Your phone number should contain at least {0} and maximal {1} characters.', 5, 100);
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
            $validation['message'] = _('Your message should contain at least {0} characters.', 10);
        else if ($contact['message'] !== \strip_tags($contact['message']))
            $validation['message'] = _('HTML code is not allowed.');

        // validate terms of use
        if ($this->termsVerification === true && (!isset($contact['terms']) || $contact['terms'] !== '1'))
            $validation['terms'] = _('Please accept the general terms and conditions.');

        // validate privacy policy
        if ($this->privacyPolicyVerification === true && (!isset($contact['privacy']) || $contact['privacy'] !== '1'))
            $validation['privacy'] = _('Please accept the privacy policy.');

        // validate cancellation policy
        if ($this->cancellationPolicyVerification === true && (!isset($contact['cancellation']) || $contact['cancellation'] !== '1'))
            $validation['cancellation'] = _('Please accept the cancellation policy.');

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
            $t = ($this->mailLanguage !== null && $this->mailLanguage !== $env->getLanguage()) ?
                Utils::createTranslator($env, $this->mailLanguage) :
                $env->getTranslator();

            $labels = array(
                'name' => \ucfirst($t->gettext('name')),
                'phone' => \ucfirst($t->gettext('phone')),
                'email' => \ucfirst($t->gettext('email')),
                'language' => \ucfirst($t->gettext('language')),
                'time' => \ucfirst($t->gettext('time')),
                'ip' => \ucfirst($t->gettext('IP address')),
                'browser' => \ucfirst($t->gettext('web browser'))
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
            $mailer->Subject = $t->gettext('Contact request for object {0}', $objectKey);
            $mailer->Body = $t->gettext('A contact request was sent for object {0}.', $objectKey) . "\n";
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
     * @param string $name
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