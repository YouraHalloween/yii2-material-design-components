<?php

namespace yh\mdc\widget\grid;

use yii\helpers\ArrayHelper;

class LinkPager extends \yii\widgets\LinkPager
{
    public $options = [
        'class' => 'mdc-data-table__pagination-navigation',
        'tag' => 'div'
    ];
    public $linkContainerOptions = [        
        'tag' => 'button'
    ];

    public $disabledListItemSubTagOptions = [
        'tag' => 'a',
        'href' => '#'
    ];

    public $pageCssClass = 'mdc-icon-button';

    public $firstPageCssClass =  'mdc-icon-button material-icons mdc-data-table__pagination-button';
    public $lastPageCssClass =  'mdc-icon-button material-icons mdc-data-table__pagination-button';
    public $prevPageCssClass =  'mdc-icon-button material-icons mdc-data-table__pagination-button';
    public $nextPageCssClass =  'mdc-icon-button material-icons mdc-data-table__pagination-button';

    public $maxButtonCount = 3;

    public $disabledPageCssClass = '';

    public $disableCurrentPageButton = true;

    public $nextPageLabel = 'chevron_right';
    public $prevPageLabel = 'chevron_left';

    public $firstPageLabel = 'first_page';    
    public $lastPageLabel = 'last_page';

    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {        
        if ($disabled) {
            $this->linkContainerOptions['disabled'] = 'disabled';
        }
        $content = parent::renderPageButton($label, $page, $class, $disabled, $active);        
        if ($disabled) {
            ArrayHelper::remove($this->linkContainerOptions, 'disabled'); 
        }
        return $content;
    }
}
