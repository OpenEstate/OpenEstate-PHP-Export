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

/**
 * Implementation of the expose view for the default theme.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @var \OpenEstate\PhpExport\View\ExposeHtml $view
 * the currently used view
 */

// Don't execute the file, if it is not properly loaded.
if (!isset($view) || !\is_object($view)) return;

use OpenEstate\PhpExport\Utils;
use OpenEstate\PhpExport\Html\Stylesheet;
use OpenEstate\PhpExport\Html\Javascript;
use OpenEstate\PhpExport\Html\Meta;
use const OpenEstate\PhpExport\VERSION;
use function OpenEstate\PhpExport\gettext as _;

// get export environment
$env = $view->getEnvironment();

// generate unique ID for this view
$uid = \substr(\sha1(__FILE__ . '-' . \time() . '-' . \rand(0, 99999)), 0, 5);

// get language information
$i18n = $env->getTranslations();
$languageCodes = $env->getLanguageCodes();
$languageCode = $env->getLanguage();
$languageSelection = $env->getConfig()->allowLanguageSelection === true
    && \is_array($languageCodes)
    && \count($languageCodes) > 1;

// get expose options
$favoritesEnabled = $env->getConfig()->favorites;

// get object data
$objectId = $view->getObjectId();
$objectData = $view->getObjectData();
$objectTexts = (\is_array($objectData)) ? $view->getObjectTexts() : null;
$objectKey = (isset($objectData['nr']) && \is_string($objectData['nr'])) ?
    $objectData['nr'] :
    '#' . $objectId;

// get object title
$objectTitle = (isset($objectData['title'][$languageCode])) ?
    $objectData['title'][$languageCode] :
    _('Real estate {1}', $objectKey);

// get object type
$objectType = (isset($objectData['type'])) ? $objectData['type'] : null;
$objectType = ($objectType !== null && isset($i18n['openestate']['types'][$objectType])) ?
    $i18n['openestate']['types'][$objectType] :
    $objectType;

// get object action
$objectAction = (isset($objectData['action'])) ? $objectData['action'] : null;
$objectAction = ($objectType !== null && isset($i18n['openestate']['actions'][$objectAction])) ?
    $i18n['openestate']['actions'][$objectAction] :
    $objectAction;

/**
 * @var OpenEstate\PhpExport\Action\Contact $contactAction
 * action for the contact form
 */
$contactAction = $env->newAction('Contact');

// set page title
$view->setTitle($objectTitle);

// send 404 response code, if the requested object was not found
if (!\is_array($objectData))
    $view->setHttpResponseCode(404);

// add meta elements
if (\is_array($objectData)) {
    $view->addHeader(Meta::newRobots('index,follow'), 100);

    // add meta description
    $metaDescription = null;
    if (isset($objectTexts['short_description'][$languageCode]) && Utils::isNotBlankString($objectTexts['short_description'][$languageCode]))
        $metaDescription = $objectTexts['short_description'][$languageCode];
    else if (isset($objectTexts['detailled_description'][$languageCode]) && Utils::isNotBlankString($objectTexts['detailled_description'][$languageCode]))
        $metaDescription = $objectTexts['detailled_description'][$languageCode];
    if (Utils::isNotBlankString($metaDescription))
        $view->addHeader(Meta::newDescription(Utils::getAbbreviatedString($metaDescription, 150, true)), 101);

    // add meta keywords
    $metaKeywords = null;
    if (isset($objectTexts['keywords'][$languageCode]) && Utils::isNotBlankString($objectTexts['keywords'][$languageCode]))
        $metaKeywords = $objectTexts['keywords'][$languageCode];
    else {
        $keywords = array();
        if ($objectType !== null)
            $keywords[] = $objectType;
        if ($objectAction !== null)
            $keywords[] = $objectAction;
        if ($objectData['address']['city'] !== null)
            $keywords[] = $objectData['address']['city'];
        if ($objectData['address']['city_part'] !== null)
            $keywords[] = $objectData['address']['city_part'];
        $metaKeywords = \implode(',', $keywords);
    }
    if (Utils::isNotBlankString($metaKeywords))
        $view->addHeader(Meta::newKeywords(Utils::getAbbreviatedString($metaKeywords, 150, true)), 102);
} else {
    $view->addHeader(Meta::newRobots('noindex,nofollow'), 100);
}

// register JQuery
$view->addHeaders($env->getAssets()->jquery(), 200);

// register Colorbox
$view->addHeaders($env->getAssets()->colorbox(), 201);

// register OpenEstate Icons
$view->addHeaders($env->getAssets()->openestate_icons(), 210);

// register Pure CSS framework
if (!$view->isBodyOnly())
    $view->addHeader(Stylesheet::newLink(
        'openestate-pure-base-css',
        $view->getThemeUrl('css/pure/base-min.css', array('v' => '1.0.0'))
    ), 310);
$view->addHeader(Stylesheet::newLink(
    'openestate-pure-buttons-css',
    $view->getThemeUrl('css/pure/buttons-min.css', array('v' => '1.0.0'))
), 311);
$view->addHeader(Stylesheet::newLink(
    'openestate-pure-forms-css',
    $view->getThemeUrl('css/pure/forms-min.css', array('v' => '1.0.0'))
), 312);
$view->addHeader(Stylesheet::newLink(
    'openestate-pure-grids-css',
    $view->getThemeUrl('css/pure/grids-min.css', array('v' => '1.0.0'))
), 313);
$view->addHeader(Stylesheet::newLink(
    'openestate-pure-grids-responsive-css',
    $view->getThemeUrl('css/pure/grids-responsive-min.css', array('v' => '1.0.0'))
), 314);
//$view->addHeader(Stylesheet::newLink(
//        'openestate-pure-menus-css',
//        $view->getThemeUrl('css/pure/menus-min.css', array('v' => '1.0.0'))
//), 315);
//$view->addHeader(Stylesheet::newLink(
//        'openestate-pure-tables-css',
//        $view->getThemeUrl('css/pure/tables-min.css', array('v' => '1.0.0'))
//), 316);

// register slick.js
$view->addHeader(Stylesheet::newLink(
    'openestate-slick-css',
    $view->getThemeUrl('js/slick/slick.css', array('v' => '1.8.0'))
), 400);
//$view->addHeader(Stylesheet::newLink(
//    'openestate-slick-theme-css',
//    $view->getThemeUrl('js/slick/slick-theme.css', array('v' => '1.8.0'))
//), 401);
$view->addHeader(Javascript::newLink(
    'openestate-slick-js',
    $view->getThemeUrl('js/slick/slick.min.js', array('v' => '1.8.0')),
    null,
    null,
    true
), 402);

// register Popper.js
//$view->addHeader(Javascript::newLink(
//    'openestate-popper-js',
//    $view->getThemeUrl('js/popper/popper.min.js', array('v' => '1.14.4')),
//    null,
//    null,
//    true
//), 900);

// register custom theme includes
$view->addHeader(Stylesheet::newLink(
    'openestate-theme-css',
    $view->getThemeUrl('css/theme.css', array('v' => VERSION))
), 1000);
$view->addHeader(Javascript::newLink(
    'openestate-theme-js',
    $view->getThemeUrl('js/theme.js', array('v' => VERSION)),
    'openestate_install_expose("' . $uid . '", "' . \htmlspecialchars($env->getActionUrl()) . '");',
    null,
    true
), 1001);

// write document header
if (!$view->isBodyOnly()) include('snippets/document-begin.php');
include('snippets/body-begin.php');

?>

    <div id="openestate-body-<?= $uid ?>" class="openestate-body openestate-expose"
         data-openestate-object="<?= \htmlspecialchars($objectId) ?>">

        <?php if (!\is_array($objectData)) { ?>

            <div class="openestate-expose-empty">
                <p><?= \htmlspecialchars(_('The offer was not found. Maybe it is not published anymore.')) ?></p>
                <p>
                    <a class="pure-button pure-button-primary openestate-button"
                       href="<?= \htmlspecialchars($env->getListingUrl()) ?>">
                        <?= \htmlspecialchars(_('Visit our current offers')) ?>
                    </a>
                </p>
            </div>

        <?php } else { ?>

        <div class="openestate-header">
            <div class="openestate-header-bar">
                <h3 class="openestate-header-title"><?= _('Real estate {1}', $objectKey) ?></h3>
                <div class="openestate-header-actions">
                    <a class="openestate-action-listing"
                       href="<?= \htmlspecialchars($env->getListingUrl()) ?>"
                       title="<?= _('Show current offers.') ?>">
                        <i class="openestate-icon-home"></i>
                    </a>
                    <?php if ($favoritesEnabled) { ?>
                        <a class="openestate-action-fav" rel="nofollow"
                           href="<?= \htmlspecialchars($env->getFavoriteUrl()) ?>"
                           title="<?= _('Show list of favorite objects.') ?>">
                            <i class="openestate-icon-star"></i>
                        </a>
                    <?php } ?>
                    <?php if ($languageSelection) { ?>
                        <a class="openestate-action-language" href="#"
                           title="<?= _('Select your preferred language.') ?>">
                            <i class="openestate-icon-globe"></i>
                        </a>
                    <?php } ?>
                </div>
            </div>

            <?php
            if ($languageSelection) {
                echo '<div class="openestate-language-form">';
                foreach ($env->getLanguageCodes() as $lang) {
                    $languageName = $env->getLanguageName($lang);
                    $languageClass = ($languageCode == $lang) ? 'active' : '';
                    $languageParams = $setLanguageAction->getParameters($env, $lang);
                    echo '<a href="' . \htmlspecialchars($env->getListingUrl($languageParams)) . '" '
                        . 'data-openestate-action="' . \htmlspecialchars(Utils::getJson($languageParams)) . '" '
                        . 'class="' . $languageClass . '">'
                        . \htmlspecialchars($languageName) . '</a>';
                }
                echo '</div>';
            }
            ?>
        </div>

        <?php
        if (Utils::isNotEmptyArray($objectData['images'])) {
            echo '<div class="openestate-expose-gallery">';
            foreach ($objectData['images'] as $image) {
                $imageUrl = $env->getDataUrl($objectId . '/' . $image['name']);
                $imageThumbUrl = ($env->getConfig()->dynamicImageScaling) ?
                    $env->getImageUrl(array('id' => $objectId, 'img' => $image['name'], 'y' => 325)) :
                    $env->getDataUrl($objectId . '/' . $image['thumb']);
                $imageTitle = (isset($image['title'][$languageCode])) ?
                    $image['title'][$languageCode] : '';

                echo '<div><a href="' . \htmlspecialchars($imageUrl) . '" title="' . \htmlspecialchars($imageTitle) . '">'
                    . '<img src="' . \htmlspecialchars($imageThumbUrl) . '" alt="' . \htmlspecialchars($imageTitle) . '">'
                    . '</a></div>';
            }
            echo '</div>';
        }
        ?>

        <div class="openestate-expose-content">
            <div class="openestate-expose-general">
                <h2 class="openestate-expose-title"><?= \htmlspecialchars($objectTitle) ?></h2>
                <ul>
                    <li><?= ($objectType !== null) ? \htmlspecialchars($objectType) : '???' ?>
                        / <?= ($objectAction !== null) ? \htmlspecialchars($objectAction) : '???' ?></li>
                    <li><?php
                        if (isset($objectData['address']['street']) && Utils::isNotBlankString($objectData['address']['street'])) {
                            echo \htmlspecialchars($objectData['address']['street']);
                            if (isset($objectData['address']['street_nr']) && Utils::isNotBlankString($objectData['address']['street_nr']))
                                echo ' ' . \htmlspecialchars($objectData['address']['street_nr']);
                            echo '<br>';
                        }
                        echo \htmlspecialchars($objectData['address']['postal']) . ' '
                            . \htmlspecialchars($objectData['address']['city']);
                        if (isset($objectData['address']['city_part']) && Utils::isNotBlankString($objectData['address']['city_part']))
                            echo ' / ' . \htmlspecialchars($objectData['address']['city_part']);
                        if (isset($objectData['address']['country_name'][$languageCode]) && Utils::isNotBlankString($objectData['address']['country_name'][$languageCode]))
                            echo '<br>' . \htmlspecialchars($objectData['address']['country_name'][$languageCode]);
                        if (isset($objectData['address']['region']) && Utils::isNotBlankString($objectData['address']['region']))
                            echo '/ ' . \htmlspecialchars($objectData['address']['region']);
                        ?></li>
                </ul>
            </div>

            <?php
            foreach ($objectData['attributes'] as $group => $attributes) {
                if (Utils::isEmptyArray($attributes)) continue;
                $groupTitle = (isset($i18n['openestate']['groups'][$group])) ? $i18n['openestate']['groups'][$group] : $group;
                echo '<div class="openestate-expose-attributes openestate-expose-attributes-' . $group . '">';
                echo '<h3>' . \htmlspecialchars($groupTitle) . '</h3>';
                echo '<ul>';
                foreach ($attributes as $name => $value) {
                    echo '<li>';
                    echo '<span class="openestate-attribute-label">' . \htmlspecialchars($i18n['openestate']['attributes'][$group][$name]) . ':</span>';
                    echo '<span class="openestate-attribute-value">' . \htmlspecialchars($value[$languageCode]) . '</span>';
                    echo '</li>';
                }
                echo '</ul>';
                echo '</div>';
            }

            foreach ($objectTexts as $name => $value) {
                if ($name === 'id' || $name === 'short_description' || $name === 'keywords') continue;
                if (Utils::isBlankString($value[$languageCode])) continue;
                $text = \trim($value[$languageCode]);
                //$text = str_replace('<br/>', "\n", $value[$languageCode]);

                echo '<div class="openestate-expose-text openestate-expose-text-' . $name . '">';
                echo '<h3>' . \htmlspecialchars($i18n['openestate']['attributes']['descriptions'][$name]) . '</h3>';
                echo '<p>' . $text . '</p>';
                echo '</div>';
            } ?>

            <div class="openestate-expose-person">
                <h3><?= _('Your contact person') ?></h3>
                <ul>
                    <?php
                    if (isset($objectData['contact']['person_fullname']) && Utils::isNotBlankString($objectData['contact']['person_fullname']))
                        echo '<li><span class="openestate-expose-person-name">' . \htmlspecialchars($objectData['contact']['person_fullname']) . '</span></li>';
                    if (isset($objectData['contact']['person_phone']) && Utils::isNotBlankString($objectData['contact']['person_phone'])) {
                        echo '<li>';
                        echo '<span class="openestate-expose-person-label">' . \htmlspecialchars(_('Phone')) . ':</span>';
                        echo '<span class="openestate-expose-person-value">' . \htmlspecialchars($objectData['contact']['person_phone']) . '</span>';
                        echo '</li>';
                    } else if (isset($objectData['contact']['company_phone']) && Utils::isNotBlankString($objectData['contact']['company_phone'])) {
                        echo '<li>';
                        echo '<span class="openestate-expose-person-label">' . \htmlspecialchars(_('Phone')) . ':</span>';
                        echo '<span class="openestate-expose-person-value">' . \htmlspecialchars($objectData['contact']['company_phone']) . '</span>';
                        echo '</li>';
                    }
                    if (isset($objectData['contact']['person_mobile']) && Utils::isNotBlankString($objectData['contact']['person_mobile'])) {
                        echo '<li>';
                        echo '<span class="openestate-expose-person-label">' . \htmlspecialchars(_('Mobile')) . ':</span>';
                        echo '<span class="openestate-expose-person-value">' . \htmlspecialchars($objectData['contact']['person_mobile']) . '</span>';
                        echo '</li>';
                    } else if (isset($objectData['contact']['company_mobile']) && Utils::isNotBlankString($objectData['contact']['company_mobile'])) {
                        echo '<li>';
                        echo '<span class="openestate-expose-person-label">' . \htmlspecialchars(_('Mobile')) . ':</span>';
                        echo '<span class="openestate-expose-person-value">' . \htmlspecialchars($objectData['contact']['company_mobile']) . '</span>';
                        echo '</li>';
                    }
                    if (isset($objectData['contact']['person_mail']) && Utils::isNotBlankString($objectData['contact']['person_mail'])) {
                        echo '<li>';
                        echo '<span class="openestate-expose-person-label">' . \htmlspecialchars(_('Email')) . ':</span>';
                        echo '<span class="openestate-expose-person-value"><a href="mailto:' . \htmlspecialchars($objectData['contact']['person_mail']) . '">' . \htmlspecialchars($objectData['contact']['person_mail']) . '</a></span>';
                        echo '</li>';
                    } else if (isset($objectData['contact']['company_mail']) && Utils::isNotBlankString($objectData['contact']['company_mail'])) {
                        echo '<li>';
                        echo '<span class="openestate-expose-person-label">' . \htmlspecialchars(_('Email')) . ':</span>';
                        echo '<span class="openestate-expose-person-value"><a href="mailto:' . \htmlspecialchars($objectData['contact']['company_mail']) . '">' . \htmlspecialchars($objectData['contact']['company_mail']) . '</a></span>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>

            <div class="openestate-expose-contact">
                <h3><?= _('Get in contact with us') ?></h3>
                <div class="openestate-expose-contact-loading">
                    <i class="openestate-spinner openestate-icon-spinner"></i>
                    <?= \htmlspecialchars(_('Processing your request. Please wait for a moment.')) ?>
                </div>
                <div class="openestate-expose-contact-success">
                    <i class="openestate-icon-thumbs-up"></i>
                    <?= \htmlspecialchars(_('Your message was successfully sent. We will report back as soon as possible.')) ?>
                </div>
                <div class="openestate-expose-contact-error">
                    <i class="openestate-icon-attention"></i>
                    <?= \htmlspecialchars(_('Sorry, but we can\'t process your message due to an error.')) ?>
                    <span class="openestate-expose-contact-error-message"></span>
                </div>
                <form class="pure-form pure-form-stacked openestate-expose-contact-form">
                    <input type="hidden" name="<?= $env->actionParameter ?>"
                           value="<?= $contactAction->getName() ?>">
                    <input type="hidden" name="<?= $contactAction->objectIdParameter ?>"
                           value="<?= $objectId ?>">
                    <div class="pure-g">
                        <div class="pure-u-1 pure-u-md-1-3 openestate-expose-contact-name">
                            <div class="openestate-expose-contact-spacer">
                                <label for="contactName-<?= $uid ?>"><?= _('Your name') ?>:</label>
                                <input id="contactName-<?= $uid ?>"
                                       name="<?= $contactAction->getVar('name') ?>"
                                       type="text" class="pure-input-1 openestate-expose-contact-field">
                                <span class="openestate-expose-contact-validation pure-form-message">
                                    <i class="openestate-icon-attention"></i>
                                    <span class="openestate-expose-contact-validation-message"></span>
                                </span>
                            </div>
                        </div>

                        <div class="pure-u-1 pure-u-md-1-3 openestate-expose-contact-email">
                            <div class="openestate-expose-contact-spacer">
                                <label for="contactEmail-<?= $uid ?>"><?= _('Your email address') ?>:</label>
                                <input id="contactEmail-<?= $uid ?>"
                                       name="<?= $contactAction->getVar('email') ?>"
                                       type="text" class="pure-input-1 openestate-expose-contact-field">
                                <span class="openestate-expose-contact-validation pure-form-message">
                                    <i class="openestate-icon-attention"></i>
                                    <span class="openestate-expose-contact-validation-message"></span>
                                </span>
                            </div>
                        </div>

                        <div class="pure-u-1 pure-u-md-1-3 openestate-expose-contact-phone">
                            <label for="contactPhone-<?= $uid ?>"><?= _('Your phone number') ?>:</label>
                            <input id="contactPhone-<?= $uid ?>"
                                   name="<?= $contactAction->getVar('phone') ?>"
                                   type="text" class="pure-input-1 openestate-expose-contact-field">
                            <span class="openestate-expose-contact-validation pure-form-message">
                                <i class="openestate-icon-attention"></i>
                                <span class="openestate-expose-contact-validation-message"></span>
                            </span>
                        </div>

                        <div class="pure-u-1 openestate-expose-contact-message">
                            <label for="contactMessage-<?= $uid ?>"><?= _('Your message') ?>:</label>
                            <textarea id="contactMessage-<?= $uid ?>"
                                      name="<?= $contactAction->getVar('message') ?>"
                                      class="pure-input-1 openestate-expose-contact-field"><?= \htmlspecialchars(_('I am interested in your offer "{1}". Please get in contact with me.', $objectKey)) ?></textarea>
                            <span class="openestate-expose-contact-validation pure-form-message">
                                <i class="openestate-icon-attention"></i>
                                <span class="openestate-expose-contact-validation-message"></span>
                            </span>
                        </div>

                        <?php if ($contactAction->captchaVerification === true) { ?>
                            <div class="pure-u-1">
                                <label for="contactCaptcha-<?= $uid ?>"><?= _('Verification code') ?>:</label>
                                <div class="openestate-expose-contact-captcha">
                                    <div class="openestate-expose-contact-captcha-image">
                                        <img src="<?= $env->getCaptchaUrl() ?>"
                                             alt="<?= _('Verification code') ?>"><br>
                                        <a href="#"><?= _('refresh') ?></a>
                                    </div>
                                    <div class="openestate-expose-contact-captcha-field">
                                        <input id="contactCaptcha-<?= $uid ?>"
                                               name="<?= $contactAction->getVar('captcha') ?>"
                                               type="text" class="pure-input-1 openestate-expose-contact-field">
                                        <span class="openestate-expose-contact-validation pure-form-message">
                                            <i class="openestate-icon-attention"></i>
                                            <span class="openestate-expose-contact-validation-message"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($contactAction->termsVerification === true) { ?>
                            <div class="pure-u-1 openestate-expose-contact-terms">
                                <label for="contactTerms-<?= $uid ?>" class="pure-checkbox">
                                    <input id="contactTerms-<?= $uid ?>"
                                           name="<?= $contactAction->getVar('terms') ?>"
                                           type="checkbox" class="openestate-expose-contact-field" value="1">
                                    <?= _('Yes, I accept the terms of use and the data privacy statement.') ?>
                                </label>
                                <span class="openestate-expose-contact-validation pure-form-message">
                                    <i class="openestate-icon-attention"></i>
                                    <span class="openestate-expose-contact-validation-message"></span>
                                </span>
                            </div>
                        <?php } ?>

                        <div class="pure-u-1 openestate-expose-contact-submit">
                            <button type="submit"
                                    class="pure-button pure-button-primary openestate-button openestate-expose-contact-submit-button">
                                <?= _('Send message') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <?php
            if (Utils::isNotEmptyArray($objectData['images'])) {
                echo '<div class="openestate-expose-gallery-print">';
                echo '<h3>' . _('Images') . '</h3>';
                echo '<div>';
                foreach ($objectData['images'] as $image) {
                    $imageUrl = $env->getDataUrl($objectId . '/' . $image['name']);
                    $imageThumbUrl = ($env->getConfig()->dynamicImageScaling) ?
                        $env->getImageUrl(array('id' => $objectId, 'img' => $image['name'], 'y' => 325)) :
                        $env->getDataUrl($objectId . '/' . $image['thumb']);
                    $imageTitle = (isset($image['title'][$languageCode])) ?
                        $image['title'][$languageCode] : '';

                    echo '<div>'
                        . '<img src="' . \htmlspecialchars($imageThumbUrl) . '" alt="' . \htmlspecialchars($imageTitle) . '">'
                        . '<br>' . \htmlspecialchars($imageTitle)
                        . '</div>';
                }
                echo '</div>';
                echo '</div>';
            }
            ?>

            <?php } ?>

        </div>
    </div>

<?php

// write document footer
include('snippets/body-end.php');
if (!$view->isBodyOnly()) include('snippets/document-end.php');
