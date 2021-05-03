<?php

namespace yh\mdc\components\base\extensions;

use yii\helpers\ArrayHelper;

trait TrWrap
{
    public array $wrapOptions = [];
    /**
     * Css классы для контейнера
     */
    public function initWrapOptions(): void
    {
        if (!is_null($this->id)) {
            $this->wrapOptions['id'] = $this->getId();
        }
    }

    public function getWrapOptions(): array
    {
        $this->initWrapOptions();
        return $this->wrapOptions;
    }

    public function setWrapOptions(array $options): self
    {
        $this->wrapOptions = array_merge($this->wrapOptions, $options);
        
        return $this;
    }

    public function initOptions(): void
    {
        parent::initOptions();
        // Remove id from ListItem
        ArrayHelper::remove($this->options, 'id');
    }
}
