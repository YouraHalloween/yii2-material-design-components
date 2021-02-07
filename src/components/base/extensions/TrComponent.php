<?php

namespace yh\mdc\components\base\extensions;

trait TrComponent
{
    protected function initOptions(): void
    {
        parent::initOptions();
        if (!is_null($this->id)) {
            $this->options['id'] = $this->getId();
        }
    }
}

