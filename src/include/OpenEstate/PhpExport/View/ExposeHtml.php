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

namespace OpenEstate\PhpExport\View;

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Utils;

/**
 * A detailed view for a real estate object.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class ExposeHtml extends AbstractHtmlView
{
    /**
     * ID of the object to show on this view.
     *
     * @var string
     */
    private $objectId = null;

    /**
     * Name of the object ID parameter.
     *
     * @var string
     */
    public $objectIdParameter = 'id';

    /**
     * ExposeHtml constructor.
     *
     * @param Environment $env
     * export environment
     */
    function __construct(Environment $env)
    {
        parent::__construct($env);

        // add previously configured prefix to parameter names
        $this->objectIdParameter = Environment::parameter($this->objectIdParameter);
    }

    protected function generate()
    {
        return Utils::encode($this->loadThemeFile('expose.php'), $this->getCharset());
    }

    /**
     * Get data for the requested real estate object.
     *
     * @return array|null
     * object data or null, if no valid object was requested
     */
    public function &getObjectData()
    {
        return $this->env->getObject($this->getObjectId());
    }

    /**
     * Get ID of the requested real estate object.
     *
     * @return string|null
     * object ID or null, if no object was requested
     */
    public function getObjectId()
    {
        if (Utils::isNotBlankString($this->objectId))
            return $this->objectId;

        return (isset($_REQUEST[$this->objectIdParameter]) && \is_string($_REQUEST[$this->objectIdParameter])) ?
            \basename($_REQUEST[$this->objectIdParameter]) : null;
    }

    /**
     * Get texts for the requested real estate object.
     *
     * @return array|null
     * object texts or null, if no valid object was requested
     */
    public function getObjectTexts()
    {
        return $this->env->getObjectText($this->getObjectId());
    }

    /**
     * Get parameter values for this view.
     *
     * @param string|null $objectId
     * ID of the object to show
     *
     * @return array
     * associative array with parameter values
     */
    public function getParameters($objectId = null)
    {
        $params = parent::getParameters();

        if ($objectId !== null)
            $params[$this->objectIdParameter] = $objectId;

        return $params;
    }

    /**
     * Get url for this view.
     *
     * @param Environment $env
     * export environment
     *
     * @param string|null $objectId
     * ID of the object to show
     *
     * @return string
     * url
     */
    public function getUrl(Environment $env, $objectId = null)
    {
        return $env->getExposeUrl($this->getParameters($objectId));
    }

    /**
     * Get an array of object ID's, that were marked as favorites by the user.
     *
     * @return array
     * array of favored object ID's
     */
    public function getFavorites()
    {
        return $this->env->getSession()->getFavorites();
    }

    /**
     * Set ID of the object to show on this view.
     *
     * @param string $objectId
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    }
}
