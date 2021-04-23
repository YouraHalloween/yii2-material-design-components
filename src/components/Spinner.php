<?php

namespace yh\mdc\components;

use yh\mdc\components\base\Component;
use yh\mdc\components\base\stable\ComponentRegister;
use yii\helpers\Html;

class Spinner extends Component
{
    const EXSMALL = 'extra-small';
    const SMALL = 'small';
    const MEDIUM = 'medium';
    const LARGE = 'large';

    protected string $cmpType = ComponentRegister::TYPE_SPINNER;    

    private static array $clsBlock = [
        'base' => 'mdc-spinner',
        'extra-small' => 'mdc-spinner-extra-small',
        'small' => 'mdc-spinner-small',
        'medium' => 'mdc-spinner-medium',
        'large' => 'mdc-spinner-large',
        'show' => 'mdc-spinner--show'
    ];

    public bool $enabled = false;
    /**
     * Размер spinner: extra-small, small, medium, large
     */
    public string $size = self::MEDIUM;

    /**
     * Css классы для контейнера
     */
    public function initOptions(): void
    {
        parent::initOptions();

        $this->options['class'][] = self::$clsBlock['base'];
        $this->options['class'][] = self::$clsBlock[$this->size];
        if ($this->enabled) {
            $this->options['class'][] = self::$clsBlock['show'];
        }        
    }
    
    /**
     * Нарисовать Snackbar
     */
    public function renderComponent(): string
    {
        return Html::tag('span', '', $this->getOptions());
    }
}
