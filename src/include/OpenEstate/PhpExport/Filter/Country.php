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
use OpenEstate\PhpExport\Utils;
use OpenEstate\PhpExport\Html\Select;
use function OpenEstate\PhpExport\gettext as _;

/**
 * Filter by country.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class Country extends AbstractFilter
{
    /**
     * Cached array of country names.
     *
     * @var array
     */
    protected $countryNames = null;

    /**
     * Country constructor.
     *
     * @param string $name
     * internal name
     *
     * @param int|null $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'Country', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    public function build(Environment $env)
    {
        $this->countryNames = array();
        return parent::build($env);
    }

    protected function filter(array &$object, array &$items)
    {
        $value = (isset($object['address']['country'])) ?
            $object['address']['country'] : null;
        if (Utils::isBlankString($value))
            return;

        $value = \trim($value);

        if (!isset($items[$value]) || !\is_array($items[$value]))
            $items[$value] = array();

        $items[$value][] = $object['id'];

        // Load and cache country names for the country code.
        if (!isset($this->countryNames[$value])) {
            $this->countryNames[$value] = array();
            if (\is_array($object['address']['country_name'])) {
                foreach ($object['address']['country_name'] as $lang => $countryName) {
                    if (!isset($this->countryNames[$value][$lang]))
                        $this->countryNames[$value][$lang] = $countryName;

                }
            }
        }
    }

    public function getTitle($lang)
    {
        return _('country');
    }

    public function getWidget(Environment $env, $selectedValue = null)
    {
        if (!$this->readOrRebuild($env) || !\is_array($this->items))
            return null;

        $lang = $env->getLanguage();
        //$translations = $env->getTranslations();

        $values = array();
        $values[''] = '[ ' . $this->getTitle($lang) . ' ]';
        $options = \array_keys($this->items);
        \asort($options);
        foreach ($options as $o) {
            $values[$o] = (isset($this->countryNames[$o][$lang])) ?
                $this->countryNames[$o][$lang] : $o;
        }

        return Select::newSingleSelect(
            'filter[' . $this->getName() . ']',
            'openestate-filter-field-' . $this->getName(),
            'openestate-filter-field',
            $selectedValue,
            $values
        );
    }

    public function read(Environment $env)
    {
        $file = $this->getFile($env);
        if (!\is_file($file))
            return false;

        // Remove outdated cache file.
        if ($this->maxLifeTime > 0 && Utils::isFileOlderThen($file, $this->maxLifeTime)) {
            \unlink($file);
            return false;
        }

        // Read data from cache file.
        $data = Utils::readFile($file);
        if (!is_string($data))
            return false;

        $values = unserialize($data);

        $this->items = $values['items'];
        $this->countryNames = $values['countryNames'];
        //echo '<pre>'; print_r( $this->items ); echo '</pre>';
        //die( 'read ' . $file );
        return true;
    }

    public function write(Environment $env)
    {
        $values = array(
            'items' => $this->items,
            'countryNames' => $this->countryNames
        );
        $data = \serialize($values);
        $file = $this->getFile($env);
        $fh = \fopen($file, 'w');
        if ($fh === false) {
            $msg = 'Can\'t write cache file for filter "' . $this->getName() . '" into "' . $file . '".';
            throw new \Exception($msg, $file);
        }
        \fwrite($fh, $data);
        \fclose($fh);
    }

}
