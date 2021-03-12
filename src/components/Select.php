<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yh\mdc\components\base\stdctrls\CustomTextField;
use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\components\base\extensions\TrOptions;
use yh\mdc\components\base\extensions\TrList;
use yh\mdc\components\base\extensions\TrParentList;
use yh\mdc\components\Menu;

class Select extends CustomTextField
{
    //TrOptions - устанавливает options['id']
    //TrList - добавляет свойство items
    //TrParentList - добалвяет listProperty, setListProperty
    use TrOptions, TrList, TrParentList;

    protected string $cmpType = ComponentRegister::TYPE_SELECT;

    private ?Menu $menu = null;

    public $fullWidth = true;

    // /**
    //  * @var array $listProperty - Настройки для ListItem
    //  */
    // public array $listProperty = [];
    
    /* Класс для блока textfield */
    protected static array $clsBlock = [
        'base' => 'mdc-select',
        self::FILLED => 'mdc-select--filled',
        self::OUTLINED => 'mdc-select--outlined',
        'disabled' => 'mdc-select--disabled',
        'icon-leading' => 'mdc-select--with-leading-icon',
        'icon-trailing' => 'mdc-select--with-trailing-icon'
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
        'block' => 'mdc-select--label-floating',
        'label' => 'mdc-floating-label--float-above',
        'no-label' => 'mdc-select--no-label',
    ];

    /* Классы для анимации линий */
    protected static array $clsRipple = [
        'filled' => 'mdc-select__ripple',
        'line' => 'mdc-line-ripple',
    ];

    /*Классы для преикса и суфикса */
    protected static array $clsAffix = [
        'base' => 'mdc-select__affix',
        'prefix' => 'mdc-select__affix--prefix',
        'suffix' => 'mdc-select__affix--suffix',
    ];
    
    /* Хелпер может быть в 3 состояниях
    class = "" - хелпер появляется, когда input в фокусе и исчезает, когда input теряет фокусе
    class = "mdc-select-helper-text--persistent" - хелпер отображается все время
    class = "mdc-select-helper-text--validation-msg" может выводить ошибку
    */
    protected static array $clsHelper = [
        'base' => 'mdc-select-helper-line',
        'required' => 'mdc-select-helper-text',
        'persistent' => 'mdc-select-helper-text--persistent',
        'validation' => 'mdc-select-helper-text--validation-msg'
    ];

    /**
     * Классы для иконок или кнопок с иконками. А так же группой иконок
     */
    protected static array $clsIcons = [
        'base' => 'mdc-select__icon',        
        'leading' => 'mdc-select__icon--leading',
        'trailing' => 'mdc-select__icon--trailing',
        'group' => 'mdc-select__group-icon'
    ];

    /**
     * Классы для input
     */
    protected static array $clsInput = [
        'container' => 'mdc-select__selected-text-container',
        'base' => 'mdc-select__selected-text'
    ];

    private static array $clsAnchor = [
        'base' => 'mdc-select__anchor'
    ];

    private static array $clsDropdownIcon = [
        'base' => 'mdc-select__dropdown-icon',
        'container' => 'mdc-select__dropdown-icon-graphic',
        'inactive' => 'mdc-select__dropdown-icon-inactive',
        'active' => 'mdc-select__dropdown-icon-active'
    ];

    private static array $clsMenu = [
        'base' => 'mdc-select__menu',
        'fullWidth' => 'mdc-menu-surface--fullwidth'
    ];

    public function __construct(string $label = '', array $property = [], array $options = [])
    {        
        parent::__construct($label, $property, $options);
        $this->menu = new Menu();
    }

    protected function initInputOptions(): void
    {
        parent::initInputOptions();

        $id = ArrayHelper::remove($this->inputOptions, 'id');

        $this->inputOptions['id'] = $id.'-text';           

        $this->inputOptions['class'][] = static::$clsInput['base'];

        $menuOptions = [
            'class' => [self::$clsMenu['base']]
        ];

        if ($this->fullWidth) {
            $menuOptions['class'][] = self::$clsMenu['fullWidth'];
        }

        $listProperty = [
            'tagList' => 'ul',
            'tagListItem' => 'li',
            'roleItem' => 'option'
        ];

        $listProperty = array_merge($this->listProperty, $listProperty);

        $this->menu
            ->setProperty([
                'items' => $this->items,
                'anchor' => false,
                'roleMenu' => 'listbox'                
            ])
            ->setListProperty($listProperty)
            ->setOptions($menuOptions);        
    }

    private function getOptionsAnchor(): array
    {
        return [
            'class' => self::$clsAnchor['base'],
            'role' => 'button',
            'aria-haspopup' => 'listbox',
            'aria-expanded' => 'false',
            'aria-labelledby' => $this->getLabelId().' '.$this->getId()
        ];
    }

    protected function getTagInput(): string
    {
        $content = Html::beginTag('span', ['class' => self::$clsInput['container']]);
        $content .= Html::tag('span', $this->value, $this->getInputOptions());
        $content .= Html::endTag('span');

        return $content;
    }

    private function getTagDropdownIcon(): string
    {
        $content = Html::beginTag('span', ['class' => self::$clsDropdownIcon['base']]);
        $content .= Html::beginTag('svg', [
            'class' => self::$clsDropdownIcon['container'],
            'viewBox' => '7 10 10 5',
            'focusable' => 'false'
        ]);
        $content .= Html::tag('polygon', '', [
            'class' => self::$clsDropdownIcon['inactive'],
            'stroke' => 'none',
            'fill-rule' => 'evenodd',
            'points' => '7 10 12 15 17 10'
        ]);
        $content .= Html::tag('polygon', '', [
            'class' => self::$clsDropdownIcon['active'],
            'stroke' => 'none',
            'fill-rule' => 'evenodd',
            'points' => '7 15 12 10 17 15'
        ]);
        $content .= Html::endTag('svg');
        $content .= Html::endTag('span');

        return $content;
    }

    protected function getComponentFilled(): string
    {
        //mdc-select
        $content = Html::beginTag('div', $this->getOptions());

        //mdc-anchor
        $content .= Html::beginTag('div', $this->getOptionsAnchor());
    
        $content .= $this->getTagRipple('filled');
        $content .= $this->getTagIcons('leading');

        if ($this->labelTemplate == 'inner') {
            $content .= $this->getTagInnerLabel();
        }
    
        $content .= $this->getTagInput();
        $content .= $this->getTagDropdownIcon();

        $content .= $this->getTagIcons('trailing');
        $content .= $this->getTagRipple('line');
        //mdc-anchor
        $content .= Html::endTag('div');

        $content .= $this->menu->renderComponent();

        //mdc-select
        $content .= Html::endTag('div');

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
        //mdc-select
        $content = Html::beginTag('div', $this->getOptions());

        //mdc-anchor
        $content .= Html::beginTag('div', $this->getOptionsAnchor());
            
        $content .= $this->getTagIcons('leading');
        
        $content .= $this->getTagOutlined();
        
        $content .= $this->getTagInput();
        $content .= $this->getTagDropdownIcon();

        $content .= $this->getTagIcons('trailing');
        
        //mdc-anchor
        $content .= Html::endTag('div');

        $content .= $this->menu->renderComponent();

        //mdc-select
        $content .= Html::endTag('div');

        //Добавить под блок Helper
        $content .= $this->renderHelper();
        //Добавить в начале вертикальный лейбл
        if ($this->labelTemplate != 'inner') {
            $content = $this->getTagOuterLabel() . $content;
        }

        return $content;
    }
}
