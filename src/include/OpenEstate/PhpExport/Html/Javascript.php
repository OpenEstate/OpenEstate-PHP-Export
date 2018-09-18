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

namespace OpenEstate\PhpExport\Html;

/**
 * A HTML element for Javascript.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @see http://www.w3schools.com/tags/tag_script.asp Details about the "script" element.
 */
class Javascript extends AbstractHeadElement
{
    /**
     * JavaScript code to include.
     *
     * @var string
     */
    public $content = null;

    /**
     * URL of the JavaScript file to include.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_script_src.asp Details about the "src" attribute.
     */
    public $src = null;

    /**
     * Load JavaScript files asynchronously.
     *
     * @var bool
     * @see http://www.w3schools.com/tags/att_script_async.asp Details about the "async" attribute.
     */
    public $async = false;

    /**
     * Defer execution of loaded JavaScript files.
     *
     * @var bool
     * @see http://www.w3schools.com/tags/att_script_defer.asp Details about the "defer" attribute.
     */
    public $defer = false;

    /**
     * Charset of the included JavaScript file.
     *
     * @var string
     * @see http://www.w3schools.com/tags/att_script_charset.asp Details about the "charset" attribute.
     */
    public $charset = null;

    /**
     * Javascript, that is executed after the external JavaScript file was loaded.
     *
     * @var string
     * @see https://www.w3schools.com/tags/ev_onload.asp Details about the "onload" event.
     */
    public $onload = null;

    /**
     * Javascript constructor.
     *
     * @param string|null $id
     * id attribute
     *
     * @param string|null $class
     * class attribute
     */
    function __construct($id = null, $class = null)
    {
        parent::__construct($id, $class);
    }

    public function generate()
    {
        $element = '<script';

        if (\is_string($this->id))
            $element .= ' id="' . \htmlspecialchars($this->id) . '"';

        // The attribute type="text/javascript" is not required for HTML5.
        // $element .= ' type="text/javascript"';

        if (\is_string($this->src)) {
            if (\is_string($this->src))
                $element .= ' src="' . \htmlspecialchars($this->src) . '"';

            if (\is_string($this->charset))
                $element .= ' charset="' . \htmlspecialchars($this->charset) . '"';

            if (\is_string($this->onload))
                $element .= ' onload="' . \htmlspecialchars($this->onload) . '"';

            if ($this->async === true)
                $element .= ' async';

            if ($this->defer === true)
                $element .= ' defer';

            return $element . "></script>";
        }

        if (\is_string($this->content)) {
            return $element . ">\n"
                //. "//<![CDATA[\n"
                . \trim($this->content) . "\n"
                //. "//]]>\n";
                . "</script>";
        }

        return $element . '></script>';
    }

    /**
     * Create an embedded JavaScript.
     *
     * @param string $id
     * id attribute
     *
     * @param string $content
     * JavaScript code
     *
     * @return Javascript
     * created JavaScript element
     */
    public static function newContent($id, $content)
    {
        $script = new Javascript($id);
        $script->content = $content;
        return $script;
    }

    /**
     * Create an external JavaScript.
     *
     * @param string $id
     * id attribute
     *
     * @param string $src
     * URL of the external JavaScript
     *
     * @param string|null $onload
     * Javascript, that is executed after the external JavaScript file was loaded.
     *
     * @param string|null $charset
     * charset of the external JavaScript
     *
     * @param bool|null $defer
     * defer execution of the external JavaScript
     *
     * @param bool|null $async
     * load external JavaScript asynchronously.
     *
     * @return Javascript
     * created JavaScript element
     */
    public static function newLink($id, $src, $onload = null, $charset = null, $defer = false, $async = false)
    {
        $script = new Javascript($id);
        $script->src = $src;
        $script->onload = $onload;
        $script->charset = $charset;
        $script->defer = (\is_bool($defer)) ? $defer : false;
        $script->async = (\is_bool($async)) ? $async : false;
        return $script;
    }
}
