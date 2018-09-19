<?php

class StringHelper
{
    static function removeEverythingButAlphaNumeric(string $string)
    {
        return preg_replace('/[^0-9a-zA-Z ]/', '', $string);
    }
}