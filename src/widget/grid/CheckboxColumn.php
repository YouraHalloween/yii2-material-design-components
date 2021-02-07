<?php

namespace yh\mdc\widget\grid;

use yh\mdc\components\CheckBox;

class CheckboxColumn extends \yh\mdc\widget\grid\Column
{    
    private static function getCheckBox($options, $inputOptions = []): string
    {
        return CheckBox::one()
            ->setProperty([
                'id' => null,                
                'formField' => false
            ])
            ->setOptions($options)
            ->setInputOptions($inputOptions)
            ->renderComponent();
    }

    public function init()
    {
        $this->grid->checkBox= true;
        $this->headerOptions['class'][] = 'mdc-data-table__header-cell--checkbox';
        $this->contentOptions['class'][] = 'mdc-data-table__cell--checkbox';        
        $this->header = self::getCheckBox(['class' => 'mdc-data-table__header-row-checkbox']);
    }

    protected function renderDataCellContent($model, $key, $index)
    {

        return self::getCheckBox([
            'class' => 'mdc-data-table__row-checkbox'            
        ], [
            'aria-labelledby' => $this->grid->getRowId($key)
        ]);
    }
}
