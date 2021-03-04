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

    public array $gridProperty = [];
    public bool $pagination = true;
    public bool $useAjax = true;
    public bool $progress = true;

    public function setGridProperty(array $property): DataTable
    {
        $this->gridProperty = $property;
        return $this;
    }
    
    public function renderComponent(): string
    {
        $this->gridProperty = ArrayHelper::merge($this->gridProperty, $this->getOptions());
        $content = GridView::widget($this->gridProperty);
        // if ($this->progress) {
        //     $progress = new _DataTableProgressIndicator();
        //     $content .= $progress->renderComponent();
        // }
        return $content;
    }

    public function render(): string
    {
        $content = parent::render();
        if ($this->useAjax) {
            $param = ["'".$this->options['id']."'"];
            $obj = 'DataTableProcessing('.implode(',', $param).')';
            ComponentRegister::registerObjectJs($obj);
        }
        return $content;
    }
}
