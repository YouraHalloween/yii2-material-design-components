<?php

namespace yh\mdc\components\base\stdctrls;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yh\mdc\components\base\Vars;
use yh\mdc\components\IconButton;
use yh\mdc\components\Typography;
use yh\mdc\components\base\ControlInput;

abstract class CustomTextField extends ControlInput
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
     *    trailing, leading =>
     *  Если строка массива состоит из leading => [
     *  'clear', 'user'
     *  'clear' => 'undo-clear'
     *  'clear' => 'button' or 'icon',
     *  'clear' => ['role' => 'icon', ...options],
     *  'clear' => ['toggle' => 'undo-clear', ...options],
     *  'clear' => [...options],
     * ]
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
    protected static array $clsBlock = [];
    protected static array $clsLabel = [];
    public static array $clsFormField = [
        'label-top' => 'mdc-form-field__label--top',
        'label-left' => 'mdc-form-field__label--left',
        'label-inner' => 'mdc-form-field__label--inner',
        'with-label' => 'mdc-form-field__with-label',
    ];

    /* Классы для лейбла
    block - класс для блока, если в inpute есть значение во время инициализации
    label - класс для лейбла, если в inpute есть значение во время инициализации
    no-label - лейбл отсуствует
    */
    protected static array $clsLabelFloating = [];

    /* Классы для анимации линий */
    protected static array $clsRipple = [];

    /*Классы для преикса и суфикса */
    protected static array $clsAffix = [];
    
    /* Хелпер может быть в 3 состояниях
    class = "" - хелпер появляется, когда input в фокусе и исчезает, когда input теряет фокусе
    class = "mdc-text-field-helper-text--persistent" - хелпер отображается все время
    class = "mdc-text-field-helper-text--validation-msg" может выводить ошибку
    */
    protected static array $clsHelper = [];

    /**
     * Классы для иконок или кнопок с иконками. А так же группой иконок
     */
    protected static array $clsIcons = [];

    /**
     * Классы для input
     */
    protected static array $clsInput = [];

    protected function initOptions(): void
    {
        parent::initOptions();

        $this->options['class'][] = static::$clsBlock['base'];
        $this->options['class'][] = static::$clsBlock[$this->template];
        $this->options['class'][] = $this->getClsLabelFloating('block');
        if ($this->height !== Vars::NORMAL) {
            $this->options['class'][] = Vars::cmpHeight($this->height);
        }
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
            $this->trailing['clear'] = 'button';
            $this->jsProperty['trailingIcon.clear'] = true;
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
            if ($this->isLabelInner()) {
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
        if (empty($this->label) || !$this->isLabelInner()) {
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
        $id = null;
        if ($options['role'] === 'button') {
            $options['tabindex'] = '0';
            $id = $this->getId().'-'.$property['icon'];
        };
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
                     *  'clear' => ['toggle' => 'undo-clear', ...options],
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

    abstract protected function getComponentFilled(): string;

    abstract protected function getComponentOutlined(): string;
    
    public function isLabelInner(): bool
    {
        return $this->labelTemplate === self::ALIGN_INNER;
    }

    /**
     * Class _ComponentInput
     * Вывод темплайта
     */
    public function renderComponent(): string
    {
        $content = $this->template === self::FILLED ? $this->getComponentFilled() : $this->getComponentOutlined();

        // Добавить внешний лейбл
        if (!$this->isLabelInner()) {
            $content = $this->getTagOuterLabel() . $content;
            if ($this->labelTemplate === self::ALIGN_LEFT) {
                $content = Html::tag('div', $content, ['class' => self::$clsFormField['with-label']]);
            }
        }

        // Добавить блок Helper
        $content .= $this->renderHelper();

        if (!$this->hasParent()) {
            $content= Html::tag('div', $content, ['class' => self::$clsFormField['label-' . $this->labelTemplate]]);
        }
        
        return $content;
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
