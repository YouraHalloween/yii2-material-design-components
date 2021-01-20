<?php

namespace yh\mdc;

use yii\helpers\ArrayHelper;

class ActiveField extends \yii\widgets\ActiveField
{
    public $labelOptions = [];
    public $errorOptions = ['tag' => false];
    public $hintOptions = ['tag' => false];

    private static $pathComponent = 'yh\\mdc\\components\\';
    /**
     * @var bool adds aria HTML attributes `aria-required` and `aria-invalid` for inputs
     * @since 2.0.11
     */
    public $addAriaAttributes = false;

    private function getProperty(array &$options) 
    {
        $property = ArrayHelper::remove($options, 'property', []);
        $property['inputId'] = $this->getInputId();
        $property['inputName'] = $this->attribute;
        $property['value'] = $this->model[$this->attribute];        
        return $property;
    }

    private function generateInput(string $class, array $options = []): array
    {        
        //В options записаны property
        $property = $this->getProperty($options);
        $class = self::$pathComponent.$class;
        $textField = new $class('', $options, $property);   
        $textField->setParent($this->form); 
        // template для textField
        $this->template = $textField->template();
    
        // Options input
        return $textField->getInputOptions();
    }

    public function textInput($options = [])
    {
        return parent::textInput($this->generateInput('TextField', $options));
    }

    public function passwordInput($options = [])
    {        
        return parent::passwordInput($this->generateInput('TextField', $options));
    }

    public function checkbox($options = [], $enclosedByLabel = false)
    {            
        return parent::checkbox($this->generateInput('CheckBox', $options), $enclosedByLabel);
    }

    public function radio($options = [], $enclosedByLabel = false)
    {
        return parent::checkbox($this->generateInput('Radio', $options), $enclosedByLabel);
    }
}
