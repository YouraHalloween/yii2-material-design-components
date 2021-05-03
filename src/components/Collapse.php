<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yh\mdc\components\ListItem;
use yh\mdc\components\Typography;
use yh\mdc\components\base\extensions\trWrap;
use yh\mdc\components\base\stable\ComponentRegister;

class Collapse extends ListItem
{
    use trWrap {
        trWrap::initWrapOptions as traitInitWrapOptions;
    }

    protected string $cmpType = ComponentRegister::TYPE_COLLAPSE;

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
     * @var string $itemIcon - конка для items
     */
    public string $itemIcon = 'unfold_more';
    public string $itemIconActive = 'unfold_less';
    /**
     * @var array $items
     * В items необходимо указать content, компоненты, которые будут использоваться для фильтрации
     * Например:
     * 'content' => [
     *      text,
     *      text,
     *      ...
     *    ]
     */

    public function __construct(array $property = [], array $options = [])
    {
        parent::__construct($property, $options);
        $this->clsItem['selected'] = self::$clsHeader['active'];
    }

    public function initWrapOptions(): void
    {
        // parent initWrapOptions();
        $this->traitInitWrapOptions();
        $this->wrapOptions['class'][] = self::$clsWrap['base'];
        $this->wrapOptions['class'][] = self::$clsGroup['base'];
    }

    public function initOptions(): void
    {
        parent::initOptions();
        // Remove id from ListItem
        ArrayHelper::remove($this->options, 'id');
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

    protected function renderItemContent($itemContent)
    {
        return Html::tag('p', $itemContent, ['class' => Typography::body()]);        
    }

    protected function getTagItemContent(array $item): string
    {
        $itemContent = $item['content'];

        if (is_array($itemContent)) {
            $itemContent = array_map(function ($i) {
                return $this->renderItemContent($i);
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
