<?php

namespace yh\mdc\components;

use yh\mdc\components\ButtonBase;
use yh\mdc\components\base\ComponentRegister;

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

    public function submit(string $viewType = 'gray'): string
    {        
        $this->options['type'] = 'submit';
        $this->cmpType = ComponentRegister::TYPE_SUBMIT;
        $this->viewType = $viewType;        

        return $this->render();
    }

    // public function default(string $label, array $options = [], array $property = []): string
    // {        
    //     return $this->render($label, $property, $options);
    // }

    // public function outlined(string $label, array $options = [], array $property = []): string
    // {
    //     $property['view-type'] = 'outlined';
    //     return $this->render($label, $property, $options);
    // }

    // public function raised(string $label, array $options = [], array $property = []): string
    // {
    //     $property['view-type'] = 'raised';
    //     return $this->render($label, $property, $options);
    // }

    // public function unelevated(string $label, array $options = [], array $property = []): string
    // {
    //     $property['view-type'] = 'raised';
    //     return $this->render($label, $property, $options);
    // }
}
