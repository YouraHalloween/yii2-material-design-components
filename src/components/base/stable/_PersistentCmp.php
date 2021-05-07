<?php

namespace yh\mdc\components\base\stable;

use yh\mdc\components\base\stable\_Persistent;
use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\ActiveForm;

class _PersistentCmp extends _Persistent
{
    /**
     * @var string PATH_VIEW папка для View
     */
    const PATH_VIEW = '@yh/mdc/views/';

    /**
     * @var string $cmpType - Тип контрола, указывается в каждом наследуемом компоненте
     */
    protected string $cmpType;

    /**
     * @var string $id id компонента, если равен NULL, то id не используется
     */
    public ?string $id = '';
    /**
     * @var bool $enabled
     */
    public bool $enabled = true;
    /**
     * @var ?ActiveForm $owner используется для задания группы компонентов
     */
    public ?ActiveForm $owner = null;
    /**
     * @var array $jsProperty Параметры будут переданы в компонент в JavaScript
     */
    public array $jsProperty = [];
    /**
     * @var bool $registerControlJs В некоторых случаях компонент не надо регистрировать
     * @see render()
     */
    public bool $registerControlJs = true;

    /**
     * Если нет id, то оно будет сгенерировано
     * @param string $id
     * @return self
     */
    public function setId(?string $id): _PersistentCmp
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Установить Id равным Null    
     * @return self
     */
    public function setIdNull(): _PersistentCmp
    {
        return $this->setId(Null);
    }

    /**
     * Вернуть id
     * @return string id
     */
    public function getId(): string
    {
        if (empty($this->id)) {
            $this->id = $this->generateId();
        }
        return $this->id;
    }

    /**
     * If id is not given it will be generated
     * @return string id
     */
    private function generateId(): string
    {
        if (isset($this->options['id'])) {
            $id = $this->options['id'];
        } elseif (!is_null($this->owner)) {
            $id = $this->owner->getId() . '-' . $this->cmpType;
        } else {
            $id = uniqid($this->cmpType . '-');
        }

        return $id;
    }

    /**
     * @param ActiveForm $owner
     * @return self
     */
    public function setOwner(ActiveForm $owner): _PersistentCmp
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Если есть собственник компонента
     * @return bool 
     */
    public function hasOwner(): bool
    {
        return !is_null($this->owner);
    }

    /**
     * Принудительная регистрация компонента, если у компонента есть id
     * Все компоненты регистрируются через Render()
     * Иногда Render() не используется
     */
    public function registerComponent(): void
    {
        if (!is_null($this->id)) {
            $ownerId = is_null($this->owner) ? '' : $this->owner->getId();

            ComponentRegister::registerControlJs(
                $this->getId(),
                $this->cmpType,
                $this->jsProperty,
                $ownerId
            );
        }
    }

    /**
     * Render component
     * @return string Html code
     */
    public function render(): string
    {
        $content = $this->renderComponent();
        //Регистрация компонента
        if ($this->registerControlJs) {
            $this->registerComponent();
        }
        return $content;
    }
}
