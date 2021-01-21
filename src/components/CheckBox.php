<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yh\mdc\components\base\ControlInput;
use yh\mdc\components\base\ComponentRegister;

class CheckBox extends ControlInput {

    protected string $cmpType = ComponentRegister::TYPE_CHECKBOX;

    public string $type = 'checkbox';

    //Третье состояние чекбокса
    public bool $indeterminate = false; 
    public $value = false;

    private static string $clsBlock = 'mdc-form-field'; 
    private static string $clsInput = 'mdc-checkbox__native-control';
    /* Контейнер для чекбокса */
    private static array $clsBlockInput = [
        'base' => 'mdc-checkbox',
        'disabled' => 'mdc-checkbox--disabled',
    ]; 

    private static array $clsBackground = [
        'base' => 'mdc-checkbox__background',
        'chechmark' => 'mdc-checkbox__checkmark',
        'checkmark-path' => 'mdc-checkbox__checkmark-path',
        'mixedmark' => 'mdc-checkbox__mixedmark',
    ];

    private static string $clsRipple = 'mdc-checkbox__ripple';
    
    protected function initInputOptions(): void
    {
        parent::initInputOptions();

        $this->inputOptions['class'][] = self::$clsInput;        

        if ($this->indeterminate) {
            $this->inputOptions['data-indeterminate'] = 'true';
        }
        if ($this->value) {
            $this->inputOptions['checked'] = 'true';
        }
    }

    protected function initOptions(): void
    {
        parent::initOptions();

        $this->options['class'][] = self::$clsBlockInput['base'];

        if (!$this->enabled) {
            $this->options['class'][] = self::$clsBlockInput['disabled'];
        }
    }

    private function getTagBackgorund(): string
    {
        $content = Html::beginTag('div', ['class' => self::$clsBackground['base']]);
        $content .= Html::beginTag('svg', [
            'class' => self::$clsBackground['chechmark'],
            'viewBox' => '0 0 24 24'
        ]);
        $content .= Html::tag('path', '', [
            'class' => self::$clsBackground['checkmark-path'],
            'fill' => 'none',
            'd' => 'M1.73,12.91 8.1,19.28 22.79,4.59'
        ]);
        $content .= Html::endTag('svg');
        $content .= Html::tag('div', '', [
            'class' => self::$clsBackground['mixedmark']
        ]);

        $content .= Html::endTag('div');

        return $content;
    }

    private function getTagRipple()
    {
        return $this->ripple ? Html::tag('div', '', ['class' => self::$clsRipple]) : '';
    }
    
    public function renderComponent(): string
    {
        $content = Html::beginTag('div', $this->getOptions());
        $content .= $this->getTagInput();
        $content .= $this->getTagBackgorund();
        $content .= $this->getTagRipple();
        $content .= Html::endTag('div');

        $content .= $this->getTagLabel();

        return Html::tag('div', $content, ['class' => self::$clsBlock]);
    }        
}