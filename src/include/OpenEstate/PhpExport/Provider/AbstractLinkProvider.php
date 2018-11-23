<?php
/*
 * Copyright 2009-2018 OpenEstate.org.
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

namespace OpenEstate\PhpExport\Provider;

/**
 * An abstract embedded view for a provider link.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
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