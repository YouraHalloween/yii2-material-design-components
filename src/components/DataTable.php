<?php

namespace yh\mdc\components;

use yh\mdc\components\base\Component;
use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\widget\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class DataTable extends Component
{
    protected string $cmpType = ComponentRegister::TYPE_DATATABLE;

    public array $property = [];
    public bool $pagination = true;    

    public function setProperty(array $property): DataTable
    {
        $this->property = $property;
        return $this;
    }
    
    public function renderComponent(): string
    {
        $this->property = ArrayHelper::merge($this->property, $this->getOptions());
        $content .= GridView::widget($this->property);    
        return $content;
    }
}
