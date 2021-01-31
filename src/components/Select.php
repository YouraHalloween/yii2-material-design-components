<?php

namespace yh\mdc\components;

use yii\helpers\Html;
use yh\mdc\components\base\CustomTextField;
use yh\mdc\components\base\ComponentRegister;

class Select extends CustomTextField
{
    protected string $cmpType = ComponentRegister::TYPE_SELECT;   
    
    // public function renderComponent(): string
    // {
    //     $content = Html::beginTag('div', $this->getOptions());
    //     $content .= $this->getTagInput();
    //     $content .= $this->getTagBackgorund();
    //     $content .= $this->getTagRipple();
    //     $content .= Html::endTag('div');

    //     $content .= $this->getTagLabel();

    //     return Html::tag('div', $content, ['class' => self::$clsBlock]);
    // }
}
