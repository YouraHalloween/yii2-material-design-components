<?php

namespace yh\mdc\components\base;

use yh\mdc\components\base\ComponentRegister;
use yh\mdc\ActiveForm;

class _Persistent
{
    /**
     * @var string PATH_VIEW - папка для View
     */
    const PATH_VIEW = '@yh/mdc/views/';

    /**
     * @var string $cmpType - Тип контрола, указывается в каждом наследуемом компоненте
     */
    protected string $cmpType;

    protected array $options = [
        'class' => []
    ];
    private bool $hasInitOptions = false;

    /**
     * @var string $id - id компонента
     */
    public string $id = '';
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
     * @var bool $registerComponent - В некоторых случаях компонент является составной частью
     * другого компонента и его не надо регистрировать
     */
    // public bool $registerComponent = true;
    
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
     * Если нет id, то оно будет сгенерировано
     * @param string $id
     * @return _Persistent
     */
    public function setId(string $id): _Persistent
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): string
    {
        if (empty($this->id)) {
            $this->id = $this->generateId();
        }
        return $this->id;
    }

    /**
     * If id is not given it will be generated
     */
    private function generateId(): string
    {
        if (isset($this->options['id'])) {
            $id = $this->options['id'];
        } elseif (!is_null($this->parent)) {
            $id = $this->parent->getId() . '-' . $this->cmpType;
        } else {
            $id = uniqid($this->cmpType.'-');
        }

        return $id;
    }

    /**
     * Options initialization
     */
    protected function initOptions(): void
    {
        //Сделать из строки массив, чтобы была возможность в наследуемых классах добавлять class
        if (is_string($this->options['class'])) {
            $this->options['class'] = [$this->options['class']];
        }
    }

    /**
     * Свойства компонента
     */
    public function setProperty(array $property): _Persistent
    {
        foreach ($property as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    /**
     * DOM options для контейнера
     * @param array $options - wrap options
     */
    public function setOptions(array $options): _Persistent
    {
        $this->options = array_merge($this->options, $options);
        
        return $this;
    }

    public function getOptions(): array
    {
        if (!$this->hasInitOptions) {
            $this->initOptions();
            $this->hasInitOptions = true;
        }
        
        return $this->options;
    }

    /**
     * @param ActiveForm $parent
     */
    public function setParent(ActiveForm $parent): _Persistent
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Render some view from path $this->pathView
     * @param string $nameView - Name View
     */
    public function renderView(string $nameView): string
    {
        return \Yii::$app->getView()->renderFile(self::PATH_VIEW.$nameView.'.php');
    }

    /**
     * Принудительная регистрация компонента
     * Все компоненты регистрируются через Render()
     * Иногда Render() не используется
     */
    public function registerComponent(): void
    {
        $parentId = is_null($this->parent) ? '' : $this->parent->getId();        

        ComponentRegister::registerControlJs(
            $this->getId(),
            $this->cmpType,
            $this->jsProperty,
            $parentId
        );
    }

    /**
     * Component initialization
     */
    public function renderComponent(): string
    {
        // $this->initOptions();
        return '';
    }

    /**
     * Render component
     */
    public function render(): string
    {
        $content = $this->renderComponent();
        //Регистрация компонента
        $this->registerComponent();
        return $content;
    }
}
