<?php

namespace yh\mdc\components;

use yh\mdc\components\base\ComponentRegister;
use yh\mdc\components\base\ControlList;
use yh\mdc\components\ListItem;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Menu extends ControlList
{
    protected string $cmpType = ComponentRegister::TYPE_MENU;

    private static string $clsAnchor = 'mdc-menu-surface--anchor';
    private static string $clsBlock = 'mdc-menu mdc-menu-surface';

    /**
     * @var bool $anchor - Меню будет выводится не по абсолютному значению x,y, а из
     * родительского div
     */
    public bool $anchor = true;
    /**
     * @var array $listProperty - Настройки для ListItem
     */
    public array $listProperty = [];

    /**
     * Css классы для контейнера
     */
    public function initOptions(): void
    {
        parent::initOptions();

        $this->options['class'][] = self::$clsBlock;
    }

    public function setListProperty(array $property): Menu
    {
        $this->listProperty = $property;
        return $this;
    }

    private function renderList(): string
    {
        if (!empty($this->items)) {
            $this->listProperty['items'] = $this->items;
        }
        $list = ListItem::one($this->listProperty, [
            'role' => 'menu',
            'aria-hidden' => 'true',
            'aria-orientation' => 'vertical'
        ]);        
        //идет перебор всех items и заполняется массив values
        $content =  $list->renderComponent();
        
        if (!empty($list->jsProperty)) {
            $this->jsProperty = array_merge($this->jsProperty, $list->jsProperty);
        }
        
        return $content;
    }

    public function renderComponent(): string
    {
        //Нужно заранее собрать все jsProperty, после чего зарегать компонент
        $contentList = $this->renderList();
        
        //Menu begin
        $content = Html::beginTag('div', $this->getOptions());
        $content .= $contentList;
        //Menu end
        $content .= Html::endTag('div');

        if ($this->anchor) {
            $content = Html::tag('div', $content, ['class' => self::$clsAnchor]);
        }

        return $content;
    }
}
