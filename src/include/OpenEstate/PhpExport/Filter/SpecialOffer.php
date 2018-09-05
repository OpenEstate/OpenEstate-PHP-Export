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

namespace OpenEstate\PhpExport\Filter;

use function OpenEstate\PhpExport\gettext as _;

/**
 * Filter by special offers.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class SpecialOffer extends AbstractFilter
{
    /**
     * SpecialOffer constructor.
     *
     * @param $name
     * internal name
     *
     * @param int $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'SpecialOffer', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function filter(&$object, &$items)
    {
        $value = (isset($object['attributes']['prices']['special_offer']['value'])) ?
            $object['attributes']['prices']['special_offer']['value'] : null;
        if ($value === true) {
            if (!isset($items['1']) || !\is_array($items['1'])) {
                $items['1'] = array();
            }
            $items['1'][] = $object['id'];
        } else {
            if (!isset($items['0']) || !\is_array($items['0'])) {
                $items['0'] = array();
            }
            $items['0'][] = $object['id'];
        }
    }

    public function getTitle($lang)
    {
        return _('special offer');
    }

    public function getWidget(\OpenEstate\PhpExport\Environment $env, $selectedValue = null)
    {
        if (!$this->readOrRebuild($env) || !\is_array($this->items)) {
            return null;
        }

        $lang = $env->getLanguage();
        //$translations = $env->getTranslations();

        $selectedValue = (string)$selectedValue;
        $checked = $selectedValue == '1';
        return \OpenEstate\PhpExport\Html\Checkbox::newBox(
            'filter[' . $this->getName() . ']',
            'openestate-filter-field-' . $this->getName(),
            'openestate-filter-field',
            '1',
            $checked,
            $this->getTitle($lang)
        );
    }

}
