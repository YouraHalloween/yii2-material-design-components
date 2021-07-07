<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yh\mdc\components\Drawer;
use yh\mdc\components\ListItem;
use yh\mdc\components\base\Vars;
use yh\mdc\components\base\extensions\trWrap;
use yh\mdc\components\base\stable\ComponentRegister;

class LeftAppBar extends ListItem
{
    use trWrap {
        trWrap::initWrapOptions as traitInitWrapOptions;
    }

    protected string $cmpType = ComponentRegister::TYPE_LEFTAPPBAR;

    protected static array $clsWrap = [
        'base' => 'mdc-left-app-bar'        
    ];

    private static string $clsItemAll = 'mdc-left-app-bar-item__all';

    public bool $all = true;

    public string $heightItem = Vars::NORMAL;
    public string $itemTextSize = Vars::NORMAL;

    /**
     * @see trWrap
     */
    public function initWrapOptions(): void
    {
        $this->traitInitWrapOptions();
        $this->wrapOptions['class'][] = self::$clsWrap['base'];
    }

    /**
     * Если All инициализировать класс для пункта меню All
     * @see yh\mdc\components\ListItem
     */
    protected function initItemOptions(array &$item)
    {
        if (!isset($item['value'])) {
            $index = $this->all ? $item['index'] - 1 : $item['index'];
            $item['value'] = $index;
        }

        parent::initItemOptions($item);        

        if ($this->all && $item['index'] == 0) {
            $item['options']['class'][] = self::$clsItemAll;
            $item['ripple'] = false;
        }
    }

    /**
     * @see yh\mdc\components\ListItem
     */
    protected function getTagText(array $item): string
    {
        return '';
    }

    public function attachDrawer(Drawer $drawer)
    {
        $selectedIndex = $drawer->getGroupSelectedIndex();
        $this->setValue($selectedIndex);
        
        return $this;
    }

    /**
     * Если All добавить пункт меню в начало
     * @see yh\mdc\components\ListItem
     */
    public function renderItems(): string
    {
        if ($this->all) {
            array_unshift($this->items, ['icon' => 'more_horiz']);            
        }
        return parent::renderItems();
    }

    public function renderComponent(): string
    {        
        $content = Html::beginTag('div', $this->getWrapOptions());
        $content .= parent::renderComponent();     
        $content .= Html::endTag('div');

        return $content;
    }
}
