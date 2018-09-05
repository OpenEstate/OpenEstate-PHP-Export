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

namespace OpenEstate\PhpExport\Order;

use OpenEstate\PhpExport\Environment;
use function OpenEstate\PhpExport\gettext as _;

/**
 * Order by number of rooms.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class Rooms extends AbstractOrder
{
    /**
     * Rooms constructor.
     *
     * @param string $name
     * internal name
     *
     * @param int|null $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'Rooms', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function getSortFlag()
    {
        return SORT_NUMERIC;
    }

    protected function getSortValue(Environment $env, array &$object, $lang)
    {
        return (isset($object['attributes']['measures']['count_rooms']['value'])) ?
            $object['attributes']['measures']['count_rooms']['value'] : null;
    }

    public function getTitle($lang)
    {
        return _('number of rooms');
    }

}
