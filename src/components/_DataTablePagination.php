<?php

namespace yh\mdc\components;

use yh\mdc\components\base\ComponentInitital;
use yii\helpers\Html;

class _DataTablePagination extends ComponentInitital
{
    private static array $clsBlock = [
        'base' => 'mdc-data-table__pagination',
        'trailing' => 'mdc-data-table__pagination-trailing'
    ];

    private static array $perPage = [
        'base' => 'mdc-data-table__pagination',
        'trailing' => 'mdc-data-table__pagination-trailing',
        'select' => 'mdc-data-table__pagination-rows-per-page-select'
    ];

    private static array $navig = [
        'base' => 'mdc-data-table__pagination-navigation',
        'total' => 'mdc-data-table__pagination-total',
        'button' => 'mdc-data-table__pagination-button',
    ];
    /**
     * Css классы для контейнера
     */
    public function initOptions(): void
    {
        parent::initOptions();

        $this->options['class'][] = self::$clsBlock['base'];
        $this->options['class'][] = self::$clsBlock[$this->size];
        if ($this->enabled) {
            $this->options['class'][] = self::$clsBlock['open'];
        }
    }
    
    /**
     * Нарисовать Snackbar
     */
    public function renderComponent(): string
    {
        return Html::tag('span', '', $this->getOptions());
    }
}
