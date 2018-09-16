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
 * Implementation of the listing view for the default theme.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @var View\ListingHtml $view
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
$favoritesEnabled = $env->getConfig()->favorites;
$favorites = ($favoritesEnabled) ? $view->getFavorites() : array();
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
 * action to change filter values
 *
 * @var Action\SetListingFilter $setFilterAction
 */
$setFilterAction = $env->newAction('SetListingFilter');

/**
 * @var Action\SetListingOrder $setOrderAction
 * action to change ordering
 */
$setOrderAction = $env->newAction('SetListingOrder');

/**
 * action to switch between different listing views
 *
 * @var Action\SetListingView $setViewAction
 */
$setViewAction = $env->newAction('SetListingView');

/**
 * action to change the current page of the listing view
 *
 * @var Action\SetListingPage $setPageAction
 */
$setPageAction = $env->newAction('SetListingPage');

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

// set page title
$view->setTitle(\ucfirst(_('current offers')));

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
    'openestate_install_listing("' . $uid . '", "' . html($env->getActionUrl()) . '");',
    null,
    true
), 1001);

// write document header
if (!$view->isBodyOnly()) include('snippets/document-begin.php');
include('snippets/body-begin.php');

?>

    <div id="openestate-body-<?= $uid ?>" class="openestate-body openestate-listing">

        <div class="openestate-header">
            <div class="openestate-header-bar">
                <h3 class="openestate-header-title">
                    <i class="openestate-icon-listing"></i><?= html(\ucfirst(_('current offers'))) ?>
                </h3>
                <div class="openestate-header-actions">
                    <a class="openestate-action openestate-action-filter" href="#" title="<?= html(_('Show search options.')) ?>">
                        <i class="openestate-icon-search"></i>
                    </a>
                    <a class="openestate-action openestate-action-sort" href="#" title="<?= html(_('Show sort options.')) ?>">
                        <i class="openestate-icon-sort"></i>
                    </a>
                    <a class="openestate-action openestate-action-details"
                       href="<?= html($env->getListingUrl($setViewAction->getParameters($env, 'detail'))) ?>"
                       data-openestate-action="<?= html(Utils::getJson($setViewAction->getParameters($env, 'detail'))) ?>"
                       title="<?= html(_('Show objects in detailed view.')) ?>">
                        <i class="openestate-icon-list-detail"></i>
                    </a>
                    <a class="openestate-action openestate-action-thumb"
                       href="<?= html($env->getListingUrl($setViewAction->getParameters($env, 'thumb'))) ?>"
                       data-openestate-action="<?= html(Utils::getJson($setViewAction->getParameters($env, 'thumb'))) ?>"
                       title="<?= html(_('Show objects in gallery view.')) ?>">
                        <i class="openestate-icon-list-thumb"></i>
                    </a>
                    <?php if ($favoritesEnabled || $languageSelection) { ?>
                        <span class="openestate-action-separator"></span>
                    <?php } ?>
                    <?php if ($favoritesEnabled) { ?>
                        <a class="openestate-action openestate-action-fav" rel="nofollow"
                           href="<?= html($env->getFavoriteUrl()) ?>"
                           title="<?= html(_('Show list of favorite objects.')) ?>">
                            <i class="openestate-icon-fav"></i>
                        </a>
                    <?php } ?>
                    <?php if ($languageSelection) { ?>
                        <a class="openestate-action openestate-action-language" href="#"
                           title="<?= html(_('Select your preferred language.')) ?>">
                            <i class="openestate-icon-language"></i>
                        </a>
                    <?php } ?>
                </div>
            </div>

            <form action="<?= html($env->getListingUrl()) ?>" method="get"
                  class="openestate-filter-form pure-form">
                <input type="hidden" name="<?= html($env->actionParameter) ?>"
                       value="<?= html($setFilterAction->getName()) ?>">
                <fieldset>
                    <legend><?= html(\ucfirst(_('search offers'))) ?></legend>
                    <?php
                    $values = $view->getFilterValues();

                    /** @var Filter\AbstractFilter $filter */
                    foreach ($view->filters as $filter) {
                        $value = (isset($values[$filter->getName()])) ? $values[$filter->getName()] : null;
                        $widget = $filter->getWidget($env, $value);
                        $widget->id = $widget->id . '-' . $uid;
                        $widget->name = $setFilterAction->filterParameter . '[' . $filter->getName() . ']';

                        echo '<div>';
                        if ($widget instanceof Html\Checkbox) {
                            $widget->label = null;
                            echo '<label for="' . html($widget->id) . '">'
                                . $widget->generate() . ' '
                                . html($filter->getTitle($languageCode))
                                . '</label>';
                        } else {
                            echo $widget->generate();
                        }
                        echo '</div>' . "\n";
                    }
                    ?>
                    <div class="pure-button-group" role="group">
                        <button type="submit"
                                class="openestate-filter-form-submit pure-button pure-button-primary openestate-button">
                            <i class="openestate-icon-search"></i><?= html(_('search')) ?>
                        </button>
                        <button type="submit"
                                class="openestate-filter-form-clear pure-button openestate-button openestate-button-secondary"
                                name="<?= html($setFilterAction->clearParameter) ?>" value="1">
                            <i class="openestate-icon-cancel"></i><?= html(_('clear')) ?>
                        </button>
                    </div>
                </fieldset>
            </form>

            <form action="<?= html($env->getListingUrl()) ?>" method="get"
                  class="openestate-sort-form pure-form">
                <input type="hidden" name="<?= html($env->actionParameter) ?>"
                       value="<?= html($setOrderAction->getName()) ?>">
                <fieldset>
                    <legend><?= html(\ucfirst(_('sort offers'))) ?></legend>
                    <?php
                    $orderValue = $view->getOrder();
                    $orderDir = $view->getOrderDirection();
                    if ($orderDir == 'asc') {
                        $orderAscClass = 'pure-button-primary';
                        $orderDescClass = 'openestate-button-secondary';
                    } else {
                        $orderAscClass = 'openestate-button-secondary';
                        $orderDescClass = 'pure-button-primary';
                    }

                    /** @var Order\AbstractOrder $order */
                    foreach ($view->orders as $order) {
                        $selected = ($orderValue == $order->getName()) ? 'checked' : '';
                        echo '<div><label>' .
                            '<input type="radio" name="' . html($setOrderAction->orderParameter) . '" value="' . html($order->getName()) . '" ' . $selected . '> '
                            . html($order->getTitle($languageCode)) .
                            '</label></div>' . "\n";
                    }
                    ?>
                    <div class="pure-button-group" role="group">
                        <button type="submit"
                                class="openestate-sort-form-asc pure-button openestate-button <?= $orderAscClass ?>"
                                name="<?= html($setOrderAction->directionParameter) ?>" value="asc">
                            <i class="openestate-icon-sort-asc"></i><?= html(_('ascending')) ?>
                        </button>
                        <button type="submit"
                                class="openestate-sort-form-desc pure-button openestate-button <?= $orderDescClass ?>"
                                name="<?= html($setOrderAction->directionParameter) ?>" value="desc">
                            <i class="openestate-icon-sort-desc"></i><?= html(_('descending')) ?>
                        </button>
                    </div>
                </fieldset>
            </form>

            <?php
            if ($languageSelection) {
                echo '<div class="openestate-language-form">';
                foreach ($languageCodes as $lang) {
                    $languageName = $env->getLanguageName($lang);
                    $languageClass = ($languageCode == $lang) ? 'active' : '';
                    $languageParams = $setLanguageAction->getParameters($env, $lang);
                    echo '<a href="' . html($env->getListingUrl($languageParams)) . '" '
                        . 'data-openestate-action="' . html(Utils::getJson($languageParams)) . '" '
                        . 'class="' . $languageClass . '">'
                        . html($languageName) . '</a>';
                }
                echo '</div>';
            }
            ?>
        </div>

        <?php

        if (Utils::isEmptyArray($objectIds)) {
            $filterValues = $view->getFilterValues();

            echo '<div class="openestate-listing-empty">';
            if (Utils::isEmptyArray($filterValues)) {
                echo '<p>' . html(_('There are currently no objects published.')) . '</p>';
            } else {
                $clearFilterParams = $setFilterAction->getParameters($env, null, true);
                echo '<p>' . html(_('No objects were found according to your filter settings.')) . '</p>'
                    . '<hr>'
                    . '<p><a class="pure-button pure-button-primary openestate-button" '
                    . 'href="' . html($env->getListingUrl($clearFilterParams)) . '" '
                    . 'data-openestate-action="' . html(Utils::getJson($clearFilterParams)) . '">'
                    . html(_('Show all offers.'))
                    . '</a></p>';
            }
            echo '</div>';

        } else {
            echo '<div class="openestate-listing-items openestate-listing-items-' . html($currentView) . '">';
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

                $objectFav = $favoritesEnabled && \array_search($objectId, $favorites) !== false;
                $objectFavAddParams = ($favoritesEnabled) ?
                    $addFavoriteAction->getParameters($env, $objectId) : null;
                $objectFavRemoveParams = ($favoritesEnabled) ?
                    $removeFavoriteAction->getParameters($env, $objectId) : null;

                if ($currentView == 'thumb') { ?>

                    <div class="openestate-listing-item openestate-listing-thumb"
                         data-openestate-object="<?= html($objectId) ?>">
                        <?php
                        // print object image
                        if (!\is_null($objectImageLink)) {
                            echo '<div class="openestate-listing-thumb-image">'
                                . '<a href="' . html($objectUrl) . '" title="' . html(_('Show details about this object.')) . '">'
                                . '<img src="' . html($objectImageLink) . '" alt="">'
                                . '</a>'
                                . '</div>';
                        }
                        ?>

                        <div class="openestate-listing-thumb-popup">
                            <h3 class="openestate-listing-thumb-title">
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

                            echo '<ul class="openestate-listing-col">';
                            foreach ($entries as $entry) {
                                echo '<li>' . $entry . '</li>';
                            }
                            echo '</ul>';

                            ?>

                            <div class="openestate-listing-thumb-actions">
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
                                <?php if ($favoritesEnabled) { ?>
                                    <a class="openestate-action openestate-action-fav-add" rel="nofollow"
                                       href="<?= html($env->getListingUrl($objectFavAddParams)) ?>"
                                       data-openestate-fav="<?= html(Utils::getJson($objectFavAddParams)) ?>"
                                       title="<?= html(_('Add this object to your list of favorites.')) ?>"
                                       style="<?= ($objectFav) ? 'display:none;' : '' ?>">
                                        <i class="openestate-icon-fav-add"></i><?= html(\ucfirst(_('add to favorites'))) ?>
                                    </a>
                                    <a class="openestate-action openestate-action-fav-remove" rel="nofollow"
                                       href="<?= html($env->getListingUrl($objectFavRemoveParams)) ?>"
                                       data-openestate-fav="<?= html(Utils::getJson($objectFavRemoveParams)) ?>"
                                       title="<?= html(_('Remove this object from your list of favorites.')) ?>"
                                       style="<?= (!$objectFav) ? 'display:none;' : '' ?>">
                                        <i class="openestate-icon-fav-remove"></i><?= html(\ucfirst(_('remove from favorites'))) ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                <?php } else { ?>

                    <div class="openestate-listing-item openestate-listing-detail"
                         data-openestate-object="<?= html($objectId) ?>">
                        <h3 class="openestate-listing-detail-title">
                            <a href="<?= html($objectUrl) ?>"
                               title="<?= html(_('Show details about this object.')) ?>">
                                <?= html($objectTitle) ?>
                            </a>
                        </h3>
                        <div class="openestate-listing-detail-content">
                            <?php

                            // print object image
                            if (!\is_null($objectImageLink)) {
                                echo '<div class="openestate-listing-detail-image">'
                                    . '<a href="' . html($objectUrl) . '" title="' . html(_('Show details about this object.')) . '">'
                                    . '<img src="' . html($objectImageLink) . '" alt="">'
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
                                echo '<ul class="openestate-listing-col openestate-listing-col-' . html($colIndex) . '">';
                                foreach ($entries as $entry) {
                                    echo '<li>' . $entry . '</li>';
                                }
                                echo '</ul>';
                            }

                            ?>
                        </div>
                        <div class="openestate-listing-detail-actions">
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
                            <?php if ($favoritesEnabled) { ?>
                                <a class="openestate-action openestate-action-fav-add" rel="nofollow"
                                   href="<?= html($env->getListingUrl($objectFavAddParams)) ?>"
                                   data-openestate-fav="<?= html(Utils::getJson($objectFavAddParams)) ?>"
                                   title="<?= html(_('Add this object to your list of favorites.')) ?>"
                                   style="<?= ($objectFav) ? 'display:none;' : '' ?>">
                                    <i class="openestate-icon-fav-add"></i><?= html(\ucfirst(_('add to favorites'))) ?>
                                </a>
                                <a class="openestate-action openestate-action-fav-remove" rel="nofollow"
                                   href="<?= html($env->getListingUrl($objectFavRemoveParams)) ?>"
                                   data-openestate-fav="<?= html(Utils::getJson($objectFavRemoveParams)) ?>"
                                   title="<?= html(_('Remove this object from your list of favorites.')) ?>"
                                   style="<?= (!$objectFav) ? 'display:none;' : '' ?>">
                                    <i class="openestate-icon-fav-remove"></i><?= html(\ucfirst(_('remove from favorites'))) ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>

                <?php }
            }
            echo '</div>';

            if ($totalPages > 1) {
                echo '<div class="openestate-listing-pagination openestate-listing-pagination-' . html($currentView) . '">';

                if ($currentPage > 1) {
                    $params = $setPageAction->getParameters($env, $currentPage - 1);
                    echo '<a class="openestate-listing-pagination-prev openestate-button pure-button" '
                        . 'href="' . html($env->getListingUrl($params)) . '" '
                        . 'data-openestate-action="' . html(Utils::getJson($params)) . '" '
                        . 'title="' . html(_('Show previous page.')) . '">'
                        . '<i class="openestate-icon-left"></i></a>';
                } else {
                    echo '<a class="openestate-listing-pagination-prev openestate-button pure-button pure-button-disabled" '
                        . 'href="#" title="' . html(_('Show previous page.')) . '" '
                        . 'disabled>'
                        . '<i class="openestate-icon-left"></i></a>';
                }

                if ($currentPage < $totalPages) {
                    $params = $setPageAction->getParameters($env, $currentPage + 1);
                    echo '<a class="openestate-listing-pagination-next openestate-button pure-button" '
                        . 'href="' . html($env->getListingUrl($params)) . '" '
                        . 'data-openestate-action="' . html(Utils::getJson($params)) . '" '
                        . 'title="' . html(_('Show next page.')) . '">'
                        . '<i class="openestate-icon-right"></i></a>';
                } else {
                    echo '<a class="openestate-listing-pagination-next openestate-button pure-button pure-button-disabled" '
                        . 'href="#" '
                        . 'title="' . html(_('Show next page.')) . '" '
                        . 'disabled>'
                        . '<i class="openestate-icon-right"></i></a>';
                }

                echo '</div>';
            }
        }
        ?>

    </div>

    <div id="openestate-loading-<?= $uid ?>" class="openestate-loading">
        <i class="openestate-spinner openestate-icon-spinner"></i>
        <?= html(_('Processing your request. Please wait for a moment.')) ?>
    </div>

    <?php

// write document footer
include('snippets/body-end.php');
if (!$view->isBodyOnly()) include('snippets/document-end.php');
