<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yh\mdc\components\base\ControlInput;
use yh\mdc\components\Typography;
use yh\mdc\components\base\ComponentRegister;

class TextField extends ControlInput
{
    protected string $cmpType = ComponentRegister::TYPE_TEXTFIELD;
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
     * @var string $prefix - input prefix
     */
    public string $prefix = '';
    /**
     * @var string $suffix - input siffix
     */
    public string $suffix = '';
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
     * @var string $template - внешний вид textfield
     */
    public string $template = 'filled';

    /* Класс для блока textfield */
    private static array $clsBlock = [
        'base' => 'mdc-text-field',
        'filled' => 'mdc-text-field--filled',
        'outline' => '',
        'disabled' => 'mdc-text-field--disabled',
        'icon-leading' => 'mdc-text-field--with-leading-icon',
        'icon-trailing' => 'mdc-text-field--with-trailing-icon',
        // 'unfilled' => 'mdc-text-field--unfilled'
    ];
    private static array $clsLabel = [
        'inner' => 'mdc-floating-label',
        'outer-base' => 'mdc-outer-label'
    ];

    /* Классы для лейбла
    block - класс для блока, если в inpute есть значение во время инициализации
    label - класс для лейбла, если в inpute есть значение во время инициализации
    no-label - лейбл отсуствует
    */
    private static array $clsLabelFloating = [
        'block' => 'mdc-text-field--label-floating',
        'label' => 'mdc-floating-label--float-above',
        'no-label' => 'mdc-text-field--no-label',
    ];

    /* Классы для анимации линий */
    private static array $clsRipple = [
        'field' => 'mdc-text-field__ripple',
        'line' => 'mdc-line-ripple',
    ];

    /*Классы для преикса и суфикса */
    private static array $clsPrSuRender = [
        'base' => 'mdc-text-field__affix',
        'prefix' => 'mdc-text-field__affix--prefix',
        'suffix' => 'mdc-text-field__affix--suffix',
    ];
    
    /* Хелпер может быть в 3 состояниях
    class = "" - хелпер появляется, когда input в фокусе и исчезает, когда input теряет фокусе
    class = "mdc-text-field-helper-text--persistent" - хелпер отображается все время
    class = "mdc-text-field-helper-text--validation-msg" может выводить ошибку
    */
    private static array $clsHelper = [
        'base' => 'mdc-text-field-helper-line',
        'required' => 'mdc-text-field-helper-text',
        'persistent' => 'mdc-text-field-helper-text--persistent',
        'validation' => 'mdc-text-field-helper-text--validation-msg'
    ];

    /**
     *Классы для иконок или кнопок с иконками. А так же группой иконок
     */
    private static array $clsIcons = [
        'base' => 'material-icons mdc-text-field__icon',
        'button' => 'mdc-icon-button',
        'leading' => 'mdc-text-field__icon--leading',
        'trailing' => 'mdc-text-field__icon--trailing',
        'group' => 'mdc-text-field__group-icon'
    ];

    protected function initInputOptions(): void
    {
        parent::initInputOptions();

        $this->inputOptions['class'][] = 'mdc-text-field__input';
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

        $this->options['class'][] = self::$clsBlock['base'];
        $this->options['class'][] = self::$clsBlock[$this->template];
        $this->options['class'][] = $this->getClsLabelFloating('block');

        if (!$this->enabled) {
            $this->options['class'][] = self::$clsBlock['disabled'];
        }

        foreach (['leading', 'trailing'] as $key=>$icon) {
            if ($this->hasIcon($icon)) {
                $this->options['class'][] = self::$clsBlock['icon-'.$icon];
            }
        }
        // if (!$this->ripple)
        //     $cls[] = self::$clsBlock['unfilled'];
    }

    /**
     * Class ControlInput
     * Добавить кнопку Clear
     */
    public function setProperty(array $property): TextField
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
    private function getTagRipple(string $mode): string
    {
        if ($mode == 'field' && !$this->ripple) {
            return '';
        }
        return Html::tag('span', '', ['class' => self::$clsRipple[$mode]]);
    }

    /**
     * Tag span label
     */
    private function getTagInnerLabel(): string
    {        
        if (!empty($this->label)) {
            $options = [
                'class' => [self::$clsLabel['inner'], $this->getClsLabelFloating('label')],
                'id' => $this->getLabelId()
            ];
            return Html::tag('span', $this->label, $options);
        }
        return '';
    }

    /**
     * Лейбл вертикальный или горизонтальный
     * @param string $mode - 'vert' | 'gor'
     */
    private function getTagOuterLabel()
    {
        $clsSize = Typography::getLabelSize($this->labelSize);
        $options = [
                'class' => [self::$clsLabel['outer-base'], $clsSize],
                'for' => $this->getId(),
            ];
        return Html::tag('label', $this->label, $options);
    }

    /**
    * Если есть значение у модели, то Пласе холдер будет некрасиво съезжать при рендеринге
    * @param $mode = block - Возвращается класс для блока
    * @param $mode = label - Возвращается класс для PlaceHolder
    */
    private function getClsLabelFloating(string $mode): string
    {    
        if (empty($this->label) || $this->labelTemplate !== 'inner') {
            return self::$clsLabelFloating['no-label'];
        }

        if (empty($this->value) && !$this->autoFocus) {
            return '';
        }

        return self::$clsLabelFloating[$mode];
    }

    /**
     * Ids для лейбла и хелпера
     */
    private function getIdByName(string $name): string
    {
        return 'id-'.$name.'-'.$this->getName();
    }

    private function getLabelId(): string
    {
        return $this->getIdByName('label');
    }

    private function getHelperId(): string
    {
        return $this->getIdByName('hint');
    }

    /**
     * Хелпер установлен в настройках form->field
     */
    private function hasHelper(): bool
    {
        return !is_null($this->helper);
    }

    private function getTagHelper(): string
    {
        $options = [
            'id' => $this->getHelperId(),
            'class' => [
                self::$clsHelper['required'],
                $this->helperPersistent ? self::$clsHelper['persistent'] : '',
                $this->helperValidation ? self::$clsHelper['validation'] : '',
            ],
            'aria-hidden' => 'true',
        ];
        return Html::tag('div', $this->helper, $options);
    }

    private function renderHelper(): string
    {
        $content = '';
        if ($this->hasHelper()) {
            $content = Html::beginTag('div', ['class' => self::$clsHelper['base']]);
            $content .= $this->getTagHelper();
            $content .= Html::endTag('div');
        }
        return $content;
    }

    /**
     * Возвращает тег префикс или суффикс
     * @param string $mode - prefix | suffix
     */
    private function getTagPrSu(string $mode): string
    {
        if (!empty($this->$mode)) {
            return Html::tag('span', $this->$mode, [
                'class' => [
                    self::$clsPrSuRender['base'],
                    self::$clsPrSuRender[$mode]
                ]
            ]);
        }
        return '';
    }    

    /**
     * Есть иконка?
     * @param string $mode - 'leading', 'trailing'
     */
    private function hasIcon(string $mode): bool
    {
        return !\is_null($this->$mode) && !empty($this->$mode);
    }

    /**
     * Вернет тег иконка или батон
     */
    private function _getTagIcon(string $mode, string $iconName, array $options= [], bool $button = false): string
    {
        $options['class'] = [
            self::$clsIcons['base'],
            self::$clsIcons[$mode],
        ];
        if ($button) {
            $options['class'][] = self::$clsIcons['button'];
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
    private function getTagIcons(string $mode): string
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
                    return Html::tag('div', $content, ['class' => self::$clsIcons['group']]);
                }

                return $content;
            }
            return $this->_getTagIcon($mode, $icons);
        }
        return '';
    }
    /**
     * Class _ComponentInput
     * Вывод темплайта
     */
    public function renderComponent(): string
    {        
        $content = Html::beginTag('label', $this->getOptions());

        $content .= $this->getTagRipple('field');
        $content .= $this->getTagIcons('leading');

        if ($this->labelTemplate == 'inner') {
            $content .= $this->getTagInnerLabel();
        }
        $content .= $this->getTagPrSu('prefix');
        $content .= $this->getTagInput();
        $content .= $this->getTagPrSu('suffix');

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
}
