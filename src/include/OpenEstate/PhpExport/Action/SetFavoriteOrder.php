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

namespace OpenEstate\PhpExport\Action;

use OpenEstate\PhpExport\Environment;

/**
 * Change ordering of the favorite view.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class SetFavoriteOrder extends AbstractAction
{
    /**
     * Action name.
     *
     * @var string
     */
    const NAME = 'SetFavoriteOrder';

    /**
     * Parameter name for the ordering method.
     *
     * @var string
     */
    public $orderParameter = 'order';

    /**
     * Parameter name for the ordering direction.
     *
     * @var string
     */
    public $directionParameter = 'direction';

    /**
     * SetFavoriteOrder constructor.
     *
     * @param string $name
     * internal name
     */
    function __construct($name = self::NAME)
    {
        parent::__construct($name);

        // add previously configured prefix to parameter names
        $this->orderParameter = Environment::parameter($this->orderParameter);
        $this->directionParameter = Environment::parameter($this->directionParameter);
    }

    public function execute(Environment $env)
    {
        $order = (isset($_REQUEST[$this->orderParameter])) ?
            $_REQUEST[$this->orderParameter] : null;
        $direction = (isset($_REQUEST[$this->directionParameter])) ?
            $_REQUEST[$this->directionParameter] : null;

        if (\is_string($order))
            $env->getSession()->setFavoriteOrder($order);
        else
            $env->getSession()->setFavoriteOrder(null);

        if (\is_string($direction))
            $env->getSession()->setFavoriteOrderDirection($direction);
        else
            $env->getSession()->setFavoriteOrderDirection(null);

        return true;
    }

    /**
     * Get parameter values for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param string|null $order
     * name of the ordering method
     *
     * @param string|null $direction
     * direction of ordering ("asc" or "desc")
     *
     * @return array
     * associative array with parameter values
     */
    public function getParameters(Environment $env, $order = null, $direction = null)
    {
        $params = parent::getParameters($env);
        if ($order !== null && \is_string($order))
            $params[$this->orderParameter] = $order;
        if ($direction !== null && \is_string($direction))
            $params[$this->directionParameter] = $direction;
        return $params;
    }

    /**
     * Get url for this action.
     *
     * @param Environment $env
     * export environment
     *
     * @param string|null $order
     * name of the ordering method
     *
     * @param string|null $direction
     * direction of ordering ("asc" or "desc")
     *
     * @return string
     * url
     */
    public function getUrl(Environment $env, $order = null, $direction = null)
    {
        return $env->getActionUrl($this->getParameters($env, $order, $direction));
    }
}