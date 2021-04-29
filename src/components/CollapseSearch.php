<?php

namespace yh\mdc\components;

use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\components\Collapse;
use yh\mdc\components\Button;
use yh\mdc\components\base\Vars;
use yh\mdc\components\base\stdctrls\CustomTextField;
use yh\mdc\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CollapseSearch extends Collapse
{
    protected string $cmpType = ComponentRegister::TYPE_COLLAPSE_SEARCH;

    public string $labelTemplate = CustomTextField::ALIGN_TOP;
    public string $cmpTemplate = CustomTextField::OUTLINED;
    public string $cmpHeight = Vars::EXTRA_SMALL;

    /**
     * @var bool $showButtonSearch - Вывести кнопку поиска
     */
    public bool $showButtonSearch = true;
    /**
     * @var bool $showHelperSearch - Вывести панель, накоторой будут показано количество найденных записей
     */
    public bool $showHelperSearch = true;
    /**
     * @var int $countSearch - Количество найденых записей
     */
    public int $countSearch = -1;
    /**
     * @var string $captionSearch - Текст для кнопки поиска
     * Инициализация в конструкторе
     */
    public string $captionSearch = '';
    /**
     * @var string $helperText - Найдено
     * Инициализация в конструкторе
     */
    public string $helperText = '';
    /**
     *
     */
    public $model = null;
    /**
     *
     */
    public $form = null;
    

    protected static array $clsWrapAlt = [
        'base' => 'mdc-collapse-search'
    ];

    protected static array $clsContentAlt = [
        'switch' => 'mdc-collapse-search__i--switch',
    ];

    protected static array $clsSearch = [
        'base' => 'mdc-collapse-search__action',
        'button' => 'mdc-collapse-search__button',
        'helper' => 'mdc-typography--caption mdc-collapse-search__action-helper',
        'helper-text' => 'mdc-collapse-search__action-text',
        'helper-count' => 'mdc-collapse-search__action-count'
    ];
    
    /**
     * @var array $items
     * В items необходимо указать content, компоненты, которые будут использоваться для фильтрации
     * Например:
     * 'content' => [
     *                    [
     *                            'class' => 'TextField',
     *                            'name' => 'username',
     *                            'property' => [
     *                                'label' => Yii::t('backend/user', 'Пользователь:'),
     *                            ]
     *                        ],
     */

    public function __construct(array $property = [], array $options = [])
    {
        parent::__construct($property, $options);
        // Init
        $this->header = \Yii::t('mdc/components/CollapseSearch/header', 'Параметры поиска');
        $this->captionSearch = \Yii::t('mdc/components/CollapseSearch/captionSearch', 'Найти');
        $this->helperText = \Yii::t('mdc/components/CollapseSearch/helperText', 'Найдено');
        //
        $this->form = ActiveForm::begin([
                'id' => 'filterform'
            ]);
    }

    /**
     * Css классы для контейнера
     */
    public function initWrapOptions(): void
    {
        parent::initWrapOptions();

        $this->wrapOptions['class'][] = self::$clsWrapAlt['base'];
    }

    protected function getTagHelperSearch(): string
    {
        if ($this->showHelperSearch) {
            $content = Html::beginTag('div', ['class' => self::$clsSearch['helper']]);
            $content .= Html::tag('span', $this->helperText, ['class' => self::$clsSearch['helper-text']]);
            $countText = $this->countSearch === -1 ? '...' : $this->countSearch;
            $content .= Html::tag('span', $countText, ['class' => self::$clsSearch['helper-count']]);
            $content .= Html::endTag('div');
            return $content;
        }
        return '';
    }

    public function renderHeader(): string
    {
        $content = parent::renderHeader();
        if ($this->showButtonSearch || $this->showHelperSearch) {
            $content .= Html::beginTag('div', ['class' => self::$clsSearch['base']]);
            if ($this->showButtonSearch) {
                $content .= Button::one($this->captionSearch)
                ->setProperty([
                    'icon' => 'search',
                    'spinner' => Button::SP_AUTO
                ])
                ->setOptions([
                    'class' => self::$clsSearch['button']
                ])
                ->setParent($this->form)
                ->raised()
                ->submit();
            }
            if ($this->showHelperSearch) {
                $content .= $this->getTagHelperSearch();
            }
            $content .= Html::endTag('div');
        }
        return $content;
    }

    protected function renderItemComponent($configComponent): string
    {    
        $className = ArrayHelper::remove($configComponent, 'class');
        $name = ArrayHelper::remove($configComponent, 'name', $configComponent['id']);

        $field = $this->form->field($this->model, $name);

        $bufCmpHeight = $this->cmpHeight;
        
        switch ($className) {
            case 'TextField':
                $configComponent['property']['buttonClear'] = true;
                $field->textInput($configComponent);
                break;
            case 'CheckBox':      
                if ($this->labelTemplate == CustomTextField::ALIGN_LEFT) {
                    $configComponent['property']['rtl'] = true;
                }
                if ($bufCmpHeight === Vars::EXTRA_SMALL) {
                    $bufCmpHeight = Vars::SMALL;
                }
                $field->options['class'][] = self::$clsContentAlt['switch'];
                $field->checkbox($configComponent);                                
                break;
            
            default:
                # code...
                break;
        }
        $field->component->labelTemplate = $this->labelTemplate;
        $field->component->template= $this->cmpTemplate;
        $field->component->height = $bufCmpHeight;

        return $field->render();
    }

    protected function renderItemContent($itemContent)
    {
        if (\is_string($itemContent)) {
            return parent::renderItemContent($itemContent);
        } elseif (is_array($itemContent)) {             
            return $this->renderItemComponent($itemContent);
        }
        return "";
    }

    public function renderComponent(): string
    {
        $content = parent::renderComponent();
        ActiveForm::end();
        return $content;
    }
}
