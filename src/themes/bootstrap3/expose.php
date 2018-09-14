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

use function htmlspecialchars as html;
use function OpenEstate\PhpExport\gettext as _;

/**
 * Implementation of the expose view for the Bootstrap3 theme.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @var View\ExposeHtml $view
 * the currently used view
 */

// Don't execute the file, if it is not properly loaded.
if (!isset($view) || !\is_object($view)) return;

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
    \ucfirst(_('real estate {1}', $objectKey));

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

// get object pdf
$objectPdf = $env->getObjectPdf($objectId, $languageCode);
$objectPdfLink = (\is_file($objectPdf)) ?
    $env->getDownloadUrl(array('id' => $objectId, 'lang' => $languageCode)) :
    null;

/**
 * action to change the language
 *
 * @var Action\SetLanguage $setLanguageAction
 */
$setLanguageAction = $env->newAction('SetLanguage');

/**
 * action for the contact form
 *
 * @var Action\Contact $contactAction
 */
$contactAction = $env->newAction('Contact');

/**
 * action to add an object to the list of favorites
 *
 * @var Action\AddFavorite $addFavoriteAction
 */
$addFavoriteAction = ($favoritesEnabled) ?
    $env->newAction('AddFavorite') : null;

/**
 * action to remove an object from the list of favorites
 *
 * @var Action\RemoveFavorite $removeFavoriteAction
 */
$removeFavoriteAction = ($favoritesEnabled) ?
    $env->newAction('RemoveFavorite') : null;

// get favorite options
$favorites = ($favoritesEnabled) ? $view->getFavorites() : array();
$objectFav = $favoritesEnabled && \array_search($objectId, $favorites) !== false;
$objectFavAddParams = ($favoritesEnabled) ?
    $addFavoriteAction->getParameters($env, $objectId) : null;
$objectFavRemoveParams = ($favoritesEnabled) ?
    $removeFavoriteAction->getParameters($env, $objectId) : null;

// init link providers
$linkProviders = array();
if (\is_array($objectData)) {
    foreach ($objectData['links'] as $link) {
        if (!isset($link['provider']))
            continue;

        $providerName = $link['provider'];
        if (isset($linkProviders[$providerName]))
            continue;

        $provider = $env->newLinkProvider($providerName);
        $linkProviders[$providerName] = $provider;
        $view->addHeaders($provider->getHeaderElements(), 800);
    }
}

// init map provider
$mapProvider = $env->newMapProvider();
if (\is_array($objectData) && $mapProvider !== null && $mapProvider->init($objectData))
    $view->addHeaders($mapProvider->getHeaderElements(), 900);
else
    $mapProvider = null;

// set page title
$view->setTitle($objectTitle);

// send 404 response code, if the requested object was not found
if (!\is_array($objectData))
    $view->setHttpResponseCode(404);

// add meta elements
if (!\is_array($objectData)) {
    $view->addHeader(Html\Meta::newRobots('noindex,follow'), 99);
} else {
    $view->addHeader(Html\Meta::newRobots('index,follow'), 99);

    // add meta description
    $metaDescription = null;
    if (isset($objectTexts['short_description'][$languageCode]) && Utils::isNotBlankString($objectTexts['short_description'][$languageCode]))
        $metaDescription = $objectTexts['short_description'][$languageCode];
    else if (isset($objectTexts['detailled_description'][$languageCode]) && Utils::isNotBlankString($objectTexts['detailled_description'][$languageCode]))
        $metaDescription = $objectTexts['detailled_description'][$languageCode];
    if (Utils::isNotBlankString($metaDescription)) {
        $view->addHeader(
            Html\Meta::newDescription(Utils::getAbbreviatedString($metaDescription, 150, true)),
            10);
    }

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
    if (Utils::isNotBlankString($metaKeywords)) {
        $view->addHeader(
            Html\Meta::newKeywords(Utils::getAbbreviatedString($metaKeywords, 150, true)),
            11);
    }
}

// register custom theme includes
$view->addHeader(Html\Stylesheet::newLink(
    'openestate-theme-css',
    $view->getThemeUrl('css/theme.css', array('v' => VERSION))
), 1000);
$view->addHeader(Html\Javascript::newLink(
    'openestate-theme-js',
    $view->getThemeUrl('js/theme.js', array('v' => VERSION)),
    'openestate_install_expose("' . $uid . '", "' . html($env->getActionUrl()) . '");',
    null,
    true
), 1001);

// write document header
if (!$view->isBodyOnly()) include('snippets/document-begin.php');
include('snippets/body-begin.php');

?>

    <div id="openestate-body-<?= $uid ?>" class="openestate-body openestate-expose"
         data-openestate-object="<?= html($objectId) ?>">

        <?php if (!\is_array($objectData)) { ?>

            <div class="openestate-expose-empty alert alert-info" role="alert">
                <p>
                    <i class="openestate-icon-problem"></i><?= html(_('The offer was not found. Maybe it is not published anymore.')) ?>
                </p>
                <hr>
                <p>
                    <a class="btn btn-primary"
                       href="<?= html($env->getListingUrl()) ?>">
                        <?= html(_('Visit our current offers.')) ?>
                    </a>
                </p>
            </div>

        <?php } else { ?>

        <div class="openestate-header">
            <div class="openestate-header-bar">
                <h3 class="openestate-header-title">
                    <i class="openestate-icon-expose"></i><?= html(\ucfirst(_('real estate {1}', $objectKey))) ?>
                </h3>
                <div class="openestate-header-actions">
                    <?php if ($objectPdfLink !== null) { ?>
                        <a class="openestate-action openestate-action-download"
                           href="<?= html($objectPdfLink) ?>" target="_blank"
                           title="<?= html(_('Download information about this object as PDF file.')) ?>">
                            <i class="openestate-icon-download"></i>
                        </a>
                    <?php } ?>
                    <?php if ($favoritesEnabled) { ?>
                        <a class="openestate-action openestate-action-fav-add" rel="nofollow"
                           href="<?= html($env->getExposeUrl(\array_merge($objectFavAddParams, $view->getParameters($objectId)))) ?>"
                           data-openestate-fav="<?= html(Utils::getJson($objectFavAddParams)) ?>"
                           title="<?= html(_('Add this object to your list of favorites.')) ?>"
                           style="<?= ($objectFav) ? 'display:none;' : '' ?>">
                            <i class="openestate-icon-fav-add"></i>
                        </a>
                        <a class="openestate-action openestate-action-fav-remove" rel="nofollow"
                           href="<?= html($env->getExposeUrl(\array_merge($objectFavRemoveParams, $view->getParameters($objectId)))) ?>"
                           data-openestate-fav="<?= html(Utils::getJson($objectFavRemoveParams)) ?>"
                           title="<?= html(_('Remove this object from your list of favorites.')) ?>"
                           style="<?= (!$objectFav) ? 'display:none;' : '' ?>">
                            <i class="openestate-icon-fav-remove"></i>
                        </a>
                    <?php } ?>
                    <?php if ($objectPdfLink !== null || $favoritesEnabled) { ?>
                        <span class="openestate-action-separator"></span>
                    <?php } ?>
                    <a class="openestate-action openestate-action-listing"
                       href="<?= html($env->getListingUrl()) ?>"
                       title="<?= html(_('Show current offers.')) ?>">
                        <i class="openestate-icon-listing"></i>
                    </a>
                    <?php if ($favoritesEnabled) { ?>
                        <a class="openestate-action openestate-action-fav" rel="nofollow"
                           href="<?= html($env->getFavoriteUrl()) ?>"
                           title="<?= html(_('Show list of favorite objects.')) ?>">
                            <i class="openestate-icon-fav"></i>
                        </a>
                    <?php } ?>
                    <?php if ($languageSelection) { ?>
                        <div class="dropdown">
                            <a class="openestate-action openestate-action-language" href="#" data-toggle="dropdown"
                               title="<?= html(_('Select your preferred language.')) ?>">
                                <i class="openestate-icon-language"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right openestate-action-language-dropdown">
                                <?php
                                foreach ($languageCodes as $lang) {
                                    $languageName = $env->getLanguageName($lang);
                                    $languageClass = ($languageCode == $lang) ? 'active' : '';
                                    $languageParams = $setLanguageAction->getParameters($env, $lang);
                                    echo '<li><a href="' . html($env->getExposeUrl(\array_merge($languageParams, $view->getParameters($objectId)))) . '" '
                                        . 'data-openestate-action="' . html(Utils::getJson($languageParams)) . '" '
                                        . 'class="' . $languageClass . '">'
                                        . html($languageName) . '</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
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

                echo '<div><a href="' . html($imageUrl) . '" title="' . html($imageTitle) . '">'
                    . '<img src="' . html($imageThumbUrl) . '" alt="' . html($imageTitle) . '">'
                    . '</a></div>';
            }
            echo '</div>';
        }
        ?>

        <div class="openestate-expose-content">
            <div class="openestate-expose-general panel panel-primary">
                <div class="panel-heading">
                    <h2 class="openestate-expose-title panel-title"><?= html($objectTitle) ?></h2>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-4">
                            <h4><?= html(_('identifier')) ?>:</h4>
                            <p>
                                <?= html($objectKey) ?>
                            </p>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <h4><?= html(_('offer')) ?>:</h4>
                            <p>
                                <?= ($objectType !== null) ? html($objectType) : '???' ?> /
                                <?= ($objectAction !== null) ? html($objectAction) : '???' ?>
                            </p>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <h4><?= html(_('address')) ?>:</h4>
                            <p>
                                <?php
                                if (isset($objectData['address']['street']) && Utils::isNotBlankString($objectData['address']['street'])) {
                                    echo html($objectData['address']['street']);
                                    if (isset($objectData['address']['street_nr']) && Utils::isNotBlankString($objectData['address']['street_nr']))
                                        echo ' ' . html($objectData['address']['street_nr']);
                                    echo '<br>';
                                }
                                echo html($objectData['address']['postal']) . ' '
                                    . html($objectData['address']['city']);
                                if (isset($objectData['address']['city_part']) && Utils::isNotBlankString($objectData['address']['city_part']))
                                    echo ' / ' . html($objectData['address']['city_part']);
                                if (isset($objectData['address']['country_name'][$languageCode]) && Utils::isNotBlankString($objectData['address']['country_name'][$languageCode]))
                                    echo '<br>' . html($objectData['address']['country_name'][$languageCode]);
                                if (isset($objectData['address']['region']) && Utils::isNotBlankString($objectData['address']['region']))
                                    echo ' / ' . html($objectData['address']['region']);
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            foreach ($objectData['attributes'] as $group => $attributes) {
                if (Utils::isEmptyArray($attributes)) continue;
                $groupTitle = (isset($i18n['openestate']['groups'][$group])) ? $i18n['openestate']['groups'][$group] : $group;
                echo '<div class="openestate-expose-attributes openestate-expose-attributes-' . html($group) . ' panel panel-default">';
                echo '<div class="panel-heading">';
                echo '<h3 class="panel-title">' . \ucfirst(html($groupTitle)) . '</h3>';
                echo '</div>';
                echo '<div class="panel-body">';
                echo '<div class="row">';
                foreach ($attributes as $name => $value) {

                    $text = html(\trim($value[$languageCode]));
                    if ((isset($value['value']) && $value['value'] === true) || \strtolower($text) === \strtolower(_('yes'))) {
                        $text = '<i class="openestate-icon-yes" title="' . html(_('yes')) . '"></i>';
                    } else if ((isset($value['value']) && $value['value'] === false) || \strtolower($text) === \strtolower(_('no'))) {
                        $text = '<i class="openestate-icon-no" title="' . html(_('no')) . '"></i>';
                    }

                    echo '<div class="col-sm-6 col-md-4">';
                    echo '<h4>' . html($i18n['openestate']['attributes'][$group][$name]) . ':</h4>';
                    echo '<p>' . $text . '</p>';
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>

            <?php
            foreach ($objectTexts as $name => $value) {
                if ($name === 'id' || $name === 'short_description' || $name === 'keywords') continue;
                if (Utils::isBlankString($value[$languageCode])) continue;
                $text = \trim($value[$languageCode]);
                //$text = str_replace('<br/>', "\n", $value[$languageCode]);

                echo '<div class="openestate-expose-text openestate-expose-text-' . html($name) . ' panel panel-default">';
                echo '<div class="panel-heading">';
                echo '<h3 class="panel-title">' . \ucfirst(html($i18n['openestate']['attributes']['descriptions'][$name])) . '</h3>';
                echo '</div>';
                echo '<div class="panel-body">';
                echo '<p>' . $text . '</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>

            <?php if ($mapProvider !== null) { ?>
                <div class="openestate-expose-map openestate-expose-map-<?= html($mapProvider->getName()) ?> panel panel-default hidden-print">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= html(\ucfirst(_('area map'))) ?></h3>
                    </div>
                    <div class="panel-body">
                        <?= $mapProvider->getBody($objectData) ?>
                    </div>
                </div>
            <?php } ?>

            <?php
            $localVideos = array();
            foreach ($objectData['media'] as $media) {
                if (strpos(\strtolower($media['mimetype']), 'video/') === 0)
                    $localVideos[] = $media;
            }
            $linkedVideos = array();
            foreach ($objectData['links'] as $link) {
                if (!isset($link['provider']))
                    continue;

                $providerName = $link['provider'];
                if (!isset($linkProviders[$providerName]))
                    continue;

                if (strpos(\strtolower($providerName), 'video@') === 0)
                    $linkedVideos[] = $link;
            }
            if (\count($localVideos) > 0 || \count($linkedVideos) > 0) {
                echo '<div class="openestate-expose-videos panel panel-default hidden-print">';
                echo '<div class="panel-heading">';
                echo '<h3 class="panel-title">' . html(\ucfirst(_('videos'))) . '</h3>';
                echo '</div>';
                if (\count($localVideos) > 0) {
                    echo '<div class="openestate-expose-video-local panel-body">';
                    echo '<video controls>';
                    foreach ($localVideos as $video) {
                        $videoUrl = $env->getDataUrl($objectId . '/' . $video['name']);
                        echo '<source src="' . html($videoUrl) . '" type="' . html($video['mimetype']) . '">';
                    }
                    echo html(_('Your web browser does not support video playback.'));
                    echo '</video>';
                    echo '</div>';
                }
                foreach ($linkedVideos as $video) {
                    $provider = $env->newLinkProvider($video['provider']);
                    if ($provider === null)
                        continue;

                    $providerName = \explode('@', $video['provider'], 2);
                    echo '<div class="openestate-expose-video-embed openestate-expose-video-embed-' . html(\str_replace('.', '-', $providerName[1])) . ' panel-body">';
                    echo $provider->getBody($video['id'], $video['url'], $video['title'][$languageCode]);
                    echo '</div>';
                }
                echo '</div>';
            }
            ?>

            <?php
            $localFiles = array();
            foreach ($objectData['media'] as $media) {
                if (strpos(\strtolower($media['mimetype']), 'video/') === 0)
                    continue;
                $localFiles[] = $media;
            }

            $links = array();
            foreach ($objectData['links'] as $link) {
                if (!isset($link['provider']))
                    $links[] = $link;
            }

            if (\count($localFiles) > 0 || \count($links) > 0) {
                echo '<div class="openestate-expose-videos panel panel-default hidden-print">';
                echo '<div class="panel-heading">';
                echo '<h3 class="panel-title">' . html(\ucfirst(_('further links'))) . '</h3>';
                echo '</div>';
                echo '<div class="panel-body">';
                echo '<ul>';
                foreach ($localFiles as $file) {
                    $filePath = $env->getDataPath($objectId, $file['name']);
                    if (!\is_file($filePath))
                        continue;

                    $fileSize = \filesize($filePath);
                    $fileUrl = $env->getDataUrl($objectId . '/' . $file['name']);
                    $fileTitle = (isset($file['title'][$languageCode]) && Utils::isNotBlankString($file['title'][$languageCode])) ?
                        $file['title'][$languageCode] : _('attachment');

                    echo '<li><a href="' . html($fileUrl) . '" target="_blank">' . html($fileTitle) . '</a> (' . Utils::writeBytes($fileSize) . ')</li>';
                }

                foreach ($links as $link) {
                    $linkUrl = $link['url'];
                    $linkTitle = (isset($link['title'][$languageCode]) && Utils::isNotBlankString($link['title'][$languageCode])) ?
                        $link['title'][$languageCode] : $linkUrl;
                    echo '<li><a href="' . html($linkUrl) . '" target="_blank">' . html($linkTitle) . '</a></li>';
                }
                echo '</ul>';
                echo '</div>';
                echo '</div>';
            }
            ?>

            <div class="openestate-expose-person panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= html(\ucfirst(_('your contact person'))) ?></h3>
                </div>
                <div class="panel-body">
                    <ul>
                        <?php
                        if (isset($objectData['contact']['person_fullname']) && Utils::isNotBlankString($objectData['contact']['person_fullname']))
                            echo '<li><span class="openestate-expose-person-name">' . html($objectData['contact']['person_fullname']) . '</span></li>';
                        if (isset($objectData['contact']['person_phone']) && Utils::isNotBlankString($objectData['contact']['person_phone'])) {
                            echo '<li>';
                            echo '<span class="openestate-expose-person-label">' . html(_('phone')) . ':</span>';
                            echo '<span class="openestate-expose-person-value">' . html($objectData['contact']['person_phone']) . '</span>';
                            echo '</li>';
                        } else if (isset($objectData['contact']['company_phone']) && Utils::isNotBlankString($objectData['contact']['company_phone'])) {
                            echo '<li>';
                            echo '<span class="openestate-expose-person-label">' . html(_('phone')) . ':</span>';
                            echo '<span class="openestate-expose-person-value">' . html($objectData['contact']['company_phone']) . '</span>';
                            echo '</li>';
                        }
                        if (isset($objectData['contact']['person_mobile']) && Utils::isNotBlankString($objectData['contact']['person_mobile'])) {
                            echo '<li>';
                            echo '<span class="openestate-expose-person-label">' . html(_('mobile')) . ':</span>';
                            echo '<span class="openestate-expose-person-value">' . html($objectData['contact']['person_mobile']) . '</span>';
                            echo '</li>';
                        } else if (isset($objectData['contact']['company_mobile']) && Utils::isNotBlankString($objectData['contact']['company_mobile'])) {
                            echo '<li>';
                            echo '<span class="openestate-expose-person-label">' . html(_('mobile')) . ':</span>';
                            echo '<span class="openestate-expose-person-value">' . html($objectData['contact']['company_mobile']) . '</span>';
                            echo '</li>';
                        }
                        if (isset($objectData['contact']['person_mail']) && Utils::isNotBlankString($objectData['contact']['person_mail'])) {
                            echo '<li>';
                            echo '<span class="openestate-expose-person-label">' . html(_('email')) . ':</span>';
                            echo '<span class="openestate-expose-person-value"><a href="mailto:' . html($objectData['contact']['person_mail']) . '">' . html($objectData['contact']['person_mail']) . '</a></span>';
                            echo '</li>';
                        } else if (isset($objectData['contact']['company_mail']) && Utils::isNotBlankString($objectData['contact']['company_mail'])) {
                            echo '<li>';
                            echo '<span class="openestate-expose-person-label">' . html(_('email')) . ':</span>';
                            echo '<span class="openestate-expose-person-value"><a href="mailto:' . html($objectData['contact']['company_mail']) . '">' . html($objectData['contact']['company_mail']) . '</a></span>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <div class="openestate-expose-contact panel panel-default hidden-print">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= html(\ucfirst(_('get in contact'))) ?></h3>
                </div>
                <div class="panel-body">
                    <div class="openestate-expose-contact-loading alert alert-info" role="alert">
                        <i class="openestate-spinner openestate-icon-spinner"></i><?= html(_('Processing your request. Please wait for a moment.')) ?>
                    </div>
                    <div class="openestate-expose-contact-success alert alert-success" role="alert">
                        <i class="openestate-icon-success"></i><?= html(_('Your message was successfully sent. We will report back as soon as possible.')) ?>
                    </div>
                    <div class="openestate-expose-contact-error alert alert-danger" role="alert">
                        <i class="openestate-icon-problem"></i><?= html(_('Sorry, but we can\'t process your message due to an error.')) ?>
                        <span class="openestate-expose-contact-error-message"></span>
                    </div>
                    <form class="openestate-expose-contact-form">
                        <input type="hidden" name="<?= html($env->actionParameter) ?>"
                               value="<?= html($contactAction->getName()) ?>">
                        <input type="hidden" name="<?= html($contactAction->objectIdParameter) ?>"
                               value="<?= html($objectId) ?>">
                        <div class="row">
                            <div class="col-sm-4 form-group openestate-expose-contact-name">
                                <label for="contactName-<?= $uid ?>"><?= html(_('your name')) ?>:</label>
                                <input id="contactName-<?= $uid ?>"
                                       name="<?= html($contactAction->getVar('name')) ?>"
                                       type="text" class="form-control openestate-expose-contact-field">
                                <p class="openestate-expose-contact-validation help-block">
                                    <i class="openestate-icon-attention"></i>
                                    <span class="openestate-expose-contact-validation-message"></span>
                                </p>
                            </div>
                            <div class="col-sm-4 form-group openestate-expose-contact-email">
                                <label for="contactEmail-<?= $uid ?>"><?= html(_('your email address')) ?>:</label>
                                <input id="contactEmail-<?= $uid ?>"
                                       name="<?= html($contactAction->getVar('email')) ?>"
                                       type="text" class="form-control openestate-expose-contact-field">
                                <p class="openestate-expose-contact-validation help-block">
                                    <i class="openestate-icon-attention"></i>
                                    <span class="openestate-expose-contact-validation-message"></span>
                                </p>
                            </div>
                            <div class="col-sm-4 form-group openestate-expose-contact-phone">
                                <label for="contactPhone-<?= $uid ?>"><?= html(_('your phone number')) ?>:</label>
                                <input id="contactPhone-<?= $uid ?>"
                                       name="<?= html($contactAction->getVar('phone')) ?>"
                                       type="text" class="form-control openestate-expose-contact-field">
                                <p class="openestate-expose-contact-validation help-block">
                                    <i class="openestate-icon-attention"></i>
                                    <span class="openestate-expose-contact-validation-message"></span>
                                </p>
                            </div>
                        </div>

                        <div class="form-group openestate-expose-contact-message">
                            <label for="contactMessage-<?= $uid ?>"><?= html(_('your message')) ?>:</label>
                            <textarea id="contactMessage-<?= $uid ?>"
                                      name="<?= html($contactAction->getVar('message')) ?>"
                                      class="form-control openestate-expose-contact-field"><?= html(_('I am interested in your offer "{1}". Please get in contact with me.', $objectKey)) ?></textarea>
                            <p class="openestate-expose-contact-validation help-block">
                                <i class="openestate-icon-attention"></i>
                                <span class="openestate-expose-contact-validation-message"></span>
                            </p>
                        </div>

                        <?php if ($contactAction->captchaVerification === true) { ?>
                            <div class="form-group openestate-expose-contact-captcha">
                                <label for="contactCaptcha-<?= $uid ?>"><?= html(_('verification code')) ?>:</label>
                                <div class="media">
                                    <div class="media-left">
                                        <div class="openestate-expose-contact-captcha-image">
                                            <img src="<?= html($env->getCaptchaUrl()) ?>"
                                                 alt="<?= html(_('verification code')) ?>"><br>
                                            <a href="#"><?= html(_('refresh')) ?></a>
                                        </div>
                                    </div>
                                    <div class="media-body openestate-expose-contact-captcha-field">
                                        <input id="contactCaptcha-<?= $uid ?>"
                                               name="<?= html($contactAction->getVar('captcha')) ?>"
                                               type="text" class="form-control openestate-expose-contact-field">
                                        <p class="openestate-expose-contact-validation help-block">
                                            <i class="openestate-icon-attention"></i>
                                            <span class="openestate-expose-contact-validation-message"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($contactAction->termsVerification === true) { ?>
                            <div class="form-group openestate-expose-contact-terms">
                                <label for="contactTerms-<?= $uid ?>" class="pure-checkbox">
                                    <input id="contactTerms-<?= $uid ?>"
                                           name="<?= html($contactAction->getVar('terms')) ?>"
                                           type="checkbox" class="openestate-expose-contact-field" value="1">
                                    <?= html(_('Yes, I accept the terms of use and the data privacy statement.')) ?>
                                </label>
                                <p class="openestate-expose-contact-validation help-block">
                                    <i class="openestate-icon-attention"></i>
                                    <span class="openestate-expose-contact-validation-message"></span>
                                </p>
                            </div>
                        <?php } ?>

                        <div class="form-group openestate-expose-contact-submit">
                            <button type="submit"
                                    class="btn btn-primary openestate-button openestate-expose-contact-submit-button">
                                <i class="openestate-icon-send"></i><?= html(_('send message')) ?>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <?php
            if (Utils::isNotEmptyArray($objectData['images'])) {
                echo '<div class="openestate-expose-gallery-print panel panel-default visible-print-block">';
                echo '<div class="panel-heading">';
                echo '<h3 class="panel-title">' . html(\ucfirst(_('images'))) . '</h3>';
                echo '</div>';
                echo '<div class="panel-body">';
                foreach ($objectData['images'] as $image) {
                    //$imageUrl = $env->getDataUrl($objectId . '/' . $image['name']);
                    $imageThumbUrl = ($env->getConfig()->dynamicImageScaling) ?
                        $env->getImageUrl(array('id' => $objectId, 'img' => $image['name'], 'y' => 325)) :
                        $env->getDataUrl($objectId . '/' . $image['thumb']);
                    $imageTitle = (isset($image['title'][$languageCode])) ?
                        $image['title'][$languageCode] : '';

                    echo '<div>'
                        . '<img src="' . html($imageThumbUrl) . '" alt="' . html($imageTitle) . '">'
                        . '<br>' . html($imageTitle)
                        . '</div>';
                }
                echo '</div>';
                echo '</div>';
            }
            ?>

            <?php } ?>

        </div>

        <div class="modal fade openestate-gallery-dialog" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title openestate-gallery-dialog-title">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        <img src="#" class="img-responsive img-rounded openestate-gallery-dialog-image" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="openestate-loading-<?= $uid ?>" class="openestate-loading alert alert-info" role="alert">
        <i class="openestate-spinner openestate-icon-spinner"></i>
        <?= html(_('Processing your request. Please wait for a moment.')) ?>
    </div>

    <?php

// write document footer
include('snippets/body-end.php');
if (!$view->isBodyOnly()) include('snippets/document-end.php');
