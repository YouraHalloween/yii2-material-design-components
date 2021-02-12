<?php

namespace yh\mdc\components\base\extensions;

trait TrOne
{
    public static function one(array $property = [], array $options = [])
    {
        $class = static::class;
        return new $class($property, $options);
    }
}
