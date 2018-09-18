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

use \TrueBV\Punycode;
use function gettext as _;

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
     * Create a translator for a certain language.
     *
     * @param Environment $env
     * export environment
     *
     * @param string|null $languageCode
     * custom ISO language code
     *
     * @return Translator
     * translator instance
     */
    public static function createTranslator(Environment $env, $languageCode = null)
    {
        $lang = (\is_string($languageCode)) ?
            $languageCode : $env->getLanguage();

        // init internal translations
        $translations = new \Gettext\Translations();

        // load global translations
        $localeFile = $env->getLocalePath($lang . '.mo');
        if (\is_file($localeFile) && \is_readable($localeFile)) {
            $translations->mergeWith(
                \Gettext\Translations::fromMoFile($localeFile),
                \Gettext\Merge::ADD | \Gettext\Merge::TRANSLATION_OVERRIDE
            );
        } else {
            $localeFile = $env->getLocalePath($lang . '.po');
            if (\is_file($localeFile) && \is_readable($localeFile)) {
                $translations->mergeWith(
                    \Gettext\Translations::fromPoFile($localeFile),
                    \Gettext\Merge::ADD | \Gettext\Merge::TRANSLATION_OVERRIDE
                );
            }
        }

        // load theme translations
        $localeFile = $env->getThemePath('locale/' . $lang . '.mo');
        if (\is_file($localeFile) && \is_readable($localeFile)) {
            $translations->mergeWith(
                \Gettext\Translations::fromMoFile($localeFile),
                \Gettext\Merge::ADD | \Gettext\Merge::TRANSLATION_OVERRIDE
            );
        } else {
            $localeFile = $env->getThemePath('locale/' . $lang . '.po');
            if (\is_file($localeFile) && \is_readable($localeFile)) {
                $translations->mergeWith(
                    \Gettext\Translations::fromPoFile($localeFile),
                    \Gettext\Merge::ADD | \Gettext\Merge::TRANSLATION_OVERRIDE
                );
            }
        }

        // create translator
        $translator = new Translator($env, $languageCode);
        $translator->loadTranslations($translations);
        return $translator;
    }

    /**
     * Get an abbreviated string.
     *
     * @param string $value
     * string value to abbreviate
     *
     * @param int $length
     * maximum length of the abbreviated string
     *
     * @param bool $singleLine
     * remove line breaks before abbreviation
     *
     * @return string
     * abbreviated string
     */
    public static function getAbbreviatedString($value, $length, $singleLine = false)
    {
        if ($value === null || !\is_string($value))
            return '';

        $value = \trim($value);

        if ($singleLine === true) {
            $value = \trim(\str_replace(
                array("\n", '<br>', '<br/>'),
                array(' ', ' ', ' '),
                $value
            ));
            while (\strpos($value, '  ') !== false) {
                $value = \str_replace('  ', ' ', $value);
            }
        }

        return (\strlen($value) > ($length - 3)) ?
            \substr($value, 0, $length - 3) . '...' :
            $value;
    }

    /**
     * Get the value for an attribute.
     *
     * @param string $group
     * name of the attribute group
     *
     * @param string $attribute
     * name of the attribute
     *
     * @param array $value
     * attribute value
     *
     * @param array $i18n
     * data translations
     *
     * @param string $lang
     * language code
     *
     * @return string
     * readable output of the attribute value
     */
    public static function getAttributeValue($group, $attribute, array $value, array &$i18n, $lang)
    {
        if (!\is_array($value) || !isset($value['value']))
            return null;

        $txt = null;

        // ggf. individuellen Attribut-Wert aus der myconfig.php ermitteln
        //if (is_callable(array('immotool_myconfig', 'write_attribute_value'))) {
        //    $txt = immotool_myconfig::write_attribute_value($group, $attribute, $value, $i18n, $lang);
        //}

        // ggf. den Text "ab sofort" ausgeben,
        // wenn der Verfügbarkeits-Beginn in der Vergangenheit liegt
        if ($txt === null && $group == 'administration' && $attribute == 'availability_begin_date') {
            $stamp = (isset($value['value'])) ? $value['value'] : null;
            if (\is_numeric($stamp) && $stamp <= time())
                return _('from now on');
        }

        // ggf. Attribut-Wert zur angeforderten Sprache ermitteln
        if ($txt === null) {
            $txt = (isset($value[$lang])) ? $value[$lang] : null;
        }

        // ggf. den unformatierten Attribut-Wert ermitteln
        if ($txt === null) {
            $txt = (isset($value['value'])) ? $value['value'] : null;
        }

        return $txt;
    }

    /**
     * Generates a secure captcha has value.
     *
     * @param string $captchaCode
     * captcha code
     *
     * @param int $stamp
     * timestamp
     *
     * @return string
     * captcha hash value
     */
    public static function getCaptchaHash($captchaCode, $stamp = null)
    {
        if ($stamp === null || !\is_int($stamp))
            $stamp = \time();

        $secret = $stamp . '-' . __FILE__;
        if (isset($_SERVER['SERVER_NAME']))
            $secret .= '-' . $_SERVER['SERVER_NAME'];
        if (isset($_SERVER['REMOTE_ADDR']))
            $secret .= '-' . $_SERVER['REMOTE_ADDR'];
        $secret .= '-' . $captchaCode;

        return \strtolower($stamp . ':' . \sha1($secret));
    }

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
            \filemtime($file) : null;

        return ($stamp !== false && $stamp !== null) ?
            $stamp : null;
    }

    /**
     * Convert a variable to JSON.
     *
     * @param mixed $obj
     * variable to convert
     *
     * @param bool $json
     * encode for JSON if true, otherwise encode for Javascript
     *
     * @return string
     * JSON string
     *
     * @see http://loewald.com/blog/2010/05/php-json_encode-replacement/
     */
    public static function getJson($obj, $json = true)
    {
        switch (\gettype($obj)) {
            case 'array':
            case 'object':
                $code = array();
                // is it anything other than a simple linear array
                if (\array_keys($obj) !== \range(0, \count($obj) - 1)) {
                    foreach ($obj as $key => $val) {
                        $code [] = $json ?
                            '"' . $key . '":' . self::getJson($val) :
                            $key . ':' . self::getJson($val);
                    }
                    $code = '{' . \implode(',', $code) . '}';
                } else {
                    foreach ($obj as $val) {
                        $code [] = self::getJson($val);
                    }
                    $code = '[' . \implode(',', $code) . ']';
                }
                return $code;
                break;

            case 'boolean':
                return $obj ? 'true' : 'false';
                break;

            case 'integer':
            case 'double':
                return \floatVal($obj);
                break;

            case 'NULL':
            case 'resource':
            case 'unknown':
                return 'null';
                break;

            default:
                return '"' . \addslashes($obj) . '"';
        }
    }

    /**
     * Returns minimized HTML code.
     *
     * @param string $html
     * HTML code to minimize
     *
     * @return string
     * minimized HTML code
     */
    public static function getMinimizedHtml($html)
    {
        $search = array(
            '/\>[^\S ]+/s',
            '/[^\S ]+\</s',
            '/(\s)+/s'
        );
        $replace = array(
            '>',
            '<',
            '\\1'
        );
        //if (preg_match("/\<html/i",$html) == 1 && preg_match("/\<\/html\>/i",$html) == 1) {
        //    $html = preg_replace($search, $replace, $html);
        //}
        return preg_replace($search, $replace, $html);
    }

    /**
     * Returns the number of pages required to show a certain list of objects.
     *
     * @param int $numberOfObjects
     * total number of objects
     *
     * @param int $objectsPerPage
     * number of objects visible on a page
     *
     * @return int
     * number of pages
     */
    public static function getNumberOfPages($numberOfObjects, $objectsPerPage)
    {
        return (int)\ceil($numberOfObjects / $objectsPerPage);
    }

    /**
     * Converts a hexadecimal color code to an RGB array.
     *
     * @param string $hex
     * hexadecimal color code, e.g. #c0c0c0 or 1a2b3c
     *
     * @return array
     * associative array with separate RGB integer values or null,
     * if the provided hexadecimal code is invalid
     */
    public static function getRgbFromHex($hex)
    {
        if (self::isBlankString($hex))
            return null;

        $hex = \trim($hex);

        if (\substr($hex, 0, 1) == '#')
            $hex = \substr($hex, 1);
        if (\strlen($hex) >= 6) {
            return array(
                'r' => \hexdec(\substr($hex, 0, 2)),
                'g' => \hexdec(\substr($hex, 2, 2)),
                'b' => \hexdec(\substr($hex, 4, 2)),
            );
        }
        return null;
    }

    /**
     * Converts an array of parameters into a URL query string.
     *
     * @param array $parameters
     * associative array of parameters
     *
     * @return string
     * URL query string
     */
    public static function getUrlParameters($parameters)
    {
        if (self::isEmptyArray($parameters))
            return '';

        $query = '';
        foreach ($parameters as $name => $value) {
            if ($query === '')
                $query .= '?';
            else
                $query .= '&';

            $query .= \rawurlencode($name) . '=' . \rawurlencode($value);
        }
        return $query;
    }

    /**
     * Returns a list of preferred languages by the user's browser.
     *
     * @return array
     * list of language codes
     */
    public static function getUserLanguages()
    {
        $languages = array();

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            // break up string into pieces (languages and q factors)
            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

            if (count($lang_parse[1])) {
                // create a list like "en" => 0.8
                $languages = array_combine($lang_parse[1], $lang_parse[4]);

                // set default to 1 for any without q factor
                foreach ($languages as $lang => $val) {
                    if ($val === '') $languages[$lang] = 1;
                }

                // sort list based on value
                arsort($languages, SORT_NUMERIC);
            }
        }

        return \array_keys($languages);
    }

    /**
     * Tests, if a string value is blank.
     *
     * @param string $string
     * the string value to test
     *
     * @return bool
     * true, if the provided value is not a string
     * or it is an empty string
     * or it contains only non-whitespaces
     */
    public static function isBlankString($string)
    {
        return $string === null
            || !\is_string($string)
            || \strlen(\trim($string)) < 1;
    }

    /**
     * Tests, if an array is empty.
     *
     * @param array|null $array
     * the array value to test
     *
     * @return bool
     * true, if the provided value is not an array
     * or it is doesn't contain any elements
     */
    public static function isEmptyArray(&$array)
    {
        return $array === null
            || !\is_array($array)
            || \count($array) < 1;
    }

    /**
     * Tests, if a string value is empty.
     *
     * @param string $string
     * the string value to test
     *
     * @return bool
     * true, if the provided value is not a string
     * or it is an empty string
     */
    public static function isEmptyString($string)
    {
        return $string === null
            || !\is_string($string)
            || \strlen($string) < 1;
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
     * Tests, if the GD extension is available in the PHP environment.
     *
     * @return bool
     * true, if the GD extension is available
     */
    public static function isGdExtensionAvailable()
    {
        return \extension_loaded('gd');
    }

    /**
     * Tests, if a string value is not blank.
     *
     * @param string $string
     * the string value to test
     *
     * @return bool
     * true, if the provided value is a string
     * and contains at least one non-whitespace character
     */
    public static function isNotBlankString($string)
    {
        return !self::isBlankString($string);
    }

    /**
     * Tests, if an array is not empty.
     *
     * @param array|null $array
     * the array value to test
     *
     * @return bool
     * true, if the provided value is an array
     * and contains at least one element
     */
    public static function isNotEmptyArray(&$array)
    {
        return !self::isEmptyArray($array);
    }

    /**
     * Tests, if a string value is not empty.
     *
     * @param string $string
     * the string value to test
     *
     * @return bool
     * true, if the provided value is a string
     * and contains at least one character
     */
    public static function isNotEmptyString($string)
    {
        return !self::isEmptyString($string);
    }

    /**
     * Tests, if a captcha code does not match against a captcha hash.
     *
     * @param string $captchaCode
     * captcha code
     *
     * @param string $captchaHash
     * captcha hash
     *
     * @param int $maxAge
     * maximum age, a captcha code is considered valid (in seconds)
     *
     * @return bool
     * true, if the captcha code matches the hash
     */
    public static function isNotValidCaptcha($captchaCode, $captchaHash, $maxAge = 3600)
    {
        return !self::isValidCaptcha($captchaCode, $captchaHash, $maxAge);
    }

    /**
     * Tests, if an email address is not valid.
     *
     * @param string $email
     * email address to test
     *
     * @return bool
     * true, if the provided email address is valid
     */
    public static function isNotValidEmail($email)
    {
        return !self::isValidEmail($email);
    }

    /**
     * Tests, if a captcha code matches against a captcha hash.
     *
     * @param string $captchaCode
     * captcha code
     *
     * @param string $captchaHash
     * captcha hash
     *
     * @param int $maxAge
     * maximum age, a captcha code is considered valid (in seconds)
     *
     * @return bool
     * true, if the captcha code matches the hash
     */
    public static function isValidCaptcha($captchaCode, $captchaHash, $maxAge = 1800)
    {
        if (self::isBlankString($captchaCode))
            return false;
        if (self::isBlankString($captchaHash))
            return false;

        $now = \time();
        $val = \explode(':', $captchaHash);

        // Hash does not contain two elements.
        if (\count($val) != 2)
            return false;

        // First hash element is not a timestamp.
        if (!\is_numeric($val[0]))
            return false;

        // Timestamp is newer then the current time.
        $stamp = (int)$val[0];
        if ($stamp > $now)
            return false;

        // Timestamp is older then the maximum age.
        $age = $now - $stamp;
        if ($age > $maxAge)
            return false;

        // Compare hash values.
        return $captchaHash === self::getCaptchaHash($captchaCode, $stamp);
    }

    /**
     * Tests, if an email address is valid.
     *
     * @param string $email
     * email address to test
     *
     * @return bool
     * true, if the provided email address is valid
     */
    public static function isValidEmail($email)
    {
        if (self::isBlankString($email))
            return false;

        // convert international hostname
        if (\strpos($email, '@') !== false) {
            $val = \explode('@', $email, 2);

            //$email = $val[0] . '@' . idn_to_ascii( $val[1] );
            try {
                $idn = new Punycode();
                $email = $val[0] . '@' . $idn->encode($val[1]);
            } catch (\Exception $e) {
                $email = $val[0] . '@';
            }
        }

        return \filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Join a path with a file or subfolder name.
     *
     * @param string $path
     * parent path
     *
     * @param array|null $children
     * child elements within the path
     *
     * @return string
     * joined path
     */
    public static function joinPath($path, ...$children)
    {
        if (self::isBlankString($path))
            return null;

        // remove trailing slash in parent path
        $path = \trim($path);
        if (\substr($path, -1) === '/')
            $path = \substr($path, 0, -1);

        if (self::isEmptyArray($children))
            return $path;

        $fragments = array();
        foreach ($children as $child) {

            if ($child === null)
                break;

            // remove leading slashes
            $child = \trim($child);
            if (\substr($child, 0, 1) === '/')
                $child = \substr($child, 1);

            $fragments[] = $child;
        }

        // create path
        return $path . '/' . \implode('/', $fragments);
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

    /**
     * Replace URL's in a text with HTML links.
     *
     * @param string $text
     * text
     *
     * @return string
     * HTML code of the text with replaced links
     */
    public static function replaceLinks($text)
    {
        $replacements = array(
            '#(https?|ftps?):\/\/([&;:=\.\/\-\?\w]+)#i' => '<a href="\1://\2" target="_blank" rel="follow">\2</a>',
            '#(mailto|tel):\/\/([&;:=\.\/\-\?\w]+)#i' => '<a href="\1://\2" rel="nofollow">\2</a>',
        );
        return \preg_replace(
            \array_keys($replacements),
            \array_values($replacements),
            $text
        );
    }

    /**
     * Write number of bytes in readable form.
     *
     * @param int $bytes
     * number of bytes
     *
     * @return string
     * readable number of bytes
     */
    public static function writeBytes($bytes)
    {
        $unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        return round($bytes / pow(1024, ($i = (int)floor(log($bytes, 1024)))), 2) . ' ' . $unit[$i];
    }

    /**
     * Write a certain object attribute.
     *
     * @param array $object
     * object data
     *
     * @param string $field
     * name of the field to show
     *
     * @param array $i18n
     * translations
     *
     * @param string $lang
     * language code
     *
     * @param bool $valueOnly
     * only write the attribute value
     *
     * @return null|string
     * HTML encoded output for the requested field
     */
    public static function writeObjectField(array &$object, $field, array &$i18n, $lang, $valueOnly = false)
    {
        if (!\is_array($object))
            return null;

        // write object type
        if ($field == 'type')
            return (isset($i18n['openestate']['types'][$object['type']])) ?
                \htmlspecialchars($i18n['openestate']['types'][$object['type']]) :
                \htmlspecialchars($object['type']);

        // write object action
        if ($field == 'action')
            return (isset($i18n['openestate']['actions'][$object['action']])) ?
                \htmlspecialchars($i18n['openestate']['actions'][$object['action']]) :
                \htmlspecialchars($object['action']);

        // write object address
        if ($field == 'address')
            return \htmlspecialchars($object['address']['postal'] . ' ' . $object['address']['city']);

        // write object country
        if ($field == 'country')
            return (isset($object['address']['country_name'][$lang])) ?
                \htmlspecialchars($object['address']['country_name'][$lang]) :
                \htmlspecialchars($object['address']['country']);

        // write rent flat rate
        if ($field == 'rent_flat_rate') {
            $value = (isset($object['attributes']['prices']['rent_flat_rate'])) ?
                $object['attributes']['prices']['rent_flat_rate'] : null;
            $interval = (isset($object['attributes']['prices']['rent_flat_rate_per'][$lang])) ?
                $object['attributes']['prices']['rent_flat_rate'][$lang] : null;

            if (self::isBlankString($interval))
                $interval = _('month');

            $title = (isset($i18n['openestate']['attributes']['prices']['rent_flat_rate'])) ?
                $i18n['openestate']['attributes']['prices']['rent_flat_rate'] : 'rent flat rate';
            $text = self::getAttributeValue('prices', 'rent_flat_rate', $value, $i18n, $lang);

            return '<span class="openestate-attribute-label">' . \htmlspecialchars($title) . ':</span>'
                . '<span class="openestate-attribute-value">' . \htmlspecialchars(_('{0} per {1}', $text, $interval)) . '</span>';
        }

        // write primary area
        if ($field == 'area') {
            $types = $object['type_path'];

            if (\array_search('housing_complex', $types) !== false)
                return self::writeObjectField($object, 'measures.residential_area', $i18n, $lang, $valueOnly);

            if (\array_search('retail', $types) !== false)
                return self::writeObjectField($object, 'measures.retail_area', $i18n, $lang, $valueOnly);

            if (\array_search('general_agriculture', $types) !== false)
                return self::writeObjectField($object, 'measures.total_area', $i18n, $lang, $valueOnly);

            if (\array_search('general_commercial', $types) !== false)
                return self::writeObjectField($object, 'measures.commercial_area', $i18n, $lang, $valueOnly);

            if (\array_search('general_piece_of_land', $types) !== false)
                return self::writeObjectField($object, 'measures.plot_area', $i18n, $lang, $valueOnly);

            if (\array_search('general_parking', $types) !== false)
                return self::writeObjectField($object, 'measures.car_parking_area', $i18n, $lang, $valueOnly);

            if (\array_search('general_residence', $types) !== false)
                return self::writeObjectField($object, 'measures.residential_area', $i18n, $lang, $valueOnly);

            return self::writeObjectField($object, 'measures.total_area', $i18n, $lang, $valueOnly);
        }

        // write primary price
        if ($field == 'price') {
            $action = $object['action'];

            if ($action == 'auction')
                return self::writeObjectField($object, 'prices.market_value', $i18n, $lang, $valueOnly);

            if ($action == 'emphyteusis')
                return self::writeObjectField($object, 'prices.lease', $i18n, $lang, $valueOnly);

            if ($action == 'lease')
                return self::writeObjectField($object, 'prices.lease', $i18n, $lang, $valueOnly);

            if ($action == 'purchase')
                return self::writeObjectField($object, 'prices.purchase_price', $i18n, $lang, $valueOnly);

            if ($action == 'rent')
                return self::writeObjectField($object, 'prices.rent_excluding_service_charges', $i18n, $lang, $valueOnly);

            if ($action == 'short_term_rent')
                return self::writeObjectField($object, 'rent_flat_rate', $i18n, $lang, $valueOnly);

            return null;
        }

        // write an object attribute
        if (\strpos($field, '.') !== false) {
            $attribute = \explode('.', $field, 2);
            $value = (isset($object['attributes'][$attribute[0]][$attribute[1]])) ?
                $object['attributes'][$attribute[0]][$attribute[1]] : null;

            if (!\is_array($value)) return null;

            $text = self::getAttributeValue($attribute[0], $attribute[1], $value, $i18n, $lang);
            if ($valueOnly === true)
                return $text;

            $title = (isset($i18n['openestate']['attributes'][$attribute[0]][$attribute[1]])) ?
                $i18n['openestate']['attributes'][$attribute[0]][$attribute[1]] : $attribute[1];

            return '<span class="openestate-attribute-label">' . \htmlspecialchars($title) . ':</span>'
                . '<span class="openestate-attribute-value">' . \htmlspecialchars($text) . '</span>';
        }

        return null;
    }

    /**
     * Write statistics.
     *
     * @param int $buildTime
     * page generation time in milliseconds
     *
     * @return string
     * HTML code with statistics
     */
    public static function writeStatistics($buildTime = 0)
    {
        $output = "version      : " . VERSION;
        $output .= "\nphp version  : " . phpversion();

        if ($buildTime > 0)
            $output .= "\nbuild time   : " . \number_format($buildTime, '3') . ' s';

        $output .= "\nmemory usage : " . self::writeBytes(\memory_get_usage());
        $output .= "\nmemory peak  : " . self::writeBytes(\memory_get_peak_usage());
        return $output;
    }
}
