<?php

namespace yh\mdc\widget\grid;

use yh\mdc\components\base\stdctrls\_DataTableSortButton;

class DataColumn extends \yii\grid\DataColumn
{
    public $headerOptions = [
        'class' => ['mdc-data-table__header-cell'],
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
        if ($this->grid->dataTable->checkBox && $this->attribute === 'name') {
            $this->contentOptions['id'] = $this->getRowId();
            $this->contentOptions['scope'] = 'row';
        }
    }

    public function getSortObject()
    {
        if ($this->attribute !== null && $this->enableSorting &&
            ($sort = $this->grid->dataProvider->getSort()) !== false && $sort->hasAttribute($this->attribute)) {
            return $sort;
        }
        return false;
    }

    public function renderHeaderCell()
    {
        if ($this->grid->dataTable->useAjax && $sort = $this->getSortObject()) {
            $this->headerOptions['data-column-id'] = $this->attribute;
            $direction = $sort->getAttributeOrder($this->attribute);
            if (!is_null($direction)) {
                $this->headerOptions['aria-sort'] = _DataTableSortButton::getSortName($direction);                
            }
            $this->headerOptions['class'][] = _DataTableSortButton::getClassTh($direction);
            $this->headerOptions['link'] = $sort->createUrl($this->attribute, true);
        }

        return parent::renderHeaderCell();
    }

    protected function renderHeaderCellContent()
    {        
        if ($this->grid->dataTable->useAjax && $sort = $this->getSortObject()) {
            //Отключить enableSorting, чтобы не формировать тег <а> и вернуть $content
            $enableSorting = $this->enableSorting;
            $this->enableSorting = false;
            $content = parent::renderHeaderCellContent();
            $this->enableSorting = $enableSorting;

            $direction = $sort->getAttributeOrder($this->attribute);
            $btn = new _DataTableSortButton();
            $content = $btn
                ->setProperty([
                    'attribute' => $this->attribute, 
                    'label' => $content,
                    'direction' => $direction
                ])
                ->renderComponent();
        } else {
            $content = parent::renderHeaderCellContent();
        }

        return $content;
    }

    public function renderDataCell($model, $key, $index)
    {
        if ($this->grid->dataTable->checkBox && $this->attribute === 'name') {
            // dump($this->grid->getRowId($key));
            $this->contentOptions['id'] = $this->grid->getRowId($key);
        }
        return parent::renderDataCell($model, $key, $index);
    }
}
