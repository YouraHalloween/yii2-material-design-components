<?php

namespace yh\mdc\components\base;

use yh\mdc\components\base\_Persistent;

class _Component extends _Persistent
{
    public static function one(array $property = [], array $options = [])
    {
        $class = static::class;
        return new $class($property, $options);
    }

    // protected function initOptions(): void
    // {
    //     parent::initOptions();
    //     $this->options['id'] = $this->getId();
    // }
}
