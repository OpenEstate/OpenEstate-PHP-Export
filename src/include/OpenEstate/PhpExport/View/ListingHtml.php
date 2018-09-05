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

namespace OpenEstate\PhpExport\View;

use OpenEstate\PhpExport\Utils;

/**
 * A view for a listing of real estate objects.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
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
     * @see \OpenEstate\PhpExport\Filter\AbstractFilter
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
     * @see \OpenEstate\PhpExport\Order\AbstractOrder
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
     * Maximum number of entries per attribute column.
     *
     * @var int
     */
    public $objectColumnsLimit = 4;


    /**
     * ListingHtml constructor.
     *
     * @param \OpenEstate\PhpExport\Environment $env
     * export environment
     */
    function __construct(\OpenEstate\PhpExport\Environment $env)
    {
        parent::__construct($env);
    }

    protected function generate()
    {
        try {
            return $this->loadThemeFile('listing.php');
        } catch (\Exception $e) {
            Utils::logError($e);
            return null;
        }
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
    public function getObjectColumns(&$object, $lang)
    {
        return $this->objectColumns;
    }

    /**
     * Get a column value for a specific object.
     *
     * @param array $object
     * object data
     *
     * @param $field
     * name of the field to show
     *
     * @param $i18n
     * translations
     *
     * @param $lang
     * language code
     *
     * @return null|string
     * HTML encoded value for the requested field
     */
    public function getObjectColumnValue(&$object, $field, &$i18n, $lang)
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
        /** @var \OpenEstate\PhpExport\Order\AbstractOrder $o */
        foreach ($this->orders as $o) {
            if ($o->getName() === $orderName) {
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
                if (Utils::isNotEmptyArray($objectIds) && $orderDirection == 'desc')
                    $objectIds = \array_reverse($objectIds);
            }
        }

        // get list of available object ID's, if something went wrong
        if (!\is_array($objectIds)) {
            $objectIds = $this->env->getObjectIds();
            if ($orderDirection === 'desc')
                \rsort($objectIds);
            else
                \sort($objectIds);
        }

        // reduce list of object ID's to the favorites
        //if (is_array($favIds)) {
        //    $objectIds = array_values(array_intersect($objectIds, $favIds));
        //}

        // filter object ID's
        $filterValues = $this->getFilterValues();
        if (\is_array($filterValues)) {
            foreach ($filterValues as $filterName => $filterValue) {
                if (Utils::isBlankString($filterValue))
                    continue;

                $filter = null;

                /** @var \OpenEstate\PhpExport\Filter\AbstractFilter $f */
                foreach ($this->filters as $f) {
                    if ($f->getName() === $filterName) {
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
     * @param $objectIds
     * array of object ID's
     *
     * @return array
     * array of object ID's to show on the current page
     */
    public function getObjectIdsOnThisPage($objectIds)
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
     * @param $numberOfObjects
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
