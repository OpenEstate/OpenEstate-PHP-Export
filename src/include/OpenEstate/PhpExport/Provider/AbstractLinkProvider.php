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

namespace OpenEstate\PhpExport\Provider;

/**
 * An abstract embedded view for a provider link.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class AbstractLinkProvider extends AbstractProvider
{
    /**
     * AbstractLinkProvider constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * AbstractLinkProvider destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Get HTML code for the embedded view.
     *
     * @param string $linkId
     * ID, that is used by the provider to identify the target
     *
     * @param string|null $linkUrl
     * link URL
     *
     * @param string|null $linkTitle
     * link title
     *
     * @return string
     * HTML code
     */
    abstract public function getBody($linkId, $linkUrl = null, $linkTitle = null);

}
