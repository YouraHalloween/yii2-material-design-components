<?php

namespace yh\mdc\components;

use yh\mdc\components\_Component;
use yh\mdc\components\ComponentRegister;
use yii\helpers\Html;

class Spinner extends _Component
{
    protected string $type = ComponentRegister::TYPE_SPINNER;    

    private static array $clsBlock = [
        'base' => 'mdc-spinner',
        'extra-small' => 'mdc-spinner-extra-small',
        'small' => 'mdc-spinner-small',
        'medium' => 'mdc-spinner-medium',
        'large' => 'mdc-spinner-large',
        'open' => 'mdc-spinner--open'
    ];

    public bool $enabled = false;
    /**
     * Размер spinner: extra-small, small, medium, large
     */
    public string $size = 'medium';

    /**
     * Css классы для контейнера
     */
    public function initClassWrap(): void
    {
        parent::initClassWrap();
        $this->options['class'][] = self::$clsBlock['base'];
        $this->options['class'][] = self::$clsBlock[$this->size];
        if ($this->enabled) {
            $this->options['class'][] = self::$clsBlock['open'];
        }        
    }
    
    /**
     * Нарисовать Snackbar
     */
    public function render(): string
    {
        //Регистрация компонента
        parent::render();
        
        return Html::tag('span', '', $this->options);
    }

    public static function oneAlt($size = 'medium', array $options = [], array $property = []): Spinner
    {        
        $property['size'] = $size;
        return static::one('', $options, $property);
    }
}
