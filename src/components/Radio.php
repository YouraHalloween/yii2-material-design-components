<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yh\mdc\components\base\ControlInput;
use yh\mdc\components\base\ComponentRegister;

class Radio extends ControlInput {

    protected string $cmpType = ComponentRegister::TYPE_RADIO;

    public string $type = 'radio';
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

    protected function initInputOptions(): void
    {
        parent::initInputOptions();

        $this->inputOptions['class'][] = self::$clsInput;

        if ($this->checked) {
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
        $content .= Html::tag('div', '', ['class' => self::$clsBackground['outer']]);
        $content .= Html::tag('div', '', ['class' => self::$clsBackground['inner']]);
        $content .= Html::endTag('div');

        return $content;
    }

    private function getTagRipple(): string
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