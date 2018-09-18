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

use function htmlspecialchars as html;
use function OpenEstate\PhpExport\gettext as _;

/**
 * Implementation of the favorites view for the Bootstrap3 theme.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 *
 * @var View\FavoriteHtml $view
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

// get listing options
$objectIds = $view->getObjectIds();
$currentView = $view->getView();
$currentPage = $view->getPage();
$totalPages = $view->getPageCount(\count($objectIds));

/**
 * expose view
 *
 * @var View\ExposeHtml $exposeView
 */
$exposeView = $env->newExposeHtml();

/**
 * action to change the language
 *
 * @var Action\SetLanguage $setLanguageAction
 */
$setLanguageAction = $env->newAction('SetLanguage');

/**
 * action to change ordering
 *
 * @var Action\SetFavoriteOrder $setOrderAction
 */
$setOrderAction = $env->newAction('SetFavoriteOrder');

/**
 * action to switch between different listing views
 *
 * @var Action\SetFavoriteView $setViewAction
 */
$setViewAction = $env->newAction('SetFavoriteView');

/**
 * action to change the current page of the listing view
 *
 * @var Action\SetFavoritePage $setPageAction
 */
$setPageAction = $env->newAction('SetFavoritePage');

/**
 * action to remove an object from the list of favorites
 *
 * @var Action\RemoveFavorite $removeFavoriteAction
 */
$removeFavoriteAction = $env->newAction('RemoveFavorite');

// set page title
$view->setTitle(\ucfirst(_('my favored objects')));

// add meta elements
$view->addHeader(Html\Meta::newRobots('noindex,follow'), 99);

// register custom theme includes
$view->addHeader(Html\Stylesheet::newLink(
    'openestate-theme-css',
    $view->getThemeUrl('css/theme.css', array('v' => VERSION))
), 1000);
$view->addHeader(Html\Javascript::newLink(
    'openestate-theme-js',
    $view->getThemeUrl('js/theme.js', array('v' => VERSION)),
    'openestate_install_favorite("' . $uid . '", "' . html($env->getActionUrl()) . '");',
    null,
    true
), 1001);

// write document header
if (!$view->isBodyOnly()) include('snippets/document-begin.php');
include('snippets/body-begin.php');

?>

    <div id="openestate-body-<?= $uid ?>" class="openestate-body openestate-fav">

        <div class="openestate-header">
            <div class="openestate-header-bar">
                <h3 class="openestate-header-title">
                    <i class="openestate-icon-fav"></i><?= html(\ucfirst(_('my favored objects'))) ?>
                </h3>
                <div class="openestate-header-actions">
                    <a class="openestate-action openestate-action-sort" href="#"
                       title="<?= html(_('Show sort options.')) ?>">
                        <i class="openestate-icon-sort"></i>
                    </a>
                    <a class="openestate-action openestate-action-details"
                       href="<?= html($env->getFavoriteUrl($setViewAction->getParameters($env, 'detail'))) ?>"
                       data-openestate-action="<?= html(Utils::getJson($setViewAction->getParameters($env, 'detail'))) ?>"
                       title="<?= html(_('Show objects in detailed view.')) ?>">
                        <i class="openestate-icon-list-detail"></i>
                    </a>
                    <a class="openestate-action openestate-action-thumb"
                       href="<?= html($env->getFavoriteUrl($setViewAction->getParameters($env, 'thumb'))) ?>"
                       data-openestate-action="<?= html(Utils::getJson($setViewAction->getParameters($env, 'thumb'))) ?>"
                       title="<?= html(_('Show objects in gallery view.')) ?>">
                        <i class="openestate-icon-list-thumb"></i>
                    </a>
                    <span class="openestate-action-separator"></span>
                    <a class="openestate-action openestate-action-listing"
                       href="<?= html($env->getListingUrl()) ?>"
                       title="<?= html(_('Show current offers.')) ?>">
                        <i class="openestate-icon-listing"></i>
                    </a>
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
                                    echo '<li><a href="' . html($env->getFavoriteUrl($languageParams)) . '" '
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

            <form action="<?= html($env->getFavoriteUrl()) ?>" method="get"
                  class="openestate-sort-form form-inline">
                <input type="hidden" name="<?= html($env->actionParameter) ?>"
                       value="<?= html($setOrderAction->getName()) ?>">
                <fieldset>
                    <legend><?= html(\ucfirst(_('sort offers'))) ?></legend>
                    <?php
                    $orderValue = $view->getOrder();
                    $orderDir = $view->getOrderDirection();
                    if ($orderDir == 'asc') {
                        $orderAscClass = 'btn-primary';
                        $orderDescClass = 'btn-default';
                    } else {
                        $orderAscClass = 'btn-default';
                        $orderDescClass = 'btn-primary';
                    }

                    /** @var Order\AbstractOrder $order */
                    foreach ($view->orders as $order) {
                        $selected = ($orderValue == $order->getName()) ? 'checked' : '';
                        echo '<div class="radio"><label>' .
                            '<input type="radio" name="' . html($setOrderAction->orderParameter) . '" value="' . html($order->getName()) . '" ' . $selected . '> '
                            . html($order->getTitle($languageCode)) .
                            '</label></div>' . "\n";
                    }
                    ?>
                    <div class="btn-group" role="group">
                        <button type="submit"
                                class="openestate-sort-form-asc btn <?= $orderAscClass ?>"
                                name="<?= html($setOrderAction->directionParameter) ?>" value="asc">
                            <i class="openestate-icon-sort-asc"></i><?= html(_('ascending')) ?>
                        </button>
                        <button type="submit"
                                class="openestate-sort-form-desc btn <?= $orderDescClass ?>"
                                name="<?= html($setOrderAction->directionParameter) ?>" value="desc">
                            <i class="openestate-icon-sort-desc"></i><?= html(_('descending')) ?>
                        </button>
                    </div>
                </fieldset>
            </form>
        </div>

        <?php

        if (Utils::isEmptyArray($objectIds)) {
            echo '<div class="openestate-fav-empty alert alert-info" role="alert">'
                . '<p>' . html(_('Your list of favorite objects is empty.')) . '</p>'
                . '<hr>'
                . '<p><a class="btn btn-primary" href="' . html($env->getListingUrl()) . '">'
                . html(_('Visit our current offers.'))
                . '</a></p>'
                . '</div>';
        } else {
            echo '<div class="openestate-fav-items openestate-fav-items-' . html($currentView) . '">';
            foreach ($view->getObjectIdsOnThisPage($objectIds) as $objectId) {
                $objectData = $env->getObject($objectId);
                if ($objectData === null)
                    continue;

                //$objectTexts = $env->getObjectText($objectId);
                //if ($objectTexts === null)
                //    continue;

                $objectKey = (isset($objectData['nr']) && \is_string($objectData['nr'])) ?
                    $objectData['nr'] :
                    '#' . $objectId;

                $objectUrl = $exposeView->getUrl($env, $objectId);
                $objectTitle = (isset($objectData['title'][$languageCode])) ?
                    $objectData['title'][$languageCode] :
                    _('real estate {0}', $objectKey);

                $objectImage = (isset($objectData['images']) && \is_array($objectData['images']) && \count($objectData['images']) > 0) ?
                    $objectData['images'][0] : null;
                $objectImageLink = (\is_array($objectImage)) ?
                    ($env->getConfig()->dynamicImageScaling) ?
                        $env->getImageUrl(array('id' => $objectId, 'img' => $objectImage['name'], 'x' => 500, 'y' => 325)) :
                        $env->getDataUrl($objectId . '/' . $objectImage['thumb']) :
                    null;

                $objectPdf = $env->getObjectPdf($objectId, $languageCode);
                $objectPdfLink = (\is_file($objectPdf)) ?
                    $env->getDownloadUrl(array('id' => $objectId, 'lang' => $languageCode)) :
                    null;

                $objectFavRemoveParams = $removeFavoriteAction->getParameters($env, $objectId);

                if ($currentView == 'thumb') { ?>

                    <div class="openestate-fav-item openestate-fav-thumb"
                         data-openestate-object="<?= html($objectId) ?>">
                        <?php
                        // print object image
                        if (!\is_null($objectImageLink)) {
                            echo '<div class="openestate-fav-thumb-image">'
                                . '<a href="' . html($objectUrl) . '" title="' . html(_('Show details about this object.')) . '">'
                                . '<img src="' . html($objectImageLink) . '" alt="" class="img-thumbnail">'
                                . '</a>'
                                . '</div>';
                        }
                        ?>

                        <div class="openestate-fav-thumb-popup">
                            <h3 class="openestate-fav-thumb-title">
                                <a href="<?= html($objectUrl) ?>"
                                   title="<?= html(_('Show details about this object.')) ?>">
                                    <?= html($objectTitle) ?>
                                </a>
                            </h3>
                            <?php

                            // print object details
                            $entries = array();
                            foreach ($view->getObjectColumns($objectData, $languageCode) as $colIndex => $fields) {
                                foreach ($fields as $field) {
                                    $entry = $view->getObjectColumnValue($objectData, $field, $i18n, $languageCode);
                                    if (Utils::isNotBlankString($entry))
                                        $entries[] = $entry;
                                    if (\count($entries) >= 6) break;
                                }
                                if (\count($entries) >= 6) break;
                            }

                            echo '<ul class="openestate-fav-col">';
                            foreach ($entries as $entry) {
                                echo '<li>' . $entry . '</li>';
                            }
                            echo '</ul>';

                            ?>

                            <div class="openestate-fav-thumb-actions">
                                <a class="openestate-action openestate-action-expose" href="<?= html($objectUrl) ?>"
                                   title="<?= html(_('Show details about this object.')) ?>">
                                    <i class="openestate-icon-expose"></i><?= html(\ucfirst(_('details'))) ?>
                                </a>
                                <?php if ($objectPdfLink !== null) { ?>
                                    <a class="openestate-action openestate-action-download"
                                       href="<?= html($objectPdfLink) ?>" target="_blank"
                                       title="<?= html(_('Download information about this object as PDF file.')) ?>">
                                        <i class="openestate-icon-download"></i><?= html(\ucfirst(_('download'))) ?>
                                    </a>
                                <?php } ?>
                                <a class="openestate-action openestate-action-fav-remove" rel="nofollow"
                                   href="<?= html($env->getFavoriteUrl($objectFavRemoveParams)) ?>"
                                   data-openestate-action="<?= html(Utils::getJson($objectFavRemoveParams)) ?>"
                                   title="<?= html(_('Remove this object from your list of favorites.')) ?>">
                                    <i class="openestate-icon-fav-remove"></i><?= html(\ucfirst(_('remove from favorites'))) ?>
                                </a>
                            </div>
                        </div>
                    </div>

                <?php } else { ?>

                    <div class="panel panel-default openestate-fav-item openestate-fav-detail"
                         data-openestate-object="<?= html($objectId) ?>">
                        <div class="panel-heading">
                            <h3 class="panel-title openestate-fav-detail-title">
                                <a href="<?= html($objectUrl) ?>"
                                   title="<?= html(_('Show details about this object.')) ?>">
                                    <?= html($objectTitle) ?>
                                </a>
                            </h3>
                        </div>
                        <div class="panel-body openestate-fav-detail-content">
                            <?php

                            // print object image
                            if (!\is_null($objectImageLink)) {
                                echo '<div class="openestate-fav-detail-image">'
                                    . '<a href="' . html($objectUrl) . '" title="' . html(_('Show details about this object.')) . '">'
                                    . '<img src="' . html($objectImageLink) . '" class="media-object img-thumbnail" alt="">'
                                    . '</a>'
                                    . '</div>';
                            }

                            // print object details
                            foreach ($view->getObjectColumns($objectData, $languageCode) as $colIndex => $fields) {
                                $entries = array();
                                foreach ($fields as $field) {
                                    $entry = $view->getObjectColumnValue($objectData, $field, $i18n, $languageCode);
                                    if (Utils::isNotBlankString($entry))
                                        $entries[] = $entry;
                                }

                                if (\count($entries) < 1) continue;
                                echo '<ul class="openestate-fav-col openestate-fav-col-' . html($colIndex) . '">';
                                foreach ($entries as $entry) {
                                    echo '<li>' . $entry . '</li>';
                                }
                                echo '</ul>';
                            }
                            ?>
                        </div>
                        <div class="panel-footer openestate-fav-detail-actions">
                            <a class="openestate-action openestate-action-expose" href="<?= html($objectUrl) ?>"
                               title="<?= html(_('Show details about this object.')) ?>">
                                <i class="openestate-icon-expose"></i><?= html(\ucfirst(_('details'))) ?>
                            </a>
                            <?php if ($objectPdfLink !== null) { ?>
                                <a class="openestate-action openestate-action-download"
                                   href="<?= html($objectPdfLink) ?>" target="_blank"
                                   title="<?= html(_('Download information about this object as PDF file.')) ?>">
                                    <i class="openestate-icon-download"></i><?= html(\ucfirst(_('download'))) ?>
                                </a>
                            <?php } ?>
                            <a class="openestate-action openestate-action-fav-remove" rel="nofollow"
                               href="<?= html($env->getFavoriteUrl($objectFavRemoveParams)) ?>"
                               data-openestate-action="<?= html(Utils::getJson($objectFavRemoveParams)) ?>"
                               title="<?= html(_('Remove this object from your list of favorites.')) ?>">
                                <i class="openestate-icon-fav-remove"></i><?= html(\ucfirst(_('remove from favorites'))) ?>
                            </a>
                        </div>
                    </div>

                <?php }
            }
            echo '</div>';

            if ($totalPages > 1) {
                echo '<nav class="openestate-fav-pagination openestate-fav-pagination-' . html($currentView) . '">';
                echo '<ul class="pager">';
                if ($currentPage > 1) {
                    $params = $setPageAction->getParameters($env, $currentPage - 1);
                    echo '<li class=""><a class="openestate-fav-pagination-prev" '
                        . 'href="' . html($env->getFavoriteUrl($params)) . '" '
                        . 'data-openestate-action="' . html(Utils::getJson($params)) . '" '
                        . 'title="' . html(_('Show previous page.')) . '">'
                        . '<i class="openestate-icon-left"></i></a></li>';
                } else {
                    echo '<li class="disabled"><a class="openestate-fav-pagination-prev" '
                        . 'href="#" title="' . html(_('Show previous page.')) . '" '
                        . 'disabled>'
                        . '<i class="openestate-icon-left"></i></a></li>';
                }

                if ($currentPage < $totalPages) {
                    $params = $setPageAction->getParameters($env, $currentPage + 1);
                    echo '<li class=""><a class="openestate-fav-pagination-next" '
                        . 'href="' . html($env->getFavoriteUrl($params)) . '" '
                        . 'data-openestate-action="' . html(Utils::getJson($params)) . '" '
                        . 'title="' . html(_('Show next page.')) . '">'
                        . '<i class="openestate-icon-right"></i></a></li>';
                } else {
                    echo '<li class="disabled"><a class="openestate-fav-pagination-next" '
                        . 'href="#" '
                        . 'title="' . html(_('Show next page.')) . '" '
                        . 'disabled>'
                        . '<i class="openestate-icon-right"></i></a></li>';
                }

                echo '</ul>';
                echo '</nav>';
            }
        }
        ?>

    </div>

    <div id="openestate-loading-<?= $uid ?>" class="openestate-loading alert alert-info" role="alert">
        <i class="openestate-spinner openestate-icon-spinner"></i>
        <?= html(_('Processing your request. Please wait for a moment.')) ?>
    </div>

    <?php

// write document footer
include('snippets/body-end.php');
if (!$view->isBodyOnly()) include('snippets/document-end.php');
