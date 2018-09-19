<?php

namespace notalentgeek\Internals;

use DOMDocument;
use notalentgeek\Internals\Helpers\StringHelper;

class HTML
{
    private $rawHTML;
    private $cleanedHTML;

    function __construct(string $rawHTML)
    {
        $this->setRawHTML($rawHTML)
             ->setCleanedHTML();
    }

    ///// START GETTERS AND SETTERS
    private function getRawHTML     () { return $this->rawHTML;     }
    private function setRawHTML     (string $rawHTML) { $this->rawHTML = $rawHTML; return $this; }
    public  function getCleanedHTML () { return $this->cleanedHTML; }

    private function setCleanedHTML()
    {
        $this->cleanedHTML = $this->getRawHTML();
        $this->cleanedHTML = self::removeScriptStyleElementsAndTheirInner($this->cleanedHTML);
        $this->cleanedHTML = self::removeHTMLEntities($this->cleanedHTML);
        $this->cleanedHTML = strip_tags($this->cleanedHTML);
        $this->cleanedHTML = StringHelper::removeEverythingButAlphaNumeric($this->cleanedHTML);
        $this->cleanedHTML = strtolower($this->cleanedHTML);

        return $this;
    }
    ///// END GETTERS AND SETTERS

    static function removeScriptStyleElementsAndTheirInner(string $html)
    {
         $domDocument = new DOMDocument();
        @$domDocument->loadHtml($html);

        self::removeElementAndItsInnerFromDOMDocument(
            'script',
            $domDocument
        );
        self::removeElementAndItsInnerFromDOMDocument(
            'style',
            $domDocument
        );

        return $domDocument->saveHtml();
    }

    static function removeHTMLEntities(string $html)
    {
        return preg_replace('/&#?[a-z0-9]{2,8};/i', '', $html);
    }

    /**
     * Function to remove tags and its contents (inner HTML) from a
     * DOMDocument PHP object
     * 
     * @param string      $htmlTag For example `'script'` or `'style'`
     * @param DOMDocument
     */
    static function removeElementAndItsInnerFromDOMDocument
    (
        string      $htmlTag,
        DOMDocument $domDocument
    )
    {
        $allNodes = $domDocument->getElementsByTagName($htmlTag);
        for ($i = $allNodes->length; --$i >=0;)
        {
            $node = $allNodes->item($i);
            $node->parentNode->removeChild($node);
        }
    }
}