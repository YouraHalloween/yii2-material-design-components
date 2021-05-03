<?php

namespace yh\mdc\components;

use yh\mdc\components\base\Control;
use yh\mdc\components\base\stable\ComponentRegister;
use yii\helpers\Html;

class IconButton extends Control
{
    protected string $cmpType = ComponentRegister::TYPE_ICONBUTTON;

    private static string $clsBlock = 'mdc-icon-button';

    private static array $clsIcons = [
        'base' => 'material-icons',
        'button' => 'mdc-icon-button__icon',
        'toggle' => 'mdc-icon-button__icon--on'
    ];

    public bool $isButton = true;

    /**
     * Icon button toggle with toggled aria label
     * Some designs may call for the aria label to change depending on the icon button state.
     * In this case, specify the data-aria-label-on (aria label in on state)
     * and aria-data-label-off (aria label in off state) attributes, and omit the aria-pressed attribute.
     */
    public string $labelOn = '';
    public string $labelOff = '';
    public string $icon = 'favorite_border';
    /**
     * Icon for toggle
     */
    public string $iconOn = 'favorite';
    /**
     * @param bool $toggle - Возможность переключать состояния иконок
     */
    public bool $toggle = false;
    /**
     * @param bool $pressed - Нажата кнопка или нет
     */
    public bool $pressed = false;
    /**
     * @param string $tagIcon - Иконка может быть в виде тэга <i>, <svg>, <img>
     */
    public string $tagIcon = 'i';

    /**
     * Css классы для контейнера
     */
    public function initOptions(): void
    {
        parent::initOptions();

        $this->options['class'][] = self::$clsBlock;
        if (!$this->toggle) {
            $this->options['class'][] = self::$clsIcons['base'];
        } else {
            if (!empty($this->label)) {
                $this->options['aria-label'] = $this->label;
            }
            $this->options['aria-pressed'] = $this->pressed;
            if (!empty($this->labelOn)) {
                $this->options['data-aria-label-on'] = $this->labelOn;
            }
            if (!empty($this->labelOff)) {
                $this->options['data-aria-label-off'] = $this->labelOff;
            }
        }
    }

    private function getTagIcons(): string
    {
        $options = ['class' => [
                self::$clsIcons['base'],
                self::$clsIcons['button']
            ]];
        $optionsOn = $options;
        $optionsOn['class'][] = self::$clsIcons['toggle'];
        if ($this->tagIcon !== 'img') {
            $content = Html::tag($this->tagIcon, $this->iconOn, $optionsOn);
            $content .= Html::tag($this->tagIcon, $this->icon, $options);
        } else {
            $content = Html::img($this->iconOn, $optionsOn);
            $content .= Html::img($this->icon, $options);
        }
        return $content;
    }

    public function setIcon($icon): IconButton 
    {
        $this->icon = $icon;
        return $this;
    }
    
    public function renderComponent(): string
    {
        $tag = $this->isButton ? 'button' : 'i';
        $content = Html::beginTag($tag, $this->getOptions());
        if ($this->toggle) {
            $content .= $this->getTagIcons();
        } else {
            $content .= $this->icon;
        }
        $content .= Html::endTag($tag);
        return $content;
    }
}
