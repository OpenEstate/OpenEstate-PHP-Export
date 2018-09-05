<?php

namespace OpenEstate\PhpExport;

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
