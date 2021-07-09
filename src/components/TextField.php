<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yh\mdc\components\Typography;
use yh\mdc\components\ItemIconButton;
use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\components\base\stdctrls\CustomTextField;

class TextField extends CustomTextField
{
    protected string $cmpType = ComponentRegister::TYPE_TEXTFIELD;
    
    /**
     * @var string $prefix - input prefix
     */
    public string $prefix = '';
    /**
     * @var string $suffix - input siffix
     */
    public string $suffix = '';
    /**
     * Обязательно для заполнения
     * @var bool $requried
     */
    public bool $required = false;
    /**
     * @var int $maxLength
     */
    public int $maxLength = -1;
    /**
     * @var int $minLength
     */
    public int $minLength = -1;
    /**
     * @var string $pattern
     */
    public string $pattern = '';
    /**
     * Используются браузерные подсказки
     * @var bool $useNativeMessage 
     */
    public bool $useNativeMessage = false;

    public $formField = false;

    /* Класс для блока textfield */
    protected static array $clsBlock = [
        'base' => 'mdc-text-field',
        self::FILLED => 'mdc-text-field--filled',
        self::OUTLINED => 'mdc-text-field--outlined',
        'disabled' => 'mdc-text-field--disabled',
        'icon-leading' => 'mdc-text-field--with-leading-icon',
        'icon-trailing' => 'mdc-text-field--with-trailing-icon'
    ];
    protected static array $clsLabel = [
        'inner' => 'mdc-floating-label',
        'outer-base' => 'mdc-outer-label',
        'outline-notched' => 'mdc-notched-outline',
        'outline-leading' => 'mdc-notched-outline__leading',
        'outline-notch' => 'mdc-notched-outline__notch',
        'outline-trailing' => 'mdc-notched-outline__trailing',
    ];

    /* Классы для лейбла
    block - класс для блока, если в inpute есть значение во время инициализации
    label - класс для лейбла, если в inpute есть значение во время инициализации
    no-label - лейбл отсуствует
    */
    protected static array $clsLabelFloating = [
        'block' => 'mdc-text-field--label-floating',
        'label' => 'mdc-floating-label--float-above',
        'no-label' => 'mdc-text-field--no-label',
    ];

    /* Классы для анимации линий */
    protected static array $clsRipple = [
        'filled' => 'mdc-text-field__ripple',
        'line' => 'mdc-line-ripple',
    ];

    /*Классы для преикса и суфикса */
    protected static array $clsAffix = [
        'base' => 'mdc-text-field__affix',
        'prefix' => 'mdc-text-field__affix--prefix',
        'suffix' => 'mdc-text-field__affix--suffix',
    ];
    
    /* Хелпер может быть в 3 состояниях
    class = "" - хелпер появляется, когда input в фокусе и исчезает, когда input теряет фокусе
    class = "mdc-text-field-helper-text--persistent" - хелпер отображается все время
    class = "mdc-text-field-helper-text--validation-msg" может выводить ошибку
    */
    protected static array $clsHelper = [
        'base' => 'mdc-text-field-helper-line',
        'required' => 'mdc-text-field-helper-text',
        'persistent' => 'mdc-text-field-helper-text--persistent',
        'validation' => 'mdc-text-field-helper-text--validation-msg'
    ];

    /**
     * Классы для иконок или кнопок с иконками. А так же группой иконок
     */
    protected static array $clsIcons = [
        'base' => 'mdc-text-field__icon',        
        'leading' => 'mdc-text-field__icon--leading',
        'trailing' => 'mdc-text-field__icon--trailing'
    ];

    /**
     * Классы для input
     */
    protected static array $clsInput = [
        'base' => 'mdc-text-field__input',        
    ];

    protected function initInputOptions(): void
    {
        parent::initInputOptions();

        $this->inputOptions['class'][] = 'mdc-text-field__input';
        if ($this->labelTemplate === 'inner') {
            $this->inputOptions['aria-labelledby'] = $this->getLabelId();
        }

        if (!empty($this->placeHolder)) {
            $this->inputOptions['placeholder'] = $this->placeHolder;
        }

        if ($this->hasHelper()) {
            $this->inputOptions['aria-controls'] = $this->getHelperId();
            $this->inputOptions['aria-describedby'] = $this->getHelperId();
        }

        if ($this->required) {
            $this->inputOptions['required'] = true;
            $this->jsProperty['required'] = true;
        }

        if ($this->maxLength > -1) {            
            $this->jsProperty['maxLength'] = $this->maxLength;
        }

        if ($this->minLength > -1) {
            $this->jsProperty['minLength'] = $this->minLength;
        }

        if ($this->pattern !== '') {
            $this->jsProperty['pattern'] = $this->pattern;
        }

        if (!empty($this->helper) && $this->useNativeMessage) {
            $this->jsProperty['helperMessage.useNativeMessage'] = $this->useNativeMessage;
        }
    }

    /**
     * Возвращает тег префикс или суффикс
     * @param string $mode - prefix | suffix
     */
    protected function getTagAffix(string $mode): string
    {
        if (!empty($this->$mode)) {
            return Html::tag('span', $this->$mode, [
                'class' => [
                    self::$clsAffix['base'],
                    self::$clsAffix[$mode]
                ]
            ]);
        }
        return '';
    }    

    protected function getComponentFilled(): string
    {
        $content = Html::beginTag('label', $this->getOptions());
    
        $content .= $this->getTagRipple('filled');
        $content .= $this->icons->render(ItemIconButton::LEADING);        

        if ($this->labelTemplate == 'inner') {
            $content .= $this->getTagInnerLabel();
        }
        $content .= $this->getTagAffix('prefix');
        $content .= $this->getTagInput();
        $content .= $this->getTagAffix('suffix');

        $content .= $this->icons->render(ItemIconButton::TRAILING);        

        $content .= $this->getTagRipple('line');
        $content .= Html::endTag('label');

        return $content;
    }

    protected function getComponentOutlined(): string
    {
        $content = Html::beginTag('label', $this->getOptions());
                    
        $content .= $this->icons->render(ItemIconButton::LEADING);
        $content .= $this->getTagAffix('prefix');
        
        $content .= $this->getTagOutlined();
        
        $content .= $this->getTagInput();

        $content .= $this->getTagAffix('suffix');
        $content .= $this->icons->render(ItemIconButton::TRAILING);        
        
        $content .= Html::endTag('label');

        return $content;
    }
}
