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

namespace OpenEstate\PhpExport;

/**
 * Translation methods.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

use Gettext\BaseTranslator;

/**
 * Returns the translation of a string.
 *
 * @param string $original
 * original message
 *
 * @param array $parameters
 * optional parameters to replace
 *
 * @return string
 * translated message
 */
function gettext($original, ...$parameters)
{
    return BaseTranslator::$current->gettext($original, ...$parameters);
}

/**
 * Marks a string for translation and returns it unchanged.
 *
 * @param string $original
 * original message
 *
 * @return string
 * original message
 */
function noop($original)
{
    return $original;
}

/**
 * Returns the translation of a string in singular or plural form.
 *
 * @param string $original
 * original message
 *
 * @param string $plural
 * plural form of the original message
 *
 * @param int $value
 * value to determine between singular and plural form
 *
 * @param array $parameters
 * optional parameters to replace
 *
 * @return string
 * translated message
 */
function ngettext($original, $plural, $value, ...$parameters)
{
    return BaseTranslator::$current->ngettext($original, $plural, $value, ...$parameters);
}
