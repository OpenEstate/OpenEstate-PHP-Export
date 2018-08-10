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

namespace OpenEstate\PhpExport;

/**
 * Static helper methods.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class Utils
{
    /**
     * Returns the timestamp, when a certain file was last modified.
     *
     * @param string $file
     * path to the file
     *
     * @return int|null
     * timestamp of last modification or null, if the file is not available
     */
    public static function getFileStamp($file)
    {
        $stamp = (is_file($file)) ?
            filemtime($file) : null;

        return ($stamp !== false && $stamp !== null) ?
            $stamp : null;
    }

    /**
     * Tests, if a file is older then a certain lifetime.
     *
     * @param string $file
     * path to the file
     *
     * @param int $maxLifetime
     * maximum lifetime of the file in seconds
     *
     * @return bool
     * true, if the file is older then the specified lifetime.
     */
    public static function isFileOlderThen($file, $maxLifetime)
    {
        return !self::isFileYoungerThen($file, $maxLifetime);
    }

    /**
     * Tests, if a file is younger then a certain lifetime.
     *
     * @param string $file
     * path to the file
     *
     * @param int $maxLifetime
     * maximum lifetime of the file in seconds
     *
     * @return bool
     * true, if the file is younger then the specified lifetime.
     */
    public static function isFileYoungerThen($file, $maxLifetime)
    {
        if (!\is_string($file) || !\is_file($file))
            return false;

        $fileTime = \filemtime($file) + $maxLifetime;
        return $fileTime > \time();
    }

    /**
     * List names of files and sub-folders in a directory.
     *
     * @param string $directory
     * path to the directory
     *
     * @return array
     * names of files and sub-folders in the directory
     */
    public static function listDirectory($directory)
    {
        if (!\is_string($directory) || !\is_dir($directory))
            return array();

        $results = array();
        $handler = \opendir($directory);
        while ($file = \readdir($handler)) {
            if ($file != '.' && $file != '..')
                $results[] = $file;
        }
        \closedir($handler);
        return $results;
    }

    /**
     * Send a PHP log message.
     *
     * @param string|\Throwable $msg
     * message or exception
     *
     * @param int $type
     * log level
     */
    private static function log($msg, $type)
    {
        //if ($msg instanceof \Throwable)
        //    \trigger_error((string) $msg, $type);
        //else
        //    \trigger_error($msg, $type);

        \trigger_error((string)$msg, $type);
    }

    /**
     * Log a deprecation notice.
     *
     * @param string $msg
     * message
     */
    public static function logDeprecated($msg)
    {
        self::log($msg, \E_USER_DEPRECATED);
    }

    /**
     * Log an error.
     *
     * @param string $msg
     * message
     */
    public static function logError($msg)
    {
        self::log($msg, \E_USER_ERROR);
    }

    /**
     * Log a notice.
     *
     * @param string $msg
     * message
     */
    public static function logNotice($msg)
    {
        self::log($msg, \E_USER_NOTICE);
    }

    /**
     * Log a warning.
     *
     * @param string $msg
     * message
     */
    public static function logWarning($msg)
    {
        self::log($msg, \E_USER_WARNING);
    }

    /**
     * Returns the contents of a file as string.
     *
     * @param string $file
     * path to the file
     *
     * @return string|null
     * file contents or null, if the file is not loadable
     */
    public static function readFile($file)
    {
        if (!\is_string($file) || !\is_file($file))
            return null;

        $contents = \file_get_contents($file);
        return ($contents !== false) ?
            $contents : null;
    }

}
