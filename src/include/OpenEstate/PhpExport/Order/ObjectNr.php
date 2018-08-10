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

/**
 * Order by public object nr.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class ObjectNr extends AbstractOrder
{
    /**
     * ObjectNr constructor.
     *
     * @param $name
     * internal name
     *
     * @param int $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'ObjectNr', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function getSortFlag()
    {
        return SORT_STRING;
    }

    protected function getSortValue(\OpenEstate\PhpExport\Environment &$env, &$object, $lang)
    {
        $nr = (isset($object['nr'])) ?
            $object['nr'] : null;
        $id = (isset($object['id'])) ?
            $object['id'] : null;

        return \is_string($nr) ?
            $nr : \is_string($id) ?
                '#' . $id : null;
    }

    public function getTitle(&$translations, $lang)
    {
        $title = (isset($translations['labels']['estate.nr'])) ?
            $translations['labels']['estate.nr'] : null;
        return \is_string($title) ?
            $title : $this->getName();
    }

}
