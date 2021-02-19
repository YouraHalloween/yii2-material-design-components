<?php

namespace yh\mdc\components;

use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\components\base\ControlList;
use yh\mdc\components\Drawer;
use yii\helpers\Html;

class LeftAppBar extends ControlList
{
    protected string $cmpType = ComponentRegister::TYPE_LEFTAPPBAR;
    
    private static string $clsBlock = 'mdc-left-app-bar';
    private static string $clsContent = 'mdc-left-app-bar-content';

    private static array $clsList = [
        'base' => 'mdc-left-app-bar-list',
        'item' => 'mdc-left-app-bar-item',
        'label' => 'mdc-left-app-bar-item__label',
        'icon' => 'material-icons mdc-left-app-bar-item__icon',
        'all' => 'mdc-left-app-bar-item__all',
        'selected' => 'active'
    ];
    
    /**
     * Css классы для контейнера
     */
    public function initOptions(): void
    {
        parent::initOptions();
        $this->options['class'][] = self::$clsBlock;
    }

    private function getTagIem(array $item): string
    {
        $options = ['class'=> [self::$clsList['item']]];
        if (isset($item['all'])) {
            $options['class'][] = self::$clsList['all'];
            $item['menu-index'] = '--visible-all';
        }
        $content = Html::beginTag('li', $options);

        $text = Html::tag('i', $item['icon'], ['class' => self::$clsList['icon']]);
        
        $options = [
            'title' => $item['title'],
            'class' => [self::$clsList['label']]
        ];        
        $options['menu-index'] = isset($item['menu-index']) ? $item['menu-index'] : $item['icon'];
        if (isset($item['selected']) && $item['selected']) {
            $options['class'][] = self::$clsList['selected'];
        }

        $content .= Html::a($text, '#', $options);
        $content .= Html::endTag('li');

        return $content;
    }

    private function renderItems(): string
    {
        $content = Html::beginTag('ul', ['class' => self::$clsList['base']]);

        foreach ($this->items as $item) {
            $content .= $this->getTagIem($item);
        }

        $content .= Html::endTag('ul');
        return $content;
    }

    public function attachDrawer(Drawer $drawer): LeftAppBar
    {
        $i = 0;
        $activeClass = 'active';        
        $selectedItemAll = true;
        foreach ($drawer->items as $key => $item) {
            if (isset($this->items[$key]['all'])) {
                //Инициализация, если не найдется другого активного блока меню
                $this->items[$key]['selected'] = &$selectedItemAll;
                $i++;
            }
            $this->items[$key + $i]['title'] = $item['header'];
            $drawer->items[$key]['options']['menu-index'] = $this->items[$key+ $i]['icon'];
            
            if ($drawer->items[$key]['selected'] === true) {
                $this->items[$key + $i]['selected'] = true;
                $selectedItemAll = false;
                $drawer->items[$key]['options']['class'][] = 'active';
                $activeClass = '';    
            } else {
                $drawer->items[$key]['options']['class'][] = &$activeClass;                
            }
        }        
        return $this;
    }

    /**
     * Нарисовать Snackbar
     */
    public function renderComponent(): string
    {
        $content = Html::beginTag('div', $this->getOptions());
        
        $items = $this->renderItems();
        $content .= Html::tag('div', $items, ['class' => self::$clsContent]);

        $content .= Html::endTag('div');

        return $content;
    }
}
