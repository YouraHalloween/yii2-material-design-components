<?php

namespace yh\mdc\components;

use yh\mdc\components\ButtonBase;
use yh\mdc\components\base\stable\ComponentRegister;

class Button extends ButtonBase
{
    public bool $trailing = false;

    protected static array $clsBlock = [
        'base' => 'mdc-button',
        'outlined' => 'mdc-button--outlined',
        'raised' => 'mdc-button--raised',
        'unelevated' => 'mdc-button--unelevated',
        'gray' => 'mdc-button--gray'
    ];

    protected static string $clsRipple = 'mdc-button__ripple';
    protected static string $clsLabel = 'mdc-button__label';
    protected static string $clsIcon = 'material-icons mdc-button__icon';

    protected function getContent(): string
    {
        $content = '';
        if ($this->ripple) {
            $content .= $this->getTagiRipple();
        }
        // Иконка Leading
        if (!$this->trailing) {
            $content .= $this->getTagIcon();
        }

        $content .= $this->getTagLabel();

        // Иконка trailing
        if ($this->trailing) {
            $content .= $this->getTagIcon();
        }

        $content.= $this->getTagSpinner();
        
        return $content;
    }

    public function submit(): string
    {
        $this->options['type'] = 'submit';
        $this->cmpType = ComponentRegister::TYPE_SUBMIT;
        return $this->render();
    }

    public function gray(): Button
    {
        $this->viewType = self::VIEW_GRAY;
        return $this;
    }

    public function raised(): Button
    {
        $this->viewType = self::VIEW_RAISED;
        return $this;

    }
}
