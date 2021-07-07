<?php

namespace yh\mdc\components;

use yh\mdc\components\base\Component;
use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\widget\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yh\mdc\widget\grid\LinkPager;

class DataTable extends Component
{
    protected string $cmpType = ComponentRegister::TYPE_DATATABLE;

    public array $gridProperty = [];
    public bool $useAjax = true;
    public bool $isAjaxRequest = false;    
    public bool $progress = true;
    public bool $checkBox = false;
    public bool $byScreenHeight = false;
    public int $rowHeight= 52; //52px

    public function setGridView(array $property): DataTable
    {
        $this->gridProperty = $property;
        return $this;
    }

    public function isAjax(): bool
    {
        return $this->useAjax && $this->isAjaxRequest;
    }

    public function getDataProvider()
    {
        return $this->gridProperty['dataProvider'];
    }

    public function getSummuryAjax(): array
    {
        $dataProvider = $this->getDataProvider();
        $count = $dataProvider->getCount();
        $totalCount= 0;
        $begin = 0;
        $end = 0;
        if ($count > 0) {
            $totalCount = $dataProvider->getTotalCount();
            $begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
            $count = $dataProvider->getCount();
            $end = $begin + $count - 1;
        }
        return [
            'begin' => $begin,
            'end' => $end,
            'totalCount' => $totalCount
        ];
    }
    
    public function renderComponent(): string
    {
        $this->gridProperty = ArrayHelper::merge($this->gridProperty, $this->getOptions());
        $this->gridProperty['dataTable'] = $this;
        $content = GridView::widget($this->gridProperty);
        return $content;
    }

    public function render(): string
    {
        if ($this->isAjax()) {
            //Вернуть текущие данные таблицы, без регистрации компонента
            //Если registerControlJs = true, вернется <script>...</script>
            $this->registerControlJs = false;
        }

        $content = parent::render();
        if ($this->useAjax && !$this->isAjaxRequest) {
            /**
             * Если установлен флаг useAjax таблица будет использовать функцию DataTableProcessing,
             * которую можно вернуть app.controls.item(id.'-processing')
             */
            $param = ["'".$this->options['id']."'"];
            $obj = 'app.utils.DataTableProcessing('.implode(',', $param).')';
            ComponentRegister::registerObjectJs($obj);
        } elseif ($this->isAjax()) {
            $vanContent = '';
            $pagination = $this->getDataProvider()->getPagination();
            if ($pagination !== false && $this->getDataProvider()->getCount() > 0) {
                $prop = [
                    'pagination' => $pagination,
                    'options' => ['tag' => '']
                ];                
                $navContent = substr(LinkPager::widget($prop), 2, -3);                
            }
            $data = [
                'items' => $content,
                'summury' => $this->getSummuryAjax(),
                'nav' => $navContent,
                'pageSize' => $this->getDataProvider()->getPagination()->getPageSize()
            ];
            return json_encode([
                'data' => $data,
                'status' => 'success'
            ]);
        }
        return $content;
    }
}
