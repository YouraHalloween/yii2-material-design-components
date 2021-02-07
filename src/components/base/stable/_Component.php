<?php

namespace yh\mdc\components\base\stable;

use yh\mdc\components\base\stable\_Persistent;

class _Component extends _Persistent
{
    public static function one(array $property = [], array $options = [])
    {
        $class = static::class;
        return new $class($property, $options);
    }
}
