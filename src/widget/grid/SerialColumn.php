<?php

namespace yh\mdc\widget\grid;

class SerialColumn extends \yii\grid\SerialColumn
{
    public $headerOptions = [
        'class' => 'mdc-data-table__header-cell mdc-data-table__cell-serial',
        'role' => 'columnheader',
        'scope' => 'col'
    ];
    
    public $contentOptions = [
        'class' => 'mdc-data-table__cell mdc-data-table__cell-serial'
    ];
}
