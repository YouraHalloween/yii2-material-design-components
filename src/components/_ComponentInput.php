<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yh\mdc\components\ComponentRegister;
use yh\mdc\ActiveForm;

class _ComponentInput {

    /**
     * Наследуемый компонент инициализирует type 
     * для регистрации компонента
     */
    protected string $type;

    public string $inputId = '';
    public string $inputName = '';
    //Fieldname модели    
    public string $inputType = 'text';

    //ActiveForm
    public ?ActiveForm $parent = null;    
    public string $label = '';    
    /**
     * Значение input 
     * не возможно объявить тип переменной, т.к. у checbox он bool. Бред сивой кобылы
     */
    public $value;
    public bool $autoFocus = false;
    public bool $enabled = true;
    public bool $ripple = true;

    // Используется ActiveField
    private bool $activeField = false;
    protected array $inputOptions = [];

    protected string $templateInput = '{input}';

    //Параметры будут переданы в компонент в JavaScript
    public array $jsProperty = [];

    public function __construct(string $label = 'Button', array $options = [], array $property = [])
    {       
        $this->label = $label;
        if (count($property) > 0) {
            $this->setProperty($property);
        }                
        $this->setInputOptions($options);                 
    }

     /* Установить Property component */
    public function setProperty(array $property): _ComponentInput
    {        
        foreach ($property as $field => $value) {
            // $prop = $this->$field;
            // if (is_array($prop) && !empty($prop)) {
            //     $this->$field[] = $value;
            // } else
            $this->$field = $value;
        }        
        return $this;
    }

    /**
     * Input options
     */
    public function setInputOptions(array $options): _ComponentInput
    {                
        //array merge
        foreach ($options as $key => $value) {
            if ($key == 'class') {
                $this->inputOptions[$key][] = $value;
            } else {
                $this->inputOptions[$key] = $value;
            }
        }
        
        if (!$this->enabled) {
            $this->inputOptions['disabled'] = true;
        }
        return $this;
    }

    /**
     * parent ActiveForm
     * @param ActiveForm $parent
     */
    public function setParent(ActiveForm $parent): _ComponentInput
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Если нет Id, то взять уникальный
     */
    public function setInputId(string $id = ''): _ComponentInput
    {
        if (empty($id)) {
            if (isset($this->inputOptions['id'])) {
                $id = $this->inputOptions['id'];
            } else {
                $id = uniqid('cmp-');
            }            
        } 
        $this->inputId = $id;
        $this->inputOptions['id'] = $id;

        return $this;
    }

    public function setInputName(string $name): _ComponentInput
    {
        $this->inputName = $name;
        return $this;
    }
    
    public function getInputId(): string
    {
        if (empty($this->inputId)) {
            $this->setInputId();
        }

        return $this->inputId;
    }

    /**
     * Если нет Name, то взять Id
     */
    public function getInputName(): string
    {
        if (empty($this->inputName)) {
            $this->inputName = $this->getInputId();
        }

        return $this->inputName;
    }

    protected function getTagLabel(): string
    {
        if (empty($this->label)) {
            return '';
        }
        return Html::label($this->label, $this->getInputId());
    }

    /**
     * Вернуть либо Template для ActiveField, либо tag <input>
     */
    protected function getTagInput(): string
    {
        if ($this->activeField) {
            return $this->templateInput;
        }
        else {            
            return Html::input($this->inputType, $this->inputName, $this->value, $this->inputOptions);
        }
    }

    /**
     * @param array $activeComponents Если = true, то вместо Input будет вставлен template {input} 
     */
    public function render(): string 
    {        
        if (!$this->activeField && empty($this->inputId)) {
            $this->setInputId();
        }

        $parentId = is_null($this->parent) ? '' : $this->parent->getId();

        ComponentRegister::registerControlJs(
            $this->getInputId(), 
            $this->type, 
            $this->jsProperty,
            $parentId
        );
        return '';
    }

    /**
     * Возвращает template для ActiveField
     * Если property не задано в function template, то должны быть заданы в конструкторе
     */
    public function template(array $options = []): string
    {
        $this->activeField = true;

        if (count($options) > 0) {
            $this->setInputOptions($options);
        }

        return $this->render();
    }
    
    public function getInputOptions(): array 
    {
        return $this->inputOptions;
    }

    public static function one(string $label = '', array $options = [], array $property = [])
    {
        $class = get_called_class();
        return new $class($label, $options, $property);
    }
}
