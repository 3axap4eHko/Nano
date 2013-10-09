<?php

class Nano
{
    /**
     * @param string $handler
     * @param string $namespace
     *
     * @return array
     */
    public static function parseHandler($handler, $namespace)
    {
        if (preg_match('#^([a-zA-Z0-9_\\\\]+)\:([a-zA-Z0-9_\\\\]+)\:?([a-zA-Z0-9_]*)$#',$handler, $matches ))
        {
            $handler = ["{$matches[1]}\\{$namespace}\\{$matches[2]}{$namespace}"];
            if (!empty($matches[3]))
            {
                $handler[]= "{$matches[3]}Action";
            }

            return $handler;
        }
        return [];
    }

    public static function toCamelCase($text)
    {
        return preg_replace_callback(
            '/(^|[^a-zA-Z0-9_]+)(\w)/',
            function ($matches) {
                return ucfirst($matches[2]);
            } ,
            $text
        );
    }
}