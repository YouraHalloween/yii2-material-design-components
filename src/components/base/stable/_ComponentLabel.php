<?php

namespace yh\mdc\components\base\stable;

use yh\mdc\components\base\stable\_PersistentCmp;

class _ComponentLabel extends _PersistentCmp
{
    /**
     * @var string $label
     */
    public string $label;
    
    public function __construct(string $label = '', array $property = [], array $options = [])
    {
        $this->label = $label;
        parent::__construct($property, $options);
    }

    public static function one(string $label = '', array $property = [], array $options = [])
    {
        $class = static::class;
        return new $class($label, $property, $options);
    }
}
