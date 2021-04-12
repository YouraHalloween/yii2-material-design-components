<?php

namespace yh\mdc\components\base\stdctrls;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yh\mdc\components\base\ControlInput;
use yh\mdc\components\Typography;
use yh\mdc\components\IconButton;
use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\components\base\Vars;

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
     * @var string $height - Высота компонента
     */
    public string $height = Vars::SMALL;
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
     * @var string $textSize - задается размер текста
     */
    public string $textSize = Vars::NORMAL;

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
    
    protected array $labelOptions = [
        'class' => ['mdc-typography--subtitle1']
    ];  

    /* Класс для блока textfield */
    protected static array $clsBlock = [
        // 'base' => 'mdc-text-field',
        // self::FILLED => 'mdc-text-field--filled',
        // self::OUTLINED => 'mdc-text-field--outlined',
        // 'disabled' => 'mdc-text-field--disabled',
        // 'icon-leading' => 'mdc-text-field--with-leading-icon',
        // 'icon-trailing' => 'mdc-text-field--with-trailing-icon'
    ];
    protected static array $clsLabel = [
        // 'inner' => 'mdc-floating-label',
        // 'outer-base' => 'mdc-outer-label',
        // 'outline-notched' => 'mdc-notched-outline',
        // 'outline-leading' => 'mdc-notched-outline__leading',
        // 'outline-notch' => 'mdc-notched-outline__notch',
        // 'outline-trailing' => 'mdc-notched-outline__trailing',
    ];

    /* Классы для лейбла
    block - класс для блока, если в inpute есть значение во время инициализации
    label - класс для лейбла, если в inpute есть значение во время инициализации
    no-label - лейбл отсуствует
    */
    protected static array $clsLabelFloating = [
        // 'block' => 'mdc-text-field--label-floating',
        // 'label' => 'mdc-floating-label--float-above',
        // 'no-label' => 'mdc-text-field--no-label',
    ];

    /* Классы для анимации линий */
    protected static array $clsRipple = [
        // 'filled' => 'mdc-text-field__ripple',
        // 'line' => 'mdc-line-ripple',
    ];

    /*Классы для преикса и суфикса */
    protected static array $clsAffix = [
        // 'base' => 'mdc-text-field__affix',
        // 'prefix' => 'mdc-text-field__affix--prefix',
        // 'suffix' => 'mdc-text-field__affix--suffix',
    ];
    
    /* Хелпер может быть в 3 состояниях
    class = "" - хелпер появляется, когда input в фокусе и исчезает, когда input теряет фокусе
    class = "mdc-text-field-helper-text--persistent" - хелпер отображается все время
    class = "mdc-text-field-helper-text--validation-msg" может выводить ошибку
    */
    protected static array $clsHelper = [
        // 'base' => 'mdc-text-field-helper-line',
        // 'required' => 'mdc-text-field-helper-text',
        // 'persistent' => 'mdc-text-field-helper-text--persistent',
        // 'validation' => 'mdc-text-field-helper-text--validation-msg'
    ];

    /**
     * Классы для иконок или кнопок с иконками. А так же группой иконок
     */
    protected static array $clsIcons = [
        // 'base' => 'mdc-text-field__icon',
        // 'leading' => 'mdc-text-field__icon--leading',
        // 'trailing' => 'mdc-text-field__icon--trailing',
        // 'group' => 'mdc-text-field__group-icon'
    ];

    /**
     * Классы для input
     */
    protected static array $clsInput = [
        // 'base' => 'mdc-text-field__input',
    ];

    protected function initOptions(): void
    {
        parent::initOptions();

        $this->options['class'][] = static::$clsBlock['base'];
        $this->options['class'][] = static::$clsBlock[$this->template];
        $this->options['class'][] = $this->getClsLabelFloating('block');
        $this->options['class'][] = Vars::cmpHeight($this->height);   
        $this->options['class'][] = Typography::fontSize($this->textSize);

        if (!$this->enabled) {
            $this->options['class'][] = static::$clsBlock['disabled'];
        }

        foreach (['leading', 'trailing'] as $key=>$icon) {
            if ($this->hasIcon($icon)) {
                $this->options['class'][] = static::$clsBlock['icon-'.$icon];
            }
        }        
    }

    // protected function initInputOptions(): void
    // {
    //     parent::initInputOptions();        
    // }

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
        if ($mode == 'filled' && !$this->ripple) {
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
            if ($this->labelTemplate == self::ALIGN_INNER) {
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
     */
    protected function getTagOuterLabel()
    {
        $this->labelOptions['class'][] = static::$clsLabel['outer-base'];
        return $this->getTagLabel();
    }

    /**
    * Если есть значение у модели, то Пласе холдер будет некрасиво съезжать при рендеринге
    * @param $mode = block - Возвращается класс для блока
    * @param $mode = label - Возвращается класс для PlaceHolder
    */
    protected function getClsLabelFloating(string $mode): string
    {
        if (empty($this->label) || $this->labelTemplate !== self::ALIGN_INNER) {
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
    private function _getTagIcon(string $position, array $property, array $options= []): string
    {
        $options['class'] = [
            static::$clsIcons['base'],
            static::$clsIcons[$position],
        ];
        if ($options['role'] === 'button') {
            $options['tabindex'] = '0';
            $id = $this->getId().'-'.$property['icon'];
        } else {
            $id = null;
        }
        $property['isButton'] = false;  
         
        return IconButton::one()
            ->setProperty($property)
            ->setOptions($options)
            ->setId($id)
            ->render();
    }

    /**
     * Вернет иконку или кнопку с иконкой
     * @param string $position - 'leading', 'trailing'
     */
    protected function getTagIcons(string $position): string
    {
        if ($this->hasIcon($position)) {
            $icons = $this->$position;
            if (\is_array($icons)) {
                $countIcon = 0;
                $content = '';
                foreach ($icons as $key => $value) {
                    /**
                     *  Если строка массива состоит из leading => [
                     *  'clear', 'user'
                     *  'clear' => 'button' or 'icon',
                     *  'clear' => ['role' => 'icon', ...options],
                     *  'clear' => ['toggle' => 'UndoClear', ...options],
                     *  'clear' => [...options],
                     * ]
                     */
                    $options = [];
                    $property= [];
                    if (\is_array($value)) {
                        $options = $value;
                        $property['icon'] = $key;
                        $toggle = ArrayHelper::remove($options, 'toggle', false);
                        if ($toggle) {
                            $options['role'] = 'button';
                            $property['toggle'] = true;
                            $property['iconOn']= $toggle;
                        } else {
                            $options['role'] = ArrayHelper::getValue($value, 'role', 'icon');
                        }                        
                    } else {
                        /**
                         * Массив иконок может быть двух видов
                         * ['clear', 'phone']
                         * ['clear' => 'button', 'phone' => 'icon', 'user', 'visible' => 'un_visible']
                         * Если $key is int, то имя иконки берем из $value
                         */
                        if (is_int($key)) {
                            $property['icon'] = $value;
                            $options['role'] = 'icon';
                        } else {
                            $property['icon'] = $key;
                            if ($value === 'button' || $value === 'icon') {
                                $options['role'] = $value;                                
                            } else {
                                $options['role'] = 'button';
                                $property['toggle'] = true;
                                $property['iconOn']= $value;
                            }
                        }                                                
                    }
                    $content .= $this->_getTagIcon($position, $property, $options);
                    $countIcon++;
                }
                // Ессли иконок больше одной, то лучше их объединить
                if ($countIcon > 1) {
                    return Html::tag('div', $content, ['class' => static::$clsIcons['group']]);
                }

                return $content;
            }
        }
        return '';
    }

    protected function getComponentFilled(): string
    {
        $content = Html::beginTag('label', $this->getOptions());
    
        $content .= $this->getTagRipple('filled');
        $content .= $this->getTagIcons('leading');

        if ($this->labelTemplate == self::ALIGN_INNER) {
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
        if ($this->labelTemplate != self::ALIGN_INNER) {
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
        if ($this->labelTemplate != self::ALIGN_INNER) {
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
        return $this->template === self::FILLED ? $this->getComponentFilled() : $this->getComponentOutlined();
    }

    public static function filled(string $label = '', array $property = [], array $options = [])
    {
        $property = array_merge($property, ['template' => self::FILLED]);
        return self::one($label, $property, $options);
    }

    public static function outlined(string $label = '', array $property = [], array $options = [])
    {
        $property = array_merge($property, ['template' => self::OUTLINED]);
        return self::one($label, $property, $options);

    }
}
