<?php

namespace yh\mdc;


class Config {
    public static $pathComponent = 'yh\\mdc\\components\\';

    public static function getClassComponent(string $className) 
    {
        return self::$pathComponent.$className;
    }
}