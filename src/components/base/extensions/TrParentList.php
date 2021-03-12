<?php

namespace yh\mdc\components\base\extensions;

trait TrParentList
{
    /**
     * @var array $listProperty - Настройки для ListItem
     */
    public array $listProperty = [];

    public function setListProperty(array $property): Self
    {
        $this->listProperty = $property;
        return $this;
    }
}
