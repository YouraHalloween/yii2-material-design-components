<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yh\mdc\components\_ComponentInput;
use yh\mdc\components\ComponentRegister;

class Radio extends _ComponentInput {

    protected string $type = ComponentRegister::TYPE_RADIO;

    public string $inputType = 'radio';
    public bool $checked = false;

    private static string $clsBlock = 'mdc-form-field';  
    private static string $clsInput = 'mdc-radio__native-control';  
    /* Контейнер для чекбокса */
    private static array $clsBlockInput = [
        'base' => 'mdc-radio',
        'disabled' => 'mdc-radio--disabled',
    ]; 

    private static array $clsBackground = [
        'base' => 'mdc-radio__background',
        'outer' => 'mdc-radio__outer-circle',
        'inner' => 'mdc-radio__inner-circle',
    ];

    private static string $clsRipple = 'mdc-radio__ripple';
    
    /* Вернуть список классов для блока */
    private function getClsRadio(): array
    {
        $cls = [
            self::$clsBlockInput['base'],
        ];

        if (!$this->enabled)
            $cls[] = self::$clsBlockInput['disabled'];

        return $cls;
    }

    private function getTagBackgorund(): string
    {
        $content = Html::beginTag('div', ['class' => self::$clsBackground['base']]);
        $content .= Html::tag('div', '', ['class' => self::$clsBackground['outer']]);
        $content .= Html::tag('div', '', ['class' => self::$clsBackground['inner']]);
        $content .= Html::endTag('div');

        return $content;
    }

    private function getTagRipple(): string
    {
        return $this->ripple ? Html::tag('div', '', ['class' => $this->clsRipple]) : '';
    }

    /**
     * Class _ComponentInput
     */
    public function render(): string
    {
        parent::render();

        $options = ['class' => $this->getClsRadio()];

        $content = Html::beginTag('div', $options);
        $content .= $this->getTagInput();
        $content .= $this->getTagBackgorund();
        $content .= $this->getTagRipple();
        $content .= Html::endTag('div');

        $content .= $this->getTagLabel();

        return Html::tag('div', $content, ['class' => self::$clsBlock]);
    }    
    /**
     * Class _ComponentInput
     */
    public function setInputOptions(array $options): Radio
    {
        parent::setInputOptions($options);
        
        $this->inputOptions['class'][] = self::$clsInput;

        if ($this->checked) {
            $this->inputOptions['checked'] = 'true';
        }
        
        return $this;
    }
}