<?php

namespace yh\mdc\components\base\stable;

class _Persistent
{
    /**
     * @var string PATH_VIEW - папка для View
     */
    const PATH_VIEW = '@yh/mdc/views/';

    public array $options = [
        'class' => []
    ];

    /**
     * Options уже инициализированны, чтобы не выполнять фугкцию InitOptions повторно
     */
    private bool $hasInitOptions = false;

    /**
     * Получить имя класса
     * @param bool $shortName имя класса без namespace
     * @return string 
     */
    public static function className(bool $shortName = false): string
    {
        
        $class = static::class;
        if ($shortName) {
            $classObject = new \ReflectionClass($class);
            $class = $classObject->getShortName();
        }
        return $class;
    }
    
    /**
     * @param array $property инициализация свойств компонента
     * @param array $options инициализация options компонента
     */
    public function __construct(array $property = [], array $options = [])
    {
        if (!empty($property)) {
            $this->setProperty($property);
        }
        if (!empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Options initialization
     */
    protected function initOptions(): void
    {
        //Сделать из строки массив, чтобы была возможность в наследуемых классах добавлять class
        if (isset($this->options['class']) && is_string($this->options['class'])) {
            $this->options['class'] = [$this->options['class']];
        }
    }

    /**
     * Изменить стандартное присваивание свойств
     * @param string $propertyName Наименование свойства
     * @param mixed $value Значение свойства
     */
    public function setter(string $propertyName, mixed $value): bool
    {
        return true;
    }

    /**
     * Установить свойства компонента
     * @param array $property массив свойств
     * @return self
     */
    public function setProperty(array $property): _Persistent
    {
        $className = $this->className();
        foreach ($property as $key => $value) {
            if (property_exists($className, $key)) {
                if ($this->setter($key, $value)) {
                    $this->$key = $value;
                }
            } else {                
                throw new \Exception("'$className' Class property '$key' not found");                
            }
        }
        return $this;
    }

    /**
     * DOM options для контейнера
     * @param array $options wrap options
     * @return self
     */
    public function setOptions(array $options): _Persistent
    {
        $this->options = array_merge($this->options, $options);
        
        return $this;
    }

    /**
     * Вернуть массив options. Все DOM свойства будут инициализированны в ф-ии initOptions
     * @return array DOM options 
     */
    public function getOptions(): array
    {
        if (!$this->hasInitOptions) {
            $this->initOptions();
            $this->hasInitOptions = true;
        }
        
        return $this->options;
    }

    /**
     * Render some view from path $this->pathView
     * @param string $nameView Name View
     * @return string view
     */
    public function renderView(string $nameView): string
    {
        return \Yii::$app->getView()->renderFile(self::PATH_VIEW.$nameView.'.php');
    }

    /**
     * Вывести текст Html компонента
     * @return string Html code
     */
    public function renderComponent(): string
    {
        return '';
    }
}
