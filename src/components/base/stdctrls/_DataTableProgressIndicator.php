<?php

namespace yh\mdc\components\base\stdctrls;

use yh\mdc\components\base\ComponentInitial;
use yh\mdc\components\LinearProgress;
use yii\helpers\Html;

class _DataTableProgressIndicator extends ComponentInitial
{
    private static string $clsBlock = 'mdc-data-table__progress-indicator';

    private static string $clsScrim = 'mdc-data-table__scrim';

    private static string $clsProgress = 'mdc-data-table__linear-progress';

    /**
     * Css классы для контейнера
     */
    public function initOptions(): void
    {
        parent::initOptions();
        $this->options['class'][] = self::$clsBlock;        
    }

    public function renderComponent(): string
    {
        $content = Html::beginTag('div', $this->getOptions());
        $content .= Html::tag('div', '', ['class' => self::$clsScrim]);
        $content .= LinearProgress::one([
            'indeterminate' => true
        ],[
            'class' => self::$clsProgress
        ])->renderComponent();
        $content .= Html::endTag('div');
        return $content;
    }
}
