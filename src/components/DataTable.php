<?php

namespace yh\mdc\components;

use yh\mdc\components\base\Component;
use yh\mdc\components\base\ComponentRegister;
use yh\mdc\widget\grid\GridView;
use yii\helpers\Html;

class DataTable extends Component
{
    protected string $cmpType = ComponentRegister::TYPE_DATATABLE;

    private static array $clsBlock = [
        'base' => 'mdc-data-table'        
    ];

    public array $property = [];

    public function setProperty(array $property): DataTable
    {
        $this->property = $property;
        return $this;
    }

    /**
     * Css классы для контейнера
     */
    public function initOptions(): void
    {
        parent::initOptions();

        $this->options['class'][] = self::$clsBlock['base'];
    }
    
    public function renderComponent(): string
    {
        $content = Html::beginTag('div', $this->getOptions());
        $content .= GridView::widget($this->property);
        $content .= Html::endTag('div');
        return $content;
    }
}
