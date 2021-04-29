<?php

namespace yh\mdc\components\base\stdctrls;

use yii\helpers\Html;
use yh\mdc\components\base\ControlInput;
use yh\mdc\components\base\Vars;

abstract class CustomSwitch extends ControlInput
{
    /**
     * @var bool $value - checked
     */
    public $value = false;
    /**
     * @var bool $rtl - render right to left
     */
    public bool $rtl = false;
    /**
     * @var string $height - Высота компонента
     */
    public string $height = Vars::NORMAL;

    protected static array $clsBlock = [
        'base' => 'mdc-form-field',
        'rtl' => 'mdc-form-field--align-end'
    ];
    protected static string $clsInput = '';
    /* Контейнер для чекбокса */
    protected static array $clsBlockInput = [];

    protected static array $clsBackground = [];

    protected static string $clsRipple = '';

    protected static string $clsLabel = 'mdc-switch__label';
    
    protected function initInputOptions(): void
    {
        parent::initInputOptions();

        $this->inputOptions['class'][] = static::$clsInput;

        if ($this->value) {
            $this->inputOptions['checked'] = 'true';
        }
    }

    protected function initOptions(): void
    {
        parent::initOptions();

        $this->options['class'][] = static::$clsBlockInput['base'];

        if (!$this->enabled) {
            $this->options['class'][] = static::$clsBlockInput['disabled'];
        }
    }

    protected function getTagLabel(): string
    {
        $this->labelOptions['class'][] = static::$clsLabel;

        return parent::getTagLabel();
    }

    abstract protected function getTagBackgorund(): string;

    protected function getTagRipple()
    {
        return $this->ripple ? Html::tag('div', '', ['class' => static::$clsRipple]) : '';
    }
    
    public function renderComponent(): string
    {
        $content = Html::beginTag('div', $this->getOptions());
        $content .= $this->getTagInput();
        $content .= $this->getTagBackgorund();
        $content .= $this->getTagRipple();
        $content .= Html::endTag('div');

        $content .= $this->getTagLabel();

        if ($this->formField) {
            $cls = [static::$clsBlock['base']];

            if ($this->height !== Vars::NORMAL) {
                $cls[] = Vars::cmpHeight($this->height);
            }

            if ($this->rtl) {
                $cls[] = static::$clsBlock['rtl'];
            }
            return Html::tag('div', $content, ['class' => $cls]);
        } else {
            return $content;
        }
    }
}
