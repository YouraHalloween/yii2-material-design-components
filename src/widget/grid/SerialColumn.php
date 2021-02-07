<?php

namespace yh\mdc\widget\grid;

class SerialColumn extends \yii\grid\SerialColumn
{
    public $headerOptions = [
        'class' => 'mdc-data-table__header-cell',
        'role' => 'columnheader',
        'scope' => 'col'
    ];
    
    public $contentOptions = [
        'class' => 'mdc-data-table__cell'
    ];
}
