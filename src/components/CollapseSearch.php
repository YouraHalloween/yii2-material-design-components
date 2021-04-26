<?php

namespace yh\mdc\components;

use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\components\Collapse;
use yh\mdc\components\Button;
use yh\mdc\components\base\Vars;
use yh\mdc\components\base\stdctrls\CustomTextField;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CollapseSearch extends Collapse
{
    protected string $cmpType = ComponentRegister::TYPE_COLLAPSE_SEARCH;

    public string $labelTemplate = CustomTextField::ALIGN_LEFT;
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

    protected static array $clsWrapAlt = [
        'base' => 'mdc-collapse-search'
    ];

    protected static array $clsContentAlt = [
        'wrap' => 'mdc-collapse-search__item',
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
     *      TextField::one(Yii::t('backend/user-filter', 'Пользователь'))->setId('filter-user-name'),
     *      TextField::one(Yii::t('backend/user-filter', 'Email'))->setId('filter-email'),
     *      Select::one(Yii::t('backend/user-filter', 'Статус'))->setId('filter-status'),
     *      CheckBox::one(Yii::t('backend/user-filter', 'Активный'))->setId('filter-active'),
     *      text,
     *      ...
     *    ]
     */

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
            $content .= Html::tag(
                'span',
                \Yii::t('mdc/components/CollapseSearch/HelperSearch', 'Найдено'),
                [
                    'class' => self::$clsSearch['helper-text']
                ]
            );
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
                $content .= Button::one(\Yii::t('mdc/components/CollapseSearch/BtnSearch', 'Найти'))
                ->setProperty([
                    'icon' => 'search',
                    'spinner' => Button::SP_AUTO
                ])
                ->setOptions([
                    'class' => self::$clsSearch['button']
                ])
                ->raised();
            }
            if ($this->showHelperSearch) {
                $content .= $this->getTagHelperSearch();
            }
            $content .= Html::endTag('div');
        }
        return $content;
    }

    protected function renderItemContent($itemContent)
    {
        if (\is_string($itemContent)) {
            return parent::renderItemContent($itemContent);
        } elseif (is_object($itemContent)) {
            $itemContent->labelTemplate = $this->labelTemplate;
            $itemContent->template= $this->cmpTemplate;
            $itemContent->height = $this->cmpHeight;
            return Html::tag('div', $itemContent->render(), ['class' => self::$clsContentAlt['wrap']]);
        }
        return "";
    }
}
