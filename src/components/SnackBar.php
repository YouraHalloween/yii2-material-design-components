<?php

namespace yh\mdc\components;

use yh\mdc\components\base\Control;
use yh\mdc\components\base\ComponentRegister;
use yii\helpers\Html;

class SnackBar extends Control
{
    protected string $cmpType = ComponentRegister::TYPE_SNACKBAR;

    private static array $clsBlock = [
        'base' => 'mdc-snackbar',
        'leading' => 'mdc-snackbar--leading',
        'trailing' => 'mdc-snackbar--trailing',
        'stacked' => 'mdc-snackbar--stacked',
        'action-baseline' => 'mdc-snackbar--action-baseline'
    ];

    private static string $clsSurface = 'mdc-snackbar__surface';
    private static string $clsLabel = 'mdc-snackbar__label';
    private static string $clsBlockButtons = 'mdc-snackbar__actions';
    private static array $clsActionButton = [
        'base' => 'mdc-button mdc-snackbar__action',
        'ripple' => 'mdc-button__ripple',
        'label' => 'mdc-button__label',
        'close' => 'mdc-icon-button mdc-snackbar__dismiss material-icons'
    ];

    public bool $leading = false;
    public bool $trailing = false;
    public bool $stacked = false;
    public bool $actionBaseline = true;
    public string $captionButton = '';
    public string $closeButton = 'Close';

    /**
     * Css классы для контейнера
     */
    public function initOptions(): void
    {
        parent::initOptions();

        $this->options['class'][] = self::$clsBlock['base'];
        if ($this->leading) {
            $this->options['class'][] = self::$clsBlock['leading'];
        } elseif ($this->trailing) {
            $this->options['class'][] = self::$clsBlock['trailing'];
        }
        if ($this->stacked) {
            $this->options['class'][] = self::$clsBlock['stacked'];
        } elseif ($this->actionBaseline) {
            $this->options['class'][] = self::$clsBlock['action-baseline'];
        }
    }

    /**
     * Выводит текст сообщения $this->label
     */
    private function getTagLabel(): string
    {
        return Html::tag('div', $this->label, ['class' => self::$clsLabel, 'aria-atomic' => false]);
    }

    /**
     * Выводит две кнопки Action and Close
     */
    private function getTagActionButton(): string
    {
        $content = Html::tag('div', '', ['class' => self::$clsActionButton['ripple']]);
        $content .= Html::tag('span', $this->captionButton, ['class' => self::$clsActionButton['label']]);

        $options = ['class' => self::$clsActionButton['base'], 'type' => 'button'];
        if (empty($this->captionButton)) {
            $options['style'] = 'display: none';
        }

        return Html::button($content, $options);
    }

    /**
     * Контейнер. Выводит две кнопки Action and Close
     */
    private function getTagButtons():string
    {
        $content = Html::beginTag('div', ['class' => self::$clsBlockButtons, 'aria-atomic' => 'true']);
        $content .= $this->getTagActionButton();
        if (!empty($this->closeButton)) {
            $content .= Html::button('close', ['class' => self::$clsActionButton['close'], 'title' => $this->closeButton]);
        }
        $content .= Html::endTag('div');
        return $content;
    }
    
    /**
     * Нарисовать Snackbar
     */
    public function renderComponent(): string
    {
        //Snackbar begin
        $content = Html::beginTag('div', $this->getOptions());
        //Surface begin
        $content .= Html::beginTag('div', ['class' => self::$clsSurface, 'role' => 'status', 'aria-relevant' => 'additions']);

        $content .= $this->getTagLabel();
        $content .= $this->getTagButtons();

        //Surface end
        $content .= Html::endTag('div');
        //Snackbar end
        $content .= Html::endTag('div');

        return $content;
    }
}
