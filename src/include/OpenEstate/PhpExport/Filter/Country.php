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

use OpenEstate\PhpExport\Utils;
use function OpenEstate\PhpExport\gettext as _;

/**
 * Filter by country.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
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
     * @param $name
     * internal name
     *
     * @param int $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name = 'Country', $maxLifeTime = null)
    {
        parent::__construct($name, $maxLifeTime);
    }

    public function build(\OpenEstate\PhpExport\Environment $env)
    {
        $this->countryNames = array();
        return parent::build($env);
    }

    protected function filter(&$object, &$items)
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

    public function getWidget(\OpenEstate\PhpExport\Environment $env, $selectedValue = null)
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

        return \OpenEstate\PhpExport\Html\Select::newSingleSelect(
            'filter[' . $this->getName() . ']',
            'openestate-filter-field-' . $this->getName(),
            'openestate-filter-field',
            $selectedValue,
            $values
        );
    }

    public function read(\OpenEstate\PhpExport\Environment $env)
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

    public function write(\OpenEstate\PhpExport\Environment $env)
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
