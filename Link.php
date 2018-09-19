<?php

namespace notalentgeek\Link;

require_once __DIR__ . '/vendor/autoload.php';

use DOMDocument;
use GuzzleHttp\Client;

class Link
{
    private $httpClient;

    private $isCompleted;

    private $rawLink;
    private $cleanedLink;

    private $rawSource;
    private $cleanedSource;

    private $tagsFromSource;
    private $inputtedTags;

    function __construct
    (
        string $rawLink='https://www.learnstorybook.com/'
    )
    {
        $this
           ->setHTTPClient(new Client())
           ->setRawLink($rawLink)
           ->setRawSource()
           ->setCleanedSource()
           ->setTagsFromSource($this->getCleanedSource());
    }

    ///// START GETTERS AND SETTERS
    public  function getCleanedSource  () { return $this->cleanedSource;  }
    public  function getTagsFromSource () { return $this->tagsFromSource; }
    private function getCleanedLink    () { return $this->cleanedLink;    }
    private function getHTTPClient     () { return $this->httpClient;     }
    private function getInputtedTags   () { return $this->inputtedTags;   }
    private function getIsCompleted    () { return $this->isCompleted;    }
    private function getRawLink        () { return $this->rawLink;        }
    private function getRawSource      () { return $this->rawSource;      }
    private function setCleanedLink    (string $cleanedLink)  { $this->cleanedLink  = $cleanedLink;  return $this; }
    private function setIsCompleted    (bool   $isCompleted)  { $this->isCompleted  = $isCompleted;  return $this; }
    private function setHTTPClient     (Client $httpClient)   { $this->httpClient   = $httpClient;   return $this; }
    private function setInputtedTags   (array  $inputtedTags) { $this->inputtedTags = $inputtedTags; return $this; }
    private function setRawLink        (string $rawLink)      { $this->rawLink      = $rawLink;      return $this; }
    private function setCleanedSource()
    {
        $this->cleanedSource = $this->getRawSource();
        $this->cleanedSource = HTMLHelper::removeScriptStyleElementsAndTheirInner($this->cleanedSource);
        $this->cleanedSource = HTMLHelper::removeHTMLEntities($this->cleanedSource);
        $this->cleanedSource = strip_tags($this->cleanedSource);
        $this->cleanedSource = StringHelper::removeEverythingButAlphaNumeric($this->cleanedSource);
        $this->cleanedSource = strtolower($this->cleanedSource);

        return $this;
    }
    private function setRawSource()
    {
        $response = $this->getHTTPClient()->request(
            'GET',
            $this->getRawLink()
        );

        if ($response->getStatusCode() === 200)
        {
            $this->rawSource = $response->getBody()
                                        ->getContents();
        }

        return $this;
    }
    private function setTagsFromSource
    (
        string $source 
    )
    {   
        $this->tagsFromSource = explode(" ", $source);

        return $this;
    }
    ///// END GETTERS AND SETTERS
}