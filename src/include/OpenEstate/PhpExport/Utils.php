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

use \TrueBV\Punycode;
use function htmlspecialchars as html;

/**
 * Static helper methods.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
class Utils
{
    /**
     * Construct an absolute URL from a base URL and a relative part.
     *
     * @param $relativePart
     * relative part of the URL
     *
     * @param $baseUrl
     * base URL
     *
     * @return string
     * absolute URL
     *
     * @see http://www.gambit.ph/converting-relative-urls-to-absolute-urls-in-php/ Converting Relative URLs to Absolute URLs in PHP
     */
    public static function createAbsoluteUrl($relativePart, $baseUrl)
    {
        // parse base URL
        $scheme = \parse_url($baseUrl, PHP_URL_SCHEME);
        if (strpos($relativePart, "//") === 0) {
            return $scheme . ':' . $relativePart;
        }

        // return if already absolute URL
        if (\parse_url($relativePart, PHP_URL_SCHEME) != '') {
            return $relativePart;
        }

        // queries and anchors
        if ($relativePart[0] == '#' || $relativePart[0] == '?') {
            return $baseUrl . $relativePart;
        }

        // remove non-directory element from path
        $path = \parse_url($baseUrl, PHP_URL_PATH);
        $path = \preg_replace('#/[^/]*$#', '', $path);

        // destroy path if relative url points to root
        if ($relativePart[0] === '/') {
            $path = '';
        }

        // dirty absolute URL
        $host = \parse_url($baseUrl, PHP_URL_HOST);
        $abs = $host . $path . '/' . $relativePart;

        // replace '//' or  '/./' or '/foo/../' with '/'
        $abs = \preg_replace('/(\/\.?\/)/', '/', $abs);
        $abs = \preg_replace('/\/(?!\.\.)[^\/]+\/\.\.\//', '/', $abs);

        // absolute URL is ready!
        return $scheme . '://' . $abs;
    }

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
     * Convert a string into another charset.
     *
     * @param string $input
     * input string
     *
     * @param string $targetCharset
     * target encoding
     *
     * @return string
     * encoded output string
     */
    public static function encode($input, $targetCharset)
    {
        if (!\function_exists('\mb_detect_encoding') || !\function_exists('\iconv'))
            return $input;

        // Detect the encoding of the input string
        $sourceCharset = \mb_detect_encoding($input);
        if ($sourceCharset === false || self::isBlankString($sourceCharset))
            return $input;

        // Convert string to the target encoding, if necessary
        return (\strtoupper(\trim($sourceCharset)) !== \strtoupper(\trim($targetCharset))) ?
            \iconv(\strtoupper(\trim($sourceCharset)), \strtoupper(\trim($targetCharset)) . '//TRANSLIT', $input) :
            $input;
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

        if (\extension_loaded('mbstring')) {
            return (\mb_strlen($value) > ($length - 3)) ?
                \mb_substr($value, 0, $length - 3) . '...' :
                $value;
        } else {
            return (\strlen($value) > ($length - 3)) ?
                \substr($value, 0, $length - 3) . '...' :
                $value;
        }
    }

    /**
     * Convert a relative URL to an absolute URL.
     *
     * @param $url
     * relative URL
     *
     * @return string
     * absolute URL
     */
    public static function getAbsoluteUrl($url)
    {
        // URL is already absolute
        $scheme = \parse_url($url, PHP_URL_SCHEME);
        if ($scheme !== false && self::isNotBlankString($scheme)) {
            return $url;
        }

        // create base URL
        $baseUrl = ($_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';
        $baseUrl .= $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
        //die('getAbsoluteUrl / ' . $url . ' / ' . $baseUrl . ' = ' . self::createAbsoluteUrl($url, $baseUrl));
        return self::createAbsoluteUrl($url, $baseUrl);
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
     * @param string $lang
     * language code
     *
     * @return string
     * readable output of the attribute value
     */
    public static function getAttributeValue($group, $attribute, array $value, $lang)
    {
        if (!\is_array($value) || !isset($value['value']))
            return null;

        $txt = null;

        // ggf. den Text "ab sofort" ausgeben,
        // wenn Beginn der Verfügbarkeit in der Vergangenheit liegt
        if ($txt === null && $group == 'administration' && $attribute == 'availability_begin_date') {
            $stamp = (isset($value['value'])) ? $value['value'] : null;
            if (\is_numeric($stamp) && $stamp <= time())
                return gettext('from now on');
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
     * Get list of default filter objects.
     *
     * @param string $basePath
     * absolute path, that points to the root of the export environment
     *
     * @return array
     * list of filter objects
     */
    public static function getDefaultFilterObjects($basePath)
    {
        $objects = array();

        $dir = self::joinPath($basePath, 'include', 'OpenEstate', 'PhpExport', 'Filter');
        if (!\is_dir($dir))
            return $objects;

        foreach (self::listDirectory($dir) as $file) {
            if ($file == 'AbstractFilter.php')
                continue;
            if (!\is_file(self::joinPath($dir, $file)))
                continue;

            $name = \explode('.', $file);
            if (\count($name) != 2 || \strtolower($name[1]) != 'php')
                continue;

            $class = '\\OpenEstate\\PhpExport\\Filter\\' . $name[0];
            $objects[] = new $class();
        }

        return $objects;
    }

    /**
     * Get list of default order objects.
     *
     * @param string $basePath
     * absolute path, that points to the root of the export environment
     *
     * @return array
     * list of order objects
     */
    public static function getDefaultOrderObjects($basePath)
    {
        $objects = array();

        $dir = self::joinPath($basePath, 'include', 'OpenEstate', 'PhpExport', 'Order');
        if (!\is_dir($dir))
            return $objects;

        foreach (self::listDirectory($dir) as $file) {
            if ($file == 'AbstractOrder.php')
                continue;
            if (!\is_file(self::joinPath($dir, $file)))
                continue;

            $name = \explode('.', $file);
            if (\count($name) != 2 || \strtolower($name[1]) != 'php')
                continue;

            $class = '\\OpenEstate\\PhpExport\\Order\\' . $name[0];
            $objects[] = new $class();
        }

        return $objects;
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
     * Sets http response headers "last-modified" and "etag"
     *
     * This function also checks if the client cached version of the requested site
     * is still up to date by comparing last-modified and/or etag headers of server
     * and client (in stict mode both have to match) for a given last modification
     * timestamp and identifier (optional). If this client cached version is up to
     * date the status header 304 (not modified) will be set and the program will be
     * terminated.
     *
     * @author Ansas Meyer
     * @param int $timestamp late modification timestamp
     * @param string $identifier additional identifier (optional, default: "")
     * @param bool $strict use strict mode (optional, default: false)
     * @return bool true if headers could be set, otherwise false
     * @see https://ansas-meyer.de/programmierung/php/http-response-header-last-modified-und-etag-mit-php-fuer-caching-setzen/
     */
    function lastModified($timestamp, $identifier = "", $strict = false)
    {
        // check: are we still allowed to send headers?
        if (\headers_sent()) {
            return false;
        }

        // get: header values from client request
        $client_etag = !empty($_SERVER['HTTP_IF_NONE_MATCH']) ?
            \trim($_SERVER['HTTP_IF_NONE_MATCH']) :
            null;
        $client_last_modified = !empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) ?
            \trim($_SERVER['HTTP_IF_MODIFIED_SINCE']) :
            null;
        $client_accept_encoding = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ?
            $_SERVER['HTTP_ACCEPT_ENCODING'] :
            '';

        /**
         * Notes
         *
         * HTTP requires that the ETags for different responses associated with the
         * same URI are different (this is the case in compressed vs. non-compressed
         * results) to help caches and other receivers disambiguate them.
         *
         * Further we cannot trust the client to always enclose the ETag in normal
         * quotation marks (") so we create a "raw" server sided ETag and only
         * compare if our ETag is found in the ETag sent from the client
         */

        // calculate: current/new header values
        $server_last_modified = \gmdate('D, d M Y H:i:s', $timestamp) . ' GMT';
        $server_etag_raw = \md5($timestamp . $client_accept_encoding . $identifier);
        $server_etag = '"' . $server_etag_raw . '"';

        // calculate: do client and server tags match?
        $matching_last_modified = $client_last_modified == $server_last_modified;
        $matching_etag = $client_etag && \strpos($client_etag, $server_etag_raw) !== false;

        // set: new headers for cache recognition
        \header('Last-Modified: ' . $server_last_modified);
        \header('ETag: ' . $server_etag);

        // check: are client and server headers identical ("no changes")?
        if (($client_last_modified && $client_etag) || $strict ?
            $matching_last_modified && $matching_etag :
            $matching_last_modified || $matching_etag) {
            \header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
            exit(304);
        }

        return true;
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
     * Print an error exception.
     *
     * @param \Exception $exception
     * exception to print
     */
    public static function printErrorException($exception)
    {
        self::printErrorMessage($exception->getMessage());
        echo '<pre>' . html($exception) . '</pre>';
    }

    /**
     * Print an error message.
     *
     * @param string $message
     * error message to print
     */
    public static function printErrorMessage($message)
    {
        echo '<h1>An internal error occurred!</h1>';
        echo '<p>' . html($message) . '</p>';
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
                html($i18n['openestate']['types'][$object['type']]) :
                html($object['type']);

        // write object action
        if ($field == 'action')
            return (isset($i18n['openestate']['actions'][$object['action']])) ?
                html($i18n['openestate']['actions'][$object['action']]) :
                html($object['action']);

        // write object address
        if ($field == 'address')
            return html($object['address']['postal'] . ' ' . $object['address']['city']);

        // write object country
        if ($field == 'country')
            return (isset($object['address']['country_name'][$lang])) ?
                html($object['address']['country_name'][$lang]) :
                html($object['address']['country']);

        // write rent flat rate
        if ($field == 'rent_flat_rate') {
            $value = (isset($object['attributes']['prices']['rent_flat_rate'])) ?
                $object['attributes']['prices']['rent_flat_rate'] : null;
            $interval = (isset($object['attributes']['prices']['rent_flat_rate_per'][$lang])) ?
                $object['attributes']['prices']['rent_flat_rate_per'][$lang] : null;

            if (self::isBlankString($interval))
                $interval = gettext('month');

            $title = (isset($i18n['openestate']['attributes']['prices']['rent_flat_rate'])) ?
                $i18n['openestate']['attributes']['prices']['rent_flat_rate'] : 'rent flat rate';
            $text = self::getAttributeValue('prices', 'rent_flat_rate', $value, $lang);

            return '<span class="openestate-attribute-label">' . html($title) . ':</span>'
                . '<span class="openestate-attribute-value">' . html(gettext('{0} per {1}', $text, $interval)) . '</span>';
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

            $text = self::getAttributeValue($attribute[0], $attribute[1], $value, $lang);
            if ($valueOnly === true)
                return $text;

            $title = (isset($i18n['openestate']['attributes'][$attribute[0]][$attribute[1]])) ?
                $i18n['openestate']['attributes'][$attribute[0]][$attribute[1]] : $attribute[1];

            return '<span class="openestate-attribute-label">' . html($title) . ':</span>'
                . '<span class="openestate-attribute-value">' . html($text) . '</span>';
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
