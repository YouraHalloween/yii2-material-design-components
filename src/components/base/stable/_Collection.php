<?php

namespace yh\mdc\components\base\stable;

use yh\mdc\Config;

abstract class _Collection
{
    /**
     * @var array $items - список объектов коллекции
     */
    public array $items;

    /**
     * @var string $className - класс создаваемого компонента
     */
    public string $className;

    /**
     * Добавить новый объект в коллекцию
     * @param mixed $component
     * @param int|string $index
     * @return _Collection
     */    
    public function add(mixed $component, $index = -1): _Collection 
    {
        if ($index == -1) {
            $this->items[] = $component;
        } else {
            $this->items[$index] = $component;
        }
        return $this;
    }

    /**
     * Вернуть объект из коллекции
     * @param int|string $index
     * @return mixed|null
     */
    public function item($index): mixed
    {
        if (isset($this->items[$index])) {
            return $this->items[$index];
        } else {
            return null;
        }
    }

    /**
     * Создать компонент и добавить в коллекцию
     * @param array $property - свойства компонента
     * @param array $options - параметры html wrap компонента
     */
    public function create(array $property, array $options): mixed
    {
        $class = Config::getClassComponent(self::$className);
        $component = new $class();
        $component->setProperty($property)->setOptions($options);
        $this->add($component);
        return $component;
    }

    abstract public function render(): string;
}