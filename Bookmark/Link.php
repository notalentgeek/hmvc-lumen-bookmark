<?php

namespace notalentgeek\Bookmark;

use GuzzleHttp\Client;
use notalentgeek\Internals\HTML;

class Link
{
    private $rawLink;
    private $cleanedLink;

    private $isCompleted;

    private $html;

    private $httpClient;

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
           ->setHTML()
           ->setTagsFromSource($this->getHTML()->getCleanedHTML());
    }

    ///// START GETTERS AND SETTERS
    private function getCleanedLink    () { return $this->cleanedLink;    }
    private function getHTML           () { return $this->html;           }
    private function getHTTPClient     () { return $this->httpClient;     }
    private function getInputtedTags   () { return $this->inputtedTags;   }
    private function getIsCompleted    () { return $this->isCompleted;    }
    private function setCleanedLink    (string $cleanedLink)  { $this->cleanedLink  = $cleanedLink;  return $this; }
    private function setHTTPClient     (Client $httpClient)   { $this->httpClient   = $httpClient;   return $this; }
    private function setInputtedTags   (array  $inputtedTags) { $this->inputtedTags = $inputtedTags; return $this; }
    private function setIsCompleted    (bool   $isCompleted)  { $this->isCompleted  = $isCompleted;  return $this; }
    private function setRawLink        (string $rawLink)      { $this->rawLink      = $rawLink;      return $this; }
    public  function getRawLink        () { return $this->rawLink;        }
    public  function getTagsFromSource () { return $this->tagsFromSource; }
    
    private function setHTML()
    {
        $response = $this->getHTTPClient()->request(
            'GET',
            $this->getRawLink()
        );

        if ($response->getStatusCode() === 200)
        {
            $this->html = new HTML($response->getBody()->getContents());
        }

        return $this;
    }
    private function setTagsFromSource(string $source)
    {   
        $this->tagsFromSource = explode(" ", $source);

        return $this;
    }
    ///// END GETTERS AND SETTERS
}