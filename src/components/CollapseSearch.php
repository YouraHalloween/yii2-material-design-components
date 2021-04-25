<?php

namespace yh\mdc\components;

use yh\mdc\components\base\stable\ComponentRegister;
use yh\mdc\components\Collapse;
use yh\mdc\components\base\Vars;
use yh\mdc\components\base\stdctrls\CustomTextField;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CollapseSearch extends Collapse
{
    protected string $cmpType = ComponentRegister::TYPE_COLLAPSE_SEARCH;

    public string $labelTemplate = CustomTextField::ALIGN_LEFT;
    public string $cmpTemplate = CustomTextField::OUTLINED;
    public string $cmpHeight = Vars::EXTRA_SMALL;

    protected static array $clsWrapAlt = [
        'base' => 'mdc-collapse-search'
    ];

    protected static array $clsContentAlt = [
        'wrap' => 'mdc-collapse-search__item',
    ];
    
    /**
     * @var array $items
     * В items необходимо указать content, компоненты, которые будут использоваться для фильтрации
     * Например:
     * 'content' => [
     *      TextField::one(Yii::t('backend/user-filter', 'Пользователь'))->setId('filter-user-name'),
     *      TextField::one(Yii::t('backend/user-filter', 'Email'))->setId('filter-email'),
     *      Select::one(Yii::t('backend/user-filter', 'Статус'))->setId('filter-status'),
     *      CheckBox::one(Yii::t('backend/user-filter', 'Активный'))->setId('filter-active'),
     *      text,
     *      ...
     *    ]
     */

    /**
     * Css классы для контейнера
     */
    public function initWrapOptions(): void
    {
        parent::initWrapOptions();

        $this->wrapOptions['class'][] = self::$clsWrapAlt['base'];
    }

    protected function renderItemContent($itemContent)
    {
        if (\is_string($itemContent)) {
            return parent::renderItemContent($itemContent);
        } elseif (is_object($itemContent)) {
            $itemContent->labelTemplate = $this->labelTemplate;
            $itemContent->template= $this->cmpTemplate;
            $itemContent->height = $this->cmpHeight;
            return Html::tag('div', $itemContent->render(), ['class' => self::$clsContentAlt['wrap']]);
        } 
        return "";
    }

}
