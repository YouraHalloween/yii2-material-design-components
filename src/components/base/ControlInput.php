<?php

namespace yh\mdc\components\base;

use yii\helpers\Html;
use yh\mdc\components\base\stable\_ComponentLabel;

class ControlInput extends _ComponentLabel
{
    /**
     * @var array $inputOptions - input options
     */
    protected array $inputOptions = [];
    private bool $hasInitInputOptions = false;

    /**
     * @var string $name - input name
     */
    public string $name = '';
    /**
     * @var string $type - input type
     */
    public string $type = 'text';
 
    /**
     * @var string $value - input value
     * @var bool $value - input checbox, radio value
     */
    public $value;
    /**
     * @var bool $autoFocus - input auto focus
     */
    public bool $autoFocus = false;
    /**
     * @var bool $ripple - ripple animate
     */
    public bool $ripple = true;
    /**
     * @var bool $activeField - For use yii2 ActiveField
     */
    protected bool $activeField = false;
    /**
     * @var bool $formField - use wrap class - mdc-form-field
     */
    public $formField = true;
    /**
     * @var string $templateInput - For use yii2 ActiveField
     */
    protected string $templateInput = '{input}';

    protected function initInputOptions(): void
    {
        if (!is_null($this->id)) {
            $this->inputOptions['id'] = $this->getId();
        }

        if (!$this->enabled) {
            $this->inputOptions['disabled'] = 'disabled';
        }

        if ($this->autoFocus) {
            $this->inputOptions['autofocus'] = true;
        }
    }

    public function setName(string $name): ControlInput
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Если нет Name, то взять Id
     */
    public function getName(): string
    {
        if (empty($this->name)) {
            $this->name = $this->getId();
        }

        return $this->name;
    }

    /**
     * DOM options for input
     * @param array $options - input options
     */
    public function setInputOptions(array $options): ControlInput
    {
        $this->inputOptions = array_merge($this->inputOptions, $options);
        
        return $this;
    }

    public function getInputOptions(): array
    {
        if (!$this->hasInitInputOptions) {
            $this->initInputOptions();
            $this->hasInitInputOptions = true;
        }
        
        return $this->inputOptions;
    }

    protected function getTagLabel(): string
    {
        if (empty($this->label)) {
            return '';
        }
        return Html::label($this->label, $this->getId());
    }

    /**
     * Вернуть либо Template для ActiveField, либо tag <input>
     */
    protected function getTagInput(): string
    {
        if ($this->activeField) {
            return $this->templateInput;
        } else {
            return Html::input($this->type, $this->name, $this->value, $this->getInputOptions());
        }
    }

    /**
     * Возвращает template для ActiveField
     * Если property не задано в function template, то должны быть заданы в конструкторе
     */
    public function template(array $options = []): string
    {
        $this->activeField = true;

        if (count($options) > 0) {
            $this->setOptions($options);
        }

        return $this->render();
    }
}
