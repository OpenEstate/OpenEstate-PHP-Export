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
use OpenEstate\PhpExport\Utils;

/**
 * An abstract object order.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class AbstractOrder
{
    /**
     * Maximum cache lifetime for this order in seconds.
     *
     * @var int
     */
    public $maxLifeTime;

    /**
     * Internal name of this order.
     *
     * @var string
     */
    private $name;

    /**
     * Container for ordering results.
     *
     * @var array
     */
    protected $items = array();

    /**
     * AbstractOrder constructor.
     *
     * @param string $name
     * internal name
     *
     * @param int|null $maxLifeTime
     * maximum lifetime of cache files in seconds
     */
    function __construct($name, $maxLifeTime = null)
    {
        $this->name = $name;
        $this->maxLifeTime = (\is_int($maxLifeTime) && $maxLifeTime >= 0) ?
            $maxLifeTime : 60 * 60 * 24;
    }

    /**
     * AbstractOrder destructor.
     */
    public function __destruct()
    {
    }

    /**
     * Create an ordered array of object ID's.
     *
     * @param Environment $env
     * export environment
     *
     * @return bool
     * true, if the data was successfully loaded
     */
    public function build(Environment $env)
    {
        $this->items = array();
        $ids = $env->getObjectIds();
        if (!\is_array($ids))
            return false;

        $values = array();
        $unordered = array();
        foreach ($ids as $id) {
            $object = $env->getObject($id);
            if (!\is_array($object))
                continue;

            // Get ordering values independent from any language.
            if (!$this->isLanguageSpecific()) {
                $field = $this->getSortValue($env, $object, null);
                if ($field == null) {
                    $unordered[] = $object['id'];
                    continue;
                }

                $values[$object['id']] = $field;
                continue;
            }

            // Get ordering values separately for each available language.
            foreach ($env->getLanguageCodes() as $lang) {
                $field = $this->getSortValue($env, $object, $lang);
                if ($field == null) {
                    if (!isset($unordered[$lang]))
                        $unordered[$lang] = array();

                    $unordered[$lang][] = $object['id'];
                    continue;
                }
                if (!isset($values[$lang]) || !\is_array($values[$lang]))
                    $values[$lang] = array();

                $values[$lang][$object['id']] = $field;
            }
        }

        // Order objects independent from any language.
        if (!$this->isLanguageSpecific()) {
            \asort($values, $this->getSortFlag());
            if (\is_array($unordered) && \count($unordered) > 0)
                $this->items = \array_merge(\array_keys($values), $unordered);
            else
                $this->items = \array_keys($values);

            return true;
        }

        // Order objects separately for each available language.
        foreach (\array_keys($values) as $lang) {
            \asort($values[$lang], $this->getSortFlag());
            if (isset($unordered[$lang]) && \is_array($unordered[$lang]) && \count($unordered[$lang]) > 0)
                $this->items[$lang] = \array_merge(\array_keys($values[$lang]), $unordered[$lang]);
            else
                $this->items[$lang] = \array_keys($values[$lang]);
        }

        return true;
    }

    /**
     * Get the path to the cache file for this order.
     *
     * @param Environment $env
     * export environment
     *
     * @return string
     * absolute path to the cache file
     */
    public function getFile(Environment $env)
    {
        return $env->getCachePath('order.' . $this->getName());
    }

    /**
     * Get the array of object ID's ordered by this class.
     *
     * @param string $lang
     * language code
     *
     * @return array
     * ordered array of object ID's
     */
    function getItems($lang)
    {
        return ($this->isLanguageSpecific()) ?
            $this->items[$lang] :
            $this->items;
    }

    /**
     * Get the internal order name.
     *
     * @return string
     * order name
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * Get the flag for sorting the object values.
     *
     * @return int
     * sort flag
     *
     * @see http://php.net/manual/en/function.sort.php PHP documentation about sort flags.
     */
    protected function getSortFlag()
    {
        return SORT_STRING;
    }

    /**
     * Get the sorted value of an object.
     *
     * @param Environment $env
     * export environment
     *
     * @param array $object
     * array with object data
     *
     * @param string $lang
     * language code
     *
     * @return string|int|float
     * value used to sort the object
     */
    abstract protected function getSortValue(Environment $env, array &$object, $lang);

    /**
     * Get the order title for the current language.
     *
     * @param string $lang
     * current language code
     *
     * @return string
     * title
     */
    abstract public function getTitle($lang);

    /**
     * Determine, if each language requires separate sorting.
     *
     * @return bool
     * true, if each language is ordered separately
     */
    protected function isLanguageSpecific()
    {
        return false;
    }

    /**
     * Load array with ordering values from cache file.
     *
     * @param Environment $env
     * export environment
     *
     * @return bool
     * true, if the cache file was loaded, otherwise false
     */
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

        $this->items = unserialize($data);
        //echo '<pre>'; print_r( $this->items ); echo '</pre>';
        //die( 'read ' . $file );
        return true;
    }

    /**
     * Load array with ordering values from cache file.
     * If no valid cache file is available, load the data from available objects.
     *
     * @param Environment $env
     * export environment
     *
     * @return bool
     * true, if the filter values were loaded, otherwise false
     */
    public function readOrRebuild(Environment $env)
    {
        // Try reading ordering values from cache.
        if ($this->read($env))
            return true;

        // Otherwise load ordering values from available objects.
        if (!$this->build($env))
            return false;

        // Write ordering values into the cache file for future usage.
        try {
            $this->write($env);
        } catch (\Exception $e) {
            Utils::logWarning($e);
        }

        return true;
    }

    /**
     * Write ordering values into the cache file.
     *
     * @param Environment $env
     * export environment
     *
     * @throws \Exception
     * if the cache file is not writable
     */
    public function write(Environment $env)
    {
        $data = \serialize($this->items);
        $file = $this->getFile($env);
        $fh = \fopen($file, 'w');
        if ($fh === false) {
            $msg = 'Can\'t write cache file for order "' . $this->getName() . '" into "' . $file . '".';
            throw new \Exception($msg, $file);
        }
        \fwrite($fh, $data);
        \fclose($fh);
    }

}
