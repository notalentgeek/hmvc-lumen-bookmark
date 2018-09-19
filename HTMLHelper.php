<?php

class HTML
{
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

    static function removeHTMLEntities(string $string)
    {
        return preg_replace('/&#?[a-z0-9]{2,8};/i', '', $string);
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