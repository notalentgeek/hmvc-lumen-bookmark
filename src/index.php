<?php

require_once __DIR__ . '/../vendor/autoload.php';

use notalentgeek\Bookmark\Link;

$link = new Link();
var_dump($link->getTagsFromSource());