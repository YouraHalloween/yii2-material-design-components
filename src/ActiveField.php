<?php

namespace yh\mdc;

use yii\helpers\ArrayHelper;
use yh\mdc\Config;

class ActiveField extends \yii\widgets\ActiveField
{
    public $labelOptions = [];
    public $errorOptions = ['tag' => false];
    public $hintOptions = ['tag' => false];

    public $options = [
        'class' => ['mdc-form-field__i']
    ];
    
    public $component;
    
    /**
     * @var bool adds aria HTML attributes `aria-required` and `aria-invalid` for inputs
     * @since 2.0.11
     */
    public $addAriaAttributes = false;

    private function getProperty(array &$options)
    {
        $property = ArrayHelper::remove($options, 'property', []);
        $property['id'] = $this->getInputId();
        $property['name'] = $this->attribute;
        $property['value'] = $this->model[$this->attribute];
        return $property;
    }

    public function generateInput(string $className, array $options = []): ActiveField
    {
        //В options записаны property
        $property = $this->getProperty($options);
        $class = Config::$pathComponent.$className;
        $this->component = new $class('', $property);
        $this->component->setInputOptions($options);
        $this->component->setParent($this->form);

        return $this;
    }

    public function textInput($options = [])
    {
        $this->generateInput('TextField', $options);
        return parent::textInput($this->component->getInputOptions());
    }

    public function passwordInput($options = [])
    {
        $this->generateInput('TextField', $options);
        return parent::passwordInput($this->component->getInputOptions());
    }

    public function checkbox($options = [], $enclosedByLabel = false)
    {
        $this->generateInput('CheckBox', $options);
        return parent::checkbox($this->component->getInputOptions(), $enclosedByLabel);
    }

    public function radio($options = [], $enclosedByLabel = false)
    {
        $this->generateInput('Radio', $options);
        return parent::checkbox($this->component->getInputOptions(), $enclosedByLabel);
    }

    public function dropDownList($items, $options = [])
    {
        if (!empty($items) && !isset($options['property']['items'])) {
            $options['property']['items'] = $items;
        }
        $this->generateInput('Select', $options);
        return parent::dropDownList($items, $this->component->getInputOptions());
    }

    public function render($content = null)
    {
        //TODO Select        
        if ($this->component->className(true) === 'TextField' && !$this->component->isLabelInner()) {
            $this->options['class'][] = $this->component::$clsFormField['label-' . $this->component->labelTemplate];
        }

        // $component->render()
        $this->template = $this->component->template();
        return parent::render($content);
    }
}
