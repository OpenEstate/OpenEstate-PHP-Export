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

namespace OpenEstate\PhpExport\Exception;

use Throwable;

/**
 * An exception, that is thrown while processing a theme file.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
class ThemeException extends \Exception
{
    /**
     * ThemeException constructor.
     *
     * @param string $message
     * exception message to throw
     *
     * @param string $file
     * absolute path to the theme file, that is not processable
     *
     * @param int $code
     * exception code.
     *
     * @param Throwable|null $previous
     * previous exception used for exception chaining
     */
    function __construct($message = '', $file = null, $code = 0, Throwable $previous = null)
    {
        // Append file name to the exception message.
        if (\is_string($file)) {
            if (\is_string($message) && \strlen($message) > 0)
                $message .= ' (file: ' . $file . ')';
            else
                $message = 'File "' . $file . '" is not writable!"';
        }

        parent::__construct($message, $code, $previous);
    }
}
