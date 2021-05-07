<?php

namespace yh\mdc\components\base\stdctrls;

use yii\helpers\Html;
use yh\mdc\components\Select;
use yh\mdc\components\base\Vars;
use yh\mdc\components\base\ComponentInitial;

class _DataTablePagination extends ComponentInitial
{
    private static array $clsBlock = [
        'base' => 'mdc-data-table__pagination',
        'trailing' => 'mdc-data-table__pagination-trailing'
    ];

    private static array $clsPerPage = [
        'base' => 'mdc-data-table__pagination-rows-per-page',
        'label' => 'mdc-data-table__pagination-rows-per-page-label',
        'select' => 'mdc-data-table__pagination-rows-per-page-select'
    ];

    public $grid;

    public string $label = '';
    public array $items = [
        ['value' => 10], 
        ['value' => 20], 
        ['value' => 50],
    ];
    public string $contentSummary = '';
    public string $contentPager = '';
    public int $value = 10;

    /**
     * Css классы для контейнера
     */
    public function initOptions(): void
    {
        parent::initOptions();

        $this->options['class'][] = self::$clsBlock['base'];
        $this->options['class'][] = self::$clsBlock['trailing'];
    }
    
    private function getTagRows(): string
    {
        $content = Html::beginTag('div', ['class' => self::$clsPerPage['base']]);
        $content .= Html::tag('div', $this->label, ['class' => self::$clsPerPage['label']]);
        $content .= Select::outlined('', [
                'items' => $this->items,
                'height' => Vars::EXTRA_SMALL,
                'textSize' => Vars::SMALL,                
                'value' => $this->value                
            ],[
                'class' => self::$clsPerPage['select']
            ])            
            ->setId($this->getSelectId())
            ->render();
        $content .= Html::endTag('div');
        return $content;
    }

    private function getTagSummary(): string
    {
        return $this->contentSummary;
    }

    public function getSelectId(): string
    {
        return $this->grid->getId().'-rows';
    }

    public function renderComponent(): string
    {
        $content = Html::beginTag('div', $this->getOptions());
        $content .= $this->getTagRows();
        $content .= $this->getTagSummary();
        $content .= $this->contentPager;
        $content .= Html::endtag('div');
        return $content;
    }
}
