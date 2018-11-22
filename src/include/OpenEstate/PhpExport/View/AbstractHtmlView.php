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

namespace OpenEstate\PhpExport\View;

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Html\Meta;
use OpenEstate\PhpExport\Html\Stylesheet;
use OpenEstate\PhpExport\Utils;
use OpenEstate\PhpExport\Html\AbstractHeadElement;
use const OpenEstate\PhpExport\VERSION;
use function OpenEstate\PhpExport\gettext as _;

/**
 * An abstract HTML document.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */
abstract class AbstractHtmlView extends AbstractView
{
    /**
     * Charset of the HTML document.
     *
     * @var string
     */
    private $charset = 'UTF-8';

    /**
     * Title of the HTML document.
     *
     * @var string
     */
    private $title = null;

    /**
     * Array of header elements to include into the document.
     *
     * @var array
     */
    private $headerElements = array();

    /**
     * Container to store the priority of header elements.
     *
     * @var array
     */
    private $headerElementsPriority = array();

    /**
     * Only the HTML body should be printed.
     *
     * @var bool
     */
    private $bodyOnly = false;

    /**
     * AbstractHtmlView constructor.
     *
     * @param Environment $env
     * export environment
     */
    function __construct(Environment $env)
    {
        parent::__construct($env);
        $this->addHeader(Meta::newGenerator('OpenEstate-PHP-Export ' . VERSION), -1);

        // include custom.css, if it is available and contains some content
        $customCss = $env->getCustomCssPath();
        if (\is_file($customCss) && \filesize($customCss) > 0)
            $this->addHeader(Stylesheet::newLink(
                'openestate-custom-css',
                $env->getCustomCssUrl(array('v' => \filemtime($customCss)))
            ), 99998);

        // register rss feed by default
        if ($env->getConfig()->rssFeed === true) {
            $feedUrl = $env->getConfig()->getFeedUrl('rss', $env->getLanguage());
            if (\is_string($feedUrl)) {
                $this->addHeader(\OpenEstate\PhpExport\Html\Link::newRssFeed(
                    'openestate-feed-rss',
                    $feedUrl,
                    \ucfirst(_('current offers'))
                ), 99999);
            }
        }

        // register atom feed by default
        if ($env->getConfig()->atomFeed === true) {
            $feedUrl = $env->getConfig()->getFeedUrl('atom', $env->getLanguage());
            if (\is_string($feedUrl)) {
                $this->addHeader(\OpenEstate\PhpExport\Html\Link::newAtomFeed(
                    'openestate-feed-atom',
                    $feedUrl,
                    \ucfirst(_('current offers'))
                ), 99999);
            }
        }
    }

    /**
     * AbstractHtmlView destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Register a header element.
     *
     * @param AbstractHeadElement $element
     * header element
     *
     * @param int $priority
     * priority of the element
     */
    public function addHeader($element, $priority = 100)
    {
        if ($element instanceof AbstractHeadElement) {
            $this->headerElements[$element->id] = $element;
            $this->headerElementsPriority[$element->id] = (is_int($priority)) ? $priority : 100;
        }
    }

    /**
     * Register multiple header elements.
     *
     * @param array $elements
     * array of header elements
     *
     * @param int $priority
     * priority of the elements
     */
    public function addHeaders($elements, $priority = 100)
    {
        if (!\is_array($elements)) {
            return;
        }

        foreach ($elements as $element) {
            $this->addHeader($element, $priority++);
        }
    }

    /**
     * Generate HTML code for registered header elements.
     *
     * @return string
     * generated HTML code
     */
    public function generateHeader()
    {
        $html = '';
        foreach ($this->getHeaders() as $element) {
            $html .= $element->generate() . "\n";
        }
        return \trim($html);
    }

    public function getContentType()
    {
        return 'text/html; charset=' . $this->getCharset();
    }

    /**
     * Get the charset of the current document.
     *
     * @return string
     * document charset
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Get ordered list of registered header elements.
     *
     * @return array
     * registered header elements
     */
    public function getHeaders()
    {
        $headers = \array_merge(array(), $this->headerElements);

        // Sort header elements according to their priority.
        \usort($headers, function ($item1, $item2) {
            $id1 = $item1->id;
            $id2 = $item2->id;

            $priority1 = $this->headerElementsPriority[$id1];
            $priority2 = $this->headerElementsPriority[$id2];

            if ($id1 == $id2) return 0;
            if ($priority1 < $priority2) return -1;
            if ($priority1 > $priority2) return 1;
            return \strcmp($id1, $id2);
        });

        return $headers;
    }

    /**
     * Get the title of the current document.
     *
     * @return string
     * document title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Determine, if only the HTML body should be printed.
     *
     * @return bool
     * true, if only the HTML body should be printed
     */
    public function isBodyOnly()
    {
        return $this->bodyOnly;
    }

    public function process($sendHeaders = true)
    {
        $html = parent::process($sendHeaders);

        return ($this->env->getConfig()->minimizeHtml === true) ?
            Utils::getMinimizedHtml($html) :
            $html;
    }

    /**
     * Remove a previously registered header element.
     *
     * @param string $elementId
     * ID of the header element to remove
     */
    public function removeHeader($elementId)
    {
        if (isset($this->headerElements[$elementId]))
            unset($this->headerElements[$elementId]);

        if (isset($this->headerElementsPriority[$elementId]))
            unset($this->headerElementsPriority[$elementId]);
    }

    /**
     * Enable or disable output of HTML head elements.
     *
     * @param bool $bodyOnly
     * true, if only the HTML body should be printed
     */
    public function setBodyOnly($bodyOnly)
    {
        $this->bodyOnly = $bodyOnly;
    }

    /**
     * Set the charset of the current document.
     *
     * @param string $charset
     * document charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * Set the title of the current document.
     *
     * @param string $title
     * document title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
