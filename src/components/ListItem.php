<?php

namespace yh\mdc\components;

use yii\helpers\Url;
use yii\helpers\Html;
use yh\mdc\components\Radio;
use yii\helpers\ArrayHelper;
use yh\mdc\components\Checkbox;
use yh\mdc\components\base\Vars;
use yh\mdc\components\Typography;
use yh\mdc\components\base\ControlList;
use yh\mdc\components\base\stable\ComponentRegister;

class ListItem extends ControlList
{
    protected string $cmpType = ComponentRegister::TYPE_LIST;

    protected static array $clsBlock = [
        'base' => 'mdc-list',
        //for helper
        'helper' => 'mdc-list--two-line',
        //Avatar
        'avatar' => 'mdc-list--avatar-list',
    ];

    protected array $clsItem = [
        'base' => 'mdc-list-item',
        'selected' => 'mdc-list-item--selected',
        'ripple' => 'mdc-list-item__ripple',
        'text' => 'mdc-list-item__text',
        'primary' => 'mdc-list-item__primary-text',
        'secondary' => 'mdc-list-item__secondary-text',
        'disabled' => 'mdc-list-item--disabled',
        'separator' => 'mdc-list-divider'
    ];

    protected static array $clsGroup = [
        'base' => 'mdc-list-group',
        'label' => 'mdc-list-group__subheader'
    ];

    protected static array $clsIcon = [
        'base' => 'mdc-list-item__graphic',
        'icon' => 'material-icons',
    ];

    protected static array $clsMeta = [
        'base' => 'mdc-list-item__meta',
        'button' => 'mdc-icon-button material-icons'
    ];
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
     * @var string $itemTextSize - задается размер текста item
     */
    public string $itemTextSize = Vars::SMALL;
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
    public string $headerSize = Vars::NORMAL;
    /**
     * @var string $tagList - Тонкая настройка. Для Drawer необходим <nav>
     */
    public string $tagList = 'nav';
    /**
     * @var string $tagList - Тонкая настройка. Для Drawer необходим <a>
     */
    public string $tagItem = 'a';
    /**
     * @var bool $action - использует tagItem = a, если = true, иначе tagList = ul, tagItem = li
     */
    public bool $action = true;
    /**
     * @var array $itemOptions
     */
    public array $itemOptions = [];

    /**
     * @var string $selectedProp - свойство по которому будет сравниваться selectedValue
     */
    // public string $selectedProp = 'value';
    /**
     * @var string|array $value - если значение совпадает, то item будет выделен
     */
    public $value = '';
    /**
     * @var bool $separator разделяет заголовки item чертой
     */
    public bool $separator = false;

    /**
     * Используется для вывода сгруппированных списков
     */
    // protected int $groupIndex = -1;
    //В переменной содержится аватарка из View _avatar.php
    private string $_avatarBuf = '';
    // private array $selectedIndex = [];

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
            $this->tagItem = 'li';
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
        $this->options['class'][] = Vars::cmpHeight($this->heightItem);
        if ($this->itemTextSize !== Vars::NORMAL) {
            $this->options['class'][] = Typography::fontSize($this->itemTextSize);
        }

        if ($this->isHelper()) {
            $this->options['class'][] = self::$clsBlock['helper'];
        }
        if ($this->avatar) {
            $this->options['class'][] = self::$clsBlock['avatar'];
        }
    }

    /**
     * Добавляет разделяющую линию между items
     * @return string html tag
     */
    protected function getTagSeparator(): string
    {
        return Html::tag('li', '', ['class' => $this->clsItem['separator'], 'role' => 'separator']);
    }

    /**
     * Текст item + helper
     * @param array $item - текущий item
     */
    protected function getTagText(array $item): string
    {
        $text = ArrayHelper::getValue($item, 'text', '');

        if (empty($text)) {
            // В качестве текста будут использоваться значения 
            $text = ArrayHelper::getValue($item, 'value', '');
        }

        if ($this->isHelper()) {
            $content = Html::tag('span', $text, ['class' => $this->clsItem['primary']]);
            $content .= Html::tag('span', $item['helper'], ['class' => $this->clsItem['secondary']]);
        } else {
            $content = $text;
        }
        return Html::tag('span', $content, ['class' => $this->clsItem['text']]);
    }

    /**
     * Вывести компонент Radio
     * @param array $item - текущий item
     * @return string render redio
     */
    public function renderRadio(array $item): string
    {
        $checked = ArrayHelper::getValue($item, 'checked', false);
        $name = $this->getId().'-radio';
        $id = $this->getId().'-radio-'.$item['index'];
        return Radio::one('', ['value' => $checked], ['value' => $item['index']])
            ->setId($id)
            ->setName($name)
            ->renderComponent();
    }

    /**
     * Вывести компонент Checbox
     * @param array $item - текущий item
     * @return string render checkbox
     */
    public function renderCheckbox(array $item): string
    {
        $checked = ArrayHelper::getValue($item, 'checked', false);
        $name = $this->getId().'-radio';
        $id = $this->getId().'-radio-'.$item['index'];
        return Checkbox::one('', ['value' => $checked], ['value' => $item['index']])
            ->setId($id)
            ->setName($name)
            ->renderComponent();
    }

    /**
     * Вернуть аватар из View _avatar.php
     * @return stirng view avatar
     */
    protected function getAvatar(): string
    {
        if (empty($this->_avatarBuf)) {
            $this->_avatarBuf = $this->renderView('_avatar');
        }
        return $this->_avatarBuf;
    }
    
    /**
     * Выводит input либо автар
     * @param array $item текущий item
     * @return string $content
     */
    protected function renderSecondaryIcon(array $item): string
    {
        $content = '';
        if ($this->checkbox) {
            //render checkbox
            $content = $this->renderCheckbox($item);
        } elseif ($this->radio) {
            //render radio box
            $content = $this->renderRadio($item);
        } elseif ($this->avatar) {
            //render avatar
            $content = $this->getAvatar();
        }
        return $content;
    }

    /**
     * Вывести иконку либо inut, avatar
     * @param array $item текущий item
     * @return string $content
     */
    protected function getTagIcon(array $item): string
    {
        $content = $this->renderSecondaryIcon($item);
        // Если есть input или иконка
        if (!empty($content) || isset($item['icon'])) {
            $options = [
                'class' => [self::$clsIcon['base']],
                'aria-hidden' => 'true'
            ];
            // Если нет input, но есть иконка
            if (empty($content) && isset($item['icon'])) {
                $content = $item['icon'];
                $options['class'][] = self::$clsIcon['icon'];
            }
            $content = Html::tag('span', $content, $options);
        }
        return $content;
    }
    
    /**
     * Доабвить мета текст с права от item, кнопка либо произвольный текст
     * @param array $item текущий item
     * @return string
     */
    protected function getTagMeta(array $item): string
    {
        $content = '';
        if (isset($item['meta'])) {
            $options = [
                'class' => self::$clsMeta['base'],
                'aria-hidden' => 'true'
            ];

            if ($item['meta'] === 'button') {
                $content = Html::button('more_vert', [
                    'class' => self::$clsMeta['button'],
                    'data-mdc-ripple-is-unbounded' => true,
                    'tabIndex' => -1
                ]);
            } else {
                $content = $item['meta'];
            }

            $content = Html::tag('span', $content, $options);
        }
        return $content;
    }

    /**
     * selected - можно указать в item
     * либо в свойстве value, оно может быть типа string|array
    */
    protected function isSelect(array $item): bool
    {
        return (isset($item['selected']) && $item['selected'] === true && empty($this->value))
                    ||
                    (!empty($this->value) && isset($item['value']) && (
                        (is_array($this->value) && ArrayHelper::isIn($item['value'], $this->value))
                        ||
                        (!is_array($this->value) && $this->value == $item['value'])
                    ));
    }

    protected function initItemOptions(array &$item)
    {
        $item['options']['class'][] = $this->clsItem['base'];

        if ($this->single) {
            $item['options']['role'] = 'option';
        }
        if (!ArrayHelper::getValue($item, 'enabled', true)) {
            $item['options']['class'][] = $this->clsItem['disabled'];
        }
        
        if ($this->isSelect($item)) {
            $item['options']['class'][] = $this->clsItem['selected'];
            $item['options']['aria-selected'] = 'true';
        }
        if (isset($item['value'])) {
            $this->jsProperty['values'][] = $item['value'];
            $item['options']['data-value'] = $item['value'];
        }
        if (isset($item['href'])) {
            $item['options']['href'] = Url::to([$item['href']]);
        }
    }

    /**
     * Выводит Item
     * @param array $item - текущий item
     */
    protected function getTagItem(array $item): string
    {
        $this->initItemOptions($item);
        // set custom item options
        if (!empty($this->itemOptions)) {
            $item['options'] = ArrayHelper::merge($item['options'], $this->itemOptions);
        }

        $content = Html::beginTag($this->tagItem, $item['options']);
        //Ripple
        $ripple = ArrayHelper::getValue($item, 'ripple', true);
        if ($ripple) {
            $content .= Html::tag('span', '', ['class' => $this->clsItem['ripple']]);
        }        
        //Icon or Avatar or Radio or Checkbox
        $content .= $this->getTagIcon($item);
        //Text
        $content .= $this->getTagText($item);
        //Meta
        $content .= $this->getTagMeta($item);
        //End
        $content .= Html::endTag($this->tagItem);
        //Separator
        if ($this->separator || isset($item['separator'])) {
            $content .= $this->getTagSeparator();
        }
        return $content;
    }

    // public function setSelected($value, string $prop = 'value'): ListItem
    // {
    //     foreach ($this->items as $key => $item) {
    //         if (isset($item[$prop])) {
    //             $propValue = $item[$prop];
    //             //Если $this->selectedValue == $propValue, либо ищем $propValue в массиве
    //             if (((\is_array($value) && array_search($propValue, $value))) || ($value === $propValue)) {
    //                 $this->items[$key]['selected'] = true;
    //                 $this->selectedIndex[] = $key;
    //             }
    //         }
    //     }
    //     return $this;
    // }

    // public function getSelectedIndex(): array
    // {
    //     return $this->selectedIndex;
    // }

    /**
     * Выводит список items
     */
    public function renderItems(): string
    {
        $content = '';
        foreach ($this->items as $key => $item) {
            //Простой список состоящий из 'value' => 'label'
            if (!is_array($item)) {
                $item = ['text' => $item, 'value' => $key];
            }
            $item['index'] = $key;
            $content .= $this->getTagItem($item);
        }
        return $content;
    }

    public function renderHeader(): string
    {
        if (!empty($this->header)) {
            $options = [
                'class' => [self::$clsGroup['label']]
            ];
            if ($this->headerSize !== Vars::NORMAL) {
                $options['class'][] = Typography::fontSize($this->headerSize);
            }
            return Html::tag('h4', $this->header, $options);
        }
        return '';
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

        $content = $this->renderHeader() . $content;
        
        return $content;
    }

    /**
     * Нарисовать список tagItem внутри TagList
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
            // $localProperty['groupIndex'] = $key;
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
