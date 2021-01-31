<?php

namespace yh\mdc\components\base;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yh\mdc\components\base\ControlInput;
use yh\mdc\components\Typography;
use yh\mdc\components\base\ComponentRegister;

class CustomTextField extends ControlInput
{
    const FILLED = 'filled';
    const OUTLINED = 'outlined';
    /*
    * PROPERTY
     */
    /**
     * @var string $helper - если null helper не отоброжать
     */
    public ?string $helper = null;
    /**
     * @var bool $helperPersistent -  Если true, Helper всегда видим, default = true
     */
    public bool $helperPersistent = true;
    /**
     * @var bool $helperValidation - Если true, Helper может использоваться для указания ошибки, default = false
     */
    public bool $helperValidation = true;
    /**
     * @var string $placeHolder - input placeholder
     */
    public string $placeHolder = '';    
    /**
     * @var bool $buttonClear - отоброжать кнопку Отчистить
     */
    public bool $buttonClear = false;
    /**
     * @var string $labelTemplate - Задать расположение label: 'inner', 'vert', 'gor'
     */
    public string $labelTemplate = 'inner';
    /**
     * @var string $labelSize - Задать размер внешнего лейбл
     */
    public string $labelSize = 'large';
    /**
     * @var array $leading
     * ICONS or BUTTONS
     *    trailing, leading => [
     *        'clear',
     *        'visibility' => 'button',
     *        'user' => ['aria-hidden' => true],
     *        'admin' => ['button', 'aria-hidden' => false]
     *    ]
     */
    /**
     * @var string $leading - одна иконка
     * @var array $leading - список иконок с параметрами
     */
    public $leading = [];
    /**
     * @var string $trailing - одна иконка
     * @var array $trailing - список иконок с параметрами
     */
    public $trailing = [];
    
    /**
     * @var string $template - внешний вид textfield FILLED or OUTLINED
     */
    public string $template = self::FILLED;

    /* Класс для блока textfield */
    protected static array $clsBlock = [];
    protected static array $clsLabel = [];

    /* Классы для лейбла
    block - класс для блока, если в inpute есть значение во время инициализации
    label - класс для лейбла, если в inpute есть значение во время инициализации
    no-label - лейбл отсуствует
    */
    protected static array $clsLabelFloating = [];
    /* Классы для анимации линий */
    protected static array $clsRipple = [];
    /*Классы для преикса и суфикса */
    protected static array $clsPrSuRender = [];    
    /* Хелпер может быть в 3 состояниях
    class = "" - хелпер появляется, когда input в фокусе и исчезает, когда input теряет фокусе
    class = "mdc-text-field-helper-text--persistent" - хелпер отображается все время
    class = "mdc-text-field-helper-text--validation-msg" может выводить ошибку
    */
    protected static array $clsHelper = [];
    /**
     *Классы для иконок или кнопок с иконками. А так же группой иконок
     */
    protected static array $clsIcons = [];
    /**
     * Классы для input
     */
    protected static array $clsInput = [];

    protected function initInputOptions(): void
    {
        parent::initInputOptions();

        $this->inputOptions['class'][] = static::$clsInput['base'];
        $this->inputOptions['aria-labelledby'] = $this->getLabelId();

        if (!empty($this->placeHolder)) {
            $this->inputOptions['placeholder'] = $this->placeHolder;
        }

        if ($this->hasHelper()) {
            $this->inputOptions['aria-controls'] = $this->getHelperId();
            $this->inputOptions['aria-describedby'] = $this->getHelperId();
        }
    }

    protected function initOptions(): void
    {
        parent::initOptions();

        $this->options['class'][] = static::$clsBlock['base'];
        $this->options['class'][] = static::$clsBlock[$this->template];
        $this->options['class'][] = $this->getClsLabelFloating('block');

        if (!$this->enabled) {
            $this->options['class'][] = static::$clsBlock['disabled'];
        }

        foreach (['leading', 'trailing'] as $key=>$icon) {
            if ($this->hasIcon($icon)) {
                $this->options['class'][] = static::$clsBlock['icon-'.$icon];
            }
        }        
    }

    /**
     * Class ControlInput
     * Добавить кнопку Clear
     */
    public function setProperty(array $property): CustomTextField
    {
        parent::setProperty($property);
        if ($this->buttonClear) {
            $this->trailing['clear'] = ['button', 'aria-clear' => 'true'];
        }
        return $this;
    }

    /**
     * Tag span ripple
     */
    protected function getTagRipple(string $mode): string
    {
        if ($mode == 'field' && !$this->ripple) {
            return '';
        }
        return Html::tag('span', '', ['class' => static::$clsRipple[$mode]]);
    }

    /**
     * Tag span label
     */
    protected function getTagInnerLabel(): string
    {
        if (!empty($this->label)) {
            $options = [
                'class' => [static::$clsLabel['inner'], $this->getClsLabelFloating('label')],
                'id' => $this->getLabelId()
            ];
            return Html::tag('span', $this->label, $options);
        }
        return '';
    }

    /**
     * Tag Outlined
     */
    protected function getTagOutlined(): string
    {
        $content = Html::beginTag('span', ['class' => static::$clsLabel['outline-notched']]);
        $content .= Html::tag('span', '', ['class' => static::$clsLabel['outline-leading']]);

        if (!empty($this->label)) {
            $content .= Html::beginTag('span', ['class' => static::$clsLabel['outline-notch']]);
            if ($this->labelTemplate == 'inner') {
                $content .= $this->getTagInnerLabel();
            }
            $content .= Html::endTag('span');
        }

        $content .= Html::tag('span', '', ['class' => static::$clsLabel['outline-trailing']]);
        $content .= Html::endTag('span');

        return $content;
    }

    /**
     * Лейбл вертикальный или горизонтальный
     * @param string $mode - 'vert' | 'gor'
     */
    protected function getTagOuterLabel()
    {
        $clsSize = Typography::getLabelSize($this->labelSize);
        $options = [
                'class' => [static::$clsLabel['outer-base'], $clsSize],
                'for' => $this->getId(),
            ];
        return Html::tag('label', $this->label, $options);
    }

    /**
    * Если есть значение у модели, то Пласе холдер будет некрасиво съезжать при рендеринге
    * @param $mode = block - Возвращается класс для блока
    * @param $mode = label - Возвращается класс для PlaceHolder
    */
    protected function getClsLabelFloating(string $mode): string
    {
        if (empty($this->label) || $this->labelTemplate !== 'inner') {
            return static::$clsLabelFloating['no-label'];
        }

        if (empty($this->value) && !$this->autoFocus) {
            return '';
        }

        return static::$clsLabelFloating[$mode];
    }

    /**
     * Ids для лейбла и хелпера
     */
    protected function getIdByName(string $name): string
    {
        return 'id-'.$name.'-'.$this->getName();
    }

    protected function getLabelId(): string
    {
        return $this->getIdByName('label');
    }

    protected function getHelperId(): string
    {
        return $this->getIdByName('hint');
    }

    /**
     * Хелпер установлен в настройках form->field
     */
    protected function hasHelper(): bool
    {
        return !is_null($this->helper);
    }

    protected function getTagHelper(): string
    {
        $options = [
            'id' => $this->getHelperId(),
            'class' => [
                static::$clsHelper['required'],
                $this->helperPersistent ? static::$clsHelper['persistent'] : '',
                $this->helperValidation ? static::$clsHelper['validation'] : '',
            ],
            'aria-hidden' => 'true',
        ];
        return Html::tag('div', $this->helper, $options);
    }

    protected function renderHelper(): string
    {
        $content = '';
        if ($this->hasHelper()) {
            $content = Html::beginTag('div', ['class' => static::$clsHelper['base']]);
            $content .= $this->getTagHelper();
            $content .= Html::endTag('div');
        }
        return $content;
    }

    /**
     * Возвращает тег префикс или суффикс
     * @param string $mode - prefix | suffix
     */
    protected function getTagPrefixSuffix(string $mode): string
    {        
        return '';
    }

    /**
     * Есть иконка?
     * @param string $mode - 'leading', 'trailing'
     */
    protected function hasIcon(string $mode): bool
    {
        return !\is_null($this->$mode) && !empty($this->$mode);
    }

    /**
     * Вернет тег иконка или батон
     */
    private function _getTagIcon(string $mode, string $iconName, array $options= [], bool $button = false): string
    {
        $options['class'] = [
            static::$clsIcons['base'],
            static::$clsIcons[$mode],
        ];
        if ($button) {
            $options['class'][] = static::$clsIcons['button'];
            $options['tabindex'] = '0';
            $options['role'] = 'button';
            $options['id'] = 'text-field-button-'.$iconName;
        }
        return Html::tag('i', $iconName, $options);
    }

    /**
     * Вернет иконку или кнопку с иконкой
     * @param string $mode - 'leading', 'trailing'
     */
    protected function getTagIcons(string $mode): string
    {
        if ($this->hasIcon($mode)) {
            $icons = $this->$mode;
            if (\is_array($icons)) {
                $countIcon = 0;
                $content = '';
                foreach ($icons as $key => $value) {
                    /**
                     *  Если строка массива состоит из leading => [
                     *  'clear', 'user'
                     *  'clear' => 'button' or 'icon',
                     *  'clear' => ['button'],
                     *  'clear' => ['icon', options],
                     *  'clear' => [options],
                     * ]
                     */
                    $options = [];
                    if (\is_array($value)) {
                        $typeIcon = ArrayHelper::getValue($value, 'role', 'icon');
                        $options = $value;
                        $icon = $key;
                    } else {
                        /**
                         * Массив иконок может быть двух видов
                         * ['clear', 'phone']
                         * ['clear' => 'button', 'phone' => 'icon', 'user']
                         * Если $key is int, то имя иконки берем из $value
                         */
                        $icon = \is_int($key) ? $value : $key;
                        $typeIcon = \is_int($key) ? 'icon' : $value;
                    }
                    $content .= $this->_getTagIcon($mode, $icon, $options, $typeIcon === 'button');
                    $countIcon++;
                }
                // Ессли иконок больше одной, то лучше их объединить
                if ($countIcon > 1) {
                    return Html::tag('div', $content, ['class' => static::$clsIcons['group']]);
                }

                return $content;
            }
            return $this->_getTagIcon($mode, $icons);
        }
        return '';
    }

    protected function getComponentFilled(): string
    {
        $content = Html::beginTag('label', $this->getOptions());
    
        $content .= $this->getTagRipple('field');
        $content .= $this->getTagIcons('leading');

        if ($this->labelTemplate == 'inner') {
            $content .= $this->getTagInnerLabel();
        }
        $content .= $this->getTagPrefixSuffix('prefix');
        $content .= $this->getTagInput();
        $content .= $this->getTagPrefixSuffix('suffix');

        $content .= $this->getTagIcons('trailing');

        $content .= $this->getTagRipple('line');
        $content .= Html::endTag('label');

        //Добавить под блок Helper
        $content .= $this->renderHelper();
        //Добавить в начале вертикальный лейбл
        if ($this->labelTemplate != 'inner') {
            $content = $this->getTagOuterLabel() . $content;
        }

        return $content;
    }

    protected function getComponentOutlined(): string
    {
        $content = Html::beginTag('label', $this->getOptions());
            
        $content .= $this->getTagIcons('leading');
        $content .= $this->getTagPrefixSuffix('prefix');
        
        $content .= $this->getTagOutlined();
        
        $content .= $this->getTagInput();

        $content .= $this->getTagPrefixSuffix('suffix');
        $content .= $this->getTagIcons('trailing');
        
        $content .= Html::endTag('label');

        //Добавить под блок Helper
        $content .= $this->renderHelper();
        //Добавить в начале вертикальный лейбл
        if ($this->labelTemplate != 'inner') {
            $content = $this->getTagOuterLabel() . $content;
        }

        return $content;
    }
    /**
     * Class _ComponentInput
     * Вывод темплайта
     */
    public function renderComponent(): string
    {
        return $this->template == self::FILLED ? $this->getComponentFilled() : $this->getComponentOutlined();
    }
}
