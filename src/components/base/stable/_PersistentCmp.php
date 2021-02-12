<?php

namespace yh\mdc\components\base\stable;

use yh\mdc\components\base\stable\_Persistent;
use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\ActiveForm;

class _PersistentCmp extends _Persistent
{
    /**
     * @var string PATH_VIEW - папка для View
     */
    const PATH_VIEW = '@yh/mdc/views/';

    /**
     * @var string $cmpType - Тип контрола, указывается в каждом наследуемом компоненте
     */
    protected string $cmpType;

    /**
     * @var string $id - id компонента
     */
    public ?string $id = '';
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
    public function setId(?string $id): _Persistent
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
     * @param ActiveForm $parent
     */
    public function setParent(ActiveForm $parent): _Persistent
    {
        $this->parent = $parent;
        return $this;
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
