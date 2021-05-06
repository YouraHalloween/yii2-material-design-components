<?php

namespace yh\mdc\components\base\stdctrls;

use yh\mdc\components\base\ComponentInitial;
use yii\helpers\Html;
use yh\mdc\components\IconButton;

class _DataTableSortButton extends ComponentInitial
{

    private static string $clsBlock = 'mdc-data-table__header-cell-wrapper';
    private static string $clsButton = 'mdc-data-table__sort-icon-button';
    private static string $clsLabel = 'mdc-data-table__header-cell-label';
    private static string $clsHidden = 'mdc-data-table__sort-status-label';

    private static array $clsTh = [
        'base' => 'mdc-data-table__header-cell--with-sort',
        'visible' => 'mdc-data-table__header-cell--sorted',
        SORT_ASC => '',
        SORT_DESC => 'mdc-data-table__header-cell--sorted-descending'
    ];

    const SORT_NAME = [
        SORT_ASC => 'ascending',
        SORT_DESC => 'descending'
    ];

    public string $attribute = '';
    public ?int $direction = null;
    public string $label = '';
    public string $href = '';

    private function getHiddenId(): string
    {
        return $this->attribute.'-status-label';
    }

    public function initOptions(): void
    {
        parent::initOptions();
        $this->options['class'][] = self::$clsBlock;
    }
    
    public static function getSortName($direction)
    {
        return self::SORT_NAME[$direction];
    }

    public static function getClassTh($direction): string
    {
        $cls = self::$clsTh['base'].' '.self::$clsTh[$direction];
        return is_null($direction) ? $cls : $cls.' '.self::$clsTh['visible'];
    }

    private function getLabelTag(): string
    {
        return Html::tag('div', $this->label, ['class' => self::$clsLabel]);
    }

    private function getHiddenTag(): string
    {
        return Html::tag('div', $this->label, [
            'class' => self::$clsHidden,
            'aria-hidden' => 'true',
            'id' => $this->getHiddenId()
        ]);
    }

    protected function getLabel(): string 
    {
        return \Yii::t('mdc/components/DataTable', 'Сортировка по').' '.$this->label;
    }

    public function renderComponent(): string
    {
        $button = IconButton::one('sort')
            ->setId(null)
            ->setIcon('arrow_upward')
            ->setOptions([
                'class' => self::$clsButton,
                'aria-describedby' => $this->getHiddenId(),
                'aria-label' => $this->getLabel()
            ])
            ->renderComponent();

        $content = Html::beginTag('div', $this->getOptions());
        $content .= $this->getLabelTag();
        $content .= $button;
        $content .= $this->getHiddenTag();
        $content .= Html::endTag('div');

        return $content;
    }
}
