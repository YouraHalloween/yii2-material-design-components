<?php

namespace yh\mdc\components\base;

use yh\mdc\components\base\_Persistent;

trait _TrComponent
{
    protected function initOptions(): void
    {
        parent::initOptions();
        $this->options['id'] = $this->getId();
    }
}

