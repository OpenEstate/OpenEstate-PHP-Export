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
 * Implementation of the listing view for the default theme.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @var \OpenEstate\PhpExport\View\ListingHtml $view
 * the currently used view
 */

// Don't execute the file, if it is not properly loaded.
if (!isset($view) || !\is_object($view)) return;

use OpenEstate\PhpExport\Utils;
use OpenEstate\PhpExport\Html\Checkbox;
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

// get listing options
$objectIds = $view->getObjectIds();
$favoritesEnabled = $env->getConfig()->favorites;
$favorites = ($favoritesEnabled) ? $view->getFavorites() : array();
$currentView = $view->getView();
$currentPage = $view->getPage();
$totalPages = $view->getPageCount(\count($objectIds));

/**
 * @var OpenEstate\PhpExport\View\ExposeHtml $exposeView
 * expose view
 */
$exposeView = $env->newExposeHtml();

/**
 * @var OpenEstate\PhpExport\Action\SetLanguage $setLanguageAction
 * action to change the language
 */
$setLanguageAction = $env->newAction('SetLanguage');

/**
 * @var OpenEstate\PhpExport\Action\SetListingFilter $setFilterAction
 * action to change filter values
 */
$setFilterAction = $env->newAction('SetListingFilter');

/**
 * @var OpenEstate\PhpExport\Action\SetListingOrder $setOrderAction
 * action to change ordering
 */
$setOrderAction = $env->newAction('SetListingOrder');

/**
 * @var OpenEstate\PhpExport\Action\SetListingView $setViewAction
 * action to switch between different listing views
 */
$setViewAction = $env->newAction('SetListingView');

/**
 * @var OpenEstate\PhpExport\Action\SetListingPage $setPageAction
 * action to change the current page of the listing view
 */
$setPageAction = $env->newAction('SetListingPage');

/**
 * @var OpenEstate\PhpExport\Action\AddFavorite $addFavoriteAction
 * action to add an object to the list of favorites
 */
$addFavoriteAction = ($favoritesEnabled) ?
    $env->newAction('AddFavorite') : null;

/**
 * @var OpenEstate\PhpExport\Action\RemoveFavorite $removeFavoriteAction
 * action to remove an object from the list of favorites
 */
$removeFavoriteAction = ($favoritesEnabled) ?
    $env->newAction('RemoveFavorite') : null;

// set page title
$view->setTitle(_('Current offers'));

// add meta elements
$view->addHeader(Meta::newRobots('noindex,follow'), 100);

// register JQuery
$view->addHeaders($env->getAssets()->jquery(), 200);

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
//$view->addHeader(Stylesheet::newLink(
//        'openestate-pure-grids-css',
//        $view->getThemeUrl('css/pure/grids-min.css', array('v' => '1.0.0'))
//), 313);
//$view->addHeader(Stylesheet::newLink(
//        'openestate-pure-grids-responsive-css',
//        $view->getThemeUrl('css/pure/grids-responsive-min.css', array('v' => '1.0.0'))
//), 314);
//$view->addHeader(Stylesheet::newLink(
//        'openestate-pure-menus-css',
//        $view->getThemeUrl('css/pure/menus-min.css', array('v' => '1.0.0'))
//), 315);
//$view->addHeader(Stylesheet::newLink(
//        'openestate-pure-tables-css',
//        $view->getThemeUrl('css/pure/tables-min.css', array('v' => '1.0.0'))
//), 316);

// register Popper.js
$view->addHeader(Javascript::newLink(
    'openestate-popper-js',
    $view->getThemeUrl('js/popper/popper.min.js', array('v' => '1.14.4')),
    null,
    null,
    true
), 900);

// register custom theme includes
$view->addHeader(Stylesheet::newLink(
    'openestate-theme-css',
    $view->getThemeUrl('css/theme.css', array('v' => VERSION))
), 1000);
$view->addHeader(Javascript::newLink(
    'openestate-theme-js',
    $view->getThemeUrl('js/theme.js', array('v' => VERSION)),
    'openestate_install_listing("' . $uid . '", "' . \htmlspecialchars($env->getConfig()->getActionUrl()) . '");',
    null,
    true
), 1001);

// write document header
if (!$view->isBodyOnly()) include('snippets/document-begin.php');
include('snippets/body-begin.php');

//echo '<pre>' . print_r($objectIds, true) . '</pre>';
?>

    <div id="openestate-body-<?= $uid ?>" class="openestate-body openestate-listing">

        <div class="openestate-header">
            <div class="openestate-header-bar">
                <h3 class="openestate-header-title"><?= _('Current offers') ?></h3>
                <div class="openestate-header-actions">
                    <a class="openestate-action-filter" href="#" title="<?= _('Show search options.') ?>">
                        <i class="openestate-icon-search"></i>
                    </a>
                    <a class="openestate-action-sort" href="#" title="<?= _('Show sort options.') ?>">
                        <i class="openestate-icon-sort"></i>
                    </a>
                    <a class="openestate-action-details"
                       href="<?= \htmlspecialchars($env->getConfig()->getListingUrl($setViewAction->getParameters($env, 'detail'))) ?>"
                       data-openestate-action="<?= \htmlspecialchars(Utils::getJson($setViewAction->getParameters($env, 'detail'))) ?>"
                       title="<?= _('Show objects in detailed view.') ?>">
                        <i class="openestate-icon-list-detail"></i>
                    </a>
                    <a class="openestate-action-thumb"
                       href="<?= \htmlspecialchars($env->getConfig()->getListingUrl($setViewAction->getParameters($env, 'thumb'))) ?>"
                       data-openestate-action="<?= \htmlspecialchars(Utils::getJson($setViewAction->getParameters($env, 'thumb'))) ?>"
                       title="<?= _('Show objects in gallery view.') ?>">
                        <i class="openestate-icon-list-thumb"></i>
                    </a>
                    <?php if ($favoritesEnabled || $languageSelection) { ?>
                        <span class="openestate-action-separator"></span>
                    <?php } ?>
                    <?php if ($favoritesEnabled) { ?>
                        <a class="openestate-action-fav" rel="nofollow"
                           href="<?= \htmlspecialchars($env->getConfig()->getFavoriteUrl()) ?>"
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

            <form action="<?= $env->getConfig()->getListingUrl() ?>" method="get"
                  class="openestate-filter-form pure-form">
                <input type="hidden" name="<?= $env->actionParameter ?>"
                       value="<?= $setFilterAction->getName() ?>">
                <fieldset>
                    <legend><?= _('Search offers') ?></legend>
                    <?php
                    $values = $view->getFilterValues();

                    /** @var OpenEstate\PhpExport\Filter\AbstractFilter $filter */
                    foreach ($view->filters as $filter) {
                        $value = (isset($values[$filter->getName()])) ? $values[$filter->getName()] : null;
                        $widget = $filter->getWidget($env, $value);
                        $widget->id = $widget->id . '-' . $uid;
                        $widget->name = $setFilterAction->filterParameter . '[' . $filter->getName() . ']';

                        echo '<div>';
                        if ($widget instanceof Checkbox) {
                            $widget->label = null;
                            echo '<label for="' . $widget->id . '">' . $widget->generate() . ' ' . $filter->getTitle($env->getLanguage()) . '</label>';
                        } else {
                            echo $widget->generate();
                        }
                        echo '</div>' . "\n";
                    }
                    ?>
                    <div class="pure-button-group" role="group">
                        <button type="submit"
                                class="openestate-filter-form-submit pure-button pure-button-primary openestate-button">
                            <i class="openestate-icon-search"></i><?= _('search') ?>
                        </button>
                        <button type="submit"
                                class="openestate-filter-form-clear pure-button openestate-button openestate-button-secondary"
                                name="<?= $setFilterAction->clearParameter ?>" value="1">
                            <i class="openestate-icon-cancel"></i><?= _('clear') ?>
                        </button>
                    </div>
                </fieldset>
            </form>

            <form action="<?= $env->getConfig()->getListingUrl() ?>" method="get"
                  class="openestate-sort-form pure-form">
                <input type="hidden" name="<?= $env->actionParameter ?>"
                       value="<?= $setOrderAction->getName() ?>">
                <fieldset>
                    <legend><?= _('Sort offers') ?></legend>
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

                    /** @var OpenEstate\PhpExport\Order\AbstractOrder $order */
                    foreach ($view->orders as $order) {
                        $selected = ($orderValue == $order->getName()) ? 'checked' : '';
                        echo '<div><label>' .
                            '<input type="radio" name="' . $setOrderAction->orderParameter . '" value="' . $order->getName() . '" ' . $selected . '> '
                            //. _('by') . ' '
                            . $order->getTitle($env->getLanguage()) .
                            '</label></div>' . "\n";
                    }
                    ?>
                    <div class="pure-button-group" role="group">
                        <button type="submit"
                                class="openestate-sort-form-asc pure-button openestate-button <?= $orderAscClass ?>"
                                name="<?= $setOrderAction->directionParameter ?>" value="asc">
                            <i class="openestate-icon-sort-asc"></i><?= _('ascending') ?>
                        </button>
                        <button type="submit"
                                class="openestate-sort-form-desc pure-button openestate-button <?= $orderDescClass ?>"
                                name="<?= $setOrderAction->directionParameter ?>" value="desc">
                            <i class="openestate-icon-sort-desc"></i><?= _('descending') ?>
                        </button>
                    </div>
                </fieldset>
            </form>

            <?php
            if ($languageSelection) {
                echo '<div class="openestate-language-form">';
                foreach ($env->getLanguageCodes() as $lang) {
                    $languageName = $env->getLanguageName($lang);
                    $languageClass = ($languageCode == $lang) ? 'active' : '';
                    $languageParams = $setLanguageAction->getParameters($env, $lang);
                    echo '<a href="' . \htmlspecialchars($env->getConfig()->getListingUrl($languageParams)) . '" '
                        . 'data-openestate-action="' . \htmlspecialchars(Utils::getJson($languageParams)) . '" '
                        . 'class="' . $languageClass . '">'
                        . \htmlspecialchars($languageName) . '</a>';
                }
                echo '</div>';
            }
            ?>
        </div>

        <?php

        if (Utils::isEmptyArray($objectIds)) {
            $filterValues = $view->getFilterValues();

            echo '<div class="openestate-listing-empty">';
            if (Utils::isEmptyArray($filterValues))
                echo \htmlspecialchars(_('There are currently no objects published.'));
            else
                echo \htmlspecialchars(_('No objects were found according to your filter settings.'));
            echo '</div>';

        } else {
            echo '<div class="openestate-listing-items openestate-listing-items-' . \htmlspecialchars($currentView) . '">';
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

                $objectUrl = $env->getConfig()->getExposeUrl($exposeView->getParameters($objectId));
                $objectTitle = (isset($objectData['title'][$languageCode])) ?
                    $objectData['title'][$languageCode] :
                    _('Real estate %1$s', $objectKey);
                $objectImage = (isset($objectData['images']) && \is_array($objectData['images']) && \count($objectData['images']) > 0) ?
                    $objectData['images'][0] : null;
                $objectImageLink = (\is_array($objectImage)) ?
                    ($env->getConfig()->dynamicImageScaling) ?
                        $env->getUrl('img.php', array('id' => $objectId, 'img' => $objectImage['name'], 'x' => 500, 'y' => 325)) :
                        $env->getUrl('data/' . $objectId . '/' . $objectImage['thumb']) :
                    null;

                $objectFav = $favoritesEnabled && \array_search($objectId, $favorites) !== false;
                $objectFavAddParams = ($favoritesEnabled) ?
                    $addFavoriteAction->getParameters($env, $objectId) : null;
                $objectFavRemoveParams = ($favoritesEnabled) ?
                    $removeFavoriteAction->getParameters($env, $objectId) : null;

                if ($currentView == 'thumb') { ?>

                    <div class="openestate-listing-item openestate-listing-thumb"
                         data-openestate-object="<?= \htmlspecialchars($objectId) ?>">
                        <?php
                        // print object image
                        if (!\is_null($objectImageLink)) {
                            echo '<div class="openestate-listing-thumb-image">'
                                . '<a href="' . \htmlspecialchars($objectUrl) . '" title="' . \htmlspecialchars(_('Show details about this object.')) . '">'
                                . '<img src="' . \htmlspecialchars($objectImageLink) . '" alt="">'
                                . '</a>'
                                . '</div>';
                        }
                        ?>

                        <div class="openestate-listing-thumb-popup">
                            <h3 class="openestate-listing-thumb-title">
                                <a href="<?= \htmlspecialchars($objectUrl) ?>"
                                   title="<?= \htmlspecialchars(_('Show details about this object.')) ?>">
                                    <?= \htmlspecialchars($objectTitle) ?>
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
                                <a class="openestate-action-expose" href="<?= \htmlspecialchars($objectUrl) ?>"
                                   title="<?= \htmlspecialchars(_('Show details about this object.')) ?>">
                                    <?= \htmlspecialchars(_('Details')) ?>
                                </a>
                                <a class="openestate-action-download" href="#"
                                   title="<?= \htmlspecialchars(_('Download information about this object as PDF file.')) ?>">
                                    <?= \htmlspecialchars(_('Download')) ?>
                                </a>
                                <?php if ($favoritesEnabled) { ?>
                                    <a class="openestate-action-fav-add" rel="nofollow"
                                       href="<?= \htmlspecialchars($env->getConfig()->getListingUrl($objectFavAddParams)) ?>"
                                       data-openestate-fav="<?= \htmlspecialchars(Utils::getJson($objectFavAddParams)) ?>"
                                       title="<?= \htmlspecialchars(_('Add this object to your list of favorites.')) ?>"
                                       style="<?= ($objectFav) ? 'display:none;' : '' ?>">
                                        <?= \htmlspecialchars(_('Add to favorites')) ?>
                                    </a>
                                    <a class="openestate-action-fav-remove" rel="nofollow"
                                       href="<?= \htmlspecialchars($env->getConfig()->getListingUrl($objectFavRemoveParams)) ?>"
                                       data-openestate-fav="<?= \htmlspecialchars(Utils::getJson($objectFavRemoveParams)) ?>"
                                       title="<?= \htmlspecialchars(_('Remove this object from your list of favorites.')) ?>"
                                       style="<?= (!$objectFav) ? 'display:none;' : '' ?>">
                                        <?= \htmlspecialchars(_('Remove from favorites')) ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                <?php } else { ?>

                    <div class="openestate-listing-item openestate-listing-detail"
                         data-openestate-object="<?= \htmlspecialchars($objectId) ?>">
                        <h3 class="openestate-listing-detail-title">
                            <a href="<?= \htmlspecialchars($objectUrl) ?>"
                               title="<?= \htmlspecialchars(_('Show details about this object.')) ?>">
                                <?= \htmlspecialchars($objectTitle) ?>
                            </a>
                        </h3>
                        <div class="openestate-listing-detail-content">
                            <?php

                            // print object image
                            if (!\is_null($objectImageLink)) {
                                echo '<div class="openestate-listing-detail-image">'
                                    . '<a href="' . \htmlspecialchars($objectUrl) . '" title="' . \htmlspecialchars(_('Show details about this object.')) . '">'
                                    . '<img src="' . \htmlspecialchars($objectImageLink) . '" alt="">'
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
                                echo '<ul class="openestate-listing-col openestate-listing-col-' . $colIndex . '">';
                                foreach ($entries as $entry) {
                                    echo '<li>' . $entry . '</li>';
                                }
                                echo '</ul>';
                            }

                            ?>
                        </div>
                        <div class="openestate-listing-detail-actions">
                            <a class="openestate-action-expose" href="<?= \htmlspecialchars($objectUrl) ?>"
                               title="<?= \htmlspecialchars(_('Show details about this object.')) ?>">
                                <?= \htmlspecialchars(_('Details')) ?>
                            </a>
                            <a class="openestate-action-download" href="#"
                               title="<?= \htmlspecialchars(_('Download information about this object as PDF file.')) ?>">
                                <?= \htmlspecialchars(_('Download')) ?>
                            </a>
                            <?php if ($favoritesEnabled) { ?>
                                <a class="openestate-action-fav-add" rel="nofollow"
                                   href="<?= \htmlspecialchars($env->getConfig()->getListingUrl($objectFavAddParams)) ?>"
                                   data-openestate-fav="<?= \htmlspecialchars(Utils::getJson($objectFavAddParams)) ?>"
                                   title="<?= \htmlspecialchars(_('Add this object to your list of favorites.')) ?>"
                                   style="<?= ($objectFav) ? 'display:none;' : '' ?>">
                                    <?= \htmlspecialchars(_('Add to favorites')) ?>
                                </a>
                                <a class="openestate-action-fav-remove" rel="nofollow"
                                   href="<?= \htmlspecialchars($env->getConfig()->getListingUrl($objectFavRemoveParams)) ?>"
                                   data-openestate-fav="<?= \htmlspecialchars(Utils::getJson($objectFavRemoveParams)) ?>"
                                   title="<?= \htmlspecialchars(_('Remove this object from your list of favorites.')) ?>"
                                   style="<?= (!$objectFav) ? 'display:none;' : '' ?>">
                                    <?= \htmlspecialchars(_('Remove from favorites')) ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>

                <?php }
            }
            echo '</div>';

            if ($totalPages > 1) {
                echo '<div class="openestate-listing-pagination openestate-listing-pagination-' . \htmlspecialchars($currentView) . '">';

                if ($currentPage > 1) {
                    $params = $setPageAction->getParameters($env, $currentPage - 1);
                    echo '<a class="openestate-listing-pagination-prev openestate-button pure-button" '
                        . 'href="' . \htmlspecialchars($env->getConfig()->getFavoriteUrl($params)) . '" '
                        . 'data-openestate-action="' . \htmlspecialchars(Utils::getJson($params)) . '" '
                        . 'title="' . \htmlspecialchars(_('Show previous page.')) . '">'
                        . '<i class="openestate-icon-left"></i></a>';
                } else {
                    echo '<a class="openestate-listing-pagination-prev openestate-button pure-button pure-button-disabled" '
                        . 'href="#" title="' . \htmlspecialchars(_('Show previous page.')) . '" '
                        . 'disabled>'
                        . '<i class="openestate-icon-left"></i></a>';
                }

                if ($currentPage < $totalPages) {
                    $params = $setPageAction->getParameters($env, $currentPage + 1);
                    echo '<a class="openestate-listing-pagination-next openestate-button pure-button" '
                        . 'href="' . \htmlspecialchars($env->getConfig()->getFavoriteUrl($params)) . '" '
                        . 'data-openestate-action="' . \htmlspecialchars(Utils::getJson($params)) . '" '
                        . 'title="' . \htmlspecialchars(_('Show next page.')) . '">'
                        . '<i class="openestate-icon-right"></i></a>';
                } else {
                    echo '<a class="openestate-listing-pagination-next openestate-button pure-button pure-button-disabled" '
                        . 'href="#" '
                        . 'title="' . \htmlspecialchars(_('Show next page.')) . '" '
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
        <?= \htmlspecialchars(_('Processing your request. Please wait for a moment.')) ?>
    </div>

<?php

// write document footer
include('snippets/body-end.php');
if (!$view->isBodyOnly()) include('snippets/document-end.php');
