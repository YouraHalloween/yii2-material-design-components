<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yh\mdc\components\base\Control;
use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\components\Spinner;

class ButtonBase extends Control
{       
    protected string $cmpType = ComponentRegister::TYPE_BUTTON;

    /**
     * @var string $viewType - Вид кнопки base, submit, raised
     */
    public string $viewType = 'base';
    /**
     * @var string $icon - выводимая иконка
     */
    public string $icon = '';
    /**
     * @var bool $ripple - анимация
     */
    public bool $ripple = true;
    /**
     * @var string $spinner - будет выводиться спиннер. 
     * '' - спинера нет
     * auto - спинер появится, если disabled = true
     * manual - появлением спинера управляет пользователь
     */
    public string $spinner = '';
        
    protected static array $clsBlock = [
        'base' => ''
    ];

    protected static string $clsRipple = '';
    protected static string $clsLabel = '';
    protected static string $clsIcon = '';

    /**
     * Нарисовать кнопку
     * Constructor
     * @param string $label - Название кнопки
     * @param array $options - свойства кнопки
     * @param array $property - свойства класса
     *  - view-type - тип кнопки
     *  - icon - добавить иконку
     *  - ripple - анимация кнопки
     *  - trailing - иконка справа     
     */

    public function initOptions(): void
    {        
        parent::initOptions();

        $this->options['class'][] = static::$clsBlock['base']; 
        if ($this->viewType == 'gray') {
            $this->options['class'][] = static::$clsBlock['raised'];
        }
        if ($this->viewType != 'base') {
            $this->options['class'][] = static::$clsBlock[$this->viewType];
        }        
    }

    protected function getTagiRipple(): string
    {
        return Html::tag('div', '', ['class' => static::$clsRipple]);
    }

    protected function getTagLabel(): string 
    {        
        if (empty($this->label)) return '';

        return Html::tag('span', $this->label, ['class' => static::$clsLabel]);
    }

    protected function getTagIcon(): string 
    {
        if (empty($this->icon)) {
            return '';
        }
        
        return Html::tag('i', $this->icon, [
                'class' => static::$clsIcon,
                'aria-hidden' => 'true'
            ]);
    }

    protected function getTagSpinner(): string 
    {
        if (empty($this->spinner)) {
            return '';
        }

        $spinner = Spinner::one(['size' => Spinner::MEDIUM], ['show' => $this->spinner]);

        return $spinner->renderComponent();
    }

    protected function getContent(): string
    {
        return '';
    }
    
    public function renderComponent(): string
    {   
        $content = $this->getContent();        
        return Html::button($content, $this->getOptions());
    }
}
