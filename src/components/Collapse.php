<?php

namespace yh\mdc\components;

use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\components\base\ControlList;
use yh\mdc\components\ListItem;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Collapse extends ListItem
{
    protected string $cmpType = ComponentRegister::TYPE_COLLAPSE;

    public array $wrapOptions = [];
    public array $itemContentOptions = [];

    public bool $action = false;

    protected static array $clsWrap = [
        'base' => 'mdc-collapse'
    ];

    protected static array $clsHeader = [
        'item' => 'mdc-list-item__collapse',
        'header' => 'mdc-collapse__header',
        'active' => 'mdc-collapse__header-active'
    ];

    protected static array $clsContent = [
        'base' => 'mdc-collapse__content',
        'open' => 'mdc-collapse__content-open mdc-collapse__content-activated'
    ];

    /**
     * @var string $header - Заголовок списка
     */
    // public string $header = '';

    /**
     * @var array $listProperty - Настройки для ListItem
     */
    // public array $listProperty = [];

    /**
     * @var string $itemIcon - конка для items
     */
    public string $itemIcon = 'unfold_more';
    public string $itemIconActive = 'unfold_less';
    /**
     * @var array $items
     * В items необходимо указать content, компоненты, которые будут использоваться для фильтрации
     * Например:
     * 'content' => [
     *      TextField::one(Yii::t('backend/user-filter', 'Пользователь'))->setId('filter-user-name'),
     *      TextField::one(Yii::t('backend/user-filter', 'Email'))->setId('filter-email'),
     *      Select::one(Yii::t('backend/user-filter', 'Статус'))->setId('filter-status'),
     *      CheckBox::one(Yii::t('backend/user-filter', 'Активный'))->setId('filter-active')
     *    ]
     */

    public function __construct(array $property = [], array $options = [])
    {
        parent::__construct($property, $options);
        self::$clsItem['selected'] = self::$clsHeader['active'];
    }

    /**
     * Css классы для контейнера
     */
    public function initWrapOptions(): void
    {
        $this->wrapOptions['class'][] = self::$clsWrap['base'];
        $this->wrapOptions['class'][] = self::$clsGroup['base'];

        if (!is_null($this->id)) {
            $this->wrapOptions['id'] = $this->getId();
        }
    }

    public function initOptions(): void
    {
        parent::initOptions();
        // Remove id from ListItem
        ArrayHelper::remove($this->options, 'id');
    }

    public function getWrapOptions(): array
    {
        $this->initWrapOptions();
        
        return $this->wrapOptions;
    }

    protected function getItemContentOptions($item): array 
    {
        $options = [
            'class' => [self::$clsContent['base']],
            'id' => $this->getContentId($item['index']),
            'role' => 'tab',
            'aria-hidden' => 'true'
        ];

        if ($this->isSelect($item)) {
            $options['class'][] = self::$clsContent['open'];
        }

        $options = ArrayHelper::merge($options, $this->itemContentOptions);

        return $options;
    }

    protected function getTagItemContent(array $item): string
    {
        $itemContent = $item['content'];

        if (is_array($itemContent)) {
            $itemContent = array_map(function ($item) {
                return "<p class='mdc-typography--body'>$item</p>";
            }, $itemContent);
            $itemContent = \implode('', $itemContent);
        }
        
        $content = Html::beginTag($this->tagItem, $this->getItemContentOptions($item));
        $content .= $itemContent;
        $content .= Html::endTag($this->tagItem);

        return $content;
    }

    public function getItemId($index): string 
    {
        return $this->getId().'-item'.$index;
    }

    public function getContentId($index): string 
    {
        return $this->getId().'-content'.$index;
    }

    protected function initItemOptions(array &$item)
    {
        parent::initItemOptions($item);
        $item['options']['class'][] = self::$clsHeader['item'];
        $item['options']['class'][] = self::$clsHeader['header'];
        $item['options']['role'] = 'tab';
        $item['options']['aria-controls'] = $this->getContentId($item['index']);
        $item['options']['id'] = $this->getItemId($item['index']);

        $isSelect = $this->isSelect($item);

        // установить иконку по умлочанию
        if (!empty($this->itemIcon)) {
            if (!isset($item['icon'])) {
                $item['icon'] = $isSelect ? $this->itemIconActive : $this->itemIcon;
            }
        }

        // Item раскрыт или нет
        // aria-selected проставляется в js
        $item['options']['aria-expanded'] = $isSelect ? 'true' : 'false';
    }

    protected function getTagItem(array $item): string
    {
        $content = parent::getTagItem($item);
        if (isset($item['content'])) {
            $content .= $this->getTagItemContent($item);                        
        }

        return $content;
    }

    public function renderComponent(): string
    {
        $content = Html::beginTag('div', $this->getWrapOptions());
        $content .= parent::renderComponent();
        $content .= Html::endTag('div');

        return $content;
    }
}
