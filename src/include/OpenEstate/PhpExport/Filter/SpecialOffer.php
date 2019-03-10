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

namespace OpenEstate\PhpExport\Filter;

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Html\Checkbox;
use function OpenEstate\PhpExport\gettext as _;

/**
 * Filter by special offers.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class SpecialOffer extends AbstractFilter
{
    /**
     * SpecialOffer constructor.
     *
     * @param string $name
     * internal name
     *
     * @param int|null $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'SpecialOffer', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    protected function filter(array &$object, array &$items)
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

    public function getWidget(Environment $env, $selectedValue = null)
    {
        if (!$this->readOrRebuild($env) || !\is_array($this->items)) {
            return null;
        }

        $lang = $env->getLanguage();
        //$translations = $env->getTranslations();

        $selectedValue = (string)$selectedValue;
        $checked = $selectedValue == '1';
        return Checkbox::newBox(
            'filter[' . $this->getName() . ']',
            'openestate-filter-field-' . $this->getName(),
            'openestate-filter-field',
            '1',
            $checked,
            $this->getTitle($lang)
        );
    }

}
