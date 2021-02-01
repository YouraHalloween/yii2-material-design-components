<?php

namespace yh\mdc\components\base\extensions;

trait TrComponent
{
    protected function initOptions(): void
    {
        parent::initOptions();
        $this->options['id'] = $this->getId();
    }
}

