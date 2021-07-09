<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yh\mdc\components\base\Control;
use yh\mdc\components\base\stable\ComponentRegister;

class LinearProgress extends Control
{
    protected string $cmpType = ComponentRegister::TYPE_LINEARPROGRESS;

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

    public string $label = 'Progress Bar';

    /**
     * @var bool $indeterminate Не прирывное движение
     */
    public bool $indeterminate = false;
    /**
     * DEPRECATED
     * @var bool $reversed Обратное движение
     */
    public bool $reversed = false;
    /**
     * @var float $progress [0..1]
     */
    public float $progress = 0;
    /**
     * If equal to -1, then do not output the buffer
     * @var float $bufer [0..1]
     */
    public float $buffer = -1;
    /**
     * @var bool $closed Не показывать progressbar
     */
    public bool $closed = true;

    private function addClsBlock(string $property): void
    {
        if ($this->$property) {
            $this->options['class'][] = self::$clsBlock[$property];
        }
    }

    /**
     * @see _Persistent
     */
    public function setter(string $propertyName, mixed $value): bool
    {
        return !($propertyName === 'label' && empty($value));
    }
    /**
     * @see _PersistentCmp
     */
    public function initOptions(): void
    {
        parent::initOptions();
        $this->options['class'][] = self::$clsBlock['base'];
        $this->addClsBlock('indeterminate');
        $this->addClsBlock('reversed');
        $this->addClsBlock('closed');

        if ($this->buffer > -1) {
            $this->options['class'][] = self::$clsBlock['buffer'];
            $this->jsProperty['buffer'] = $this->buffer;
        }

        if ($this->progress > 0) {
            $this->jsProperty['progress'] = $this->progress;
        }

        $this->options['role'] = "progressbar";

        $this->options['aria-label'] = $this->label;
    }

    /**
     * Выводит текст сообщения $this->buffer
     */
    private function getTagBuffer(): string
    {
        $dots = '';
        $basis = '';
        if ($this->buffer > -1) {
            $dots = Html::tag('div', '', ['class' => self::$clsBuffer['dots']]);
            $buffer = $this->buffer * 100;
            $basis = 'flex-basis: ' . $buffer . '%';
        }
        $content = Html::beginTag('div', ['class' => self::$clsBuffer['base']]);
        $content .= Html::tag('div', '', ['class' => self::$clsBuffer['bar'], 'style' => $basis]);
        $content .= $dots;
        $content .= Html::endTag('div');
        return $content;
    }

    /**
     * Выводит прогресс бар
     */
    private function getTagBar(): string
    {
        $transform = $this->progress > 0 ? "transform: scaleX($this->progress);" : '';

        $barInner = Html::tag('span', '', ['class' => self::$clsBar['bar-inner']]);

        $content = Html::tag('div', $barInner, ['class' => [self::$clsBar['base'], self::$clsBar['primary-bar']], 'style' => $transform]);
        $content .= Html::tag('div', $barInner, ['class' => [self::$clsBar['base'], self::$clsBar['secondary-bar']]]);

        return $content;
    }

    public function renderComponent(): string
    {
        //LinearProgress begin
        $content = Html::beginTag('div', $this->getOptions());
        $content .= $this->getTagBuffer();
        $content .= $this->getTagBar();
        //LinearProgress end
        $content .= Html::endTag('div');

        return $content;
    }
}
