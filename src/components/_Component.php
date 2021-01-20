<?php

namespace yh\mdc\components;

use yh\mdc\components\ComponentRegister;
use yh\mdc\ActiveForm;

class _Component
{
    /**
     * @var string $type - Тип контрола, указывается в каждом наследуемом компоненте
     */
    protected string $type;

    protected array $options = [
        'class' => []
    ];
    /**
     * @var string $pathView - папка для View
     */
    private string $pathView = '@vendor/yh/mdc/views/';
    /**
     * @var string $id - id компонента
     */
    public string $id = '';
    /**
     * @var string $label
     */
    public string $label;
    /**
     * @var bool $enabled
     */
    public bool $enabled = true;
    /**
     * @var ?ActiveForm $parent - используется для задания группы компонентов
     */
    public ?ActiveForm $parent = null;
    /**
     * @var array $jsProperty - Параметры будут переданы в компонент в JavaScript
     */
    public array $jsProperty = [];
    /**
     * @var bool $registerComponent - В некоторых случаях компонентявляется составной частью
     * другого компонента и его не надо регистрировать
     */
    public bool $registerComponent = true;
    
    public function __construct(string $label = 'Button', array $options = [], array $property = [])
    {
        $this->label = $label;
        if (!empty($options)) {
            $this->setOptions($options);
        }
        if (!empty($property)) {
            $this->setProperty($property);
        }        
    }

    /**
     * Если нет id, то оно будет сгенерировано
     *
     * @param string $id
     * @return _Component
     */
    public function setId(string $id = ''): _Component
    {
        if (empty($this->id)) {
            if (empty($id)) {
                if (isset($this->options['id'])) {
                    $id = $this->options['id'];
                } elseif (!is_null($this->parent)) {
                    $id = $this->parent->getId() . '-' . $this->type;
                } else {
                    $id = uniqid('cmp-');
                }
            }
            $this->id = $id;
        }
        $this->options['id'] = $this->id;

        return $this;
    }

    public function getId(): string
    {
        return $this->setId()->id;
    }

    /**
     * Css классы для контейнера
     */
    public function initClassWrap(): void
    {
        //Сделать из строки массив, чтобы была возможность в наследуемых классах добавлять class
        if (is_string($this->options['class'])) {
            $this->options['class'] = [$this->options['class']];
        }
    }

    /**
     * Свойства компонента
     */
    public function setProperty(array $property): _Component
    {
        foreach ($property as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    /**
     * DOM options для контейнера
     */
    public function setOptions(array $options): _Component
    {
        $this->options = array_merge($this->options, $options);
        
        return $this;
    }

    /**
     * @param ActiveForm $parent
     */
    public function setParent(ActiveForm $parent): _Component
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Принудительная регистрация компонента
     * Все компоненты регистрируются через Render()
     * Иногда Render() не используется
     */
    public function forcedRegisterComponent(): void
    {
        if (empty($this->id) || empty($this->options['id'])) {
            $this->setId();
        }
            
        $parentId = is_null($this->parent) ? '' : $this->parent->getId();

        ComponentRegister::registerControlJs(
            $this->id,
            $this->type,
            $this->jsProperty,
            $parentId
        );
    }

    public function render(): string
    {
        $this->initClassWrap();
        //Регистрация компонента
        if ($this->registerComponent) {
            $this->forcedRegisterComponent();
        }

        return '';
    }

    public function renderView(string $nameView): string
    {
        return \Yii::$app->getView()->renderFile($this->pathView.$nameView.'.php');
    }

    public static function one(string $label = '', array $options = [], array $property = [])
    {
        $class = static::class;
        return new $class($label, $options, $property);
    }

    /**
     * Экземпляр объекта
     * @param array $property - свойства объекта
     * @param array $options - опции для списка
     */
    public static function list(array $property, array $options = [])
    {
        $class = static::class;
        return new $class($property, $options);
    }
}
