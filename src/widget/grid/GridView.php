<?php

namespace yh\mdc\widget\grid;

use yii\helpers\Html;

class GridView extends \yii\grid\GridView
{
    public $dataColumnClass = 'yh\mdc\widget\grid\DataColumn';

    public $tableOptions = [
        'class' => 'mdc-data-table__table',
        'aria-label' => 'Data table'
    ];

    public $options = [
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

    public $checkBox = false;

    // public $layout = "{items}\n{pager}\n{summary}";
    public $layout = "{items}";

    public function init()
    {
        parent::init();
        if ($this->checkBox) {
            $this->rowOptions = function ($model, $key, $index, $grid) {
                return [
                    'class' => 'mdc-data-table__row',
                    'data-row-id' => $this->getRowId($key)
                ];
            };
        }
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
}
