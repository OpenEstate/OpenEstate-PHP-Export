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
     * Current page number.
     *
     * @var int
     */
    public $page = 0;

    /**
     * Parameter name for the current page number.
     *
     * @var string
     */
    public $pageParam = 'page';

    /**
     * Filters used on this page.
     *
     * @var array
     */
    public $filters = array();

    /**
     * Parameter name for filter settings.
     *
     * @var string
     */
    public $filtersParam = 'filter';

    /**
     * Ordering used on this page.
     *
     * @var \OpenEstate\PhpExport\Order\AbstractOrder
     */
    public $order = null;

    /**
     * Parameter name for selection of ordering.
     *
     * @var string
     */
    public $orderParam = 'order';

    /**
     * Order in ascending or descending direction (asc or desc).
     *
     * @var string
     */
    public $direction = 'asc';

    /**
     * Parameter name for ordering direction.
     *
     * @var string
     */
    public $directionParam = 'dir';

    /**
     * ListingHtml constructor.
     *
     * @param string $name
     * internal name of the view
     *
     * @param string $charset
     * charset of the document
     *
     * @param string $theme
     * name of the theme
     */
    function __construct($name = 'ListingHtml', $charset = null, $theme = null)
    {
        parent::__construct($name, $charset, $theme);
    }

    public function generate(\OpenEstate\PhpExport\Environment &$env)
    {
        try {
            return $this->loadThemeFile($env, 'listing.php');
        } catch (\OpenEstate\PhpExport\Exception\ThemeException $e) {
            \OpenEstate\PhpExport\Utils::logError($e);
            return null;
        }
    }
}
