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

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Utils;
use OpenEstate\PhpExport\Html\AbstractInputElement;

/**
 * An abstract object filter.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class AbstractFilter
{
    /**
     * Maximum cache lifetime for this filter in seconds.
     *
     * @var int
     */
    public $maxLifeTime;

    /**
     * Internal name of this filter.
     *
     * @var string
     */
    private $name;

    /**
     * Container for filtering results.
     *
     * @var array
     */
    protected $items = array();

    /**
     * AbstractFilter constructor.
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
     * AbstractFilter destructor.
     */
    public function __destruct()
    {
    }

    /**
     * Create an array of object ID's, that are matched by this filter.
     *
     * @param Environment env
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

        foreach ($ids as $id) {
            $object = $env->getObject($id);
            if (!\is_array($object))
                continue;

            $this->filter($object, $this->items);
        }
        return true;
    }

    /**
     * Determine, if an object is matched by the filter.
     * The ID of a matching object is added to $items.
     *
     * @param array $object
     * array with object data
     *
     * @param array $items
     * array with filtered items
     *
     * @return void
     */
    abstract protected function filter(array &$object, array &$items);

    /**
     * Get the path to the cache file for this filter.
     *
     * @param Environment $env
     * export environment
     *
     * @return string
     * absolute path to the cache file
     */
    public function getFile(Environment $env)
    {
        return $env->getCachePath('filter.' . $this->getName());
    }

    /**
     * Get the array of object ID's,
     * that match with the filter for a certain value.
     *
     * @param string $value
     * filter value
     *
     * @return array
     * array of object ID's, that match with the provided filter value
     */
    public function getItems($value)
    {
        return (isset($this->items[$value]) && \is_array($this->items[$value])) ?
            $this->items[$value] :
            array();
    }

    /**
     * Get the internal filter name.
     *
     * @return string
     * filter name
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * Get the filter title for the current language.
     *
     * @param string $lang
     * current language code
     *
     * @return string
     * title
     */
    abstract public function getTitle($lang);

    /**
     * Create a HTML widget for a selection on this filter.
     *
     * @param Environment $env
     * export environment
     *
     * @param string $selectedValue
     * selected filter value
     *
     * @return AbstractInputElement|null
     * created HTML widget or null, if it can't be created
     */
    abstract public function getWidget(Environment $env, $selectedValue = null);

    /**
     * Load array with filter values from cache file.
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
     * Load array with filter values from cache file.
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
        // Try reading filter values from cache.
        if ($this->read($env))
            return true;

        // Otherwise load filter values from available objects.
        if (!$this->build($env))
            return false;

        // Write filter values into the cache file for future usage.
        try {
            $this->write($env);
        } catch (\Exception $e) {
            Utils::logWarning($e);
        }

        return true;
    }

    /**
     * Write filter values into the cache file.
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
            $msg = 'Can\'t write cache file for filter "' . $this->getName() . '" into "' . $file . '".';
            throw new \Exception($msg, $file);
        }
        \fwrite($fh, $data);
        \fclose($fh);
    }

}
