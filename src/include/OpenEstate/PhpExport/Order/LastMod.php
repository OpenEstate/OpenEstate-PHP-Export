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
 * Order by time of last modification.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class LastMod extends AbstractOrder
{
    /**
     * LastMod constructor.
     *
     * @param $name
     * internal name
     *
     * @param int $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'LastMod', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function getSortFlag()
    {
        return SORT_NUMERIC;
    }

    protected function getSortValue(\OpenEstate\PhpExport\Environment &$env, &$object, $lang)
    {
        $id = (isset($object['id'])) ?
            $object['id'] : null;
        return \is_string($id) ?
            $env->getObjectStamp($id) : null;
    }

    public function getTitle(&$translations, $lang)
    {
        $title = (isset($translations['labels']['lastModification'])) ?
            $translations['labels']['lastModification'] : null;
        return \is_string($title) ?
            $title : $this->getName();
    }

}
