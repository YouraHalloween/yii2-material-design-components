<?php

namespace yh\mdc\components;

use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\components\base\ControlList;
use yh\mdc\components\Radio;
use yh\mdc\components\Checkbox;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yh\mdc\components\base\Vars;

class ListItem extends ControlList
{
    protected string $cmpType = ComponentRegister::TYPE_LIST;

    private static array $clsBlock = [
        'base' => 'mdc-list',
        //for helper
        'helper' => 'mdc-list--two-line',
        //Avatar
        'avatar' => 'mdc-list--avatar-list',
    ];

    private static array $clsItem = [
        'base' => 'mdc-list-item',
        'selected' => 'mdc-list-item--selected',
        'ripple' => 'mdc-list-item__ripple',
        'text' => 'mdc-list-item__text',
        'primary' => 'mdc-list-item__primary-text',
        'secondary' => 'mdc-list-item__secondary-text',
        'disabled' => 'mdc-list-item--disabled',
    ];

    private static array $clsGroup = [
        'base' => 'mdc-list-group',
        'label' => 'mdc-list-group__subheader'
    ];

    private static array $clsIcon = [
        'base' => 'mdc-list-item__graphic',
        'icon' => 'material-icons',
    ];

    private static array $clsMeta = [
        'base' => 'mdc-list-item__meta',
        'button' => 'mdc-icon-button material-icons'
    ];

    private static string $clsSeparator = 'mdc-list-divider';
    /*
    'items' => [
        [
            'text' => 'Меню 1',
            'helper' => 'Это менюшечка',
            'separator' => true,
            'icon' => 'favorite',
            'meta' => 'button',
        ],
        [
            'text' => 'Меню 2',
            'selected' => true,
            'meta' => 'Text',
        ],
    ];
    */
    
    /**
     * @var string $heightItem - Высота items
     */
    public string $heightItem = Vars::SMALL;
    /**
     * @var bool $single - Возможность фокусировать на Item
     */
    public bool $single = false;
    /**
     * @var bool $avatar - Добавляет к Item аватарку, если там нет иконки
     */
    public bool $avatar = false;
    /**
     * @var bool $radio - Добавить в items radio box
     */
    public bool $radio = false;
    //
    /**
     * @var bool $checkbox - Добавить в items checkbox
     */
    public bool $checkbox = false;
    /**
     * @var string $header - Заголовок списка
     */
    public string $header = '';
    /**
     * @var string $headerSize - Размер заголовка
     */
    public string $headerSize = 'medium';
    /**
     * @var string $tagList - Тонкая настройка. Для Drawer необходим <nav>
     */
    public string $tagList = 'nav';
    /**
     * @var string $tagList - Тонкая настройка. Для Drawer необходим <a>
     */
    public string $tagListItem = 'a';
    /**
     * @var bool $action - использует tagListItem = a или tagList = ul, TagListItem = li
     */
    public bool $action = true;
    /**
     * @var string $roleItem - хуй его знает зачем это
     */
    public string $roleItem = '';
    /**
     * @var string $selectedProp - свойство по которому будет сравниваться selectedValue
     */
    // public string $selectedProp = 'value';
    /**
     * @var string|array $value - если значение совпадает, то item будет выделен
     */
    public $value = '';

    /**
     * Используется для вывода сгруппированных списков
     */
    protected int $groupIndex = -1;
    //В переменной содержится аватарка из View _avatar.php
    private string $_avatarBuf = '';
    private array $selectedIndex = [];

    /**
     * @see yh\mdc\components\base\_Component
     */
    public function setProperty(array $property): ListItem
    {
        parent::setProperty($property);
        if ($this->single) {
            //Добавляет в JavaScript настройки по умолчанию
            $this->jsProperty['singleSelection'] = true;
        }
        if (!$this->action) {
            $this->tagList = 'ul';
            $this->tagListItem = 'li';
        }
        return $this;
    }

    /**
     * Если в item есть hlper-text, тогда добавить класс mdc-list--two-line
     */
    private function isHelper(): bool
    {
        return isset($this->items[0]['helper']);
    }

    /**
     * Css классы для контейнера
     * @see yh\mdc\components\base\_Component
     */
    public function initOptions(): void
    {
        parent::initOptions();

        $this->options['class'][] = self::$clsBlock['base'];
        $this->options['class'][] = Vars::getCmpHeight($this->heightItem);

        if ($this->isHelper()) {
            $this->options['class'][] = self::$clsBlock['helper'];
        }
        if ($this->avatar) {
            $this->options['class'][] = self::$clsBlock['avatar'];
        }
    }

    //Добавляет разделяющую линию между items
    private function getTagSeparator(): string
    {
        return Html::tag('li', '', ['class' => self::$clsSeparator, 'role' => 'separator']);
    }

    /**
     * Текст item + helper
     * @param array $item - текущий item
     */
    private function getTagText(array $item): string
    {
        $text = ArrayHelper::getValue($item, 'text', $item['value']);
        if ($this->isHelper()) {
            $content = Html::tag('span', $text, ['class' => self::$clsItem['primary']]);
            $content .= Html::tag('span', $item['helper'], ['class' => self::$clsItem['secondary']]);
        } else {
            $content = $text;
        }
        return Html::tag('span', $content, ['class' => self::$clsItem['text']]);
    }

    /**
     * Вывести компонент Radio
     * @param array $item - текущий item
     */
    private function renderRadio(array $item): string
    {
        $checked = ArrayHelper::getValue($item, 'checked', false);
        $name = $this->getId().'-radio';
        $id = $this->getId().'-radio-'.$item['index'];
        return Radio::one('', [], ['checked' => $checked, 'value' => $item['index']])
            ->setId($id)
            ->setName($name)
            ->renderComponent();
    }

    /**
     * Вывести компонент Checbox
     * @param array $item - текущий item
     */
    private function renderCheckbox(array $item): string
    {
        $checked = ArrayHelper::getValue($item, 'checked', false);
        $name = $this->getId().'-radio';
        $id = $this->getId().'-radio-'.$item['index'];
        return Checkbox::one('', [], ['checked' => $checked, 'value' => $item['index']])
            ->setId($id)
            ->setName($name)
            ->renderComponent();
    }
    
    /**
     * Выводит иконку либо автар
     * @param array $item - текущий item
     */
    private function getTagIcon(array $item): string
    {
        if ($this->checkbox ||
            $this->radio ||
            $this->avatar ||
            isset($item['icon'])) {
            $class = [self::$clsIcon['base']];
            if ($this->checkbox) {
                //render checkbox
                $icon = $this->renderCheckbox($item);
            } elseif ($this->radio) {
                //render radio box
                $icon = $this->renderRadio($item);
            } elseif ($this->avatar) {
                //render avatar
                $icon = $this->getAvatar();
            } else {
                //render icon
                $icon = $item['icon'];
                $class[] = self::$clsIcon['icon'];
            }
            return Html::tag('span', $icon, [
                'class' => $class,
                'aria-hidden' => 'true'
            ]);
        }
        return '';
    }
    
    /**
     * Вернуть аватар из View _avatar.php
     */
    private function getAvatar(): string
    {
        if (empty($this->_avatarBuf)) {
            $this->_avatarBuf = $this->renderView('_avatar');
        }
        return $this->_avatarBuf;
    }

    /**
     * Доабвить мета текст с права item
     * @param array $item - текущий item
     */
    private function getTagMeta(array $item): string
    {
        if ($item['meta'] == 'button') {
            $meta = Html::button('more_vert', [
                'class' => self::$clsMeta['button'],
                'data-mdc-ripple-is-unbounded' => true,
                'tabIndex' => -1
            ]);
        } else {
            $meta = $item['meta'];
        }
        return Html::tag('span', $meta, ['class' => self::$clsMeta['base'], 'aria-hidden' => 'true']);
    }

    /**
     * Выводит Item
     * @param array $item - текущий item
     */
    private function getTagItem(array $item): string
    {
        $item['options']['class'][] = self::$clsItem['base'];
        if ($this->single) {
            $item['options']['role'] = 'option';
        }
        if (!ArrayHelper::getValue($item, 'enabled', true)) {
            $item['options']['class'][] = self::$clsItem['disabled'];
        }
        //Если задано значение selectedValue
        // if (!empty($this->selectedValue)) {
        //     if (isset($item[$this->selectedProp])) {
        //         $propValue = $item[$this->selectedProp];
        //         //Если $this->selectedValue == $propValue, либо ищем $propValue в массиве
        //         $item['selected'] = ((\is_array($this->selectedValue) && array_search($propValue, $this->selectedValue))) || ($this->selectedValue === $propValue);
        //     }
        // }
        /**
         * selected - можно указать в item
         * либо в свойстве value, оно может быть типа string|array
         */
        $isSelect = (isset($item['selected']) && $item['selected'] === true && empty($this->value))
                    ||                    
                    (!empty($this->value) && isset($item['value']) && (
                        (is_array($this->value) && ArrayHelper::isIn($item['value'], $this->value))
                        ||
                        (!is_array($this->value) && $this->value == $item['value'])
                    ));

        if ($isSelect) {
            $item['options']['class'][] = self::$clsItem['selected'];
            $item['options']['aria-selected'] = 'true';
        } 
        if (isset($item['value'])) {
            $this->jsProperty['values'][] = $item['value'];
            $item['options']['data-value'] = $item['value'];
        }
        if (isset($item['href'])) {
            $item['options']['href'] = Url::to([$item['href']]);
        }
        if (!empty($this->roleItem)) {
            $item['options']['role'] = $this->roleItem;
        }
        $content = Html::beginTag($this->tagListItem, $item['options']);
        //Ripple
        $content .= Html::tag('span', '', ['class' => self::$clsItem['ripple']]);
        //Icon or Avatar or Radio or Checkbox
        $content .= $this->getTagIcon($item);
        //Text
        $content .= $this->getTagText($item);
        //Meta
        if (isset($item['meta'])) {
            $content .= $this->getTagMeta($item);
        }
        $content .= Html::endTag($this->tagListItem);
        //Separator
        if (isset($item['separator'])) {
            $content .= $this->getTagSeparator();
        }
        return $content;
    }

    public function setSelected($value, string $prop = 'value'): ListItem
    {        
        foreach ($this->items as $key => $item) {
            if (isset($item[$prop])) {
                $propValue = $item[$prop];
                //Если $this->selectedValue == $propValue, либо ищем $propValue в массиве
                if (((\is_array($value) && array_search($propValue, $value))) || ($value === $propValue)) {
                    $this->items[$key]['selected'] = true;
                    $this->selectedIndex[] = $key;
                }
            }
        }
        return $this;
    }

    public function getSelectedIndex(): array {
        return $this->selectedIndex;
    }

    /**
     * Выводит список items
     */
    public function renderItems(): string
    {
        $content = '';
        $i = 0;
        foreach ($this->items as $key => $item) {
            // dump($item);
            //Простой список состоящий из 'value' => 'label'
            if (!is_array($item)) {
                $item = ['text' => $item, 'value' => $key];
            }
            $item['index'] = $key;
            // if ($i === 0) {
            //     $item['options']['tabindex'] = 0;
            // }
            $i++;
            $content .= $this->getTagItem($item);
        }
        return $content;
    }

    /**
     * Вывести список
     * @param bool $frame - обрамлять тегом tagList
     */
    public function renderList(bool $frame = true): string
    {
        $content = $this->renderItems();
        if ($frame) {
            $content = $this->renderFrame($content);
        }

        if (!empty($this->header)) {
            $content = Html::tag('h4', $this->header, ['class' => self::$clsGroup['label']])
                        .$content;
        }
        return $content;
    }

    /**
     * Нарисовать список tagListItem внутри TagList
     * @param string $content - Html items
     */
    public function renderFrame(string $content): string
    {
        return Html::tag($this->tagList, $content, $this->getOptions());
    }

    /**
     * Нарисовать List
     */
    public function renderComponent(): string
    {
        return $this->renderList();
    }

    /**
     * Вывести группы со списком item
     */
    public static function list(array $property, array $options = [])
    {
        $itemsContent = [];
        $groups = ArrayHelper::remove($property, 'items', []);
        foreach ($groups as $key => $group) {
            $localProperty = ArrayHelper::merge($property, $group);
            $localProperty['groupIndex'] = $key;
            $list = self::one($localProperty, ['group-index' => $key])->render();
            $itemsContent[] = $list;
        }

        $options['class'][] = self::$clsGroup['base'];
        $content = Html::beginTag('div', $options);
        $content .= implode($itemsContent);
        $content .= Html::endTag('div');
        return $content;
    }
    // 'items' => [
    //                 [
    //                     'header' => 'List 1',
    //                     'checkbox'=> true,
    //                     'items' => [
    //                         [
    //                             'text' => 'Меню 1',
    //                             'helper' => 'Это менюшечка',
    //                             'separator' => true,
    //                             'icon' => 'favorite',
    //                             'meta' => 'button',
    //                             'checked' => true
    //                         ],
    //                     ]
    //                 ],
    //                 [
    //                     'header' => 'List 2',
    //                     'avatar' => true,
    //                     'items'=> [
    //                         [
    //                         'text' => 'Меню 2',
    //                         'helper' => 'Это менюшечка',
    //                         'selected' => true,
    //                         'meta' => 'Text',
    //                         ],
    //                         [
    //                             'text' => 'Меню 3',
    //                             'helper' => 'Это менюшечка',
    //                             'meta' => 'button',
    //                         ]
    //                     ]
    //                 ]
                    // ]
}
