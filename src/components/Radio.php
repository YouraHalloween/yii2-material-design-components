<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yh\mdc\components\base\stdctrls\CustomSwitch;

use yh\mdc\components\base\stable\ComponentRegister;

class Radio extends CustomSwitch
{
    protected string $cmpType = ComponentRegister::TYPE_RADIO;

    public string $type = 'radio';

    protected static string $clsInput = 'mdc-radio__native-control';
    /* Контейнер для чекбокса */
    protected static array $clsBlockInput = [
        'base' => 'mdc-radio',
        'disabled' => 'mdc-radio--disabled',
    ];

    protected static array $clsBackground = [
        'base' => 'mdc-radio__background',
        'outer' => 'mdc-radio__outer-circle',
        'inner' => 'mdc-radio__inner-circle',
    ];

    protected static string $clsRipple = 'mdc-radio__ripple';

    // protected function initOptions(): void
    // {
    //     parent::initOptions();

    //     $this->options['class'][] = self::$clsBlockInput['base'];

    //     if (!$this->enabled) {
    //         $this->options['class'][] = self::$clsBlockInput['disabled'];
    //     }
    // }

    // protected function getTagLabel(): string
    // {
    //     $this->labelOptions['class'][] = self::$clsLabel;

    //     return parent::getTagLabel();
    // }

    protected function getTagBackgorund(): string
    {
        $content = Html::beginTag('div', ['class' => self::$clsBackground['base']]);
        $content .= Html::tag('div', '', ['class' => self::$clsBackground['outer']]);
        $content .= Html::tag('div', '', ['class' => self::$clsBackground['inner']]);
        $content .= Html::endTag('div');

        return $content;
    }

    // public function renderComponent(): string
    // {
    //     $content = Html::beginTag('div', $this->getOptions());
    //     $content .= $this->getTagInput();
    //     $content .= $this->getTagBackgorund();
    //     $content .= $this->getTagRipple();
    //     $content .= Html::endTag('div');

    //     $content .= $this->getTagLabel();
        
    //     if ($this->formField) {
    //         $cls = [self::$clsBlock['base']];
    //         if ($this->rtl) {
    //             $cls[] = self::$clsBlock['rtl'];
    //         }
    //         return Html::tag('div', $content, ['class' => $cls]);
    //     } else {
    //         return $content;
    //     }
    // }
}
