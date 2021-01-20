<?php

namespace yh\mdc\components;

use yh\mdc\components\ComponentRegister;
use yh\mdc\components\_Component;
use yh\mdc\components\ListItem;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Menu extends _Component {

    protected string $type = ComponentRegister::TYPE_MENU;

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

    public function __construct(array $property = [], array $options = [])
    {
        parent::__construct('', $options, $property);
    }

    /**
     * Css классы для контейнера
     */
    public function initClassWrap(): void
    {
        parent::initClassWrap();
        $this->options['class'][] = self::$clsBlock;        
    }

    public function setListProperty(array $property): Menu
    {
        $this->listProperty = $property;
        return $this;
    }

    private function renderList(): string
    {
        $list = ListItem::list($this->listProperty, [
            'role' => 'menu', 
            'aria-hidden' => 'true', 
            'aria-orientation' => 'vertical'
        ]);
        $list->registerComponent = false;        
        //идет перебор всех items и заполняется массив values
        $content =  $list->render();
        if (!empty($list->jsProperty)) {
            $this->jsProperty = array_merge($this->jsProperty, $list->jsProperty);         
        }
        return $content;
    }

    /**
     * Нарисовать Snackbar
     */
    public function render(): string
    {
        //Нужно заранее собрать все jsProperty, после чего зарегать компонент
        $contentList = $this->renderList();
        //Регистрация компонента
        parent::render();

        //Menu begin
        $content = Html::beginTag('div', $this->options);
        $content .= $contentList;        
        //Menu end
        $content .= Html::endTag('div');

        if ($this->anchor) {
            $content = Html::tag('div', $content, ['class' => self::$clsAnchor]);
        }

        return $content;
    }

    //Экземпляр объекта
    /**
     * @param array $property - свойства для меню и ListItem
     * @param array $options - опции для menu
     */
    public static function list(array $property, array $options = []): Menu
    {                
        $items = ArrayHelper::remove($property, 'items');        
        $menu = new Menu($property, $options);
        $menu->listProperty['items'] = $items;
        return $menu;
    }
}
