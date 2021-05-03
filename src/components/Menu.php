<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yh\mdc\components\ListItem;
use yh\mdc\components\base\extensions\trWrap;
use yh\mdc\components\base\stable\ComponentRegister;

class Menu extends ListItem
{
    use trWrap {
        trWrap::initWrapOptions as traitInitWrapOptions;
    }

    protected string $cmpType = ComponentRegister::TYPE_MENU;
    
    protected static array $clsWrap = [
        'base' => 'mdc-menu mdc-menu-surface',
        'anchor' => 'mdc-menu-surface--anchor'
    ];

    /**
     * @var bool $anchor - Меню будет выводится не по абсолютному значению x,y, а из
     * родительского div
     */
    public bool $anchor = true;

    public array $options = [
        'role' => 'menu',
        'aria-hidden' => 'true',
        'aria-orientation' => 'vertical'
    ];

    public function initWrapOptions(): void
    {
        // parent initWrapOptions();
        $this->traitInitWrapOptions();
        $this->wrapOptions['class'][] = self::$clsWrap['base'];
    }

    // private function renderList(): string
    // {
    //     $list = ListItem::one($this->listProperty, [
    //         'role' => $this->roleMenu,
            
    //     ]);        
    //     //идет перебор всех items и заполняется массив values
    //     $content =  $list->renderComponent();
        
    //     if (!empty($list->jsProperty)) {
    //         $this->jsProperty = array_merge($this->jsProperty, $list->jsProperty);
    //     }
        
    //     return $content;
    // }

    public function renderComponent(): string
    {
        //Нужно заранее собрать все jsProperty, после чего зарегать компонент
        // $contentList = $this->renderList();
        
        //Menu begin
        $content = Html::beginTag('div', $this->getWrapOptions());
        $content .= parent::renderComponent();
        //Menu end
        $content .= Html::endTag('div');

        if ($this->anchor) {
            $content = Html::tag('div', $content, ['class' => self::$clsWrap['anchor']]);
        }

        return $content;
    }
}
