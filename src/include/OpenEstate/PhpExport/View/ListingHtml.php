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

namespace OpenEstate\PhpExport\View;

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Utils;
use OpenEstate\PhpExport\Filter\AbstractFilter;
use OpenEstate\PhpExport\Order\AbstractOrder;

/**
 * A view for a listing of real estate objects.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class ListingHtml extends AbstractHtmlView
{
    /**
     * Maximal number of objects shown on a page.
     *
     * @var int
     */
    public $objectsPerPage = 10;

    /**
     * Available filters in the listing.
     *
     * @var array
     * @see AbstractFilter
     */
    public $filters = array();

    /**
     * Default filter values of the listing.
     *
     * @var array
     */
    public $defaultFilterValues = array();

    /**
     * Available orderings in the listing.
     *
     * @var array
     * @see AbstractOrder
     */
    public $orders = array();

    /**
     * Default ordering of the listing.
     *
     * @var string
     */
    public $defaultOrder = 'ObjectId';

    /**
     * Default ordering direction of the listing.
     *
     * @var string
     */
    public $defaultOrderDirection = 'desc';

    /**
     * Default view of the listing.
     *
     * @var string
     */
    public $defaultView = 'detail';

    /**
     * Array of columns with object attributes.
     *
     * @var array
     */
    public $objectColumns = array(

        // first column
        array('type', 'action', 'address', 'country'),

        // second column
        array('price', 'area', 'measures.count_rooms', 'measures.count_residential_units', 'administration.auction_date'),
    );

    /**
     * Maximal number of entries per attribute column.
     *
     * @var int
     */
    public $objectColumnsLimit = 4;


    /**
     * ListingHtml constructor.
     *
     * @param Environment $env
     * export environment
     */
    function __construct(Environment $env)
    {
        parent::__construct($env);
    }

    protected function generate()
    {
        return Utils::encode($this->loadThemeFile('listing.php'), $this->getCharset());
    }

    /**
     * Get an array of object ID's, that were marked as favorites by the user.
     *
     * @return array
     * array of favored object ID's
     */
    public function getFavorites()
    {
        return $this->env->getSession()->getFavorites();
    }

    /**
     * Get currently selected filters.
     *
     * @return array
     * array of filter values, defaults to the specified defaultFilterValues
     */
    public function getFilterValues()
    {
        $filters = $this->env->getSession()->getListingFilters();
        return (\is_array($filters)) ?
            $filters : $this->defaultFilterValues;
    }

    /**
     * Get column definitions for a specific object.
     *
     * @param array $object
     * object data
     *
     * @param string $lang
     * language code
     *
     * @return array
     * column definitions
     */
    public function getObjectColumns(array &$object, $lang)
    {
        return $this->objectColumns;
    }

    /**
     * Get a column value for a specific object.
     *
     * @param array $object
     * object data
     *
     * @param string $field
     * name of the field to show
     *
     * @param array $i18n
     * translations
     *
     * @param string $lang
     * language code
     *
     * @return null|string
     * HTML encoded value for the requested field
     */
    public function getObjectColumnValue(array &$object, $field, array &$i18n, $lang)
    {
        return Utils::writeObjectField($object, $field, $i18n, $lang);
    }

    /**
     * Get list of filtered object ID's in the specified order.
     *
     * @return array
     * array of object ID's
     */
    public function getObjectIds()
    {
        // get specified order instance
        $orderName = $this->getOrder();
        $order = null;
        /** @var AbstractOrder $o */
        foreach ($this->env->getConfig()->getOrderObjects() as $o) {
            if (\strtolower($o->getName()) == \strtolower($orderName)) {
                $order = $o;
                break;
            }
        }

        // get object ID's in specified order
        $objectIds = null;
        $orderDirection = $this->getOrderDirection();
        if ($order !== null) {
            if ($order->readOrRebuild($this->env) === true) {
                $objectIds = $order->getItems($this->env->getLanguage());
                if (Utils::isNotEmptyArray($objectIds) && \strtolower($orderDirection) == 'desc')
                    $objectIds = \array_reverse($objectIds);
            }
        }

        // get list of available object ID's, if something went wrong
        if (!\is_array($objectIds)) {
            $objectIds = $this->env->getObjectIds();
            if (\strtolower($orderDirection) == 'desc')
                \rsort($objectIds);
            else
                \sort($objectIds);
        }

        // filter object ID's
        $availableFilters = $this->env->getConfig()->getFilterObjects();
        $filterValues = $this->getFilterValues();
        if (\is_array($filterValues)) {
            foreach ($filterValues as $filterName => $filterValue) {
                if (Utils::isBlankString($filterValue))
                    continue;

                $filter = null;

                /** @var AbstractFilter $f */
                foreach ($availableFilters as $f) {
                    if (\strtolower($f->getName()) == \strtolower($filterName)) {
                        $filter = $f;
                        break;
                    }
                }

                if ($filter === null || !$filter->readOrRebuild($this->env))
                    continue;

                $filteredIds = null;
                foreach (\explode(',', $filterValue) as $val) {
                    if (Utils::isBlankString($val))
                        continue;
                    $val = trim($val);

                    $items = $filter->getItems($val);
                    if (!\is_array($items))
                        continue;
                    if ($filteredIds == null)
                        $filteredIds = $items;
                    else {
                        foreach ($items as $item)
                            $filteredIds[] = $item;
                    }
                }

                if (\is_array($filteredIds))
                    $objectIds = \array_values(\array_intersect($objectIds, $filteredIds));
            }
        }

        return $objectIds;
    }

    /**
     * Reduce the list of object ID's to the current page.
     *
     * @param array $objectIds
     * array of object ID's
     *
     * @return array
     * array of object ID's to show on the current page
     */
    public function getObjectIdsOnThisPage(array $objectIds)
    {
        // make sure, that the page number is not bigger then the maximum number of pages
        $pageNumber = $this->getPage();
        $pageCount = $this->getPageCount(\count($objectIds));
        if ($pageNumber > $pageCount) {
            $pageNumber = $pageCount;
            $this->env->getSession()->setListingPage($pageNumber);
        }

        // reduce the list of object ID's to the current page
        $objectIdsOnThisPage = array();
        $start = ($pageNumber - 1) * $this->objectsPerPage;
        $end = $start + $this->objectsPerPage;
        for ($i = $start; $i < $end; $i++) {
            if (!isset($objectIds[$i]))
                break;
            $objectIdsOnThisPage[] = $objectIds[$i];
        }
        return $objectIdsOnThisPage;
    }

    /**
     * Get currently selected ordering.
     *
     * @return string
     * internal name of the ordering, defaults to the specified defaultOrder
     */
    public function getOrder()
    {
        $order = $this->env->getSession()->getListingOrder();
        return (Utils::isNotBlankString($order)) ?
            $order : $this->defaultOrder;
    }

    /**
     * Get direction of the currently selected ordering.
     *
     * @return string
     * ordering direction ("asc" for ascending or "desc" for descending),
     * defaults to the specified defaultOrderDirection
     */
    public function getOrderDirection()
    {
        $dir = $this->env->getSession()->getListingOrderDirection();
        return ($dir == 'asc' || $dir == 'desc') ?
            $dir : $this->defaultOrderDirection;
    }

    /**
     * Get currently selected page number.
     *
     * @return int
     * page number, defaults to 1
     */
    public function getPage()
    {
        $page = $this->env->getSession()->getListingPage();
        return (\is_int($page) && $page > 0) ?
            (int)$page : 1;
    }

    /**
     * Get the number of pages to show the objects on this view.
     *
     * @param int $numberOfObjects
     * total number of objects
     *
     * @return int
     * number of pages
     */
    public function getPageCount($numberOfObjects)
    {
        return Utils::getNumberOfPages($numberOfObjects, $this->objectsPerPage);
    }

    /**
     * Get url for this view.
     *
     * @param Environment $env
     * export environment
     *
     * @return string
     * url
     */
    public function getUrl(Environment $env)
    {
        return $env->getListingUrl($this->getParameters());
    }

    /**
     * Get currently selected listing view.
     *
     * @return string
     * name of the listing view, defaults to the specified defaultView
     */
    public function getView()
    {
        $view = $this->env->getSession()->getListingView();
        return (Utils::isNotBlankString($view)) ?
            $view : $this->defaultView;
    }
}
