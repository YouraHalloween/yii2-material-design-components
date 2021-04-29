<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yh\mdc\components\base\stdctrls\CustomSwitch;
use yh\mdc\components\base\stable\ComponentRegister;

class CheckBox extends CustomSwitch {

    protected string $cmpType = ComponentRegister::TYPE_CHECKBOX;

    public string $type = 'checkbox';

    /**
     * @var bool $indeterminate - third state checkbox
     */
    public bool $indeterminate = false; 
 
    protected static string $clsInput = 'mdc-checkbox__native-control';
    /* Контейнер для чекбокса */
    protected static array $clsBlockInput = [
        'base' => 'mdc-checkbox',
        'disabled' => 'mdc-checkbox--disabled',
    ]; 

    protected static array $clsBackground = [
        'base' => 'mdc-checkbox__background',
        'chechmark' => 'mdc-checkbox__checkmark',
        'checkmark-path' => 'mdc-checkbox__checkmark-path',
        'mixedmark' => 'mdc-checkbox__mixedmark',
    ];

    protected static string $clsRipple = 'mdc-checkbox__ripple';
    
    protected function initInputOptions(): void
    {
        parent::initInputOptions();           

        if ($this->indeterminate) {
            $this->inputOptions['data-indeterminate'] = 'true';
        }
    }

    protected function getTagBackgorund(): string
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
}