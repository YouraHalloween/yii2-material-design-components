<?php

namespace yh\mdc\components\base\stable;

use yh\mdc\components\base\stable\_PersistentCmp;

class _Component extends _PersistentCmp
{
    public static function one(array $property = [], array $options = [])
    {
        $class = static::class;
        return new $class($property, $options);
    }
}
