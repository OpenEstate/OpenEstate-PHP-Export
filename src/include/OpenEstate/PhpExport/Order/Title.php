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
 * Order by object title.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class Title extends AbstractOrder
{
    /**
     * Title constructor.
     *
     * @param $name
     * internal name
     *
     * @param int $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'Title', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function getSortFlag()
    {
        return SORT_STRING;
    }

    protected function getSortValue(\OpenEstate\PhpExport\Environment &$env, &$object, $lang)
    {
        $val = (isset($object['title'][$lang])) ?
            $object['title'][$lang] : null;
        return \is_string($val) ?
            $val : '';
    }

    public function getTitle(&$translations, $lang)
    {
        $title = (isset($translations['labels']['estate.title'])) ?
            $translations['labels']['estate.title'] : null;
        return \is_string($title) ?
            $title : $this->getName();
    }

    protected function isLanguageSpecific()
    {
        return true;
    }

}
