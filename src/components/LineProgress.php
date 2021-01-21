<?php

namespace yh\mdc\components;

use yh\mdc\components\base\ComponentRegister;
use yh\mdc\components\base\_Component;
use yii\helpers\Html;

class LineProgress extends _Component
{
    protected string $cmpType = ComponentRegister::TYPE_LINEPROGRESS;

    private static array $clsBlock = [
        'base' => 'mdc-linear-progress',
        'indeterminate' => 'mdc-linear-progress--indeterminate',
        'reversed' => 'mdc-linear-progress--reversed',
        'buffer' => 'mdc-linear-progress--buffer',
        'closed' => 'mdc-linear-progress--closed',
    ];

    private static array $clsBuffer = [
        'base' => 'mdc-linear-progress__buffer',
        'bar' => 'mdc-linear-progress__buffer-bar',
        'dots' => 'mdc-linear-progress__buffer-dots',
    ];

    private static array $clsBar = [
        'base' => 'mdc-linear-progress__bar',
        'primary-bar' => 'mdc-linear-progress__primary-bar',
        'secondary-bar' => 'mdc-linear-progress__secondary-bar',
        'bar-inner' => 'mdc-linear-progress__bar-inner'
    ];

    public $indeterminate = false;
    public $reversed = false;
    public $buffer = false;
    public $closed = true;

    private function addClsBlock(string $property): void
    {
        if ($this->$property) {
            $this->options['class'][] = self::$clsBlock[$property];
        }
    }
    /**
     * Css классы для контейнера
     */
    public function initOptions(): void
    {
        parent::initOptions();
        $this->options['class'][] = self::$clsBlock['base'];
        $this->addClsBlock('indeterminate');
        $this->addClsBlock('reversed');
        $this->addClsBlock('buffer');
        $this->addClsBlock('closed');

        $this->options['role'] = "progressbar";
        //Выводит лейбл, если не загрузился прогресс бар
        $this->options['aria-label'] = $this->label;
    }

    /**
     * Выводит текст сообщения $this->buffer
     */
    private function getTagBuffer(): string
    {
        $content = Html::beginTag('div', ['class' => self::$clsBuffer['base']]);
        $content .= Html::tag('div', '', ['class' => self::$clsBuffer['bar']]);
        if ($this->buffer) {
            $content .= Html::tag('div', '', ['class' => self::$clsBuffer['dots']]);
        }
        $content .= Html::endTag('div');
        return $content;
    }

    /**
     * Выводит прогресс бар
     */
    private function getTagBar(): string
    {
        $barInner = Html::tag('span', '', ['class' => self::$clsBar['bar-inner']]);

        $content = Html::tag('div', $barInner, ['class' => [self::$clsBar['base'], self::$clsBar['primary-bar']]]);
        $content .= Html::tag('div', $barInner, ['class' => [self::$clsBar['base'], self::$clsBar['secondary-bar']]]);

        return $content;
    }

    /**
     * Нарисовать Snackbar
     */
    public function render(): string
    {
        //Регистрация компонента
        parent::render();

        //LineProgress begin
        $content = Html::beginTag('div', $this->options);
        $content .= $this->getTagBuffer();
        $content .= $this->getTagBar();
        //LineProgress end
        $content .= Html::endTag('div');

        return $content;
    }
}
