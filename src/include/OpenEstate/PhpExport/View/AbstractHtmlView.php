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

namespace OpenEstate\PhpExport\View;

use OpenEstate\PhpExport\Environment;
use OpenEstate\PhpExport\Html\Meta;
use OpenEstate\PhpExport\Html\Stylesheet;
use OpenEstate\PhpExport\Utils;
use OpenEstate\PhpExport\Html\AbstractHeadElement;

/**
 * An abstract HTML document.
 *
 * @author Andreas Rudolph & Walter Wagner
 * @copyright 2009-2018, OpenEstate.org
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
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
     * AbstractHtmlView constructor.
     *
     * @param Environment $env
     * export environment
     */
    function __construct(Environment $env)
    {
        parent::__construct($env);
        $this->addHeader(Meta::newGenerator('OpenEstate-PHP-Export'), -1);

        // include custom.css, if it is available and contains some content
        $customCss = $env->getPath('custom.css');
        if (\is_file($customCss) && \filesize($customCss) > 0)
            $this->addHeader(Stylesheet::newLink(
                'openestate-custom-css',
                $env->getUrl('custom.css', array('v' => \filemtime($customCss)))
            ), 99999);
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
    public function addHeaders(array $elements, $priority = 100)
    {
        if (!\is_array($elements)) {
            return;
        }

        foreach ($elements as $element) {
            $this->addHeader($element, $priority++);
        }
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
     * Generate HTML code for registered header elements.
     *
     * @return string
     * generated HTML code
     */
    public function generateHeader()
    {
        // Sort header elements according to the priority.
        usort($this->headerElements, function ($item1, $item2) {
            $id1 = $item1->id;
            $id2 = $item2->id;

            $priority1 = $this->headerElementsPriority[$id1];
            $priority2 = $this->headerElementsPriority[$id2];

            if ($id1 == $id2) return 0;
            if ($priority1 < $priority2) return -1;
            if ($priority1 > $priority2) return 1;
            return strcmp($id1, $id2);
        });

        // Generate HTML code for header elements.
        $html = '';
        foreach ($this->headerElements as $element) {
            $html .= $element->generate() . "\n";
        }

        return trim($html);
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
     */
    public function isBodyOnly()
    {
        return false;
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
