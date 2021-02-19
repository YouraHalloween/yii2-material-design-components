<?php

namespace yh\mdc\widget\grid;

use yh\mdc\components\_DataTablePagination;
use yii\widgets\BaseListView;
use yii\helpers\Html;

class GridView extends \yii\grid\GridView
{
    public $dataColumnClass = 'yh\mdc\widget\grid\DataColumn';

    public $tableOptions = [
        'class' => 'mdc-data-table__table',
        'aria-label' => 'Data table'
    ];

    public $options = [
        'class' => 'mdc-data-table'        
    ];

    public $tableContainerOptions = [
        'class' => 'mdc-data-table__table-container'
    ];

    public $headerRowOptions = [
        'class' => 'mdc-data-table__header-row'
    ];

    public $bodyOptions = [
        'class' => 'mdc-data-table__content'
    ];

    public $rowOptions = [
        'class' => 'mdc-data-table__row',
    ];

    public $summaryOptions = ['class' => 'mdc-data-table__pagination-total'];

    public $pager = [
        'class' => 'yh\mdc\widget\grid\LinkPager'
    ];

    public $summary = '{begin, number}-{end, number} / <b>{totalCount, number}</b>';

    public $checkBox = false;    

    // public $layout = "{items}\n{pager}\n{summary}";
    public $layout = "{items}\n{pager}\n{summary}";

    private _DataTablePagination $pagination;

    public function init()
    {
        parent::init();
        $this->pagination = new _DataTablePagination();
        $this->pagination->grid = $this;

        if ($this->checkBox) {
            $this->rowOptions = function ($model, $key, $index, $grid) {
                return [
                    'class' => 'mdc-data-table__row',
                    'data-row-id' => $this->getRowId($key)
                ];
            };
        }
    }

    public function run()
    {
        // $view = $this->getView();
        // GridViewAsset::register($view);
        // $id = $this->options['id'];
        // $options = Json::htmlEncode(array_merge($this->getClientOptions(), ['filterOnFocusOut' => $this->filterOnFocusOut]));
        // $view->registerJs("jQuery('#$id').yiiGridView($options);");
        BaseListView::run();
    }

    public function getRowId($key): string
    {
        $key = is_array($key) ? json_encode($key) : (string) $key;
        return $this->getId().'-'.$key;
    }

    public function renderTableBody()
    {
        $content = substr(parent::renderTableBody(), 7, -9);
        $content = Html::tag('tbody', $content, $this->bodyOptions);

        return $content;
    }

    public function renderItems()
    {        
        return Html::tag('div', parent::renderItems(), $this->tableContainerOptions);
    }

    public function renderSummary()
    {
        $this->pagination->contentSummary = parent::renderSummary();
        return $this->pagination->renderComponent();

    }

    public function renderPager()
    {
        $this->pagination->contentPager = parent::renderPager();
        return '';
    }
}
