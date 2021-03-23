<?php

namespace yh\mdc\widget\grid;

/**
 * ВНИМАНИЕ
 * Класс не используется в DataColumn
 */
class Column extends \yii\grid\Column
{
    public $headerOptions = [
        'class' => ['mdc-data-table__header-cell'],
        'role' => 'columnheader',
        'scope' => 'col'
    ];
    
    public $contentOptions = [
        'class' => ['mdc-data-table__cell']
    ];
}
