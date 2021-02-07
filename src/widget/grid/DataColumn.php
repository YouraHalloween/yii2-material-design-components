<?php

namespace yh\mdc\widget\grid;

class DataColumn extends \yii\grid\DataColumn
{
    public $headerOptions = [
        'class' => 'mdc-data-table__header-cell',
        'role' => 'columnheader',
        'scope' => 'col'
    ];

    public $contentOptions = [
        'class' => 'mdc-data-table__cell'
    ];

    public bool $fieldView = false;

    public function init()
    {
        parent::init();
        if ($this->grid->checkBox && $this->attribute === 'name') {
            $this->contentOptions['id'] = $this->getRowId();
            $this->contentOptions['scope'] = 'row';
        }
    }
}
