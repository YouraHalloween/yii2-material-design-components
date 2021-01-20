<?php

namespace yh\mdc;

use yii\helpers\Json;
use yh\mdc\components\ComponentRegister;

class ActiveForm extends \yii\widgets\ActiveForm
{
    // public $enableClientValidation = false;
    public $enableAjaxValidation = false;
    
    public $fieldClass = 'yh\mdc\ActiveField';
    // public $errorCssClass = 'mdc-text-field--invalid';

    /*
     * Отключить стандартную валидацию формы и сообщения браузера
    */
    public $options = [
        'class' => 'active-form',
        'novalidate' => true
    ];
    public $requiredCssClass = '';
    public $errorCssClass = '';
    public $successCssClass = '';
    public $validatingCssClass = '';
    // public $errorSummaryCssClass = false;    
    // public $enableClientScript = false;
    
    public $validateOnChange = false;  
    /**
     * @var array $blockedControls - параметры передаются в класс JS FormProcessing
     * control => submit, all
     * unblock => true, false
     */
    public array $blockedControls = [
        'control' => 'submit',
        'unblock' => false
    ];

    public function registerClientScript()
    {
        parent::registerClientScript();
        ComponentRegister::registerFormJs($this->options['id'], $this->blockedControls);
    }
}
