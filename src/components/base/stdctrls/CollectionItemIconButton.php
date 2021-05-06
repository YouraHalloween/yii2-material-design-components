<?php

namespace yh\mdc\components\base\stdctrls;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yh\mdc\components\ItemIconButton;
use yh\mdc\components\base\stable\_Collection;

class CollectionItemIconButton extends _Collection
{
    /**
     * @see _Collection
     */
    public string $className = 'ItemIconButton';

    public static array $clsBlock = [
        ItemIconButton::LEADING => 'mdc-text-field--with-leading-icon',
        ItemIconButton::TRAILING => 'mdc-text-field--with-trailing-icon'
    ];

    protected static array $clsGroup = [
        'base' => 'mdc-form-field__group-icon'
    ];

    /**
     * @var array $clsIcons ссылка на классы для иконок
     */
    public static array $clsIcons = [];

    /**
     * Вернуть css class переданный по ссылке в clsInput
     * @param string $nameCls имя класса
     * @throws Exception Required css class not found
     */
    private static function getCls(string $nameCls): string
    {
        if (!isset(static::$clsIcons[$nameCls])) {
            throw new \Exception("Required css class not found");
        }
        return static::$clsIcons[$nameCls];
    }

    /**
     * Разобрать массив свойств иконки и добавить иконку в коллекцию
     * @param string|array $icon property for icon
     * 'clear' or
     * ['icon' => 'check'] or
     * ['icon' => 'search', 'role' => 'button', 'id' => 'qwe', 'toggle' => 'cancel', 'position' => ItemIconButton::LEADING]
     * @throws Exception Required property icon is not filled
     */
    public function parseItem($icon): void
    {
        //default property
        $property = [
            'id' => null,
            'icon' => '',
            'toggle' => false,
            'position' => ItemIconButton::TRAILING
        ];

        $options = [
            'role' => 'icon',
            'class' => [self::getCls('base')]
        ];

        if (is_string($icon)) {
            $property['icon'] = $icon;
        } else {
            if (!isset($icon['icon'])) {
                throw new \Exception("Required property icon is not filled");
            }

            $property['icon'] = $icon['icon'];
            $property['position'] = ArrayHelper::getValue($icon, 'position', ItemIconButton::TRAILING);
            $property['toggle'] = ArrayHelper::getValue($icon, 'toggle', false);
            if ($property['toggle'] !== false) {
                $property['iconOn'] = $property['toggle'];
                $property['toggle'] = true;
            }
            $options['role'] = ArrayHelper::getValue($icon, 'role', 'icon');

            // Если кнопка добавить tabIndex и id компонента
            if ($options['role'] === 'button') {
                $options['tabindex'] = '0';
                if (!isset($property['id'])) {
                    $property['id'] = $this->owner->getId().'-'.$property['icon'];
                }
            }
            // Пользовательские options
            $optionsAlt = ArrayHelper::getValue($icon, 'options', []);
            if (!empty($optionsAlt)) {
                $options = ArrayHelper::merge($options, $optionsAlt);
            }
        }

        $options['class'][] = self::getCls($property['position']);

        $this->create($property, $options);
    }

    /**
     * Разобрать массив иконок и добавить ихв коллекцию
     * @param array $icons массив иконок
     */
    public function parse(array $icons): void
    {        
        foreach ($icons as $value) {
            $this->parseItem($value);
        }
    }

    /**
     * @param array &$ownerOptionsClass добавить для владельца css классы иконок
     */
    public function setWrapClass(array &$ownerOptionsClass): void
    {
        for ($i=0; $i < count($this->items); $i++) {
            $pos = $this->item($i)->position;
            if (!isset($ownerOptionsClass[$pos])) {
                $ownerOptionsClass[$pos] = static::$clsBlock[$pos];
            }
        }
    }

    /**
     * @param string $params[0] position icon
     * @see _Collection
     * @throws Execption Icon position not specified
     */
    public function render(...$params): string
    {
        if (!isset($params[0])) {
            throw new \Exception("Icon position not specified");
        }
        $content = '';
        $countIcon = 0;
        foreach ($this->items as $component) {
            if ($component->position === $params[0]) {
                $content .= $component->render();
                $countIcon++;
            }
        }

        if ($countIcon > 1) {
            return Html::tag('div', $content, ['class' => self::$clsGroup['base']]);
        }

        return $content;
    }
}
